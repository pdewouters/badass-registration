<?php
/*
Plugin Name: Badass-registration
Version: 0.1-alpha
Description: PLUGIN DESCRIPTION HERE
Author: YOUR NAME HERE
Author URI: YOUR SITE HERE
Plugin URI: PLUGIN SITE HERE
Text Domain: badass-registration
Domain Path: /languages
*/

require_once plugin_dir_path( __FILE__ ) . 'inc/class-badass-registration.php';
require_once plugin_dir_path( __FILE__ ) . 'inc/lib/class-fields.php';

add_action( 'plugins_loaded', 'badass_registration_init' );

function badass_registration_init() {

	$json = file_get_contents( apply_filters( 'badass_fields_file', plugin_dir_path( __FILE__ ) . 'inc/fields.json' ), FILE_USE_INCLUDE_PATH );
	$fields = json_decode( $json, true );
	$GLOBALS['BDSSREG'] = \BadassRegistration\Badass_Registration::get_instance( $fields );
}
