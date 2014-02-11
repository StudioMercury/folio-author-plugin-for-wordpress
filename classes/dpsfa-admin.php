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
            
            /* Add option for bulk import */
            add_action('admin_footer-edit.php',         __CLASS__ . '::add_bulk_import_to_admin_footer' );
            add_action('load-edit.php',                 __CLASS__ . '::bulk_import_action' );
            add_action('admin_notices',                 __CLASS__ . '::bulk_import_admin_notices' );
            
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
		
		/* Add bulk action to select menu */
		public static function add_bulk_import_to_admin_footer() {
			global $post_type;
			?>
				<script type="text/javascript">
					jQuery(document).ready(function() {
						jQuery('<option>').val('dpsfa-import').text('<?php _e('Import as DPS Article')?>').appendTo("select[name='action']");
						jQuery('<option>').val('dpsfa-import').text('<?php _e('Import as DPS Article')?>').appendTo("select[name='action2']");
						
						jQuery("select[name='action']").after('<select name="folios" id="folios"><option selected disabled>Select a Folio</option><option value="none">Do not associate</option></select>');
						
						<?php
						    $folioObj = DPSFolioAuthor_Folio::getInstance();
                            $folios = $folioObj->get_folios( array(
                                'limit' => -1,
                                'parentOnly' => true
                            ));
						?>
						
						<?php foreach( $folios as $folio ): ?>
						    jQuery("#folios").append("<option value='<?php echo $folio["localID"]; ?>'><?php echo $folio["meta"]["folioName"]; ?></option>");
						<?php endforeach; ?>
						
						jQuery("#folios").hide();
						
                        jQuery("select[name='action']").change(function(){
                            if( jQuery(this).val() == "dpsfa-import" ){
                                jQuery("#folios").show();
                            }else{
                                jQuery("#folios").hide();
                            }
                        });
					});
				</script>
			<?php
		}
		
		public static function bulk_import_admin_notices() {
			global $post_type, $pagenow;
			if($pagenow == 'edit.php' && isset($_REQUEST['article_imported']) && (int) $_REQUEST['article_imported']) {
				if( empty($_REQUEST["folios"]) ){
    				echo "<div class=\"error\"><p>No folio was selected to associate the imported articles</p></div>";
    			}else{
        			$message = sprintf( _n( 'Post imported as DPS article.', '%s posts imported as DPS articles.', $_REQUEST['article_imported'] ), number_format_i18n( $_REQUEST['article_imported'] ) );
                    echo "<div class=\"updated\"><p>{$message}</p></div>";
    			}
			}
		}
		
		public static function bulk_import_action() {
			global $typenow;
			$post_type = $typenow;
						
			// get the action
			$wp_list_table = _get_list_table('WP_Posts_List_Table');  // depending on your resource type this could be WP_Users_List_Table, WP_Comments_List_Table, etc
			$action = $wp_list_table->current_action();
			
			$allowed_actions = array("dpsfa-import");
			if(!in_array($action, $allowed_actions)) return;
			
			// security check
			check_admin_referer('bulk-posts');
			
			// make sure ids are submitted.  depending on the resource type, this may be 'media' or 'ids'
			if(isset($_REQUEST['post'])) {
				$post_ids = array_map('intval', $_REQUEST['post']);
			}
			
			if(empty($post_ids)) return;
			
			// this is based on wp-admin/edit.php
			$sendback = remove_query_arg( array('article_imported', 'untrashed', 'deleted', 'ids'), wp_get_referer() );
			if ( ! $sendback )
				$sendback = admin_url( "edit.php?post_type=$post_type" );
			
			$pagenum = $wp_list_table->get_pagenum();
			$sendback = add_query_arg( 'paged', $pagenum, $sendback );
			
			switch($action) {
				case 'dpsfa-import':
					
					// if we set up user permissions/capabilities, the code might look like:
					//if ( !current_user_can($post_type_object->cap->export_post, $post_id) )
					//	wp_die( __('You are not allowed to export this post.') );
					$imported = 0;
					foreach( $post_ids as $post_id ) {
					    if( !empty($_REQUEST["folios"]) ){
					        $articleService = DPSFolioAuthor_Admin::getInstance();
    						if ( !$articleService->import_article($post_id, $_REQUEST["folios"]) )
    							wp_die( __('Error importing post.') );
                        }
						$imported++;
					}
					
					$sendback = add_query_arg( array('article_imported' => $imported, 'ids' => join(',', $post_ids), 'folios'=>isset($_REQUEST["folios"])?$_REQUEST["folios"]:null ), $sendback );
				break;
				
				default: return;
			}
			
			$sendback = remove_query_arg( array('action', 'action2', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status',  'post', 'bulk_edit', 'post_view'), $sendback );
			
			wp_redirect($sendback);
			exit();
		}
		
		public function import_article($post_id, $folioID){
    		$articleService = DPSFolioAuthor_Article::getInstance();
    		return $articleService->import_article_from_post(array( 'postID' => $post_id, 'folioID' => $folioID ));
		}

		public static function addAdminScripts(){
             wp_enqueue_script( 'jquery' );
             wp_enqueue_script( 'jquery-effects-fade' );
             wp_enqueue_script( 'jquery-ui-core' );
             wp_enqueue_script( 'jquery-ui-dialog' );
             wp_enqueue_script( 'jquery-ui-datepicker' );
             wp_enqueue_script( 'jquery-ui-tooltip' );
             wp_enqueue_script( 'jquery-ui-sortable' );
             
             wp_register_script( 'table-sorter', DPSFA_URL . '/js/jquery.tablesorter.min.js' );
             wp_enqueue_script( 'table-sorter' );
             
             wp_register_script( 'modernizer-new', DPSFA_URL . '/js/modernizr.min.js' );
             wp_enqueue_script( 'modernizer-new' );

             wp_register_script( 'gumby', DPSFA_URL . '/js/gumby.min.js' );
             wp_enqueue_script( 'gumby' );

             wp_register_script( 'dps-ajax', DPSFA_URL . '/js/ajax.js' );
             wp_enqueue_script( 'dps-ajax' );

             wp_register_style( 'selectizeCSS', DPSFA_URL . '/css/selectize.default.css' );
             wp_enqueue_style( 'selectizeCSS' );
		}

		public static function addFontAwesome(){
		    // TODO: CHANGE THIS TO __FIL__
            wp_register_style( 'fontAwesome', DPSFA_URL . '/css/font-awesome.min.css' );
            wp_enqueue_style( 'fontAwesome' );

            wp_register_style( 'adminStyles', DPSFA_URL . '/css/admin.css' );
            wp_enqueue_style( 'adminStyles' );

            wp_register_style( 'jQueryUI', DPSFA_URL . '/css/jquery-ui.css' );
            wp_enqueue_style( 'jQueryUI' );
		}

		public static function registerPluginPage(){
            global $menu;
            global $wp_version;
            if($wp_version >= 3.8) {
                $main_page = add_menu_page( 'Folio Author for WordPress', 'Folio Author', self::REQUIRED_CAPABILITY, 'dpsfa_page_main' ,array(__CLASS__, 'dpsfa_settings_page'), 'dashicons-image-rotate-right', '53.5' );
            } else {
                $main_page = add_menu_page( 'Folio Author for WordPress', 'Folio Author', self::REQUIRED_CAPABILITY, 'dpsfa_page_main' ,array(__CLASS__, 'dpsfa_settings_page'), '', '53.5' );
            }
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
