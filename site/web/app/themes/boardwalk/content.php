<?php
/**
 * @package Boardwalk
 */
 
$featured_image = get_the_post_thumbnail( get_the_ID(), 'boardwalk-featured-image' );
if ( 1 != get_theme_mod( 'boardwalk_no_featured_image' ) ) {
	$featured_image = boardwalk_get_image( get_the_ID(), 'boardwalk-featured-image' );
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( $featured_image ) : ?>
		<div class="entry-thumbnail">
			<?php echo $featured_image; ?>
		</div><!-- .entry-thumbnail -->
	<?php endif; ?>

	<header class="entry-header">
		<?php if ( 'post' == get_post_type() ) : ?>
			<div class="entry-meta">
				<?php boardwalk_entry_meta(); ?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( boardwalk_get_link_url() ) ), '</a></h1>' ); ?>
	</header><!-- .entry-header -->

	<a href="<?php echo esc_url( boardwalk_get_link_url() ); ?>" class="entry-link"><span class="screen-reader-text"><?php _e( 'Continue reading <span class="meta-nav">&rarr;</span>', 'boardwalk' ); ?></span></a>
</article><!-- #post-## -->
