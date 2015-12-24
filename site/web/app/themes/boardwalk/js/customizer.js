/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	// Site title and description.
	wp.customize( 'blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );
	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					'clip': 'auto',
					'color': to,
					'position': 'relative'
				} );
			}
		} );
	} );
	// Filter.
	wp.customize( 'boardwalk_filter_featured_images', function( value ) {
		value.bind( function( to ) {
			if ( true === to ) {
				$( 'body' ).addClass( 'filter-on' );
			} else {
				$( 'body' ).removeClass( 'filter-on' );
			}
		} );
	} );
	// Entry title.
	wp.customize( 'boardwalk_entry_title', function( value ) {
		value.bind( function( to ) {
			if ( true === to ) {
				$( 'body' ).addClass( 'title-with-content' );
			} else {
				$( 'body' ).removeClass( 'title-with-content' );
			}
		} );
	} );
	// Unfixed header.
	wp.customize( 'boardwalk_unfixed_header', function( value ) {
		value.bind( function( to ) {
			if ( true === to ) {
				$( 'body' ).addClass( 'unfixed-header' );
			} else {
				$( 'body' ).removeClass( 'unfixed-header' );
			}
		} );
	} );
} )( jQuery );
