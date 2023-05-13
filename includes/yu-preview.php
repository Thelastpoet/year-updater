<?php
// Get the post type from the form submission
$post_type = $_POST['post_type'];

// Get an array of all posts of the selected post type
$posts = get_posts(array(
  'numberposts' => -1,
  'post_type'   => $post_type,
  'post_status' => 'publish',
));

// Prepare table rows
$table_rows = '';
foreach ($posts as $post) {
  $title = esc_html($post->post_title);
  $post_id = esc_html($post->ID);
  $table_rows .= <<<HTML
<tr>
  <td>{$title}</td>
  <td>{$post_id}</td>
  <td>{$post_type}</td>
</tr>
HTML;
}

// Build the full HTML string
$html = <<<HTML
<div class="wrap">
  <h1>Year Updater</h1>
  <h2>Preview of post titles:</h2>
  <form method="post" action="admin.php?page=year-updater&step=update">
    <input type="hidden" name="post_type" value="{$post_type}">
    <input type="submit" name="submit" value="Proceed with Update">
  </form>
  <table class="wp-list-table widefat striped">
    <thead>
      <tr>
        <th>Post Title</th>
        <th>Post ID</th>
        <th>Post Type</th>
      </tr>
    </thead>
    <tbody>
      {$table_rows}
    </tbody>
  </table>
</div>
HTML;

echo $html;
