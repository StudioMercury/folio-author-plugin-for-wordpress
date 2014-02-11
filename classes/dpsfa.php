<?php
/**
 *
 * Digital Publishing Suite Folio Authoring Plugin
 *
 */

if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

if( !class_exists( 'DPSFolioAuthor' ) ){

	class DPSFolioAuthor extends DPSFolioAuthor_Module {
		public static $notices;     // Needs to be static so static methods can call enqueue notices. Needs to be public so other modules can enqueue notices.
        protected static $readableProperties	= array();		// These should really be constants, but PHP doesn't allow class constants to be arrays
		protected static $writeableProperties	= array();

        protected $modules;

        const VERSION		= '1.1.0';
        const PREFIX		= 'dpsfa_';
        const DEBUG_MODE	= false;
        const PLUGIN_UPLOAD_DIR = 'dpsfa';

    	/* CONSTRUCT OBJECT */
    	public function __construct() {
    		$this->registerHookCallbacks();
    		$this->modules = array(
    			'DPSFolioAuthor_CPT_Folio'	            => DPSFolioAuthor_CPT_Folio::getInstance(),
    			'DPSFolioAuthor_CPT_Article'	        => DPSFolioAuthor_CPT_Article::getInstance(),
    			'DPSFolioAuthor_Admin'                  => DPSFolioAuthor_Admin::getInstance(),
    			'DPSFolioAuthor_Adobe'                  => DPSFolioAuthor_Adobe::getInstance(),
    			'DPSFolioAuthor_Bundlr'                 => DPSFolioAuthor_Bundlr::getInstance(),
    			'DPSFolioAuthor_Folio'                  => DPSFolioAuthor_Folio::getInstance(),
                'DPSFolioAuthor_Ajax'                   => DPSFolioAuthor_Ajax::getInstance(),
    			'DPSFolioAuthor_Article'                => DPSFolioAuthor_Article::getInstance(),
    			'DPSFolioAuthor_Settings'               => DPSFolioAuthor_Settings::getInstance(),
                'DPSFolioAuthor_Templates'              => DPSFolioAuthor_Templates::getInstance(),
                'DPSFolioAuthor_Device'                 => DPSFolioAuthor_Device::getInstance(),
                'DPSFolioAuthor_Sidecar_Importer'       => DPSFolioAuthor_Sidecar_Importer::getInstance(),
    			'DPSFolioAuthor_Update'	                => DPSFolioAuthor_Update::getInstance(),
    			'DPSFolioAuthor_Sync'	                => DPSFolioAuthor_Sync::getInstance(),
    			'DPSFolioAuthor_Filter'	                => DPSFolioAuthor_Filter::getInstance(),
    			'DPSFolioAuthor_Views'	                => DPSFolioAuthor_Views::getInstance()
    		);
    	}

    	public static function loadResources(){
			if( did_action( 'wp_enqueue_scripts' ) !== 1 && did_action( 'admin_enqueue_scripts' ) !== 1 )
				return;

			wp_register_script(
				self::PREFIX . 'admin',
				DPSFA_URL . '/js/admin.js',
				array( 'jquery' ),
				self::VERSION,
				true
			);

			wp_register_style(
				self::PREFIX .'admin',
				DPSFA_URL . 'css/admin.css',
				array(),
				self::VERSION,
				'all'
			);

			if( is_admin() ){
    		    //wp_enqueue_style( self::PREFIX . 'admin' );
				wp_enqueue_script( self::PREFIX . 'admin' );
			}else{
				wp_enqueue_script( self::PREFIX . 'admin' );
			}
		}


    	protected static function clearCachingPlugins(){
    		// WP Super Cache
    		if( function_exists( 'wp_cache_clear_cache' ) )
    			wp_cache_clear_cache();

    		// W3 Total Cache
    		if( class_exists( 'W3_Plugin_TotalCacheAdmin' ) )
    		{
    			$w3TotalCache =& w3_instance( 'W3_Plugin_TotalCacheAdmin' );

    			if( method_exists( $w3TotalCache, 'flush_all' ) )
    				$w3TotalCache->flush_all();
    		}
    	}

    	public function activateNewSite( $blogID )
		{
			if( did_action( 'wpmu_new_blog' ) !== 1 )
				return;

			switch_to_blog( $blogID );
			$this->singleActivate( $networkWide );
			restore_current_blog();
		}

		public static function setImageSizes(){
            add_theme_support( 'post-thumbnails' );
    		add_image_size( 'article-toc', 120, 120, true );
    		add_image_size( 'article-scrubber-h', 125, 166, true );
    		add_image_size( 'article-scrubber-v', 166, 125, true );
    		add_image_size( 'article-preview-h', 1024, 768, true );
    		add_image_size( 'article-preview-v', 768, 1024, true );
    		add_image_size( 'article-background', 1536, 2048 );

		}

    	public function activate($networkWide){
    		// Check Version
    		foreach( $this->modules as $module )
    			$module->activate( $networkWide );

    		flush_rewrite_rules($networkWide);

    		/* TODO: LOOK INTO WPMS */
    		/*
    		if( did_action( 'activate_' . plugin_basename( dirname( __DIR__ ) . '/bootstrap.php' ) ) !== 1 )
				return;

			if( function_exists( 'is_multisite' ) && is_multisite() )
			{
				if( $networkWide )
				{
					$blogs = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );

					foreach( $blogs as $b )
					{
						switch_to_blog( $b );
						$this->singleActivate( $networkWide );
					}

					restore_current_blog();
				}
				else
					$this->singleActivate( $networkWide );
			}
			else
				$this->singleActivate( $networkWide );
            */

    	}

    	public function deactivate(){
        	foreach( $this->modules as $module )
    			$module->deactivate();

    		flush_rewrite_rules();
    	}

    	public function uninstall($networkWide){

    	}

    	public function registerHookCallbacks(){
    		// NOTE: Make sure you update the did_action() parameter in the corresponding callback method when changing the hooks here
    		add_action( 'wp_enqueue_scripts',		__CLASS__ . '::loadResources' );
    		add_action( 'admin_enqueue_scripts',	__CLASS__ . '::loadResources' );
    		add_action( 'init',                     __CLASS__ . '::setImageSizes' );
    		add_action( 'init',						array( $this, 'init' ) );
    		add_action( 'init',						array( $this, 'upgrade' ), 11 );
    	}

    	public function init(){
        	if( did_action( 'init' ) !== 1 )
    				return;

            self::$notices = IDAdminNotices::getSingleton();
			if ( self::DEBUG_MODE ) {
				self::$notices->debugMode = true;
			}

            // check to make sure we can upload files
            $wpContentWritable = $this->checkIfFolderWritable(WP_CONTENT_DIR);
            if ($wpContentWritable) {
                $this->makePluginFolderInWPContent();
            }
            $upload_dir = wp_upload_dir();
            $this->checkIfFolderWritable($upload_dir['path']);

            /* notices enqueue takes 3 attributes */
            // @message = STRING
            // @grouping = `update` or `error`
            // @colorclass = `success`, `info`, `warning`, `danger` correspond to (green, blue, yellow, red)
			//self::$notices->enqueue( 'Folio Producer: success' , 'update', 'success');
    	}

    	public function upgrade(){
    		if( did_action( 'init' ) !== 1 )
    			return;

            /* Add option for versioning */
    		add_option(DPSFA_VERSION_META, self::VERSION);

            if (get_option(DPSFA_VERSION_META) != self::VERSION) {
    		    // Plugin needs upgraded
                foreach( $this->modules as $module )
    			    $module->upgrade( self::VERSION );

    		    // Then update the version value
    		    update_option(DPSFA_VERSION_META, self::VERSION);
    		    self::clearCachingPlugins();
    		}
    	}

        protected function isValid( $property = 'all' ){
			return true;
		}

        private function checkIfFolderWritable($path) {
            if (!is_writable($path)) {
                self::$notices->enqueue( 'Folio Producer Plugin cannot write to "'.$path.'" directory.' , 'error', 'error');
                return false;
            }
            return true;
        }

        private function makePluginFolderInWPContent() {
            if (!file_exists(WP_CONTENT_DIR.'/'.self::PLUGIN_UPLOAD_DIR)) {
                if (is_file(WP_CONTENT_DIR.'/'.self::PLUGIN_UPLOAD_DIR)) {
                    self::$notices->enqueue( 'Folio Producer Plugin needs to create necessary "'.WP_CONTENT_DIR.'/'.self::PLUGIN_UPLOAD_DIR.'" directory, but a file exists with the same name.' , 'error', 'error');
                } else {
                    $result = mkdir(WP_CONTENT_DIR.'/'.self::PLUGIN_UPLOAD_DIR);
                    if (!$result) {
                        self::$notices->enqueue( 'Folio Producer Plugin could not create the necessary "'.WP_CONTENT_DIR.'/'.self::PLUGIN_UPLOAD_DIR.'" directory.' , 'error', 'error');
                    } else {
                        $result = touch(WP_CONTENT_DIR.'/'.self::PLUGIN_UPLOAD_DIR.'/test');
                        if (!$result) {
                            self::$notices->enqueue( 'Unable to create a file in "'.WP_CONTENT_DIR.'/'.self::PLUGIN_UPLOAD_DIR.'" directory. Please update its permissions.' , 'error', 'error');
                        } else {
                            unlink(WP_CONTENT_DIR.'/'.self::PLUGIN_UPLOAD_DIR.'/test');
                        }
                    }
                }
            }
        }
    }
	require_once(  dirname( __DIR__  ) . '/libs/IDAdminNotices/id-admin-notices.php' ); // Class for Admin Notices
    require_once(  dirname( __FILE__ ) . '/dpsfa-settings.php' );                       // Class for Settings
    require_once(  dirname( __FILE__ ) . '/dpsfa-admin.php' );                          // Class for Settings
    require_once(  dirname( __FILE__ ) . '/dpsfa-custom-post-type.php' );               // Class for Article Object
    require_once(  dirname( __FILE__ ) . '/dpsfa-cpt-folio.php' );                      // Class for Folio Post Type
    require_once(  dirname( __FILE__ ) . '/dpsfa-cpt-article.php' );                    // Class for Article Post Type
    require_once(  dirname( __FILE__ ) . '/dpsfa-adobe-api-wrapper.php' );              // Class for Adobe's Folio Producer API
    require_once(  dirname( __FILE__ ) . '/dpsfa-bundlr.php' );                         // Class for Bundling Articles
    require_once(  dirname( __FILE__ ) . '/dpsfa-folio.php' );                          // Class for Folios
    require_once(  dirname( __FILE__ ) . '/dpsfa-article.php' );                        // Class for Articles
    require_once(  dirname( __FILE__ ) . '/dpsfa-ajax.php' );                           // Class for Ajax calls
    require_once(  dirname( __FILE__ ) . '/dpsfa-templates.php' );                      // Class for Template Management calls
    require_once(  dirname( __FILE__ ) . '/dpsfa-template-renderer.php' );              // Class for Template Rendering
    require_once(  dirname( __FILE__ ) . '/dpsfa-device.php' );                         // Class for Devices
    require_once(  dirname( __FILE__ ) . '/dpsfa-sidecar-importer.php' );               // Class for Sidecar XML Importer
    require_once(  dirname( __FILE__ ) . '/dpsfa-update.php' );                         // Class for Checking for Updates
    require_once(  dirname( __FILE__ ) . '/dpsfa-sync.php' );                           // Class for Syncing changes among articles and folios
    require_once(  dirname( __FILE__ ) . '/dpsfa-filter.php' );                         // Class for Filtering articles and folios
    require_once(  dirname( __FILE__ ) . '/dpsfa-views.php' );                          // Class for Rendering Views
}
