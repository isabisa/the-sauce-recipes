<?php
/**
 * Cubic functions and definitions
 *
 * @package Cubic
 */

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function cubic_setup() {

	/*
	 * Declare textdomain for this child theme.
	 */
	load_child_theme_textdomain( 'cubic', get_stylesheet_directory() . '/languages' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_image_size( 'boardwalk-featured-image', 980, 980, true );

}
add_action( 'after_setup_theme', 'cubic_setup', 11 );

/**
 * Register Montserrat font.
 *
 * @return string
 */
function cubic_montserrat_font_url() {
	$montserrat_font_url = '';

	/* translators: If there are characters in your language that are not supported
	 * by Montserrat, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Montserrat font: on or off', 'cubic' ) ) {
		$query_args = array(
			'family' => urlencode( 'Montserrat:400,700' ),
		);

		$montserrat_font_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $montserrat_font_url;
}

/**
 * Register Playfair Display font.
 *
 * @return string
 */
function cubic_playfair_display_font_url() {
	$playfair_display_font_url = '';

	/* translators: If there are characters in your language that are not supported
	 * by Playfair Display, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Playfair Display font: on or off', 'cubic' ) ) {
		$subsets = 'latin,latin-ext';

		/* translators: To add an additional Playfair Display character subset specific to your language, translate this to 'cyrillic'. Do not translate into your own language. */
		$subset = _x( 'no-subset', 'Playfair Display font: add new subset (cyrillic)', 'cubic' );

		if ( 'cyrillic' == $subset ) {
			$subsets .= ',cyrillic-ext,cyrillic';
		}

		$query_args = array(
			'family' => urlencode( 'Playfair Display:400,700,400italic,700italic' ),
			'subset' => urlencode( $subsets ),
		);

		$playfair_display_font_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $playfair_display_font_url;
}

/**
 * Enqueue scripts and styles.
 */
function cubic_scripts() {
	/* Dequeue*/
	wp_dequeue_style( 'boardwalk-lato-merriweather' );

	wp_dequeue_style( 'boardwalk-style' );

	if ( ( is_search() && have_posts() ) || is_archive() || is_home() ) {
		wp_dequeue_script( 'boardwalk-mousewheel' );
	}

	wp_dequeue_script( 'boardwalk-script' );

	/* Enqueue */
	wp_enqueue_style( 'cubic-montserrat', cubic_montserrat_font_url(), array(), null );

	wp_enqueue_style( 'cubic-playfair-display', cubic_playfair_display_font_url(), array(), null );

	wp_enqueue_style( 'cubic-parent-style', get_template_directory_uri() . '/style.css' );

	if ( is_rtl() ) {
		wp_enqueue_style( 'cubic-parent-style-rtl', get_template_directory_uri() . '/rtl.css', array( 'cubic-parent-style' ) );
	}

    wp_enqueue_style( 'cubic-style', get_stylesheet_uri(), array( 'cubic-parent-style' ) );

    if ( ( is_search() && have_posts() ) || is_archive() || is_home() ) {
		wp_enqueue_script( 'cubic-hentry', get_stylesheet_directory_uri() . '/js/hentry.js', array( 'jquery' ), '20150113', true );
	}

	wp_enqueue_script( 'cubic-script', get_stylesheet_directory_uri() . '/js/cubic.js', array( 'jquery' ), '20150113', true );
}
add_action( 'wp_enqueue_scripts', 'cubic_scripts', 11 );

/**
 * Load Jetpack compatibility file.
 */
require get_stylesheet_directory() . '/inc/jetpack.php';
