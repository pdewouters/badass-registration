<?php

/**
 * Class Badass_Registration
 */
class Badass_Registration {

	/**
	 * @var
	 */
	private static $_instance;

	/**
	 *
	 */
	private function __construct() {

		add_action( 'register_form', array( $this, 'fields' ) );
		add_filter( 'shake_error_codes', array( $this, 'errors' ), 10, 2 );
		add_filter( 'registration_errors', array( $this, 'registration_errors' ), 10, 3 );
		add_filter( 'login_messages', array( $this, 'login_messages' ) );
	}

	/**
	 * @return Badass_Registration
	 */
	public static function get_instance() {

		if ( ! ( self::$_instance instanceof Badass_Registration ) ) {
			self::$_instance = new Badass_Registration();
		}

		return self::$_instance;
	}

	/**
	 *
	 */
	public function fields() {

		$first_name = isset( $_POST['first_name'] ) ? wp_unslash( $_POST['first_name'] ) : '';
		$last_name  = isset( $_POST['last_name'] ) ? wp_unslash( $_POST['last_name'] ) : '';

		$fields = '<p><label for="first_name">' . __( 'First name' ) . '<br /><input type="text" name="first_name" id="first_name" class="input" value="' . esc_attr( $first_name ) . '" size="25" /></label></p>';
		$fields .= '<p><label for="last_name">' . __( 'Last name' ) . '<br /><input type="text" name="last_name" id="last_name" class="input" value="' . esc_attr( $last_name ) . '" size="25" /></label></p>';
		echo apply_filters( 'baddass_registration_fields', $fields );
	}

	/**
	 * @param $errors
	 * @param $redirect_to
	 *
	 * @return array
	 */
	public function errors( $errors, $redirect_to ) {

		$errors[] = 'empty_first_name';
		$errors[] = 'empty_last_name';

		return $errors;
	}

	/**
	 * @param $errors
	 * @param $sanitized_user_login
	 * @param $user_email
	 *
	 * @return mixed
	 */
	public function registration_errors( $errors, $sanitized_user_login, $user_email ) {

		$user_first_name = sanitize_text_field( $_POST['first_name'] );
		$user_last_name  = sanitize_text_field( $_POST['last_name'] );

		if ( 0 === strlen( trim( $user_first_name ) ) ) {
			$errors->add( 'first_name', __( '<strong>ERROR</strong>: Please enter your first name.' ), array( 'form-field' => 'first_name' ) );
		}

		if ( 0 === strlen( trim( $user_last_name ) ) ) {
			$errors->add( 'last_name', __( '<strong>ERROR</strong>: Please enter your last name.' ), array( 'form-field' => 'last_name' ) );
		}

		return $errors;
	}

	/**
	 * @param $messages
	 *
	 * @return string
	 */
	public function login_messages( $messages ) {
		$messages = 'Thank you for registering. An administrator will review your details and let you know when you can log in.';

		return $messages;
	}
}
