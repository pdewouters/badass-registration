<?php namespace BadassRegistration;

use BadassRegistration\Fields;
use BadassRegistration\Field;
use BadassRegistration\Text_Field;

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
	 *
	 */
	private function __construct( array $fields = array() ) {

		add_action( 'register_form', array( $this, 'fields' ) );
		add_action( 'user_register', array( $this, 'save_user_data' ), 10, 1 );

		add_filter( 'shake_error_codes', array( $this, 'errors' ), 10, 2 );
		add_filter( 'registration_errors', array( $this, 'registration_errors' ), 10, 3 );
		add_filter( 'login_messages', array( $this, 'login_messages' ) );

		$default_fields = array(
			array(
				'type'       => 'text',
				'id'         => 'first_name',
				'name'       => 'first_name',
				'value'      => '',
				'classes'    => array( 'input' ),
				'label_text' => __( 'First name', 'badass-registration' ),
			),
			array(
				'type'       => 'text',
				'id'         => 'last_name',
				'name'       => 'last_name',
				'value'      => '',
				'classes'    => array( 'input' ),
				'label_text' => __( 'Last name', 'badass-registration' ),
			)
		);

		$fields = wp_parse_args( $fields, $default_fields );

		$this->registration_fields = new Fields();

		foreach ( $fields as $field ) {

			$class = '\\BadassRegistration\\' . ucwords( $field['type'] ) . '_Field';

			$this->registration_fields->add(
				new $class( $field )
			);

		}

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

		foreach ( $this->registration_fields as $field ) {
			if ( isset( $_POST[ $field->id_attr ] ) ) {
				$field->value_attr = $field->sanitize( $_POST[ $field->id_attr ] );
			}
		}

		echo $this->registration_fields;
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

	/**
	 * Save custom fields to user meta.
	 *
	 * @param $user_id
	 */
	public function save_user_data( $user_id ) {

		foreach ( $this->registration_fields as $field ) {
			if ( isset( $_POST[ $field->id_attr ] ) ) {
				update_user_meta( $user_id, $field->id_attr, $field->sanitize( $_POST[ $field->id_attr ] ) );
			}
		}
	}
}
