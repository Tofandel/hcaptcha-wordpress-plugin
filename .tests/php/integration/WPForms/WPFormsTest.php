<?php
/**
 * WPFormsTest class file.
 *
 * @package HCaptcha\Tests
 */

namespace HCaptcha\Tests\Integration\WPForms;

use HCaptcha\Tests\Integration\HCaptchaPluginWPTestCase;

/**
 * Test wpforms.php file.
 */
class WPFormsTest extends HCaptchaPluginWPTestCase {

	/**
	 * Plugin relative path.
	 *
	 * @var string
	 */
	protected static $plugin = 'wpforms-lite/wpforms.php';

	/**
	 * Tests hcaptcha_wpforms_display().
	 */
	public function test_hcaptcha_wpforms_display() {
		$expected =
			$this->get_hcap_form() .
			wp_nonce_field(
				'hcaptcha_wpforms',
				'hcaptcha_wpforms_nonce',
				true,
				false
			);

		ob_start();

		hcaptcha_wpforms_display( [] );

		self::assertSame( $expected, ob_get_clean() );
	}

	/**
	 * Test hcaptcha_wpforms_validate().
	 */
	public function test_hcaptcha_wpforms_validate() {
		$fields    = [ 'some field' ];
		$form_data = [ 'id' => 5 ];

		$this->prepare_hcaptcha_get_verify_message( 'hcaptcha_wpforms_nonce', 'hcaptcha_wpforms' );

		wpforms()->objects();
		wpforms()->get( 'process' )->errors = [];

		hcaptcha_wpforms_validate( $fields, [], $form_data );

		self::assertSame( [], wpforms()->get( 'process' )->errors );
	}

	/**
	 * Test hcaptcha_wpforms_validate() not verified.
	 */
	public function test_hcaptcha_wpforms_validate_not_verified() {
		$fields    = [ 'some field' ];
		$form_data = [ 'id' => 5 ];

		$expected = 'The hCaptcha is invalid.';

		$this->prepare_hcaptcha_get_verify_message( 'hcaptcha_wpforms_nonce', 'hcaptcha_wpforms', false );

		wpforms()->objects();
		wpforms()->get( 'process' )->errors = [];

		hcaptcha_wpforms_validate( $fields, [], $form_data );

		self::assertSame( $expected, wpforms()->get( 'process' )->errors[ $form_data['id'] ]['footer'] );
	}
}
