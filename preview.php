<?php
// "preview.php" file

// Get the old year from the form submission
$old_year = intval($_POST['old_year']);

// Get the current year
$new_year = date('Y');

// Get an array of published posts that contain the old year in the title
$posts = get_posts(array(
  'numberposts' => -1,
  'post_type'   => 'post',
  'post_status' => 'publish',
  's'           => $old_year
));

// Display a preview of the updated titles
echo '<div class="wrap">';
echo '<h1>' . esc_html__('Year Updater', 'year-updater') . '</h1>';
echo '<h2>' . esc_html__('Preview of updated titles:', 'year-updater') . '</h2>';
echo '<table class="wp-list-table widefat striped">';
echo '<thead>';
echo '<tr>';
echo '<th>' . esc_html__('Post Title', 'year-updater') . '</th>';
echo '<th>' . esc_html__('Post ID', 'year-updater') . '</th>';
echo '<th>' . esc_html__('Post Type', 'year-updater') . '</th>';
echo '</tr>';
echo '</thead>';
echo '<tbody>';
foreach ($posts as $post) {
  $title = $post->post_title;
  $updated_title = str_replace($old_year, $new_year, $title);
  $post_id = $post->ID;
  $post_type = $post->post_type;
  echo '<tr>';
  echo '<td>' . esc_html($updated_title) . '</td>';
  echo '<td>' . esc_html($post_id) . '</td>';
  echo '<td>' . esc_html($post_type) . '</td>';
  echo '</tr>';
}
echo '</tbody>';
echo '</table>';

// Display the update form
echo '<form method="post" action="admin.php?page=year-updater&step=update">';
echo '<input type="hidden" name="old_year" value="' . esc_attr($old_year) . '">';
echo '<input type="hidden" name="new_year" value="' . esc_attr($new_year) . '">';
echo '<input type="submit" name="submit" value="' . esc_attr__('Change Year', 'year-updater') . '">';
echo '</form>';
echo '</div>';
