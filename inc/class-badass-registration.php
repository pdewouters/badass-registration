<?php namespace BadassRegistration;

use BadassRegistration\Fields;
use BadassRegistration\Field;
use BadassRegistration\Input_Field;
use BadassRegistration\Select_Field;

/**
 * Class Badass_Registration
 */
class Badass_Registration {

	/**
	 * @var Fields
	 */
	protected $registration_fields;

	/**
	 * @var
	 */
	private static $_instance;

	/**
	 * Builds the Registration object.
	 *
	 * @param array $fields
	 *
	 * @throws \Exception
	 */
	private function __construct( array $fields ) {

		if ( empty( $fields ) )
			throw new \Exception( 'No fields provided' );

		add_action( 'register_form', array( $this, 'fields' ) );
		add_action( 'user_register', array( $this, 'save_user_data' ) );

		add_filter( 'shake_error_codes', array( $this, 'errors' ) );
		add_filter( 'registration_errors', array( $this, 'registration_errors' ), 10, 3 );
		add_filter( 'login_messages', array( $this, 'login_messages' ) );

		$this->registration_fields = new Fields();

		foreach ( $fields as $field ) {

			$class = '\\BadassRegistration\\' . ucwords( $field['element'] ) . '_Field';

			$this->registration_fields->add(
				new $class( $field )
			);

		}

	}

	/**
	 * @param $fields
	 *
	 * @return Badass_Registration
	 */
	public static function get_instance( $fields ) {

		if ( ! ( self::$_instance instanceof Badass_Registration ) ) {
			self::$_instance = new Badass_Registration( $fields );
		}

		return self::$_instance;
	}

	/**
	 *
	 */
	public function fields() {

		foreach ( $this->registration_fields as $field ) {

			$id = $field->get_attribute( 'id' );

			if ( isset( $_POST[ $id ] ) ) {
				$field->set_attribute( 'value', sanitize_text_field( $_POST[ $id ] ) );
			}
		}

		echo $this->registration_fields;
	}

	/**
	 * @param $errors
	 *
	 * @return array
	 */
	public function errors( $errors ) {

		foreach ( $this->registration_fields as $field ) {

			$id = $field->get_attribute( 'id' );

			$errors[] = 'empty_'. $id;
		}

		return $errors;
	}

	/**
	 *
	 * @param $errors
	 * @param $sanitized_user_login
	 * @param $user_email
	 *
	 * @return mixed
	 */
	public function registration_errors( $errors, $sanitized_user_login, $user_email ) {

		foreach ( $this->registration_fields as $field ) {

			$id = $field->get_attribute( 'id' );
			$label = $field->get_attribute( 'label' );

			if ( 0 === strlen( trim( $_POST[ $id ] ) ) ) {
				$errors->add( $_POST[ $id ], __( '<strong>ERROR</strong>: Please enter your ' . $label . '.', 'badass-registration' ), array( 'form-field' => $_POST[ $id ] ) );
			}
		}

		return $errors;
	}

	/**
	 * Display a confirmation message after successful registration submission.
	 *
	 * @param $messages
	 *
	 * @return string
	 */
	public function login_messages( $messages ) {
		$messages = 'Thank you for registering. An administrator will review your details and let you know when you can log in.';

		return $messages;
	}

	/**
	 * Save custom fields to user meta.
	 *
	 * @param $user_id
	 */
	public function save_user_data( $user_id ) {

		foreach ( $this->registration_fields as $field ) {

			$id = $field->get_attribute( 'id' );

			if ( isset( $_POST[ $id ] ) ) {
				update_user_meta( $user_id, $id, sanitize_text_field( $_POST[ $id ] ) );
			}
		}
	}
}
