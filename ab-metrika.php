<?php
/**
 * Plugin Name: AB Metika
 * Plugin URI:  https://ab-wp.com/projects/plugins/ab-metrika/
 * Description: Simple adding the most common counters to the site and view statistics Yandex.Metrics in the administrative part of the blog.
 * Version:     2.0.2
 * Author:      AB-WP
 * Author URI:  https://ab-wp.com/projects/plugins/ab-metrika/
 * Text Domain: ab-metrika
 * Domain Path: /languages
 * Requires at least: 3.9
 * Tested up to: 4.8
**/
if ( !class_exists( 'AB_Metrika' ) ) {
	class AB_Metrika
	{

		public function __construct()
		{
			if ( is_admin() ) { // admin actions
				$this->load_dependencies();
				$this->define_admin_hooks();
			} else {
				add_action('init', array($this, 'init'));
			}
		}

		private function load_dependencies() 
		{
			require_once plugin_dir_path( __FILE__ ) . 'includes/admin-counters.php';
			require_once plugin_dir_path( __FILE__ ) . 'includes/admin-metrica.php';
		}

		private function define_admin_hooks() 
		{
			add_action('plugins_loaded', array($this,'load_plugin_textdomain'));
			add_action('admin_menu', array($this, 'admin_menu'));
			add_action('admin_init', array($this, 'admin_init'));
		}

		public function admin_menu()
		{
			$admin_metrica = new AB_Metrika_AdminMetrica();
			$admin_counters = new AB_Metrika_AdminCounters();
			
			$page = add_menu_page(
				__( 'AB Metrika', 'ab-metrika' ), 
				__( 'AB Metrika', 'ab-metrika' ), 
				'administrator', 
				'ab_metrika', 
				array( $admin_metrica, 'view' ), 
				'dashicons-chart-bar' );
			add_submenu_page(
				'ab_metrika', 
				__( 'Yandex.Metrica', 'ab-metrika' ), 
				__( 'Yandex.Metrica', 'ab-metrika' ), 
				'administrator', 
				'ab_metrika', 
				array( $admin_metrica, 'view' ));
			add_submenu_page(
				'ab_metrika', 
				__( 'Counters settings', 'ab-metrika' ), 
				__( 'Counters settings', 'ab-metrika' ), 
				'administrator', 
				'counters-settings', 
				array( $admin_counters, 'view' ));
			add_submenu_page(
				'ab_metrika', 
				__( 'Settings', 'ab-metrika' ), 
				__( 'Settings', 'ab-metrika' ), 
				'administrator', 
				'ab_metrika-settings', 
				array( $admin_metrica, 'view_settings' ));
			add_action( 'admin_print_scripts-' . $page, array($this, 'admin_scripts') );
		}

		public function admin_scripts()
		{
			wp_enqueue_script( 'highcharts', plugins_url('js/highcharts/highcharts.js', __FILE__) );
			wp_enqueue_script( 'highcharts-exporting', plugins_url( 'js/highcharts/modules/exporting.js', __FILE__ ) );
		}

		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'ab-metrika', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
		}

		public function init()
		{
			add_action("wp_head", array($this, 'get_head_code'));
			add_action("wp_footer", array($this, 'get_footer_code'));
			add_shortcode("ab-metrika-counter", array($this, 'get_shortcode_counter') );
		}

		public function get_head_code()
		{
			if(get_option('yandex_webmaster') && is_front_page()) {
				echo htmlspecialchars_decode(get_option('yandex_webmaster'))."\n";
			}
			if(get_option('google_search_console') && is_front_page()) {
				echo htmlspecialchars_decode(get_option('google_search_console'))."\n";
			}
			if (get_option('yandex_metrika_position') && (1 == get_option('yandex_metrika_position'))) {
				if(get_option('yandex_metrika')) {
					echo get_option('yandex_metrika')."\n";
				}
			}
			if (get_option('google_analytics_position') && (1 == get_option('google_analytics_position'))) {
				if(get_option('google_analytics')) {
					echo get_option('google_analytics')."\n";
				}
			}
		}

		public function get_footer_code()
		{
			if (!get_option('yandex_metrika_position') || (0 == get_option('yandex_metrika_position'))) {
				if(get_option('yandex_metrika')) {
					echo get_option('yandex_metrika')."\n";
				}
			}
			if (!get_option('google_analytics_position') || (0 == get_option('google_analytics_position'))) {
				if(get_option('google_analytics')) {
					echo get_option('google_analytics')."\n";
				}
			}
		}

		public function get_shortcode_counter($atts)
		{
			$return = '';
			switch ($atts['id']) {
				case 'metrika':
					if (get_option('yandex_metrika_position') && (2 == get_option('yandex_metrika_position'))) {
						if(get_option('yandex_metrika')) {
							$return = get_option('yandex_metrika')."\n";
						}
					}
					break;

					case 'analytics':
						if (get_option('google_analytics_position') && (2 == get_option('google_analytics_position'))) {
							if(get_option('google_analytics')) {
								$return = get_option('google_analytics')."\n";
							}
						}
						break;

				default:
					break;
			}
			return $return;
		}

		public function admin_init()
		{
			register_setting( 'ab_metrica_options_group', 'yandex_webmaster');
			register_setting( 'ab_metrica_options_group', 'google_search_console');
			register_setting( 'ab_metrica_options_group', 'yandex_metrika');
			register_setting( 'ab_metrica_options_group', 'yandex_metrika_position');
			register_setting( 'ab_metrica_options_group', 'google_analytics');
			register_setting( 'ab_metrica_options_group', 'google_analytics_position');
			register_setting( 'ab_metrica_options_group', 'yandex_metrika_token');
			register_setting( 'ab_metrica_options_group', 'yandex_metrika_counter_id');
		}
	}

	$ab_metrika = new AB_Metrika();
}