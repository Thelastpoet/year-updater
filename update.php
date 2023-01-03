<?php
// "update.php" file

// Get the old and new years from the form submission
$old_year = intval($_POST['old_year']);
$new_year = intval($_POST['new_year']);

// Get an array of posts that have not been updated
$posts = get_posts(array(
  'numberposts' => -1,
  'post_type'   => 'post',
  'meta_key'    => '_year_updater_updated',
  'meta_value'  => 0
));

// Update the year in the title and modified date for each post
foreach ($posts as $post) {
  $post_id = $post->ID;
  $title = $post->post_title;
  $modified_date = date('Y-m-d H:i:s');
  $updated_title = str_replace($old_year, $new_year, $title);
  wp_update_post(array(
    'ID'           => $post_id,
    'post_title'   => $updated_title,
    'post_modified' => $modified_date
  ));
  update_post_meta($post_id, '_year_updater_updated', 1);
}
?>

<div class="wrap">
  <h1>Year Updater</h1>
  <p>The year has been successfully updated in all published post titles!</p>
</div>
