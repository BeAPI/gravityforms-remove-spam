<?php

namespace BEA\GF_Remove_Spam;

class Parser {
	/**
	 * Use the trait
	 */
	use Singleton;

	public $spammy_emails;
	public $spammy_urls;
	public $spammy_links;

	protected function init() {
		// Should an entry be removed because it's spammy ?
		add_action( 'gform_after_submission', array( $this, 'maybe_remove_form_entry' ), 10, 2 );

		// Should notifications be disabled after a submission because it's spammy ?
		add_filter( 'gform_disable_notification', array( $this, 'maybe_disable_notifications' ), 10, 4 );
	}

	/**
	 * Should notifications be disabled for an entry ?
	 *
	 * @param $is_disabled
	 * @param $notification
	 * @param $form
	 * @param $entry
	 *
	 * @return bool
	 * @author Stéphane Gillot
	 */
	public function maybe_disable_notifications( $is_disabled, $notification, $form, $entry ): bool {
		if ( ! empty( $form['fields'] ) && $this->is_spammy( $entry, $form ) ) {
			return true;
		}

		return $is_disabled;
	}

	/**
	 * Is the new entry a spammy entry to delete
	 *
	 * @param $entry
	 * @param $form
	 *
	 * @author Stéphane Gillot
	 */
	public function maybe_remove_form_entry( $entry, $form ): void {
		if ( ! empty( $form['fields'] ) && $this->is_spammy( $entry, $form ) ) {
			\GFAPI::delete_entry( $entry['id'] );
		}
	}

	/**
	 * Get spammy emails from the options page
	 *
	 * @return array
	 * @author Stéphane Gillot
	 */
	public function get_spammy_emails(): array {
		if ( empty( $this->spammy_emails ) ) {
			if ( is_multisite() ) {
				switch_to_blog( get_main_site_id() );
				$this->spammy_emails = \get_field( 'spammy_email_addresses', 'options' ) ?? '';
				restore_current_blog();
			} else {
				$this->spammy_emails = \get_field( 'spammy_email_addresses', 'options' ) ?? '';
			}
		}

		$spammy_emails_array = explode( ',', $this->spammy_emails );
		$spammy_emails_array = array_map( 'trim', $spammy_emails_array );

		return $spammy_emails_array ?? array();
	}

	/**
	 * Get spammy links from the options page
	 *
	 * @return array
	 * @author Stéphane Gillot
	 */
	public function get_spammy_links(): array {
		if ( empty( $this->spammy_links ) ) {
			if ( is_multisite() ) {
				switch_to_blog( get_main_site_id() );
				$this->spammy_links = \get_field( 'spammy_link_in_content', 'options' ) ?? '';
				restore_current_blog();
			} else {
				$this->spammy_links = \get_field( 'spammy_link_in_content', 'options' ) ?? '';
			}
		}

		$spammy_links_array = explode( ',', $this->spammy_links );
		$spammy_links_array = array_map( 'trim', $spammy_links_array );

		return $spammy_links_array ?? array();
	}

	/**
	 * Is the email a spammy one ?
	 *
	 * @param string $email
	 *
	 * @return bool
	 * @author Stéphane Gillot
	 */
	public function check_spammy_email( string $email ): bool {
		return in_array( $email, $this->get_spammy_emails(), true );
	}

	/**
	 * Does the string contain a spammy link ?
	 *
	 * @param string $text
	 *
	 * @return bool
	 * @author Stéphane Gillot
	 */
	public function check_spammy_links( string $text ): bool {
		foreach ( $this->get_spammy_links() as $url ) {
			if ( str_contains( $url, $text ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Is a GF form entry spammy ?
	 *
	 * @param $entry
	 * @param $form
	 *
	 * @return bool
	 * @author Stéphane Gillot
	 */
	public function is_spammy( $entry, $form ): bool {

		foreach ( $form['fields'] as $form_field ) {
			switch ( $form_field->type ) {
				case 'email':
					$email = $entry[ (string) $form_field->id ];
					if ( empty( $email ) ) {
						continue 2;
					}
					$maybe_delete = $this->check_spammy_email( $email );
					if ( $maybe_delete ) {
						\GFAPI::delete_entry( $entry['id'] );

						return true;
					}
					break;
				case 'text':
				case 'textarea':
					$content = $entry[ (string) $form_field->id ];
					if ( empty( $content ) ) {
						continue 2;
					}
					$maybe_delete = $this->check_spammy_links( $content );
					if ( $maybe_delete ) {
						return true;
					}
					break;
				case 'name':
					$pattern = '/^' . preg_quote( $form_field->id, '/' ) . '\.\d+$/';
					foreach ( $entry as $key => $value ) {
						if ( preg_match( $pattern, $key ) ) {
							if ( empty( $value ) ) {
								continue;
							}
							$maybe_delete = $this->check_spammy_links( $value );
							if ( $maybe_delete ) {
								return true;
							}
						}
					}
					break;
				default:
					continue 2;
			}
		}

		return false;
	}
}
