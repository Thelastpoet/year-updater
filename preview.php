<?php
// "preview.php" file

// Get the old year from the form submission
$old_year = intval($_POST['old_year']);

// Get the current year
$new_year = date('Y');

// Get an array of posts that have not been updated
$posts = get_posts(array(
  'numberposts' => -1,
  'post_type'   => 'post',
  'meta_key'    => '_year_updater_updated',
  'meta_value'  => 0
));

// Build an array of the old and new titles for each post
foreach ($posts as $post) {
  $title = $post->post_title;
  $updated_title = str_replace($old_year, $new_year, $title);
  $post_updates[$post->ID] = array(
    'old_title' => $title,
    'new_title' => $updated_title
  );
}
?>

<div class="wrap">
  <h1>Year Updater</h1>
  <p>Here is a preview of the updated post titles:</p>
  <table class="widefat">
    <thead>
      <tr>
        <th>Old Title</th>
        <th>New Title</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($post_updates as $post_id => $update): ?>
      <tr>
        <td><?php echo esc_html($update['old_title']); ?></td>
        <td><?php echo esc_html($update['new_title']); ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <form method="post" action="admin.php?page=year-updater&step=update">
    <?php wp_nonce_field('year_updater_update'); ?>
    <input type="hidden" name="old_year" value="<?php echo esc_attr($old_year); ?>">
    <input type="hidden" name="new_year" value="<?php echo esc_attr($new_year); ?>">
    <input type="submit" name="submit" value="Update Year">
  </form>
</div>
