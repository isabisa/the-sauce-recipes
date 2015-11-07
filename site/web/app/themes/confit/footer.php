<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Confit
 * @since Confit 1.0
 */
?>

		</div><!-- #main .site-main -->

		<?php get_sidebar(); ?>

		<footer id="colophon" class="site-footer" role="contentinfo">
			<div class="site-info">
				<?php do_action( 'confit_credits' ); ?>
				<a href="http://wordpress.org/" title="<?php esc_attr_e( 'A Semantic Personal Publishing Platform', 'confit' ); ?>" rel="generator"><?php printf( __( 'Proudly powered by %s', 'confit' ), 'WordPress' ); ?></a>
				<span class="sep"> | </span>
				<?php printf( __( 'Theme: %1$s by %2$s.', 'confit' ), 'Confit', '<a href="https://wordpress.com/themes/confit" rel="designer">WordPress.com</a>' ); ?>
			</div><!-- .site-info -->
		</footer><!-- #colophon .site-footer -->
	</div><!-- #page .hfeed .site -->
</div><!-- #wrapper -->
<?php wp_footer(); ?>

</body>
</html>