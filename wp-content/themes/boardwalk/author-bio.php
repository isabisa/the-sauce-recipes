<?php
/**
 * The template for displaying Author bio
 *
 * @package Boardwalk
 */
?>

<div class="entry-author">
	<h2 class="author-heading"><?php printf( __( 'Published by %s', 'boardwalk' ), get_the_author() ); ?></h2>

	<div class="author-avatar">
		<?php
		/**
		 * Filter the author bio avatar size.
		 * @param int $size The avatar height and width size in pixels.
		 */
		$author_bio_avatar_size = apply_filters( 'boardwalk_author_bio_avatar_size', 48 );

		echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size );
		?>
	</div><!-- .author-avatar -->

	<div class="author-description">
		<p class="author-bio">
			<?php the_author_meta( 'description' ); ?>
			<span class="author-link"><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author"><?php printf( __( 'View all posts by %s', 'boardwalk' ), get_the_author() ); ?></a></span>
		</p><!-- .author-bio -->
	</div><!-- .author-description -->
</div><!-- .entry-author -->
