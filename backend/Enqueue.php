<?php

/**
 * Image_SEO_Generator
 *
 * @package   Image_SEO_Generator
 * @author    Roman Kovac <roman@muso.sk>
 * @copyright 2023 muso.sk
 * @license   GPL 2.0+
 * @link      https://muso.sk
 */

namespace Image_SEO_Generator\Backend;

use Image_SEO_Generator\Engine\Base;

/**
 * This class contain the Enqueue stuff for the backend
 */
class Enqueue extends Base {

	/**
	 * Initialize the class.
	 *
	 * @return void|bool
	 */
	public function initialize() {
		if ( !parent::initialize() ) {
			return;
		}
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function enqueue_admin_styles() {
		$admin_page = \get_current_screen();

		return array();
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function enqueue_admin_scripts() {
		$admin_page = \get_current_screen();
		$scripts    = array();

		// Here is where you enqueue your script.
		// Make sure to replace 'path-to-script' with the actual path to your script.
		wp_enqueue_script( 'image-seo-generator-admin', 'path-to-script/plugin-admin.js', array( 'jquery' ), '1.0.0', true );

		// Localize the script with necessary data
		wp_localize_script(
        'image-seo-generator-admin',
        'params',
        array(
			'ajaxurl'       => admin_url( 'admin-ajax.php' ),
			'nonce'         => wp_create_nonce( 'image-seo-generator-nonce' ),
			'poll_interval' => 5,  // You can modify this value based on your requirements
		)
        );

		return $scripts;
	}

}
