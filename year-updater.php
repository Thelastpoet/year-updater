<?php
/*
Plugin Name: Year Updater
Plugin URI: https://nabaleka.com
Description: This plugin allows you to update the year in the titles of your posts to the current year.
Version: 1.3.0
Author: Ammanulah Emmanuel
Author URI: https://nabaleka.com
Text Domain: year-updater
License: GPL2.0
*/

namespace YearUpdater;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Constants
define('YU_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('YU_PLUGIN_URL', plugin_dir_url(__FILE__));
define('YU_VERSION', '1.3.0');

// Dependencies
require_once YU_PLUGIN_PATH . 'includes/yu-settings.php';
require_once YU_PLUGIN_PATH . 'includes/yu-process.php';
require_once YU_PLUGIN_PATH . 'includes/yu-posts-table.php';

class Year_Updater_Main {

    public function __construct() {
        $this->register_hooks();
        $this->year_updater_settings = new YU_Settings();
        $this->year_updater_process = new YU_Process();
    }

    public function register_hooks() {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
        load_plugin_textdomain('year-updater', false, basename(dirname(__FILE__)) . '/languages');
    }

    public function enqueue_assets() {
        wp_enqueue_style('year-updater', YU_PLUGIN_URL . 'assets/css/yu-styles.css', array(), YU_VERSION);
        wp_enqueue_script('year-updater', YU_PLUGIN_URL . 'assets/js/yu-scripts.js', array('jquery'), YU_VERSION, true);
    }
}

// Initialize the plugin
$year_updater_main = new Year_Updater_Main();
