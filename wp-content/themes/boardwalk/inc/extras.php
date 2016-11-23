<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Boardwalk
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function boardwalk_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'boardwalk_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function boardwalk_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( get_next_posts_link() ) {
		$classes[] = 'next-link';
	}

	if ( get_previous_posts_link() ) {
		$classes[] = 'previous-link';
	}

	if ( 1 == get_theme_mod( 'boardwalk_filter_featured_images' ) ) {
		$classes[] = 'filter-on';
	}

	if ( 1 == get_theme_mod( 'boardwalk_entry_title' ) ) {
		$classes[] = 'title-with-content';
	}

	if ( 1 == get_theme_mod( 'boardwalk_unfixed_header' ) ) {
		$classes[] = 'unfixed-header';
	}

	if ( is_active_sidebar( 'sidebar-1' ) || has_nav_menu( 'primary' ) ) {
		$classes[] = 'has-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'boardwalk_body_classes' );

/**
 * Adds custom classes to the array of post classes.
 *
 * @param array $classes Classes for the post element.
 * @return array
 */
function boardwalk_post_classes( $classes ) {
	if ( 1 != get_theme_mod( 'boardwalk_no_featured_image' ) ) {
		$featured_image = boardwalk_get_image( get_the_ID(), 'boardwalk-featured-image' );
	}

	if ( ( post_password_required() && has_post_thumbnail() ) || ( $featured_image && ! is_singular() ) ) {
		$classes[] = 'has-post-thumbnail';
	}

	return $classes;
}
add_filter( 'post_class', 'boardwalk_post_classes' );

if ( version_compare( $GLOBALS['wp_version'], '4.1', '<' ) ) :
	/**
	 * Filters wp_title to print a neat <title> tag based on what is being viewed.
	 *
	 * @param string $title Default title text for current view.
	 * @param string $sep Optional separator.
	 * @return string The filtered title.
	 */
	function boardwalk_wp_title( $title, $sep ) {
		if ( is_feed() ) {
			return $title;
		}

		global $page, $paged;

		// Add the blog name
		$title .= get_bloginfo( 'name', 'display' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title .= " $sep $site_description";
		}

		// Add a page number if necessary:
		if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
			$title .= " $sep " . sprintf( __( 'Page %s', 'boardwalk' ), max( $paged, $page ) );
		}

		return $title;
	}
	add_filter( 'wp_title', 'boardwalk_wp_title', 10, 2 );

	/**
	 * Title shim for sites older than WordPress 4.1.
	 *
	 * @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
	 * @todo Remove this function when WordPress 4.3 is released.
	 */
	function boardwalk_render_title() {
		?>
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<?php
	}
	add_action( 'wp_head', 'boardwalk_render_title' );
endif;

/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function boardwalk_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', 'boardwalk_setup_author' );

/**
 * Returns the URL from the post.
 *
 * @uses get_the_link() to get the URL in the post meta (if it exists) or
 * the first link found in the post content.
 *
 * Falls back to the post permalink if no URL is found in the post.
 *
 * @return string URL
 */
function boardwalk_get_link_url() {
	$content = get_the_content();
	$has_url = get_url_in_content( $content );

	return ( $has_url && has_post_format( 'link' ) ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}

/**
 * Display menu item description.
 */
function boardwalk_primary_menu_description( $item_output, $item, $depth, $args ) {
	if ( 'primary' == $args->theme_location && $item->description ) {
		$item_output = str_replace( $args->link_after . '</a>', '<span class="menu-item-description">' . $item->description . '</span>' . $args->link_after . '</a>', $item_output );
	}

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'boardwalk_primary_menu_description', 10, 4 );

/**
 * Get one image from a specified post in the following order:
 * Featured Image, first attached image, first image from the_content HTML
 *
 * @param int $id, The post ID to check
 * @param string        $size   The image size to return, defaults to 'featured-home-big'.
 * @param string|array  $attr   Optional. Query string or array of attributes.
 * @return string       $thumb  Thumbnail image with markup.
 */

function boardwalk_get_image( $post_id = null, $size = 'post-thumbnail', $attr = '' ) {
	$post_id = ( null === $post_id ) ? get_the_ID() : $post_id;

	if ( '' != get_the_post_thumbnail( $post_id ) ) {
		return get_the_post_thumbnail( $post_id, $size, $attr );
	}

	$attached_images = get_attached_media( 'image' );
	if ( ! empty( $attached_images ) ) {
		$first_attached_image = array_shift( $attached_images );

		return wp_get_attachment_image( $first_attached_image->ID, $size, false, $attr );
	}

	if ( class_exists( 'Jetpack_PostImages' ) ) {
		global $_wp_additional_image_sizes;

		$args  = array(
			'from_thumbnail'  => false,
			'from_slideshow'  => true,
			'from_gallery'    => true,
			'from_attachment' => false,
		);

		$image = Jetpack_PostImages::get_image( $post_id, $args );

		if ( ! empty( $image ) ) {
			$image['width']  = '';
			$image['height'] = '';

			if ( array_key_exists( $size, $_wp_additional_image_sizes ) ) {
				$image['width']  = $_wp_additional_image_sizes[$size]['width'];
				$image['height'] = $_wp_additional_image_sizes[$size]['height'];
			}

			$image_src = Jetpack_PostImages::fit_image_url( $image['src'], $image['width'], $image['height'] );

			return '<img src="' . esc_url( $image_src ) . '" title="' . esc_attr( strip_tags( get_the_title() ) ) . '" class="attachment-' . esc_attr( $size ) . ' wp-post-image" />';
		}
	}

	return false;
}
