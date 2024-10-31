<?php
/*
 * Plugin Name: MYST Facebook Comment
 * Version: 1.0
 * Plugin URI: http://myst.my/
 * Description: Replace standard Wordpress Comment into Facebook Comment.
 * Author: Myst Studios
 * Author URI: http://myst.my/
 * Requires at least: 4.0
 * Tested up to: 4.0
 *
 * Text Domain: myst-facebook-comment
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Myst Studios
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'includes/class-myst-facebook-comment.php' );
require_once( 'includes/class-myst-facebook-comment-settings.php' );

// Load plugin libraries
require_once( 'includes/lib/class-myst-facebook-comment-admin-api.php' );
require_once( 'includes/lib/class-myst-facebook-comment-post-type.php' );
require_once( 'includes/lib/class-myst-facebook-comment-taxonomy.php' );

// Load frontend
if ( !is_admin() ){
	require_once( 'includes/frontend.php' );
}

/**
 * Returns the main instance of MYST_Facebook_Comment to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object MYST_Facebook_Comment
 */
function MYST_Facebook_Comment () {
	$instance = MYST_Facebook_Comment::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = MYST_Facebook_Comment_Settings::instance( $instance );
	}

	return $instance;
}

MYST_Facebook_Comment();