<?php
/**
 * Boardwalk Theme Customizer
 *
 * @package Boardwalk
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function boardwalk_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	/* Theme Options */
	$wp_customize->add_section( 'boardwalk_theme_options', array(
		'title'    => __( 'Theme Options', 'boardwalk' ),
		'priority' => 130,
	) );

	/* Filter */
	$wp_customize->add_setting( 'boardwalk_filter_featured_images', array(
		'sanitize_callback' => 'boardwalk_sanitize_checkbox',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'boardwalk_filter_featured_images', array(
		'label'             => __( 'Add a dark filter to featured images', 'boardwalk' ),
		'section'           => 'boardwalk_theme_options',
		'priority'          => 10,
		'type'              => 'checkbox',
	) );

	/* Author bio */
	$wp_customize->add_setting( 'boardwalk_author_bio', array(
		'sanitize_callback' => 'boardwalk_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'boardwalk_author_bio', array(
		'label'             => __( 'Show author bio on single posts.', 'boardwalk' ),
		'section'           => 'boardwalk_theme_options',
		'priority'          => 20,
		'type'              => 'checkbox',
	) );

	/* Entry title */
	$wp_customize->add_setting( 'boardwalk_entry_title', array(
		'sanitize_callback' => 'boardwalk_sanitize_checkbox',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'boardwalk_entry_title', array(
		'label'             => __( 'Keep title with content on single posts and pages.', 'boardwalk' ),
		'section'           => 'boardwalk_theme_options',
		'priority'          => 30,
		'type'              => 'checkbox',
	) );

	/* Unfixed header */
	$wp_customize->add_setting( 'boardwalk_unfixed_header', array(
		'sanitize_callback' => 'boardwalk_sanitize_checkbox',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( 'boardwalk_unfixed_header', array(
		'label'             => __( 'Unfixed header when scrolling down.', 'boardwalk' ),
		'section'           => 'boardwalk_theme_options',
		'priority'          => 40,
		'type'              => 'checkbox',
	) );

	/* No featured image */
	$wp_customize->add_setting( 'boardwalk_no_featured_image', array(
		'sanitize_callback' => 'boardwalk_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'boardwalk_no_featured_image', array(
		'label'             => __( 'If featured image isn\'t set, display colored block on blog page.', 'boardwalk' ),
		'section'           => 'boardwalk_theme_options',
		'priority'          => 50,
		'type'              => 'checkbox',
	) );
}
add_action( 'customize_register', 'boardwalk_customize_register' );

/**
 * Sanitize the checkbox.
 *
 * @param boolean $input.
 * @return boolean (true|false).
 */
function boardwalk_sanitize_checkbox( $input ) {
	if ( 1 == $input ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function boardwalk_customize_preview_js() {
	wp_enqueue_script( 'boardwalk-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20141209', true );
}
add_action( 'customize_preview_init', 'boardwalk_customize_preview_js' );
