<?php

if ( ! class_exists( 'WP_CLI' ) ) {
    return;
}

WP_CLI::add_command( 'yu-update', 'YU_CLI_Command' );

class YU_CLI_Command extends WP_CLI_Command {

    /**
     * Update year in the posts of a specific post type.
     *
     * ## OPTIONS
     *
     * <post_type>
     * : The post type in which the year should be updated.
     *
     * ## EXAMPLES
     *
     *     wp yu-update post
     *
     * @synopsis <post_type>
     */
    public function __invoke( $args, $assoc_args ) {
        list( $post_type ) = $args;

        require_once YU_PLUGIN_PATH . 'includes/yu-process.php';
        $yu_process = new YU_Process();
        $result = $yu_process->update_year($post_type);

        if (is_wp_error($result)) {
            WP_CLI::error( $result->get_error_message() );
        } else {
            WP_CLI::success( 'Posts updated successfully!' );
        }
    }
}