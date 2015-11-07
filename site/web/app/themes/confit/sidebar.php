<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Confit
 * @since Confit 1.0
 */
?>

		<div id="secondary" class="widget-area" role="complementary">
			<?php do_action( 'before_sidebar' ); ?>
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</div><!-- #secondary .widget-area -->
