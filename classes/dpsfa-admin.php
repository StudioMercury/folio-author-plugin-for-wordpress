<?php
/**
 *
 * Digital Publishing Suite Folio Authoring Plugin
 * Class : SETTINGS
 *
 */


 /* TODOS:

 */

if(!class_exists('DPSFolioAuthor_Admin')) {

	class DPSFolioAuthor_Admin extends DPSFolioAuthor_Module {

	    protected $settings;
		protected static $defaultSettings;
		protected static $readableProperties	= array( 'settings' );
		protected static $writeableProperties	= array( 'settings' );
		const REQUIRED_CAPABILITY = 'administrator';

        protected function __construct(){
			$this->registerHookCallbacks();
		}

        public function __set( $variable, $value ){
			// Note: WPPSModule::__set() is automatically called before this

			if( $variable != 'settings' )
				return;

			$this->settings	= self::validateSettings( $value );
			update_option( DPSFolioAuthor::PREFIX . 'settings', $this->settings );
		}

        public function registerHookCallbacks(){

			add_action( 'admin_menu',					__CLASS__ . '::registerPluginPage' );
            add_action( 'admin_print_styles',           __CLASS__ . '::addFontAwesome' );
            add_action( 'admin_init',                   __CLASS__ . '::addAdminScripts' );
			/*
			add_action( 'show_user_profile',			__CLASS__ . '::addUserFields' );
			add_action( 'edit_user_profile',			__CLASS__ . '::addUserFields' );
			add_action( 'personal_options_update',		__CLASS__ . '::saveUserFields' );
			add_action( 'edit_user_profile_update',		__CLASS__ . '::saveUserFields' );

			add_action( 'init',							array( $this, 'init' ) );
			add_action( 'admin_init',					array( $this, 'registerSettings' ) );

			add_filter(
				'plugin_action_links_'. plugin_basename( dirname( __DIR__ ) ) .'/bootstrap.php',
				__CLASS__ . '::addPluginActionLinks'
			);
			*/
		}

		public static function addAdminScripts(){
             wp_enqueue_script( 'jquery' );
             wp_enqueue_script( 'jquery-effects-fade' );
             wp_enqueue_script( 'jquery-ui-core' );
             wp_enqueue_script( 'jquery-ui-dialog' );
             wp_enqueue_script( 'jquery-ui-datepicker' );
             wp_enqueue_script( 'jquery-ui-tooltip' );
             wp_enqueue_script( 'jquery-ui-sortable' );
             
             wp_register_script( 'table-sorter', plugins_url('/adobe-folio-author-wp-plugin/js/jquery.tablesorter.min.js') );
             wp_enqueue_script( 'table-sorter' );
             
             wp_register_script( 'modernizer-new', plugins_url('/adobe-folio-author-wp-plugin/js/modernizr.min.js') );
             wp_enqueue_script( 'modernizer-new' );

             wp_register_script( 'gumby', plugins_url('/adobe-folio-author-wp-plugin/js/gumby.min.js') );
             wp_enqueue_script( 'gumby' );

             wp_register_script( 'dps-ajax', plugins_url('/adobe-folio-author-wp-plugin/js/ajax.js') );
             wp_enqueue_script( 'dps-ajax' );

             wp_register_style( 'selectizeCSS', plugins_url('/adobe-folio-author-wp-plugin/css/selectize.default.css') );
             wp_enqueue_style( 'selectizeCSS' );
		}

		public static function addFontAwesome(){
		    // TODO: CHANGE THIS TO __FIL__
            wp_register_style( 'fontAwesome', plugins_url('/adobe-folio-author-wp-plugin/css/font-awesome.min.css') );
            wp_enqueue_style( 'fontAwesome' );

            wp_register_style( 'adminStyles', plugins_url('/adobe-folio-author-wp-plugin/css/admin.css') );
            wp_enqueue_style( 'adminStyles' );

            wp_register_style( 'jQueryUI', plugins_url('/adobe-folio-author-wp-plugin/css/jquery-ui.css') );
            wp_enqueue_style( 'jQueryUI' );
		}

		public static function registerPluginPage(){
            global $menu;
            $main_page = add_menu_page( 'Folio Author for WordPress', 'Folio Author', self::REQUIRED_CAPABILITY, 'dpsfa_page_main' ,array(__CLASS__, 'dpsfa_settings_page'), plugins_url('/adobe-folio-author-wp-plugin/assets/admin/dps-icon.png'), '53.5' );
            $settings_page = add_submenu_page( 'dpsfa_page_main', "Issues",  "Issues" , self::REQUIRED_CAPABILITY, 'dpsfa_page_folios', __CLASS__ . '::dpsfa_folios_page' );
            $settings_page = add_submenu_page( 'dpsfa_page_main', "Articles",  "Articles" , self::REQUIRED_CAPABILITY, 'dpsfa_page_articles', __CLASS__ . '::dpsfa_articles_page' );
            $settings_page = add_submenu_page( 'dpsfa_page_main', "DPS Settings",  "Settings" , self::REQUIRED_CAPABILITY, 'dpsfa_page_settings', __CLASS__ . '::dpsfa_settings_page' );

            global $submenu;
            unset($submenu['dpsfa_page_main'][0]);
		}

		public static function dpsfa_settings_page(){
            include_once( dirname( __DIR__ ) .'/views/admin/dpsfa-page-settings.php');
		}

		public static function dpsfa_folios_page(){
            include_once( dirname( __DIR__ ) .'/views/admin/dpsfa-page-folios.php');
		}

		public static function dpsfa_articles_page(){
            include_once( dirname( __DIR__ ) .'/views/admin/dpsfa-page-articles.php');
		}

        public function activate( $networkWide ){

		}

        public function deactivate(){

		}

        public function init(){

			if( did_action( 'init' ) !== 1 )
				return;

			self::$defaultSettings = self::getDefaultSettings();
			$this->settings = self::getSettings();
		}

        public function upgrade(){
			/*
			if( version_compare( $dbVersion, 'x.y.z', '<' ) )
			{
				// Do stuff
			}
			*/
		}

        protected function isValid( $property = 'all' ){
			// Note: __set() calls validateSettings(), so settings are never invalid
			return true;
		}

        protected static function getDefaultSettings(){

			$basic = array(
				'field-example1'	=> ''
			);

			$advanced = array(
				'field-example2'	=> ''
			);

			return array(
				'db-version'		=> '0',
				'basic'				=> $basic,
				'advanced'			=> $advanced
			);
		}

        protected static function getSettings(){
			$settings = shortcode_atts(
				self::$defaultSettings,
				get_option( DPSFolioAuthor::PREFIX . 'settings', array() )
			);

			return $settings;
		}

    } // END class DPSFolioAuthor_Admin
}
