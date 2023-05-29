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

/**
 * Get the settings of the plugin in a filterable way
 *
 * @since 1.0.0
 * @return array
 */
function isg_get_settings() {
	return apply_filters( 'isg_get_settings', get_option( ISG_TEXTDOMAIN . '-settings' ) );
}
