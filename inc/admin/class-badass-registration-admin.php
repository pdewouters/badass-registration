<?php namespace BadassRegistration;

/**
 * Class Badass_Registration_Admin
 */
class Badass_Registration_Admin {

	private static $instance;

	private function __construct() {

		add_action( 'show_user_profile', array( $this, 'fields' ) );
		add_action( 'edit_user_profile', array( $this, 'fields' ) );

		add_action( 'personal_options_update', array( $this, 'save_user_meta' ) );
		add_action( 'edit_user_profile_update', array( $this, 'save_user_meta' ) );

		add_filter( 'manage_users_columns', array( $this, 'add_users_columns' ) );
		add_filter( 'manage_users_custom_column', array( $this, 'populate_users_columns' ), 10, 3 );
		add_filter( 'manage_users_sortable_columns', array( $this, 'sortable_columns' ) );

	}

	public static function get_instance() {

		if ( ! self::$instance instanceof Badass_Registration_Admin ) {
			self::$instance = new Badass_Registration_Admin();

		}

		return self::$instance;

	}
	
	public function get_settings() {
		
		if ( is_multisite() ) {
			$blog_id = get_current_blog_id();
			return get_blog_option( $blog_id, 'badass_registration_settings', $this->default_settings() );
		} else {
			return get_option( 'badass_registration_settings', $this->default_settings() );
		}
	}

	function default_settings() {
		return array();
	}

	/**
	 * Sanitize the user input.
	 *
	 * @param $input
	 *
	 * @return mixed
	 */
	public function validate_settings( $input ) {
		
		return $input;
	}


	/**
	 * Displays the custom user fields on the profile page.
	 *
	 * @param $user
	 */
	public function fields( $user ) {

		if ( ! current_user_can( 'manage_user', $user->ID ) ) {
			return;
		}

		$settings = $this->get_settings();

		?>

		<h3><?php esc_html_e( 'Extra Fields', 'badass-registation' ); ?></h3>

		<table class="form-table">

			<tr>
				<th><label for="last_name">Last Name</label></th>
				<td><input type="text" name="last_name" id="last_name" value="" class="regular-text"></td>
			</tr>

		</table>

	<?php
	}

	/**
	 * Adds columns to the admin users screen.
	 *
	 * @param $columns
	 *
	 * @return array
	 */
	public function add_users_columns( $columns ) {

		return array_merge( $columns, array(
			'user_karma' => __( 'Karma', 'badass-registation' )
		) );

	}

	/**
	 * Display values for the user karma column.
	 *
	 * @param $empty
	 * @param $column_name
	 * @param $user_id
	 *
	 * @return string
	 */
	public function populate_users_columns( $empty, $column_name, $user_id ) {

		if ( 'user_karma' !== $column_name ) {
			return $empty;
		}

		$user_karma = get_user_option( 'hmn_user_karma', $user_id );

		return $user_karma;

	}

	/**
	 * Add ability to sort by user karma on the users list admin view.
	 *
	 * @param $columns
	 */
	public function sortable_columns( $columns ) {

		$columns['user_karma'] = 'user_karma';

		return $columns;
	}

	/**
	 * Saves the custom user meta data.
	 *
	 * @param $user_id
	 */
	public function save_user_meta( $user_id ) {

		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

//		$user_karma = absint( $_POST['hmn_user_karma'] );
//
//		$user_expert_status = (bool) $_POST['hmn_user_expert_status'];
//
//		update_user_option( $user_id, 'hmn_user_karma', $user_karma );
//
//		update_user_option( $user_id, 'hmn_user_expert_status', $user_expert_status );

	}

}
