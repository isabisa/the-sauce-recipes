<?php
/**
 * The template used for displaying menu content in page-menu.php
 *
 * @package Confit
 * @since Confit 1.0
 */
// Access global variable directly to set content_width
if ( isset( $GLOBALS['content_width'] ) )
	$GLOBALS['content_width'] = 558;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'menu-item' ); ?>>
	<?php if ( '' != get_the_post_thumbnail() ) : ?>
		<div class="menu-thumbnail">
			<?php the_post_thumbnail( 'confit-menu-thumbnail' ); ?>
		</div>
	<?php endif; ?>

	<header class="entry-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
		<span class="menu-price"><?php echo esc_html( get_post_meta( $post->ID, 'nova_price', true ) ); ?></span>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->

	<footer class="entry-meta">
		<?php
			$terms = wp_get_object_terms( $post->ID, 'nova_menu_item_label' );
			if ( ! empty ( $terms ) ) :
		?>
			<span class="menu-labels">
				<?php
					foreach( $terms as $term ) {
						$term_name = $term->name;
						$term_slug = $term->slug;
						echo '<span class="' . $term_slug .'">' . $term_name . '</span>';
					}
				?>
			</span>
		<?php endif; ?>

		<?php edit_post_link( __( 'Edit', 'confit' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
