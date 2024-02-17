<?php

namespace YearUpdater;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class YU_Settings {
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'register_settings_page' ] );
        add_action( 'admin_post_yu_update', [ $this, 'handle_form_submission' ] );
    }

    public function register_settings_page() {
        add_menu_page(
            __( 'Year Updater', 'year-updater' ),
            __( 'Year Updater', 'year-updater' ),
            'manage_options',
            'year-updater',
            [ $this, 'display_settings_page' ],
            'dashicons-calendar'
        );
    }

    public function display_settings_page() {
        $this->display_notices();

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'You do not have sufficient permissions to access this page.', 'year-updater' ) );
        }

        if ( isset( $_GET['post_type'] ) && ! empty( $_GET['post_type'] ) ) {
            $this->display_queried_posts( sanitize_text_field( $_GET['post_type'] ) );
        } else {
            $this->display_form();
        }
    }

    private function display_form() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>">
                <input type="hidden" name="page" value="year-updater">
                <label for="post_type"><?php _e( 'Select Post Type:', 'year-updater' ); ?></label>
                <select id="post_type" name="post_type">
                    <?php
                    $post_types = get_post_types();
                    foreach ( $post_types as $post_type ) {
                        echo '<option value="' . esc_attr( $post_type ) . '">' . esc_html( $post_type ) . '</option>';
                    }
                    ?>
                </select>
                <?php submit_button( __( 'Search Posts', 'year-updater' ) ); ?>
            </form>
        </div>
        <?php
    }

    private function display_queried_posts( $post_type ) {
        $args = [
            'post_type'      => $post_type,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
        ];
    
        $query = new \WP_Query( $args );
    
        $posts_with_year = array_filter( $query->posts, function( $post ) {
            return preg_match( '/\b\d{4}\b/', $post->post_title );
        } );
    
        $posts_table = new YU_Posts_Table( $post_type );
        $posts_table->items = $posts_with_year;
        $posts_table->prepare_items();
    
        ob_start();
        ?>
        <form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
            <input type="hidden" name="action" value="yu_update">
            <input type="hidden" name="post_type" value="<?php echo esc_attr( $post_type ); ?>">
            <?php wp_nonce_field( 'yu_update_action', 'yu_nonce_field' ); ?>
    
            <?php $posts_table->display(); ?>
    
            <input type="submit" name="submit" class="button-primary" value="<?php _e( 'Update Posts', 'year-updater' ); ?>">
        </form>
        <?php
        echo ob_get_clean();
    
        wp_reset_postdata();
    }
    
    public function handle_form_submission() {
        if ( isset( $_POST['submit'] ) && 'Update Posts' === $_POST['submit'] && isset( $_POST['post_type'] ) ) {
            $post_type = sanitize_text_field( $_POST['post_type'] );
    
            require_once YU_PLUGIN_PATH . 'includes/yu-process.php';
            $yu_process = new YU_Process();
            $result = $yu_process->update_year( $post_type );
    
            $query_args = is_wp_error( $result ) ? [ 'message' => 'error' ] : [ 'message' => 'success', 'post_type' => $post_type ];
    
            wp_safe_redirect( add_query_arg( $query_args, admin_url( 'admin.php?page=year-updater' ) ) );
            exit;
        }
    }    

    private function display_notices() {
        if ( isset( $_GET['message'] ) ) {
            if ( 'success' === $_GET['message'] ) {
                $this->display_success_notice();
            } elseif ( 'error' === $_GET['message'] ) {
                $this->display_error_notice();
            }
        }
    }

    private function display_success_notice() {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e( 'All Posts updated successfully!', 'year-updater' ); ?></p>
        </div>
        <?php
    }

    private function display_error_notice() {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e( 'Failed to update posts.', 'year-updater' ); ?></p>
        </div>
        <?php
    }
}
