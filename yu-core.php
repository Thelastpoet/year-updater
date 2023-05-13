<?php

require_once YU_PLUGIN_PATH . 'includes/yu-settings.php';
require_once YU_PLUGIN_PATH . 'includes/yu-process.php';

class YU_Core {
    public function __construct() {
        $this->register_hooks();
        $this->year_updater_settings = new YU_Settings();
        $this->year_updater_process = new YU_Process();
    }

    public function register_hooks() {
        // Register hooks and actions
        add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
    }

    public function enqueue_assets() {
        // Enqueue styles and scripts
        wp_enqueue_style('year-updater', YU_PLUGIN_URL . 'assets/css/yu-styles.css', array(), YU_VERSION);
        wp_enqueue_script('year-updater', YU_PLUGIN_URL . 'assets/js/yu-scripts.js', array('jquery'), YU_VERSION, true);
    }
}