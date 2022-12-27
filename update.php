<?php
$old_year = $_POST['old_year'];
$new_year = $_POST['new_year'];

$args = array(
  'post_type' => 'post',
  'post_status' => 'publish',
  'posts_per_page' => -1,
  's' => $old_year,
);
$posts = new WP_Query($args);
if ($posts->have_posts()) {
  while ($posts->have_posts()) {
    $posts->the_post();
    $post_id = get_the_ID();
    $title = get_the_title();
    $updated_title = str_replace($old_year, $new_year, $title);
    wp_update_post(array(
      'ID' => $post_id,
      'post_title' => $updated_title,
    ));
    $post_content = get_the_content();
    $updated_content = str_replace($old_year, $new_year, $post_content);
    wp_update_post(array(
      'ID' => $post_id,
      'post_content' => $updated_content,
    ));
    $current_date = date('Y-m-d H:i:s');
    wp_update_post(array(
      'ID' => $post_id,
      'post_modified' => $current_date,
    ));
  }
}
?>

<h2>Year updated successfully!</h2>
