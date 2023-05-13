<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class YU_Settings {
    public function __construct() {
        add_action('admin_menu', [$this, 'register_settings_page']);
        add_action('admin_post_yu_update', [$this, 'handle_form_submission']);
        add_action('admin_init', [$this, 'handle_form_submission']);
    }

    public function register_settings_page() {
        add_menu_page(
            __( 'Year Updater', 'year-updater' ),
            __( 'Year Updater', 'year-updater' ),
            'manage_options',
            'year-updater',
            [$this, 'display_settings_page'],
            'dashicons-calendar'
        ); 
    }

    public function display_settings_page() {
        $this->display_notices();
    
        if (isset($_GET['post_type'])) {
            $this->display_queried_posts($_GET['post_type']);
        } elseif (isset($_POST['submit']) && $_POST['submit'] === 'Query Posts' && isset($_POST['post_type'])) {
            $this->display_queried_posts($_POST['post_type']);
        } else {
            $this->display_form();
        }
    }

    private function display_form() {
        if ( !current_user_can( 'manage_options' )) {
            wp_die( __('You do not have sufficient permissions to access this page.' ));
        }

        ?>
        <div class="wrap">
            <h1>Year Updater</h1>
            <form method="post" action="<?php echo admin_url('admin.php?page=year-updater'); ?>">
                <label for="post_type">Select Post Type:</label>
                <select id="post_type" name="post_type">
                    <?php
                    $post_types = get_post_types();
                    foreach ($post_types as $post_type) {
                        printf('<option value="%s">%s</option>', $post_type, $post_type);
                    }
                    ?>
                </select>
                <input type="submit" name="submit" value="Query Posts">
            </form>
        </div>
        <?php
    }

    private function display_queried_posts($post_type) {
        require_once YU_PLUGIN_PATH . 'includes/yu-posts-table.php';
        $posts_list_table = new YU_Posts_Table();
        $posts_list_table->prepare_items();
        ?>
        <div class="wrap">
            <h1>Year Updater</h1>
            <h2>Queried Posts With Year in Title:</h2>
            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <input type="hidden" name="action" value="yu_update">
                <input type="hidden" name="post_type" value="<?php echo esc_attr($post_type); ?>">
                <?php wp_nonce_field('yu_update_action', 'yu_nonce_field'); ?>
                <?php $posts_list_table->display(); ?>
                <input type="submit" name="submit" value="Update Posts">
            </form>
        </div>
        <?php
    } 
    
    public function handle_form_submission() {
        if (isset($_POST['submit']) && $_POST['submit'] === 'Update Posts' && isset($_POST['post_type'])) {
            $post_type = sanitize_text_field($_POST['post_type']);
    
            require_once YU_PLUGIN_PATH . 'includes/yu-process.php';
            $yu_process = new YU_Process();
            $result = $yu_process->update_year($post_type);
    
            if (is_wp_error($result)) {
                $query_args = array('message' => 'error');
            } else {
                $query_args = array('message' => 'success', 'post_type' => $post_type);
            }
    
            wp_safe_redirect(add_query_arg($query_args, admin_url('admin.php?page=year-updater')));
            exit;
        }
    }    

    private function display_notices() {
        if (isset($_GET['message'])) {
            switch ($_GET['message']) {
                case 'success':
                    $this->display_success_notice();
                    break;
                case 'error':
                    $this->display_error_notice();
                    break;
            }
        }
    }

    private function display_success_notice() {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e('All Posts updated successfully!', 'year-updater'); ?></p>
        </div>
        <?php
    }

    private function display_error_notice() {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e('Failed to update posts.', 'year-updater'); ?></p>
        </div>
        <?php
    }    
}