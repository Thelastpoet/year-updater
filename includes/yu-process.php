<?php

namespace YearUpdater;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class YU_Process {
    private $year;

    public function __construct() {
        $this->year = date('Y');
    }

    public function update_year($post_type) {
        $new_year = $this->year;
        $posts_updated = 0;

        // Batch processing
        $posts_per_page = 20; 
        $paged = 1;

        do {
            $args = [
                'post_type'      => $post_type,
                'post_status'    => 'publish',
                'posts_per_page' => $posts_per_page,
                'paged'          => $paged,
                'no_found_rows'  => true, 
            ];

            $query = new \WP_Query($args);

            if (!$query->have_posts()) {
                break; // Exit the loop if no posts are found.
            }

            foreach ($query->posts as $post) {
                $post_id = $post->ID;
                $title = $post->post_title;

                if (preg_match('/\b20\d\d\b/', $title, $matches) && $matches[0] !== $new_year) {
                    $updated_title = preg_replace('/\b20\d\d\b/', $new_year, $title);

                    wp_update_post([
                        'ID'         => $post_id,
                        'post_title' => $updated_title,
                    ]);

                    // Store a custom field to indicate the post title was updated with a new year.
                    update_post_meta($post_id, 'year_updated', $new_year);
                    $posts_updated++;
                }
            }

            wp_reset_postdata();
            $paged++;

        } while (true);

        return $posts_updated;
    }
}
