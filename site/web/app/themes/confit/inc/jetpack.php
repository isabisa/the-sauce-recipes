<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Confit
 */

function confit_jetpack_setup() {
	/**
	 * Add theme support for Infinite Scroll.
	 * See: http://jetpack.me/support/infinite-scroll/
	 *
	 * Note: IS has been disabled, pending an OpenTable jQuery issue.
	 */
	// add_theme_support( 'infinite-scroll', array(
	// 	'footer' => 'content',
	// ) );

	/**
	 * Add support for the Nova CPT (menu items)
	 */
	add_theme_support( 'nova_menu_item' );

	/**
	 * Add a custom image size for Site Logo
	 */
	add_image_size( 'confit-logo', 444, 444 );

	/**
	 * Add support for Site Logo
	 */
	add_theme_support( 'site-logo', array( 'size' => 'confit-logo' ) );

	/**
 	 * Add support for Testimonials CPT
 	 */
 	add_theme_support( 'jetpack-testimonial' );
}
add_action( 'after_setup_theme', 'confit_jetpack_setup' );

/**
 * Return early if Site Logo is not available.
 */
function confit_the_site_logo() {
	if ( ! function_exists( 'jetpack_the_site_logo' ) ) {
		return;
	} else {
		jetpack_the_site_logo();
	}
}
