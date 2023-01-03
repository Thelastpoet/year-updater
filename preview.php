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
echo '<h1>Year Updater</h1>';
echo '<h2>Preview of updated titles:</h2>';
echo '<ul>';
foreach ($posts as $post) {
  $title = $post->post_title;
  $updated_title = str_replace($old_year, $new_year, $title);
  echo '<li>' . $updated_title . '</li>';
}
echo '</ul>';
echo '</div>';

// Display the update form
echo '<form method="post" action="admin.php?page=year-updater&step=update">';
echo '<input type="hidden" name="old_year" value="' . $old_year . '">';
echo '<input type="hidden" name="new_year" value="' . $new_year . '">';
echo '<input type="submit" name="submit" value="Change Year">';
echo '</form>';
