<?php

require_once plugin_dir_path(__FILE__) . 'admin/year-updater-settings.php';
require_once plugin_dir_path(__FILE__) . 'year-updater-process.php';

class Year_Updater {
    public function __construct() {
        $this->register_hooks();
        $this->year_updater_settings = new Year_Updater_Settings();
        $this->year_updater_process = new Year_Updater_Process();
    }

    public function register_hooks() {
        // Register hooks and actions
        add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
    }

    public function enqueue_assets() {
        // Enqueue styles and scripts
        wp_enqueue_style('year-updater', plugins_url('style.css', __FILE__));
        wp_enqueue_script('year-updater', plugins_url('js/year-updater.js', __FILE__));
    }
}