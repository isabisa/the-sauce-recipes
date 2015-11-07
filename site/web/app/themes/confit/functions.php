<?php
/**
 * Confit functions and definitions
 *
 * @package Confit
 * @since Confit 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Confit 1.0
 */
if ( ! isset( $content_width ) )
	// The max size for the background image is 1600px wide.
	$content_width = 1600;

if ( ! function_exists( 'confit_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * @since Confit 1.0
 */
function confit_setup() {

	/**
	 * Make theme available for translation
	 * Translations can be filed in the /languages/ directory
	 * If you're building a theme based on Confit, use a find and replace
	 * to change 'confit' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'confit', get_template_directory() . '/languages' );

	/**
	 * Add default posts and comments RSS feed links to head
	 */
	add_theme_support( 'automatic-feed-links' );

	/**
	 * Enable support for Post Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	/**
	 * Adding several sizes for Post Thumbnails
	 */
	add_image_size( 'confit-large-background', 0, 0 );
	add_image_size( 'confit-mobile-background', 0, 1024 );
	add_image_size( 'confit-thumbnail', 618, 0 );
	add_image_size( 'confit-menu-thumbnail', 138, 104, true );
	add_image_size( 'confit-testimonial-thumbnail', 66, 66, true );

	/**
	 * This theme uses wp_nav_menu() in one location.
	 */
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'confit' ),
	) );

	/*
	 * This theme supports custom background color and image, and here
	 * we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array(
		'default-color'    => 'f6f6f6',
		'default-image'    => get_template_directory_uri() . '/images/background.jpg',
		'wp-head-callback' => 'confit_custom_background_cb',
	) );

	/**
	 * Suggest the Jetpack plugin to users
	 */
	add_theme_support( 'theme-plugin-enhancements', array(
	    array(
			'slug'    => 'jetpack',
			'name'    => 'Jetpack by WordPress.com',
			'message' => __( "The Jetpack plugin is needed to use some of Confit's special features, including the food menu custom post type (Custom Content Types module), contact information widget (Extra Sidebar Widgets module), and site logo (no particular module activation needed).", "confit" ),
	    ),
	) );
}
endif; // confit_setup
add_action( 'after_setup_theme', 'confit_setup' );

/**
 * Add a wp-head callback to the custom background
 *
 * @since Confit 1.1
 */
function confit_custom_background_cb() {
	// $background is the saved custom image, or the default image.
	$background = set_url_scheme( get_background_image() );

	// $color is the saved custom color.
	// A default has to be specified in style.css. It will not be printed here.
	$color = get_theme_mod( 'background_color' );

	if ( ! $background && ! $color )
		return;

	$style = $color ? "background-color: #$color;" : '';

	if ( $background ) {
		$image = " background-image: url('$background');";

		$repeat = get_theme_mod( 'background_repeat', 'repeat' );
		if ( ! in_array( $repeat, array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
			$repeat = 'repeat';
		$repeat = " background-repeat: $repeat;";

		$position = get_theme_mod( 'background_position_x', 'left' );
		if ( ! in_array( $position, array( 'center', 'right', 'left' ) ) )
			$position = 'left';
		$position = " background-position: top $position;";

		$attachment = get_theme_mod( 'background_attachment', 'scroll' );
		if ( ! in_array( $attachment, array( 'fixed', 'scroll' ) ) )
			$attachment = 'scroll';
		$attachment = " background-attachment: $attachment";
		$attachment_size_disabled = $attachment . " !important;";
		$attachment .= ';';

		$style .= $image . $repeat . $position . $attachment;
	}
?>
<style type="text/css" id="custom-background-css">
	body.custom-background { <?php echo trim( $style ); ?> }
	<?php
	if ( '' != get_theme_mod( 'confit_disable_background_size' ) )
		echo 'body.custom-background.background-size-disabled { ' . trim( $attachment_size_disabled ) . ' }';
	?>
</style>
<?php
}

/**
 * Register widgetized area and update sidebar with default widgets
 *
 * @since Confit 1.0
 */
function confit_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'confit' ),
		'id'            => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'confit_widgets_init' );

/**
 * Register Google fonts for Confit
 *
 * @since Confit 1.0
 */
