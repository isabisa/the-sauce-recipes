<?php
/**
 * @package Confit
 * @since Confit 1.0
 */
// Access global variable directly to set content_width
if ( isset( $GLOBALS['content_width'] ) )
	$GLOBALS['content_width'] = 558;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>

		<div class="entry-meta">
		<?php
			if ( 'nova_menu_item' == get_post_type() ) :
				$price = get_post_meta( $post->ID, 'nova_price', true );
				if ( '' != $price ) :
					$price_text = __( 'Price: %1$s', 'confit' );
					printf( $price_text, esc_html( $price ) );
				endif;
			else :
				confit_posted_on();
			endif;
		?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'confit' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php
			if ( 'nova_menu_item' == get_post_type() ) :
				$terms = wp_get_object_terms( $post->ID, 'nova_menu_item_label' );
				if ( ! empty ( $terms ) ) : ?>
					<span class="menu-labels">
						<?php
							foreach( $terms as $term ) {
								$term_name = $term->name;
								$term_slug = $term->slug;
								echo '<span class="' . $term_slug .'">' . $term_name . '</span>';
							}
						?>
					</span><?php
				endif;
			endif;
		?>

		<?php
			/* translators: used between list items, there is a space after the comma */
			$category_list = get_the_category_list( __( ', ', 'confit' ) );

			/* translators: used between list items, there is a space after the comma */
			$tag_list = get_the_tag_list( '', __( ', ', 'confit' ) );

			if ( ! confit_categorized_blog() ) {
				// This blog only has 1 category so we just need to worry about tags in the meta text
				if ( '' != $tag_list ) {
					$meta_text = __( 'This entry was tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'confit' );
				} else {
					$meta_text = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'confit' );
				}

			} else {
				// But this blog has loads of categories so we should probably display them here
				if ( '' != $tag_list ) {
					$meta_text = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'confit' );
				} else {
					$meta_text = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'confit' );
				}

			} // end check for categories on this blog

			printf(
				$meta_text,
				$category_list,
				$tag_list,
				esc_url( get_permalink() ),
				the_title_attribute( 'echo=0' )
			);
		?>

		<?php edit_post_link( __( 'Edit', 'confit' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
