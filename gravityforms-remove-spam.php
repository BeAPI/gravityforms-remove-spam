<?php
/*
Plugin Name: Gravity Forms Remove Spam
Version: 1.0.3
Version Boilerplate: 2.2
Plugin URI: https://beapi.fr
Description: Remove spam entries from Gravity Forms based on option fields - sponsored by CDC Habitat
Author: Be API Technical team
Author URI: https://beapi.fr
Domain Path: languages
Text Domain: gravityforms-remove-spam

----

Copyright 2022 Be API Technical team (human@beapi.fr)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


// Plugin constants
define( 'GRAVITYFORMS_REMOVE_SPAM_VERSION', '1.0.3' );
define( 'GRAVITYFORMS_REMOVE_SPAM_MIN_PHP_VERSION', '7.4' );
define( 'GRAVITYFORMS_REMOVE_SPAM_VIEWS_FOLDER_NAME', 'gravityforms-remove-spam' );

// Plugin URL and PATH
define( 'GRAVITYFORMS_REMOVE_SPAM_URL', plugin_dir_url( __FILE__ ) );
define( 'GRAVITYFORMS_REMOVE_SPAM_DIR', plugin_dir_path( __FILE__ ) );
define( 'GRAVITYFORMS_REMOVE_SPAM_PLUGIN_DIRNAME', basename( rtrim( dirname( __FILE__ ), '/' ) ) );

// Check PHP min version
if ( version_compare( PHP_VERSION, GRAVITYFORMS_REMOVE_SPAM_MIN_PHP_VERSION, '<' ) ) {
	require_once GRAVITYFORMS_REMOVE_SPAM_DIR . 'compat.php';

	// possibly display a notice, trigger error
	add_action( 'admin_init', [ 'Gravityforms\Remove_Spam\Compatibility', 'admin_init' ] );

	// stop execution of this file
	return;
}

// Deactivate if ACF PRO or Gravity Forms are not present
add_action( 'admin_init', function() {
	if ( ! class_exists( 'GFForms' ) || ! function_exists( 'acf_add_options_sub_page' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );

		add_action( 'admin_notices', function() {
			if ( ! class_exists( 'GFForms' ) ) {
				echo '<div class="notice notice-error"><p>' . esc_html__( 'The Gravity Forms plugin is required to use BEA GF Remove Spam. Please install and activate it.', 'gravityforms-remove-spam' ) . '</p></div>';
			}
			
			if ( ! function_exists( 'acf_add_options_sub_page' ) ) {
				echo '<div class="notice notice-error"><p>' . esc_html__( 'The ACF PRO plugin is required to use Gravity Forms Remove Spam. Please install and activate it.', 'gravityforms-remove-spam' ) . '</p></div>';
			}
		} );
	}
});

/**
 * Autoload all the things \o/
 */
require_once GRAVITYFORMS_REMOVE_SPAM_DIR . 'compat.php';
require_once GRAVITYFORMS_REMOVE_SPAM_DIR . 'classes/singleton.php';
require_once GRAVITYFORMS_REMOVE_SPAM_DIR . 'classes/main.php';
require_once GRAVITYFORMS_REMOVE_SPAM_DIR . 'classes/parser.php';

add_action( 'plugins_loaded', 'init_gravityforms_remove_spam_plugin' );

add_action( 'plugins_loaded', function() {
    load_plugin_textdomain(
        'gravityforms-remove-spam',
        false,
        dirname( plugin_basename( __FILE__ ) ) . '/languages'
    );
});

/**
 * Init the plugin
 */
function init_gravityforms_remove_spam_plugin() {
	if ( ! class_exists( 'GFForms' ) ) {
		return;
	}

	\Gravityforms\Remove_Spam\Main::get_instance();
	\Gravityforms\Remove_Spam\Parser::get_instance();
}

