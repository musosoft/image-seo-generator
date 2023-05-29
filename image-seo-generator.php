<?php

/**
 * @package   Image_SEO_Generator
 * @author    Roman Kovac <roman@muso.sk>
 * @copyright 2023 muso.sk
 * @license   GPL 2.0+
 * @link      https://muso.sk
 *
 * Plugin Name:     Image SEO Generator
 * Plugin URI:      https://github.com/musosoft/image-seo-generator
 * Description:     WordPress Image Title and Alt Text AI Generation Plugin
 * Version:         1.0.0
 * Author:          Roman Kovac
 * Author URI:      https://muso.sk
 * Text Domain:     image-seo-generator
 * License:         GPL 2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path:     /languages
 * Requires PHP:    7.4
 */

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
	die( 'We\'re sorry, but you can not directly access this file.' );
}

define( 'ISG_VERSION', '1.0.0' );
define( 'ISG_TEXTDOMAIN', 'image-seo-generator' );
define( 'ISG_NAME', 'Image SEO Generator' );
define( 'ISG_PLUGIN_ROOT', plugin_dir_path( __FILE__ ) );
define( 'ISG_PLUGIN_ABSOLUTE', __FILE__ );
define( 'ISG_MIN_PHP_VERSION', '7.4' );
define( 'ISG_WP_VERSION', '5.3' );

if ( version_compare( PHP_VERSION, ISG_MIN_PHP_VERSION, '<=' ) ) {
	add_action(
		'admin_init',
		static function() {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
	);
	add_action(
		'admin_notices',
		static function() {
			echo wp_kses_post(
			sprintf(
				'<div class="notice notice-error"><p>%s</p></div>',
				__( '"Image SEO Generator" requires PHP 5.6 or newer.', ISG_TEXTDOMAIN )
			)
			);
		}
	);

	// Return early to prevent loading the plugin.
	return;
}

$image_seo_generator_libraries = require ISG_PLUGIN_ROOT . 'vendor/autoload.php'; //phpcs:ignore

require_once ISG_PLUGIN_ROOT . 'functions/functions.php';

// Documentation to integrate GitHub, GitLab or BitBucket https://github.com/YahnisElsts/plugin-update-checker/blob/master/README.md
Puc_v4_Factory::buildUpdateChecker( 'https://github.com/musosoft/image-seo-generator', __FILE__, 'image-seo-generator' );

if ( ! wp_installing() ) {
	register_activation_hook( ISG_TEXTDOMAIN . '/' . ISG_TEXTDOMAIN . '.php', array( new \Image_SEO_Generator\Backend\ActDeact, 'activate' ) );
	register_deactivation_hook( ISG_TEXTDOMAIN . '/' . ISG_TEXTDOMAIN . '.php', array( new \Image_SEO_Generator\Backend\ActDeact, 'deactivate' ) );
	add_action(
		'plugins_loaded',
		static function () use ( $image_seo_generator_libraries ) {
			new \Image_SEO_Generator\Engine\Initialize( $image_seo_generator_libraries );
		}
	);
}
