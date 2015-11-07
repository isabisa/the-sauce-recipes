<?php
/**
 * Sample implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 *
 * @package Confit
 * @since Confit 1.0
 */

/**
 * Setup the WordPress core custom header feature.
 *
 * @uses confit_header_style()
 * @uses confit_admin_header_style()
 * @uses confit_admin_header_image()
 *
 * @package Confit
 */
function confit_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'comet_custom_header_args', array(
		'default-image'          => '',
		'default-text-color'     => 'E94F1D',
		'width'                  => 222,
		'height'                 => 74,
		'flex-width'             => true,
		'flex-height'            => true,
		'wp-head-callback'       => 'confit_header_style',
		'admin-head-callback'    => 'confit_admin_header_style',
		'admin-preview-callback' => 'confit_admin_header_image',
	) ) );
}
add_action( 'after_setup_theme', 'confit_custom_header_setup' );

if ( ! function_exists( 'confit_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see confit_custom_header_setup().
 *
 * @since Confit 1.0
 */
function confit_header_style() {

	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
	if ( HEADER_TEXTCOLOR == get_header_textcolor() )
		return;
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == get_header_textcolor() ) :
	?>
		.site-title,
		.site-description {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		.site-title a {
			color: #<?php echo get_header_textcolor(); ?> !important;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // confit_header_style

if ( ! function_exists( 'confit_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see confit_custom_header_setup().
 *
 * @since Confit 1.0
 */
function confit_admin_header_style() {
?>
	<style type="text/css">
	.appearance_page_custom-header #headimg {
		border: none;
		max-width: 222px;
	}
	#headimg h1,
	#desc {
		margin: 0;
		padding: 0;
	}
	#headimg h1 {
		font-family: 'Muli', Helvetica, Arial, sans-serif;
		font-size: 24px;
		font-weight: 300;
		line-height: 1;
	}
	#headimg h1 a {
		color: #E94F1D;
		text-decoration: none;
	}
	#headimg h1 a:hover {
		color: #85AA0C;
	}
	#desc {
		color: #8C8885 !important;
		font-family: "Enriqueta", Helvetica, Arial, sans-serif;
		font-size: 13px;
		font-weight: 400;
		line-height: 1.8571428571;
	}
	#headimg img {
		display: block;
		margin: 0 auto 24px;
		max-width: 100%
	}
	</style>
<?php
}
endif; // confit_admin_header_style

if ( ! function_exists( 'confit_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see confit_custom_header_setup().
 *
 * @since Confit 1.0
 */
function confit_admin_header_image() { ?>
	<div id="headimg">
		<?php
		if ( 'blank' == get_header_textcolor() || '' == get_header_textcolor() )
			$style = ' style="display:none;"';
		else
			$style = ' style="color:#' . get_header_textcolor() . ';"';
		?>
		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
		<?php endif; ?>
		<h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
	</div>
<?php }
endif; // confit_admin_header_image