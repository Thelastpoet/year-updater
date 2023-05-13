<?php

if ( ! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class YU_Posts_Table extends WP_List_Table {
    public function __construct() {
        parent::__construct([
            'singular' => __('Post', 'year-updater'),
            'plural'   => __('Posts', 'year-updater'),
            'ajax'     => false
        ]);
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
            'quickedit' => sprintf('<a href="#" class="quick-edit">%s</a>', __('Quick Edit', 'year-updater')),
            'trash'     => sprintf('<a href="%s">%s</a>', get_delete_post_link($item->ID), __('Trash', 'year-updater')),
            'view'      => sprintf('<a href="%s">%s</a>', get_permalink($item->ID), __('View', 'year-updater'))
        ];

        return $title . $this->row_actions($actions);
    }

    public function get_bulk_actions() {
        $actions = [
            'edit' => __('Edit', 'year-updater'),
            'trash' => __('Move to Trash', 'year-updater')
        ];
        return $actions;
    }

    public function process_bulk_action() {
        if ('edit' === $this->current_action()) {
            // Handle bulk edit
            $post_ids = isset($_REQUEST['post']) ? $_REQUEST['post'] : [];
            // ...
        } elseif ('trash' === $this->current_action()) {
            // Handle bulk trash
            $post_ids = isset($_REQUEST['post']) ? $_REQUEST['post'] : [];
            foreach ($post_ids as $post_id) {
                wp_trash_post($post_id);
            }
        }
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
            'numberposts' => -1,
            'post_type'   => $post_type,
            'post_status' => 'publish',
            'offset'      => $offset,
            'numberposts' => $per_page
        ];

        $posts = get_posts($args);

        $year_posts = [];
        foreach ($posts as $post) {
            if (preg_match('/\b\d{4}\b/', $post->post_title)) {
                $year_posts[] = $post;
            }
        }

        $this->items = $year_posts;

        $total_items = count($year_posts);
        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ]);
    }
}