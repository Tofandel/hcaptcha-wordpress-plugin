<?php
/**
 * Login class file.
 *
 * @package hcaptcha-wp
 */

namespace HCaptcha\UM;

/**
 * Class Login
 */
class Login extends Base {

	/**
	 * UM action.
	 */
	const UM_ACTION = 'um_submit_form_errors_hook_login';

	/**
	 * UM mode.
	 */
	const UM_MODE = 'login';

	/**
	 * Init hooks.
	 */
	protected function init_hooks() {
		parent::init_hooks();

		add_filter( 'login_errors', [ $this, 'mute_login_hcaptcha_notice' ], 10, 2 );
	}

	/**
	 * Prevent showing hcaptcha error before the login form.
	 *
	 * @param string $message   Message.
	 * @param string $error_key Error_key.
	 *
	 * @return string
	 */
	public function mute_login_hcaptcha_notice( $message, $error_key = '' ) {
		if ( self::KEY !== $error_key ) {
			return $message;
		}

		return '';
	}
}
