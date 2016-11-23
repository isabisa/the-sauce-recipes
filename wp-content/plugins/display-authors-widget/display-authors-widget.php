<?php
/**
 * Plugin Name: Display Authors Widget
 * Plugin URI: https://foxland.fi/downloads/display-authors-widget/
 * Description: Register widget to display authors by role in a sidebar.
 * Version: 1.1.1
 * Author: Sami Keijonen
 * Author URI: https://foxland.fi/
 * Text Domain: display-authors-widget
 * Domain Path: /languages
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package MultiAuthorWidget
 * @version 1.1.1
 * @author Sami Keijonen <sami.keijonen@foxnet.fi>
 * @copyright Copyright (c) 2015, Sami Keijonen
 * @link http://justintadlock.com/archives/2009/05/26/the-complete-guide-to-creating-widgets-in-wordpress-28
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/**
 * Display Authors Widget class.
 *
 * @since 0.1.0
 */
class Display_Authors_Widget extends WP_Widget {

	/**
	 * Set up the widget's unique name, ID, class, description, and other options.
	 *
	 * @since 0.1.0
	 */
	function __construct() {

		/* Set up the widget options. */
		$widget_options = array(
			'classname' => 'display-authors-widget',
			'description' => esc_html__( 'Displays authors by role.', 'display-authors-widget' )
		);

		/* Set up the widget control options. */
		$control_options = array(
			'width' => 200,
			'height' => 350,
			'id_base' => 'display-authors-widget'
		);

		/* Create the widget. */
		parent::__construct(
			'display-authors-widget',									// $this->id_base
			_x( 'Display Authors Widget', 'Name of the widget in widget area.', 'display-authors-widget' ),	// $this->name
			$widget_options,											// $this->widget_options
			$control_options											// $this->control_options
		);
	}
	
	/**
	 * Outputs the widget based on the arguments input through the widget controls.
	 *
	 * @since 0.1.0
	 */
	function widget( $args, $instance ) {
		extract( $args );

		/* Get the avatar size. */
		$avatar_size = absint( $instance['avatar_size'] );
		
		/* Get the limit how many authors to display. */
		$limit = absint( $instance['limit'] );

		/* Open the before widget HTML. */
		echo $before_widget;

		/* Output the widget title. */
		if ( $instance['title'] )
			echo $before_title . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $after_title;
		
		
		/* Query arguments. */
		$query_args = array(
			'role' => $instance['role'],
			'number' => $limit
			);
		
		/* Get only users by role, which user wants. */
		$users = get_users( apply_filters( 'display_authors_widget_query', $query_args ) );

			foreach ( $users as $author ) :
			
			/* Get the author ID. */
			$id = $author->ID;
			
			do_action( 'display_authors_widget_before' ); // action hook display_authors_widget_before
			
			?>

				<div id="hcard-<?php echo str_replace( ' ', '-', get_the_author_meta( 'user_nicename', $id ) ); ?>" class="author-profile vcard clear">

					<?php
					
					/* Output the authors gravatar if selected. */
					if ( $instance['show_gravatar'] ) {
	
						$avatar = get_avatar( get_the_author_meta( 'user_email', $id ), $avatar_size, '', get_the_author_meta( 'display_name', $id ) );
							
						/* Get extra class from settings. */
						$extra_class= 'display-authors-widget-' . $instance['avatar_align']; 
						?>
							
						<div class="<?php echo $extra_class; ?>"><a href="<?php echo get_author_posts_url( $id ); ?>" title="<?php the_author_meta( 'display_name', $id ); ?>"><?php echo $avatar; ?></a></div>
						
					<?php } ?>
					
					<?php do_action( 'display_authors_widget_before_author' ); // action hook display_authors_widget_before_author ?>
					
					<?php
					/* Output the authors name if selected. */
					if ( $instance['show_author_name'] ) { ?>
						<a href="<?php echo get_author_posts_url( $id ); ?>" title="<?php the_author_meta( 'display_name', $id ); ?>"><?php the_author_meta( 'display_name', $id ); ?></a>
					<?php } ?>
					
					
					<?php do_action( 'display_authors_widget_after_author' ); // action hook display_authors_widget_after_author ?>
					
					<?php 
					/* If user post count is selected and user has posts, show it. */
					if ( $instance['show_post_count'] && count_user_posts( $id ) > 0 )
						printf( __( '(%d)', 'display-authors-widget' ), count_user_posts( $id ) );
					?>

					<?php
					/* Show bio if selected. */
					if ( $instance['show_bio'] )
						echo wpautop( get_the_author_meta( 'description', $id ) );
					?>

				</div><!-- .author-profile .vcard -->
			
			<?php do_action( 'display_authors_widget_after' ); // action hook display_authors_widget_after ?>
			
			<?php endforeach; ?>
			
		<?php
		/* Close the after widget HTML. */
		echo $after_widget;
	}
	
	/**
	 * Updates the widget control options for the particular instance of the widget.
	 *
	 * @since 0.1.0
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Set the instance to the new instance. */
		$instance = $new_instance;

		/* Strip tags from elements that don't need them. */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['role'] = strip_tags( $new_instance['role'] );
		$instance['show_post_count'] = strip_tags( $new_instance['show_post_count'] );
		$instance['show_bio'] = strip_tags( $new_instance['show_bio'] );
		$instance['show_gravatar'] = strip_tags( $new_instance['show_gravatar'] );
		$instance['show_author_name'] = strip_tags( $new_instance['show_author_name'] );
		$instance['avatar_size'] = strip_tags( $new_instance['avatar_size'] );
		$instance['avatar_align'] = strip_tags( $new_instance['avatar_align'] );
		$instance['limit'] = strip_tags( $new_instance['limit'] );
		
