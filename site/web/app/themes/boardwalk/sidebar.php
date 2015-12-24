<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package Boardwalk
 */

if ( ! is_active_sidebar( 'sidebar-1' ) && ! has_nav_menu( 'primary' ) ) {
	return;
}
?>

<div id="sidebar" class="sidebar" aria-hidden="true">
	<div class="sidebar-content">
		<?php if ( has_nav_menu( 'primary' ) ) : ?>
			<nav id="site-navigation" class="main-navigation" role="navigation">
				<h1 class="menu-title"><?php _e( 'Menu', 'boardwalk' ); ?></h1>
				<?php
					wp_nav_menu( array(
						'theme_location'  => 'primary',
						'container_class' => 'menu-primary',
						'menu_class'      => 'clear',
					) );
				?>
			</nav><!-- #site-navigation -->
		<?php endif; ?>

		<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<div id="secondary" class="widget-area" role="complementary">
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</div><!-- #secondary -->
		<?php endif; ?>
	</div><!-- .sidebar-content -->
</div><!-- #sidebar -->
