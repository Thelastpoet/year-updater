<?php
// "update.php" file

// Get the old and new years from the form submission
$old_year = intval($_POST['old_year']);
$new_year = intval($_POST['new_year']);

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
  
  echo '<div class="wrap">';
  echo '<h1>Year Updater</h1>';
  echo '<h2>Year updated successfully!</h2>';
  echo '</div>';
