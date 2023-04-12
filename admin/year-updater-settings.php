<?php

require_once plugin_dir_path(__FILE__) . '../year-updater-process.php';

class Year_Updater_Settings {
    private $year_updater_process;

    public function __construct() {
        $this->year_updater_process = new Year_Updater_Process();
        add_action('admin_menu', array($this, 'register_settings_page'));
    }

    public function register_settings_page() {
        add_options_page(
            'Year Updater',
            'Year Updater',
            'manage_options',
            'year-updater',
            array($this, 'display_settings_page')
        );
    }

    public function display_settings_page() {
        // Check user permissions
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        // Display the plugin options page based on the 'step' parameter
        if (isset($_GET['step']) && $_GET['step'] == 'preview') {
            if (check_admin_referer('year_updater_options')) {
                include(plugin_dir_path(__FILE__) . 'preview.php');
            }
        } elseif (isset($_GET['step']) && $_GET['step'] == 'update') {
            if (check_admin_referer('year_updater_options')) {
                $old_year = intval($_POST['old_year']);
                $new_year = intval($_POST['new_year']);
                $this->year_updater_process->update_year($old_year, $new_year);
                include(plugin_dir_path(__FILE__) . 'update.php');
            }
        } else {
            // Display the form to enter the year to update
            ?>
            <div class="wrap">
                <h1>Year Updater</h1>
                <form method="post" action="admin.php?page=year-updater&step=preview">
                    <?php wp_nonce_field('year_updater_options'); ?>
                    <label for="old_year">Enter the year on your posts:</label>
                    <input type="text" name="old_year" id="old_year" />
                    <input type="submit" name="submit" value="Continue" />
                </form>
            </div>
            <?php
        }
    }
}