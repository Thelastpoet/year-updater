<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class YU_Process {
    private $year;

    public function __construct() {
        $this->year = date('Y');
    }

    public function update_year($post_type) {
        // Get the current year
        $new_year = $this->year;

        // Get an array of all posts of the given post type with year in title
        $args = array(
            'numberposts' => -1,
            'post_type'   => $post_type,
            'post_status' => 'publish',
        );
    
        $posts = get_posts($args);
        
        if (!is_array($posts)) {
            return new WP_Error('get_posts_failed', __('Failed to get posts.', 'year-updater'));
        }

        $year_posts = array();
        foreach ($posts as $post) {
            $title = $post->post_title;
            if (preg_match('/\b\d{4}\b/', $title)) {
                $year_posts[] = $post;
            }
        }

        // Update the year in the title and modified date for each post
        foreach ($posts as $post) {
            $post_id = $post->ID;
            $title = $post->post_title;

            // Find a four-digit number (assumed to be a year) in the title and replace it with the new year
            $updated_title = preg_replace('/\b\d{4}\b/', $new_year, $title);

            $current_date = current_time('mysql');

            $updated_post = array(
                'ID'                => $post_id,
                'post_title'        => $updated_title,
                'post_modified'     => $current_date,
                'post_modified_gmt' => get_gmt_from_date($current_date)
            );

            $result = wp_update_post($updated_post, true);

            if (is_wp_error($result) || 0 === $result) {
                return new WP_Error('update_failed', __('Failed to update post.', 'year-updater'));
            }

            // Update the "year_updated" postmeta field with the new year
            update_post_meta($post_id, 'year_updated', $new_year);
        }

        return true;
    }
}