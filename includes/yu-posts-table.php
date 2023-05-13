<?php

if ( ! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class YU_Posts_Table extends WP_List_Table {
    private static $is_updating = false;

    public function __construct() {
        parent::__construct([
            'singular' => __('Post', 'year-updater'),
            'plural'   => __('Posts', 'year-updater'),
            'ajax'     => false
        ]);
        add_filter('posts_where', [$this, 'yu_posts_where'], 10, 2);
    }

    public function yu_posts_where($where, $wp_query) {
        global $wpdb;
        if ($wp_query->get('yu_query_mode')) {
            $where .= " AND {$wpdb->posts}.post_title REGEXP '[0-9]{4}'";
        }
        return $where;
    }   

    public function get_columns() {
        $columns = [
            'cb'      => '<input type="checkbox" />',
            'title'   => __('Title', 'year-updater'),
            'id'      => __('ID', 'year-updater'),
            'type'    => __('Type', 'year-updater')
        ];
        return $columns;
    }

    public function column_default($item, $column_name) {
        switch ($column_name) {
            case 'title':
                return esc_html($item->post_title);
            case 'id':
                return esc_html($item->ID);
            case 'type':
                return esc_html($item->post_type);
            default:
                return print_r($item, true);
        }
    }

    public function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="post[]" value="%s" />', $item->ID
        );
    }

    public function column_title($item) {
        $title = '<strong>' . esc_html($item->post_title) . '</strong>';

        $actions = [
            'edit'      => sprintf('<a href="%s">%s</a>', get_edit_post_link($item->ID), __('Edit', 'year-updater')),
            'trash'     => sprintf('<a href="%s">%s</a>', get_delete_post_link($item->ID), __('Trash', 'year-updater')),
            'view'      => sprintf('<a href="%s">%s</a>', get_permalink($item->ID), __('View', 'year-updater'))
        ];

        return $title . $this->row_actions($actions);
    }

    public function extra_tablenav($which) {
        if ($which === 'top') {
            ?>
            <div class="alignleft actions">
                <label class="screen-reader-text" for="post-search-input">Search Posts:</label>
                <input type="search" id="post-search-input" name="s" value="">
                <input type="submit" id="search-submit" class="button" value="Search Posts">
            </div>
            <?php
        }
    }

    public function prepare_items() {
        $this->_column_headers = [$this->get_columns(), [], []];
    
        $post_type = isset($_REQUEST['post_type']) ? sanitize_text_field($_REQUEST['post_type']) : '';
        $per_page = $this->get_items_per_page('posts_per_page');
        $current_page = $this->get_pagenum();
        $offset = ($current_page - 1) * $per_page;
    
        $args = [
            'posts_per_page' => $per_page,
            'post_type'   => $post_type,
            'post_status' => 'publish',
            'offset'      => $offset,
            'yu_query_mode' => true
        ];
    
        $query = new WP_Query($args);
    
        $this->items = $query->posts;
    
        $total_items = $query->found_posts;
        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ]);
    }
}