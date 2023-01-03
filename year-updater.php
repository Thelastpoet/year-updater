<?php
/*
Plugin Name: Year Updater
Plugin URI: https://nabaleka.com
Description: This plugin allows you to update the year in the titles of your posts to the current year.
Version: 1.1
Author: Ammanulah Emmanuel
Author URI: 
License: GPL3.0
*/
defined( 'ABSPATH' ) or die( 'No direct access allowed!' );

/**
 * Enqueue the plugin stylesheet.
 */
function year_updater_styles() {
  wp_enqueue_style('year-updater', plugins_url('style.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'year_updater_styles');

/**
 * Add the plugin options page to the WordPress administration dashboard.
 */
function year_updater_menu() {
  add_options_page(
    'Year Updater',
    'Year Updater',
    'manage_options',
    'year-updater',
    'year_updater_options'
  );
}
add_action('admin_menu', 'year_updater_menu');

/**
 * Display the plugin options page.
 */
function year_updater_options() {
  if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
  }

  if (isset($_GET['step']) && $_GET['step'] == 'preview') {
    include(plugin_dir_path(__FILE__) . 'preview.php');
  } else if (isset($_GET['step']) && $_GET['step'] == 'update') {
    include(plugin_dir_path(__FILE__) . 'update.php');
  } else {
    // Display the form to enter the year to update.
    ?>
    <div class="wrap">
      <h1>Year Updater</h1>
      <form method="post" action="admin.php?page=year-updater&step=preview">
        <?php wp_nonce_field('year_updater_options'); ?>
        <label for="old_year">Enter the year to update:</label>
        <input type="text" name="old_year" id="old_year" />
        <input type="submit" name="submit" value="Continue" />
      </form>
    </div>
    <?php
  }
}
