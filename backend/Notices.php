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

use I18n_Notice_WordPressOrg;
use Image_SEO_Generator\Engine\Base;

/**
 * Everything that involves notification on the WordPress dashboard
 */
class Notices extends Base {

	/**
	 * Initialize the class
	 *
	 * @return void|bool
	 */
	public function initialize() {
		if ( !parent::initialize() ) {
			return;
		}

		\wpdesk_wp_notice( \__( 'Updated Messages', ISG_TEXTDOMAIN ), 'updated' );

	}

}
