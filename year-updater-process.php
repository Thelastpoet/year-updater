<?php

class Year_Updater_Process {
    public function __construct() {
        // Initialization code
    }

    public function update_year($old_year, $new_year) {
        // Get an array of published posts that contain the old year in the title
        $posts = get_posts(array(
            'numberposts' => -1,
            'post_type'   => 'post',
            'post_status' => 'publish',
            's'           => $old_year
        ));

        // Update the year in the title and modified date for each post
        foreach ($posts as $post) {
            $post_id = $post->ID;
            $title = $post->post_title;
            $updated_title = str_replace($old_year, $new_year, $title);
            wp_update_post(array(
                'ID'         => $post_id,
                'post_title' => $updated_title,
            ));
            $current_date = date('Y-m-d H:i:s');
            wp_update_post(array(
                'ID'                => $post_id,
                'post_modified'     => $current_date,
                'post_modified_gmt' => $current_date
            ));
            // Update the "year_updated" postmeta field with the new year
            update_post_meta($post_id, 'year_updated', $new_year);
        }
    }
}