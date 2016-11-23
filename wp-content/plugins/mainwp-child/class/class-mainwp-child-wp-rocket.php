<?php

class MainWP_Child_WP_Rocket {
	public static $instance = null;

	public static function Instance() {
		if ( null === MainWP_Child_WP_Rocket::$instance ) {
			MainWP_Child_WP_Rocket::$instance = new MainWP_Child_WP_Rocket();
		}

		return MainWP_Child_WP_Rocket::$instance;
	}

	public function __construct() {

	}

	public function init() {
		if ( get_option( 'mainwp_wprocket_ext_enabled' ) !== 'Y' ) {
			return;
		}

		if ( get_option( 'mainwp_wprocket_hide_plugin' ) === 'hide' ) {
			add_filter( 'all_plugins', array( $this, 'all_plugins' ) );
			add_action( 'admin_menu', array( $this, 'remove_menu' ) );
			add_filter( 'site_transient_update_plugins', array( &$this, 'remove_update_nag' ) );
			add_action( 'wp_before_admin_bar_render', array( $this, 'wp_before_admin_bar_render' ), 99 );
			add_action( 'admin_init', array( $this, 'remove_notices' ) );
		}
	}

	function remove_notices() {
		$remove_hooks['admin_notices'] = array(
			'rocket_bad_deactivations'                    => 10,
			'rocket_warning_plugin_modification'          => 10,
			'rocket_plugins_to_deactivate'                => 10,
			'rocket_warning_using_permalinks'             => 10,
			'rocket_warning_wp_config_permissions'        => 10,
			'rocket_warning_advanced_cache_permissions'   => 10,
			'rocket_warning_advanced_cache_not_ours'      => 10,
			'rocket_warning_htaccess_permissions'         => 10,
			'rocket_warning_config_dir_permissions'       => 10,
			'rocket_warning_cache_dir_permissions'        => 10,
			'rocket_warning_minify_cache_dir_permissions' => 10,
			'rocket_thank_you_license'                    => 10,
			'rocket_need_api_key'                         => 10,
		);
		foreach ( $remove_hooks as $hook_name => $hooks ) {
			foreach ( $hooks as $method => $priority ) {
				MainWP_Helper::remove_filters_with_method_name( $hook_name, $method, $priority );
			}
		}
	}


	public function wp_before_admin_bar_render() {
		global $wp_admin_bar;
		$nodes = $wp_admin_bar->get_nodes();
		if ( is_array( $nodes ) ) {
			foreach ( $nodes as $node ) {
				if ( 'wp-rocket' === $node->parent || ( $node->id = 'wp-rocket' ) ) {
					$wp_admin_bar->remove_node( $node->id );
				}
			}
		}
	}

	function remove_update_nag( $value ) {
		if ( isset( $_POST['mainwpsignature'] ) ) {
			return $value;
		}
		if ( isset( $value->response['wp-rocket/wp-rocket.php'] ) ) {
			unset( $value->response['wp-rocket/wp-rocket.php'] );
		}

		return $value;
	}

	public static function isActivated() {
		if ( ! defined( 'WP_ROCKET_VERSION' ) || ! defined( 'WP_ROCKET_SLUG' ) ) {
			return false;
		}

		return true;
	}

	public function remove_menu() {
		global $submenu;
		if ( isset( $submenu['options-general.php'] ) ) {
			foreach ( $submenu['options-general.php'] as $index => $item ) {
				if ( 'wprocket' === $item[2] ) {
					unset( $submenu['options-general.php'][ $index ] );
					break;
				}
			}
		}
		$pos = stripos( $_SERVER['REQUEST_URI'], 'options-general.php?page=wprocket' );
		if ( false !== $pos ) {
			wp_redirect( get_option( 'siteurl' ) . '/wp-admin/index.php' );
			exit();
		}
	}

