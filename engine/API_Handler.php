<?php

class API_Handler {
    const REPLICATE_API_URL = 'https://api.replicate.com/v1/predictions';
    const MODEL_VERSION = 'c4c54e3c8c97cd50c2d2fec9be3b6065563ccf7d43787fb99f84151b867178fe';
    const POLL_ATTEMPTS = 5;
    const POLL_INTERVAL = 20;
    const API_HEADERS = array(
        'Authorization' => 'Token ' . REPLICATE_API_TOKEN,
        'Content-Type'  => 'application/json',
    );

    /**
     * Fetches prediction result for the given URL and headers.
     *
     * @param string $url The URL to fetch the result from.
     * @param array  $headers Headers to be used in the request.
     *
     * @return array|null The fetched data or null if an error occurred.
     */
    public static function fetch_prediction_result( $url, $headers ) {
        $response = wp_remote_get( $url, array( 'headers' => $headers ) );

        if ( is_wp_error( $response ) ) {
            return new WP_Error( 'fetch_prediction_error', $response->get_error_message() );
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );
        return $data;
    }

    /**
     * Helper function to make prediction requests.
     *
     * @param string $prompt_string The prompt for the prediction.
     * @param string $image_url The URL of the image for the prediction.
     *
     * @return array|null The prediction data or null if an error occurred.
     */
    public static function make_prediction_request( $prompt_string, $image_url ) {
        $response = wp_remote_post(
            self::REPLICATE_API_URL,
            array(
                'headers' => self::API_HEADERS,
                'body'    => wp_json_encode(
                    array(
                        'version' => self::MODEL_VERSION,
                        'input'   => array(
                            'prompt'             => $prompt_string,
                            'img'                => $image_url,
                            'max_length'         => 512,
                            'temperature'        => 0.75,
                            'top_p'              => 1,
                            'top_k'              => 0,
                            'repetition_penalty' => 1,
                            'length_penalty'     => 1,
                            'seed'               => -1,
                        ),
                    )
                ),
            )
        );

        if ( is_wp_error( $response ) ) {
            return new WP_Error( 'prediction_request_error', $response->get_error_message() );
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );
        return $data;
    }
}
