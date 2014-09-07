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

add_action( 'plugins_loaded', 'badass_registration_init' );

function badass_registration_init() {
	$GLOBALS['BDSSREG'] = Badass_Registration::get_instance();
}