	public function all_plugins( $plugins ) {
		foreach ( $plugins as $key => $value ) {
			$plugin_slug = basename( $key, '.php' );
			if ( 'wp-rocket' === $plugin_slug ) {
				unset( $plugins[ $key ] );
			}
		}

		return $plugins;
	}

	public function action() {
		$information = array();
		if ( ! self::isActivated() ) {
			$information['error'] = 'NO_WPROCKET';
			MainWP_Helper::write( $information );
		}
		if ( isset( $_POST['mwp_action'] ) ) {
			MainWP_Helper::update_option( 'mainwp_wprocket_ext_enabled', 'Y' );
			switch ( $_POST['mwp_action'] ) {
				case 'set_showhide':
					$information = $this->set_showhide();
					break;
				case 'purge_cloudflare':
					$information = $this->purge_cloudflare();
					break;
				case 'purge_all':
					$information = $this->purge_cache_all();
					break;
				case 'preload_cache':
					$information = $this->preload_cache();
					break;
				case 'save_settings':
					$information = $this->save_settings();
					break;
				case "load_existing_settings":
					$information = $this->load_existing_settings();
					break;
				case 'optimize_database':
					$information = $this->optimize_database();
					break;
				case 'get_optimize_info':
					$information = $this->get_optimize_info();
					break;
			}
		}
		MainWP_Helper::write( $information );
	}

	function set_showhide() {
		$hide = isset( $_POST['showhide'] ) && ( $_POST['showhide'] === 'hide' ) ? 'hide' : '';
		MainWP_Helper::update_option( 'mainwp_wprocket_hide_plugin', $hide );
		$information['result'] = 'SUCCESS';

		return $information;
	}

	function purge_cloudflare() {
		if ( function_exists( 'rocket_purge_cloudflare' ) ) {
			// Purge CloudFlare
			rocket_purge_cloudflare();

			return array( 'result' => 'SUCCESS' );
		} else {
			return array( 'error' => 'function_not_exist' );
		}
	}

	function purge_cache_all() {
		if ( function_exists( 'rocket_clean_domain' ) || function_exists( 'rocket_clean_minify' ) || function_exists( 'create_rocket_uniqid' ) ) {
			// Remove all cache files
			rocket_clean_domain();

			// Remove all minify cache files
			rocket_clean_minify();

			// Generate a new random key for minify cache file
			$options                   = get_option( WP_ROCKET_SLUG );
			$options['minify_css_key'] = create_rocket_uniqid();
			$options['minify_js_key']  = create_rocket_uniqid();
			remove_all_filters( 'update_option_' . WP_ROCKET_SLUG );
			update_option( WP_ROCKET_SLUG, $options );
			//rocket_dismiss_box( 'rocket_warning_plugin_modification' );

			return array( 'result' => 'SUCCESS' );
		} else {
			return array( 'error' => 'function_not_exist' );
		}
	}

	function preload_cache() {
		if ( function_exists( 'run_rocket_bot' ) ) {
			run_rocket_bot( 'cache-preload', '' );

			return array( 'result' => 'SUCCESS' );
		} else {
			return array( 'error' => 'function_not_exist' );
		}
	}

	function save_settings() {
		$options = maybe_unserialize( base64_decode( $_POST['settings'] ) );
		if ( ! is_array( $options ) || empty( $options ) ) {
			return array( 'error' => 'INVALID_OPTIONS' );
		}

		$old_values = get_option( WP_ROCKET_SLUG );

		$defaults_fields = $this->get_rocket_default_options();
		foreach ( $old_values as $field => $value ) {
			if ( ! isset( $defaults_fields[ $field ] ) ) { // keep other options
				$options[ $field ] = $value;
			}
		}
		if (isset($_POST['do_database_optimization']) && !empty($_POST['do_database_optimization'])) {
			$_POST['wp_rocket_settings']['submit_optimize'] = 1; // simulate POST			
		}

		update_option( WP_ROCKET_SLUG, $options );

		return array( 'result' => 'SUCCESS' );
	}

