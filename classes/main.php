<?php

namespace BEA\GF_Remove_Spam;

/**
 * The purpose of the main class is to init all the plugin base code like :
 *  - Taxonomies
 *  - Post types
 *  - Shortcodes
 *  - Posts to posts relations etc.
 *  - Loading the text domain
 *
 * Class Main
 * @package BEA\GF_Remove_Spam
 */
class Main {
	/**
	 * Use the trait
	 */
	use Singleton;

	/**
	 * Init the Main class
	 *
	 * @author Stéphane Gillot
	 */
	protected function init() {
		\add_action( 'plugins_loaded', [ $this, 'init_translations' ] );
		\add_action( 'init', [ $this, 'add_subpage' ] );
		\add_action( 'init', [ $this, 'register_fields' ] );
	}

	/**
	 * Load the plugin translation
	 */
	public function init_translations() {
		// Load translations
		\load_plugin_textdomain( 'bea-gf-remove-spam', false, BEA_GF_REMOVE_SPAM_PLUGIN_DIRNAME . '/languages' );
	}

	/**
	 * Add a subpage option under GF menu
	 *
	 * @author Stéphane Gillot
	 */
	public function add_subpage() {
		if ( ! is_main_site() ) {
			return;
		}

		acf_add_options_sub_page( [
			'page_title'  => 'Spam Options',
			'menu_title'  => 'Spam',
			'parent_slug' => 'gf_edit_forms',
		] );
	}

	/**
	 * Register ACF fields
	 */
	public function register_fields() {
		$fields = [
			'spam',
		];

		foreach ( $fields as $field ) {
			$field_path = sprintf(
				'%s/%s.php',
				BEA_GF_REMOVE_SPAM_DIR . 'assets/acf/php',
				$field
			);

			if ( file_exists( $field_path ) ) {
				require_once $field_path;
			}
		}
	}
}
