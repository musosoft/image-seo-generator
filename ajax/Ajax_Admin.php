<?php

namespace Image_SEO_Generator\Ajax;

use Image_SEO_Generator\Engine\Base;

class Ajax_Admin extends Base {

	public function initialize() {
		if ( !\apply_filters( 'image_seo_generator_isg_ajax_admin_initialize', true ) ) {
			return;
		}

		\add_action( 'wp_ajax_poll_prediction', array( $this, 'poll_prediction_callback' ) );
		\add_action( 'wp_ajax_optimize_image_seo', array( $this, 'optimize_image_seo_callback' ) );
	}

	public function poll_prediction_callback() {
		check_ajax_referer( 'your_nonce_string', 'security' );

		$prediction_id = $_POST['prediction_id'];

		// Fetch the prediction from your database or external service here.
		$prediction = $this->get_prediction( $prediction_id );

		if ( $prediction ) {
			wp_send_json_success( $prediction );
		} else {
			wp_send_json_error( 'Error fetching prediction.' );
		}

		wp_die();
	}

	public function optimize_image_seo_callback() {
		check_ajax_referer( 'your_nonce_string', 'security' );

		$attachment_id      = $_POST['attachment_id'];
		$image_url          = $_POST['image_url'];
		$fields_to_optimize = $_POST['fields_to_optimize'];

		// Optimize the SEO of the image here.
		$result = $this->optimize_seo( $attachment_id, $image_url, $fields_to_optimize );

		if ( $result ) {
			wp_send_json_success( 'Image SEO has been optimized.' );
		} else {
			wp_send_json_error( 'Error optimizing image SEO.' );
		}

		wp_die();
	}

}
