<?php
/*
Plugin Name: Year Updater
Plugin URI: https://nabaleka.com
Description: This plugin allows you to update the year in the titles of your posts to the current year.
Version: 1.1
Author: Ammanulah Emmanuel
Author URI: 
Text Domain: year-updater
License: GPL3.0
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

define('YEAR_UPDATER_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('YEAR_UPDATER_PLUGIN_URL', plugin_dir_url(__FILE__));
define('YEAR_UPDATER_PLUGIN_BASENAME', plugin_basename(__FILE__));

require_once YEAR_UPDATER_PLUGIN_PATH . 'core.php';

class Year_Updater_Main {

    public function __construct() {
        $this->year_updater = new Year_Updater();
        $this->year_updater->register_hooks();
    }

    public function load_textdomain() {
        load_plugin_textdomain('year-updater', false, basename(dirname(__FILE__)) . '/languages');
    }
}

function init_year_updater() {
    $year_updater_main = new Year_Updater_Main();
    return $year_updater_main;
}

// Initialize the plugin
global $year_updater_main;
$year_updater_main = init_year_updater();

// Load plugin text domain
add_action('plugins_loaded', array($year_updater_main, 'load_textdomain'));