<?php
if ( ! class_exists( 'WP_CLI' ) ) {
    return;
}

/**
 * Custom WP CLI command for SEO optimization of images.
 */
class Image_SEO_Generator_Command extends WP_CLI_Command {
    /**
     * Optimize image SEO for a specific image.
     *
     * ## OPTIONS
     *
     * <attachment_id>
     * : The ID of the image attachment.
     *
     * ## EXAMPLES
     *
     *     wp image_seo_generator optimize 123
     *
     * @synopsis <attachment_id>
     */
    public function optimize( $args ) {
        // Image ID from command argument.
        $image_id = $args[0];

        if ( ! wp_attachment_is_image( $image_id ) ) {
            WP_CLI::error( 'Invalid or missing attachment_id.' );
            return;
        }

        $image_url = wp_get_attachment_url( $image_id );

        // Fetch optimized SEO data.
        $optimized_data = $this->fetch_optimized_data( $image_url );

        if ( ! $optimized_data ) {
            WP_CLI::error( 'Error fetching optimized data.' );
            return;
        }

        // Update image SEO data.
        update_post_meta( $image_id, '_wp_attachment_image_alt', $optimized_data['alt'] );
        wp_update_post( array(
            'ID'           => $image_id,
            'post_title'   => $optimized_data['title'],
            'post_excerpt' => $optimized_data['caption'],
        ) );

        WP_CLI::success( 'Image SEO metadata has been successfully optimized.' );
    }

    /**
     * Fetches optimized data from the API.
     *
     * @param string $image_url The URL of the image.
     *
     * @return array|null The optimized data or null if an error occurred.
     */
    protected function fetch_optimized_data( $image_url ) {
        $request_url = 'https://api.example.com/optimize?' . http_build_query(['url' => $image_url]);

        $response = wp_remote_get($request_url);

        if ( is_wp_error( $response ) ) {
            WP_CLI::error( 'Error fetching optimized data: ' . $response->get_error_message() );
            return null;
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        if ( ! isset( $data['alt'], $data['title'], $data['caption'] ) ) {
            WP_CLI::error( 'Invalid data received from the API.' );
            return null;
        }

        return [
            'alt' => $data['alt'],
            'title' => $data['title'],
            'caption' => $data['caption'],
        ];
    }
}

// Register the command with WP CLI.
WP_CLI::add_command( 'seo_image_optimizer', 'SEO_Image_Optimizer_Command' );
