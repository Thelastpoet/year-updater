<?php
// "preview.php" file

$old_year = $_POST['old_year'];
$new_year = date('Y');

$count_posts = wp_count_posts();
$published_posts = $count_posts->publish;

$updated_posts = 0;
$progress = 0;
?>

<div class="progress-container">
  <div id="progress-bar"></div>
</div>

<h2>Preview of updated titles:</h2>
<ul>
<?php
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
    $title = get_the_title();
    $updated_title = str_replace($old_year, $new_year, $title);
    echo '<li>' . $updated_title . '</li>';
    $updated_posts++;
    $progress = ($updated_posts / $published_posts) * 100;
    ?>
    <script>
      document.getElementById("progress-bar").style.width = "<?php echo $progress ?>%";
      document.getElementById("progress-bar").innerHTML = "<?php echo $progress ?>%";
    </script>
    <?php
  }
}
?>
</ul>

<form method="post" action="admin.php?page=year-updater&step=update">
  <input type="hidden" name="old_year" value="<?php echo $old_year ?>">
  <input type="hidden" name="new_year" value="<?php echo $new_year ?>">
  <input type="submit" name="submit" value="Change Year">
</form>
