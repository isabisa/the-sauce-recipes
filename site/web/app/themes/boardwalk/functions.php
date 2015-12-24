<?php
/**
 * Boardwalk functions and definitions
 *
 * @package Boardwalk
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 768; /* pixels */
}

if ( ! function_exists( 'boardwalk_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function boardwalk_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Boardwalk, use a find and replace
	 * to change 'boardwalk' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'boardwalk', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'boardwalk-featured-image', 980, 9999 );
	add_image_size( 'boardwalk-hero-image', 2000, 1500, true );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'boardwalk' ),
		'social'  => __( 'Social Menu', 'boardwalk' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link', 'chat', 'gallery',
	) );

	/*
	 * This theme styles the visual editor to resemble the theme style.
	 */
	add_editor_style( array(
		'editor-style.css',
	) );
}
endif; // boardwalk_setup
add_action( 'after_setup_theme', 'boardwalk_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function boardwalk_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'boardwalk' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'boardwalk_widgets_init' );

/**
 * Register Lato and Merriweather fonts.
 *
 * @return string
 */
function boardwalk_lato_merriweather_fonts_url() {
	$fonts_url = '';

	/* translators: If there are characters in your language that are not supported
	 * by Lato, translate this to 'off'. Do not translate into your own language.
	 */
	$lato = _x( 'on', 'Lato font: on or off', 'boardwalk' );

	/* translators: If there are characters in your language that are not supported
	 * by Merriweather, translate this to 'off'. Do not translate into your own language.
	 */
	$merriweather = _x( 'on', 'Merriweather font: on or off', 'boardwalk' );

	if ( 'off' !== $lato || 'off' !== $merriweather ) {
		$font_families = array();

		if ( 'off' !== $lato ) {
			$font_families[] = 'Lato:400,700,400italic,700italic';
		}

		if ( 'off' !== $merriweather ) {
			$font_families[] = 'Merriweather:400,700italic,700,400italic';
		}

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);
		$fonts_url = add_query_arg( $query_args, "https://fonts.googleapis.com/css" );
	}

	return $fonts_url;
}

/**
 * Register Source Code Pro font.
 *
 * @return string
 */
function boardwalk_source_code_pro_font_url() {
	$source_code_pro_font_url = '';

	/* translators: If there are characters in your language that are not supported
	 * by Source Code Pro, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Source Code Pro font: on or off', 'boardwalk' ) ) {
		$query_args = array(
			'family' => urlencode( 'Source Code Pro:400,700' ),
			'subset' => urlencode( 'latin,latin-ext' ),
		);

		$source_code_pro_font_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $source_code_pro_font_url;
}

/**
 * Enqueue scripts and styles.
 */
function boardwalk_scripts() {
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.3' );

	wp_enqueue_style( 'boardwalk-lato-merriweather', boardwalk_lato_merriweather_fonts_url() );

	wp_enqueue_style( 'boardwalk-source-code-pro', boardwalk_source_code_pro_font_url() );

	wp_enqueue_style( 'boardwalk-style', get_stylesheet_uri() );

	wp_enqueue_script( 'boardwalk-pace', get_template_directory_uri() . '/js/pace.js', array( 'jquery' ), '1.0.0', true );

	wp_enqueue_script( 'boardwalk-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( ( is_search() && have_posts() ) || is_archive() || is_home() ) {
		wp_enqueue_script( 'boardwalk-colors', get_template_directory_uri() . '/js/colors.js', array( 'jquery' ), '20141222', true );

		wp_enqueue_script( 'boardwalk-mousewheel', get_template_directory_uri() . '/js/mousewheel.js', array( 'jquery' ), '3.1.12', true );
	}

	wp_enqueue_script( 'boardwalk-script', get_template_directory_uri() . '/js/boardwalk.js', array( 'jquery' ), '20141222', true );
}
add_action( 'wp_enqueue_scripts', 'boardwalk_scripts' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
