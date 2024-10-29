<?php
if ( !class_exists( 'AB_Metrika_AdminCounters' ) ) {
	class AB_Metrika_AdminCounters
	{


		public function __construct ()
		{
			add_action('load-metrica_page_counters-settings', array($this, 'add_admin_help_tab'), 20);
		}

		public function view() {
			ob_start();
			include plugin_dir_path( __FILE__ ) . 'page-admin-counters.php';
			$content = ob_get_clean();
			echo $content;
		}

		public function add_admin_help_tab ()
		{
			$screen = get_current_screen();
			if( !method_exists( $screen, 'add_help_tab' ) )
				return;


			$sidebar = '<ul>
					<li><a href="https://yandex.ru/support/webmaster/service/about.xml">'.__( 'Yandex.Webmaster', 'ab-metrika' ).' '.__( 'help', 'ab-metrika' ).'</a></li>
					<li><a href="https://yandex.ru/support/metrika/">'.__( 'Yandex.Metrika', 'ab-metrika' ).' '.__( 'help', 'ab-metrika' ).'</a></li>
					<li><a href="https://support.google.com/webmasters/" target="_blank">'.__( 'Google Search Console Help Center', 'ab-metrika' ).'</a></li>
					<li><a href="https://support.google.com/analytics" target="_blank">'.__( 'Google Analytics Help Center', 'ab-metrika' ).'</a></li>
				</ul>';


			$screen->add_help_tab(
				array(
					'title' => __('Overview', 'ab-metrika' ),
					'id' => 'ab_metrica_overview',
					'content' => '<h2>AB Metrika home screen </h2><p>On the home screen you can add the counters</p>'
				));
			$screen->add_help_tab(
				array(
					'title' => __('Help & support', 'ab-metrika' ),
					'id' => 'ab_metrica_help',
					'content' => '<h2>Getting help with AB Metrika</h2><p>To ask us a question please start a new thread in the <a href="https://wordpress.org/support/plugin/ab-metrika" target="_blank">support forum</a>. Provide as much relevant detail as possible and please make it clear how your query is related to AB Metrika. </p>'
				));
			$screen->set_help_sidebar( $sidebar );
		}
	}
}