function confit_fonts() {
	/* translators: If there are characters in your language that are not supported
	   by Muli, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Muli font: on or off', 'confit' ) ) {
		$protocol = is_ssl() ? 'https' : 'http';
		wp_register_style( 'confit-font-muli', "$protocol://fonts.googleapis.com/css?family=Muli:300,400,300italic,400italic", array(), null );
	}

	/* translators: If there are characters in your language that are not supported
	   by Enriqueta, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Enriqueta font: on or off', 'confit' ) ) {
		$protocol = is_ssl() ? 'https' : 'http';
		wp_register_style( 'confit-font-enriqueta', "$protocol://fonts.googleapis.com/css?family=Enriqueta:400,700&subset=latin,latin-ext", array(), null );
	}
}
add_action( 'init', 'confit_fonts' );

/**
 * Enqueue scripts and styles
 */
function confit_scripts() {
	wp_enqueue_style( 'style', get_stylesheet_uri() );

	wp_enqueue_style( 'confit-font-muli' );

	wp_enqueue_style( 'confit-font-enriqueta' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	if ( is_singular() && wp_attachment_is_image() )
		wp_enqueue_script( 'keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );

	wp_enqueue_script( 'small-menu', get_template_directory_uri() . '/js/small-menu.js', array( 'jquery' ), '20120926', true );
}
add_action( 'wp_enqueue_scripts', 'confit_scripts' );

/**
 * Enqueue Google fonts style to admin screen for custom header display.
 */
function confit_admin_fonts( $hook_suffix ) {
	if ( 'appearance_page_custom-header' != $hook_suffix )
		return;
	wp_enqueue_style( 'confit-font-muli' );
	wp_enqueue_style( 'confit-font-enriqueta' );
}
add_action( 'admin_enqueue_scripts', 'confit_admin_fonts' );

/**
 * Override background image with featured image if there is attached to a page.
 */
function confit_page_background_image() {
	$jetpack_options = get_theme_mod( 'jetpack_testimonials' );

	// Check if it's on mobile or iPad and if it is set $mobile true.
	$mobile = wp_is_mobile();

	// If it's not mobile and if it's not a page, then just bail.
	if ( ! $mobile && ! is_page() && ! is_post_type_archive( 'jetpack-testimonial' ) ) :
		return;

	// It's not mobile but it's page and has a featured image then use the featured image as the background image.
	elseif ( ! $mobile && is_page() && '' != get_the_post_thumbnail() ) :

		$background_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'confit-large-background' ); ?>

		<style type="text/css">
		body.custom-background {
			background-image: url(<?php echo esc_url( $background_image_url[0] ); ?>);
		}
		</style><?php

	// It's not mobile but it's testimonial archive and has a featured image then use the featured image as the background image.
	elseif ( ! $mobile && is_post_type_archive( 'jetpack-testimonial' ) && isset( $jetpack_options['featured-image'] ) && ! empty( $jetpack_options['featured-image'] ) ) :

		$background_image_url = wp_get_attachment_image_src( (int)$jetpack_options['featured-image'], 'confit-large-background' ); ?>

		<style type="text/css">
		body.custom-background {
			background-image: url(<?php echo esc_url( $background_image_url[0] ); ?>);
		}
		</style><?php

	// It's a mobile and it's a page that has a featured image then use the featured image as background image but for #mobile-background-holder so that it sticks when we scroll.
	elseif ( $mobile && is_page() && '' != get_the_post_thumbnail() ) :

		$background_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'confit-mobile-background' ); ?>

		<style type="text/css">
		body.custom-background {
			background-image: none;
		}
		#mobile-background-holder {
			background-image: url(<?php echo esc_url( $background_image_url[0] ); ?>);
		}
		</style><?php

	// It's a mobile and it's a testimonial archive that has a featured image then use the featured image as background image but for #mobile-background-holder so that it sticks when we scroll.
	elseif ( $mobile && is_post_type_archive( 'jetpack-testimonial' ) && isset( $jetpack_options['featured-image'] ) && ! empty( $jetpack_options['featured-image'] ) ) :

		$background_image_url = wp_get_attachment_image_src( (int)$jetpack_options['featured-image'], 'confit-mobile-background' ); ?>

		<style type="text/css">
		body.custom-background {
			background-image: none;
		}
		#mobile-background-holder {
			background-image: url(<?php echo esc_url( $background_image_url[0] ); ?>);
		}
		</style><?php

	// Otherwise just move background image to the holder.
	elseif ( $mobile ) : ?>

		<style type="text/css">
		body.custom-background {
			background-image: none;
		}
		#mobile-background-holder {
			background-image: url(<?php echo esc_url( get_background_image() ); ?>);
		}
		</style><?php

	endif;

}
add_action( 'wp_head', 'confit_page_background_image', 11 );

/**
 * Add a Menu uploader to Customizer.
 */
function confit_customize( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	$wp_customize->add_section( 'confit_settings', array(
		'title'             => __( 'Theme Options', 'confit' ),
		'priority'          => 35,
	) );

	$wp_customize->add_setting( 'menu_upload', array(
		'default'           => '',
		'sanitize_callback' => 'confit_sanitize_upload',
	) );

	$wp_customize->add_control( new WP_Customize_Upload_Control( $wp_customize, 'menu_upload', array(
		'label'             => __( 'Menu Upload (PDF recommended)', 'confit' ),
		'section'           => 'confit_settings',
		'settings'          => 'menu_upload',
		'priority'		    => 1,
	) ) );

	$wp_customize->add_setting( 'confit_disable_background_size', array(
		'default'		    => false,
		'type'			    => 'theme_mod',
		'capability'	    => 'edit_theme_options',
		'sanitize_callback' => 'confit_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'confit_disable_background_size', array(
		'label'			    => __( 'Disable Full Page Background Image', 'confit' ),
		'section'		    => 'confit_settings',
		'type'              => 'checkbox',
		'priority'		    => 2,
	) );
}
add_action( 'customize_register', 'confit_customize' );

/**
 * Sanitize the custom checkbox
 */
function confit_sanitize_checkbox( $input ) {
	if ( $input == 1 ) {
		return 1;
	} else {
		return '';
	}
}

/**
 * Sanitize the custom file upload
 */
function confit_sanitize_upload( $file ) {
	// Match desired file types with regex function
	if ( preg_match( '/\.(pdf|png|jpg|jpeg)$/', $file ) ) {
		return $file;
	} else {
		return '';
	}
}

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/tweaks.php';

/**
 * Require our Theme Plugin Enhancements class.
 */
require get_template_directory() . '/inc/plugin-enhancements.php';

/*
 * Load Jetpack compatibility file.
 */
if ( file_exists( get_template_directory() . '/inc/jetpack.php' ) )
	require get_template_directory() . '/inc/jetpack.php';

/* Flush rewrite rules for Menus CPT on setup and switch */
function confit_flush_rewrite_rules() {
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'confit_flush_rewrite_rules' );

/**
 * Enqueue Javascript postMessage handlers for the Customizer.
 *
 * Binds JavaScript handlers to make the Customizer preview
 * reload changes asynchronously.
 */
function confit_customize_preview_js() {
	wp_enqueue_script( 'confit-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20140721', true );
}
add_action( 'customize_preview_init', 'confit_customize_preview_js' );