		return $instance;
		
	}
	
	/**
	 * Displays the widget control options in the Widgets admin screen.
	 *
	 * @since 0.1.0
	 */
	function form( $instance ) {

		/* Set up the defaults. */
		$defaults = apply_filters( 'display_authors_widget_defaults', array(
			'title' 				=> __( 'Authors', 'display-authors-widget' ),
			'role' 					=> 'editor',
			'show_post_count'		=> 1,
			'show_bio' 				=> 1,
			'show_gravatar'			=> 1,
			'show_author_name'		=> 1,
			'avatar_size'			=> '60',
			'avatar_align'			=> 'alignleft',
			'limit'					=> 50
	
		) );

		$instance = wp_parse_args( (array) $instance, $defaults );
		
		?>

			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'display-authors-widget' ); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'role' ); ?>"><?php _e( 'Role:', 'display-authors-widget' ); ?></label> 
				<select class="widefat" id="<?php echo $this->get_field_id( 'role' ); ?>" name="<?php echo $this->get_field_name( 'role' ); ?>">
				
					<?php wp_dropdown_roles( $instance['role'] ); // Dropdown list of roles. ?>

				</select>
			</p>
			
			<p>
				<input type="checkbox" value="1" <?php checked( '1', $instance['show_post_count'] ); ?> id="<?php echo $this->get_field_id( 'show_post_count' ); ?>" name="<?php echo $this->get_field_name( 'show_post_count' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'show_post_count' ); ?>"><?php _e( 'Display Post Count?' , 'display-authors-widget' ); ?></label> 
			</p>

			<p>
				<input type="checkbox" value="1" <?php checked( '1', $instance['show_bio'] ); ?> id="<?php echo $this->get_field_id( 'show_bio' ); ?>" name="<?php echo $this->get_field_name( 'show_bio' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'show_bio' ); ?>"><?php _e( 'Display Author Bio?', 'display-authors-widget' ); ?></label> 
			</p>

			<p>
				<input type="checkbox" value="1" <?php checked( '1', $instance['show_gravatar'] ); ?> id="<?php echo $this->get_field_id( 'show_gravatar' ); ?>" name="<?php echo $this->get_field_name( 'show_gravatar' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'show_gravatar' ); ?>"><?php _e( 'Display Author Gravatar?' , 'display-authors-widget' ); ?></label> 
			</p>
			
			<p>
				<input type="checkbox" value="1" <?php checked( '1', $instance['show_author_name'] ); ?> id="<?php echo $this->get_field_id( 'show_author_name' ); ?>" name="<?php echo $this->get_field_name( 'show_author_name' ); ?>" />
				<label for="<?php echo $this->get_field_id( 'show_author_name' ); ?>"><?php _e( 'Display Author Name?' , 'display-authors-widget' ); ?></label> 
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'avatar_size' ); ?>"><?php _e( 'Gravatar Size:', 'display-authors-widget' ); ?></label>
				<input style="float:right;width:66px;" type="text" class="widefat" id="<?php echo $this->get_field_id( 'avatar_size' ); ?>" name="<?php echo $this->get_field_name( 'avatar_size' ); ?>" value="<?php echo $instance['avatar_size']; ?>" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'avatar_align' ); ?>"><?php _e( 'Gravatar Alignment:', 'display-authors-widget' ); ?></label> 
				<select style="float:right;max-width:66px;" class="widefat" id="<?php echo $this->get_field_id( 'avatar_align' ); ?>" name="<?php echo $this->get_field_name( 'avatar_align' ); ?>">
					<?php foreach ( array( 'alignnone' => __( 'None', 'display-authors-widget'), 'alignleft' => __( 'Left', 'display-authors-widget' ), 'alignright' => __( 'Right', 'display-authors-widget' ) ) as $option_value => $option_label ) { ?>
						<option value="<?php echo $option_value; ?>" <?php selected( $instance['avatar_align'], $option_value ); ?>><?php echo $option_label; ?></option>
					<?php } /* Note. This part is from Unique theme by Justin Tadlock. @link: http://themeforest.net/item/unique-customizable-wordpress-magazine-theme/3004185?ref=greenshady*/ ?>
				</select>
			</p>
			
			<p>
				<input style="float:right;width:66px;" type="text" class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" value="<?php echo $instance['limit']; ?>" />
				<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Limit the number of authors displayed:', 'display-authors-widget' ); ?></label>
			</p>

		<div style="clear:both;">&nbsp;</div>
	<?php
	}

}

/* register Display Authors Widget. */
add_action( 'widgets_init', create_function( '', 'register_widget( "Display_Authors_Widget" );' ) );

/* Set up the plugin on the 'plugins_loaded' hook. */
add_action( 'plugins_loaded', 'display_authors_widget_setup' );

/**
 * Plugin setup function.  Loads actions and filters to their appropriate hook.
 *
 * @since 0.1.0
 */
function display_authors_widget_setup() {
	
	/* Load the translation of the plugin. */
	load_plugin_textdomain( 'display-authors-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	
	/* Enqueue styles. */
	add_action( 'wp_enqueue_scripts', 'display_authors_widget_styles' );
	
}

/**
 * Enqueue styles.
 *
 * @since 0.1.0
 */
function display_authors_widget_styles() {

	if ( !is_admin() )
		wp_enqueue_style( 'display-authors-widget-styles', trailingslashit( plugin_dir_url( __FILE__ ) ) . 'css/display-authors-widget.css', false, 20141604, 'all' );
	
}

?>