	function optimize_database() {
		$return = array();
		if (function_exists('do_rocket_database_optimization')) {
			do_rocket_database_optimization();
			$return['result'] = 'SUCCESS';
		}
		return $return;
	}

	function get_optimize_info() {

		if (function_exists('rocket_database_count_cleanup_items')) {
			$information['optimize_info'] = array(
				'total_revisions'         => rocket_database_count_cleanup_items( 'revisions' ),
				'total_auto_draft'         => rocket_database_count_cleanup_items( 'auto_drafts' ),
				'total_trashed_posts'      => rocket_database_count_cleanup_items( 'trashed_posts' ),
				'total_spam_comments'     => rocket_database_count_cleanup_items( 'spam_comments' ),
				'total_trashed_comments'   => rocket_database_count_cleanup_items( 'trashed_comments' ),
				'total_expired_transients' => rocket_database_count_cleanup_items( 'expired_transients' ),
				'total_all_transients'     => rocket_database_count_cleanup_items( 'all_transients' ),
				'total_optimize_tables'    => rocket_database_count_cleanup_items( 'optimize_tables' )
			);
			$information['result'] = 'SUCCESS';
		}
		return $information;
	}

	function load_existing_settings() {
		$options = get_option( WP_ROCKET_SLUG );
		return array('result' => 'SUCCESS', 'options' => $options);
	}

	function get_rocket_default_options() {
		return array(
			//                'secret_cache_key'         => $secret_cache_key,
			'cache_mobile'             => 0,
			'do_caching_mobile_files'     => 0,
			'cache_feed'				  => 0,
			'cache_logged_user'        => 0,
			'cache_ssl'                => 0,
			'emoji'					  => 0,
			'varnish_auto_purge' => 0,
			'manual_preload' => 0,
			'automatic_preload' => 0,
			'sitemap_preload' => 0,
			'sitemap_preload_url_crawl' => 500000,
			'sitemaps' => '',
			'database_revisions' => 0,
			'database_auto_drafts' => 0,
			'database_trashed_posts' => 0,
			'database_spam_comments' => 0,
			'database_trashed_comments' => 0,
			'database_expired_transients' => 0,
			'database_all_transients' => 0,
			'database_optimize_tables' => 0,
			'schedule_automatic_cleanup' => 0,
			'automatic_cleanup_frequency' => 'daily',
			'cache_reject_uri'         => array(),
			'cache_reject_cookies'     => array(),
			'cache_reject_ua'          => array(),
			'cache_query_strings'      => array(),
			'cache_purge_pages'        => array(),
			'purge_cron_interval'      => 24,
			'purge_cron_unit'          => 'HOUR_IN_SECONDS',
			'exclude_css'              => array(),
			'exclude_js'               => array(),
			'deferred_js_files'        => array(),
			'deferred_js_wait'         => array(),
			'lazyload'                 => 0,
			'lazyload_iframes'         => 0,
			'minify_css'               => 0,
			//                'minify_css_key'           => $minify_css_key,
			'minify_css_combine_all'   => 0,
			'minify_js'                => 0,
			//                'minify_js_key'            => $minify_js_key,
			'minify_js_in_footer'      => array(),
			'minify_js_combine_all'    => 0,
			'minify_google_fonts'      => 0,
			'minify_html'              => 0,
			'minify_html_inline_css'   => 0,
			'minify_html_inline_js'    => 0,
			'dns_prefetch'             => 0,
			'cdn'                      => 0,
			'cdn_cnames'               => array(),
			'cdn_zone'                 => array(),
			'cdn_ssl'                  => 0,
			'cdn_reject_files'         => array(),
			'do_cloudflare'            => 0,
			'cloudflare_email'         => '',
			'cloudflare_api_key'       => '',
			'cloudflare_domain'        => '',
			'cloudflare_devmode'       => 0,
			'cloudflare_auto_settings' => 0,
			'cloudflare_old_settings'  => 0,
			'do_beta'                  => 0,
		);
	}
}

