<?php

if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

if( !class_exists( 'DPSFolioAuthor_CPT_Folio' ) )
{
	/**
	 * Creates a custom post type and associated taxonomies
	 * @package WordPressPluginSkeleton
	 * @author Ian Dunn <ian@iandunn.name>
	 */
	class DPSFolioAuthor_CPT_Folio extends DPSFolioAuthor_Module implements DPSFolioAuthor_CustomPostType{
		
		const POST_TYPE_NAME	= 'Issue';
		const POST_TYPE_SLUG	= 'dpsfa_folio';
		
		//protected static $readableProperties	= array();
		//protected static $writeableProperties	= array();
		
		/*
		 * Magic methods
		 */
		
		/**
		 * Constructor
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		protected function __construct(){
			$this->registerHookCallbacks();
		}
		
		
		/*
		 * Static methods
		 */
		
		/**
		 * Registers the custom post type
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function createPostType()
		{
			if( did_action( 'init' ) !== 1 )
				return;

			if( !post_type_exists( self::POST_TYPE_SLUG ) )
			{
				$postTypeParams = self::getPostTypeParams();
				$postType = register_post_type( self::POST_TYPE_SLUG, $postTypeParams );
			}
		}

		/**
		 * Defines the parameters for the custom post type
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 * @return array
		 */
		protected static function getPostTypeParams()
		{
    		
			$labels = array
			(
				'name'					=> self::POST_TYPE_NAME . 's',
				'singular_name'			=> self::POST_TYPE_NAME,
				'add_new'				=> 'Add New '. self::POST_TYPE_NAME,
				'add_new_item'			=> 'Add New '. self::POST_TYPE_NAME,
				'edit'					=> 'Edit',
				'edit_item'				=> 'Edit '. self::POST_TYPE_NAME,
				'new_item'				=> 'New '. self::POST_TYPE_NAME,
				'view'					=> 'View '. self::POST_TYPE_NAME . 's',
				'view_item'				=> 'View '. self::POST_TYPE_NAME,
				'search_items'			=> 'Search '. self::POST_TYPE_NAME . 's',
				'not_found'				=> 'No '. self::POST_TYPE_NAME .'s found',
				'not_found_in_trash'	=> 'No '. self::POST_TYPE_NAME .'s found in Trash',
				'parent'				=> 'Parent '. self::POST_TYPE_NAME,
    			'menu_name'             => self::POST_TYPE_NAME . 's'
			);

			$postTypeParams = array(
			    'labels'				=> $labels,
				'show_ui' 				=> true, // set to false after
				'show_in_menu'          => 'none',
				'singular_label'		=> self::POST_TYPE_NAME,
				'public'				=> true,
				'publicly_queryable' 	=> true,
				'exclude_from_search' 	=> true,
				'hierarchical'			=> true,
				'capability_type'		=> 'post',
				'show_in_nav_menus' 	=> false,
				'rewrite' 				=> false,
				'query_var' 			=> true,
				'has_archive' 			=> false,
				'supports'				=> array( 'title', 'editor', 'author', 'thumbnail', 'revisions' )
			);
			
			return apply_filters( DPSFolioAuthor::PREFIX . 'post-type-params', $postTypeParams );
		}

		/**
		 * Registers the category taxonomy
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function createTaxonomies()
		{
			if( did_action( 'init' ) !== 1 )
				return;

			if( !taxonomy_exists( self::TAG_SLUG ) )
			{
				$tagTaxonomyParams = self::getTagTaxonomyParams();
				register_taxonomy( self::TAG_SLUG, self::POST_TYPE_SLUG, $tagTaxonomyParams );
			}
		}
		
		/**
		 * Defines the parameters for the custom taxonomy
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 * @return array
		 */
		protected static function getTagTaxonomyParams()
		{
			$tagTaxonomyParams = array(
				'label'					=> self::TAG_NAME,
				'labels'				=> array( 'name' => self::TAG_NAME, 'singular_name' => self::TAG_NAME ),
				'hierarchical'			=> true,
				'rewrite'				=> array( 'slug' => self::TAG_SLUG ),
				'update_count_callback'	=> '_update_post_term_count'
			);
			
			return apply_filters( DPSFolioAuthor::PREFIX . 'tag-taxonomy-params', $tagTaxonomyParams );
		}

		/**
		 * Adds meta boxes for the custom post type
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function addMetaBoxes()
		{
			
			if( did_action( 'add_meta_boxes' ) !== 1 )
				return;   
                
            global $post;
            global $typenow;
            if($typenow !== self::POST_TYPE_SLUG)
                return;

            $folioService = DPSFolioAuthor_Folio::getInstance();
            $folio = $folioService->folio($post->ID);
            $isRendition = $folioService->is_rendition($post->ID);


            // GLOBAL BOXES			
			add_meta_box(
				DPSFolioAuthor::PREFIX . 'folio-actions',
				'Actions',
				__CLASS__ . '::markupMetaBoxes',
				self::POST_TYPE_SLUG,
				'side',
				'high'
			);


            
            // SPECIFIC BOXES
            if( $isRendition ){          
                add_meta_box(
    				DPSFolioAuthor::PREFIX . 'folio-advanced-meta',
    				'Advanced Metadata',
    				__CLASS__ . '::markupMetaBoxes',
    				self::POST_TYPE_SLUG,
    				'side',
    				'core'
    			);
            }else{
                add_meta_box(
    				DPSFolioAuthor::PREFIX . 'folio-devices',
    				'Targeted Device',
    				__CLASS__ . '::markupMetaBoxes',
    				self::POST_TYPE_SLUG,
    				'side',
    				'core'
    			);
            }
			
			add_meta_box(
				DPSFolioAuthor::PREFIX . 'folio-articles',
				'Articles',
				__CLASS__ . '::markupMetaBoxes',
				self::POST_TYPE_SLUG,
				'normal',
				'core'
			);

			
		}
		
		public static function removeMetaBoxes(){
            remove_meta_box( 'postimagediv', self::POST_TYPE_SLUG, 'side' );
            remove_meta_box( 'submitdiv', self::POST_TYPE_SLUG, 'side' );
            remove_meta_box( 'slugdiv', self::POST_TYPE_SLUG, 'normal');
            remove_meta_box( 'titlediv', self::POST_TYPE_SLUG, 'normal' );
            remove_meta_box( 'authordiv', self::POST_TYPE_SLUG, 'normal' );
            remove_post_type_support( self::POST_TYPE_SLUG, 'editor' ); // removes editor
            remove_post_type_support( self::POST_TYPE_SLUG, 'title'); // removes title            
		}

		/**
		 * Builds the markup for all meta boxes
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param object $post
		 * @param array $box
		 */
		public static function markupMetaBoxes( $post, $box )
		{
            $deviceList = self::getTargetDevices();
            
            $folioService = DPSFolioAuthor_Folio::getInstance();
            $folio = $folioService->folio($post->ID);
            $isRendition = $folioService->is_rendition($post->ID);

            $fieldSlug = DPSFolioAuthor_CPT_Folio::POST_TYPE_SLUG;
			
			$view = dirname( __DIR__ ) . "/views/admin/meta-boxes/" . $box[ 'id' ] . ".php";
			if( is_file( $view ) ){
                require_once( $view );
			}else{
				throw new Exception( __METHOD__ . " error: ". $view ." doesn't exist." );
			}
		}
		

		/**
		 * Saves values of the the custom post type's extra fields
		 * @mvc Controller
		 * @param int $postID
		 * @param object $post
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public static function savePost( $post_id, $revision )
		{
		    global $post;
            
            if ( $parent_id = wp_is_post_revision( $post_id ) ) 
                $post_id = $parent_id;
                
			$ignoredActions = array( 'trash', 'untrash', 'restore' );
			
			if( did_action( 'save_post' ) !== 1 )
				return;
			
			if( isset( $_GET[ 'action' ] ) && in_array( $_GET[ 'action' ], $ignoredActions ) )
				return;

			if(	!$post || $post->post_type != self::POST_TYPE_SLUG || !current_user_can( 'edit_posts', $post_id ) )
				return;
                
			if( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) )
				return;
				
			$folioService = DPSFolioAuthor_Folio::getInstance();
		    $folioService->update_folio_from_post( $post_id );
		}
		
		/**
		 * Defines the [wpps-cpt-shortcode] shortcode
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param array $attributes
		 * return string
		 */
		public static function cptShortcodeExample( $attributes ) 
		{
			$attributes = apply_filters( DPSFolioAuthor::PREFIX . 'cpt-shortcode-example-attributes', $attributes );
			$attributes = self::validateCPTShortcodeExampleAttributes( $attributes );
			
			ob_start();
			require_once( dirname( __DIR__ ) . '/views/wpps-cpt-example/shortcode-cpt-shortcode-example.php' );
			$output = ob_get_clean();
			
			return apply_filters( DPSFolioAuthor::PREFIX . 'cpt-shortcode-example', $output );
		}
		
		/**
		 * Validates the attributes for the [cpt-shortcode-example] shortcode
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param array $attributes
		 * return array
		 */
		protected static function validateCPTShortcodeExampleAttributes( $attributes )
		{
			$defaults = self::getDefaultCPTShortcodeExampleAttributes();
			$attributes = shortcode_atts( $defaults, $attributes );
			
			if( $attributes[ 'foo' ] != 'valid data' )
				$attributes[ 'foo' ] = $defaults[ 'foo' ];
			
			return apply_filters( DPSFolioAuthor::PREFIX . 'validate-cpt-shortcode-example-attributes', $attributes );
		}

		/**
		 * Defines the default arguments for the [cpt-shortcode-example] shortcode
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param array
		 * @return array
		 */
		protected static function getDefaultCPTShortcodeExampleAttributes()
		{
			$attributes = array(
				'foo'	=> 'bar',
				'bar'	=> 'foo'
			);
			
			return apply_filters( DPSFolioAuthor::PREFIX . 'default-cpt-shortcode-example-attributes', $attributes );
		}
		
		
		/*
		 * Instance methods
		 */
		 
		/**
		 * Register callbacks for actions and filters
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function registerHookCallbacks()
		{
			// NOTE: Make sure you update the did_action() parameter in the corresponding callback method when changing the hooks here
			add_action( 'init',						__CLASS__ . '::createPostType' );
			add_action( 'admin_head',			    __CLASS__ . '::removeMetaBoxes' );
			add_action( 'add_meta_boxes',           __CLASS__ . '::addMetaBoxes' );
			add_action( 'save_post',				__CLASS__ . '::savePost', 10, 2 );
			add_action( 'init',						array( $this, 'init' ) );
			add_shortcode( 'cpt-shortcode-example',	__CLASS__ . '::cptShortcodeExample' );
		}
		
		public static function getTargetDevices(){
		    $devices	= array();
		    
		    $devices["apple"]["name"] = "Apple";
		    $devices["google"]["name"] = "Google";
		    $devices["amazon"]["name"] = "Amazon";
		    $devices["barnesandnoble"]["name"] = "Barnes & Noble";
	       
	       $devices["apple"]["device"][] = array(
	            "name"      => "Custom",
	            "slug"      => "custom",
	            "width"     => "",
	            "height"    => "",
	            "thumb"     => "/assets/devices/custom.png"
	       );
	       
	       $devices["apple"]["device"][] = array(
	            "name"      => "iPad HD",
	            "slug"      => "iphone-hd",
	            "width"     => "1536",
	            "height"    => "2048",
	            "thumb"     => "/assets/devices/ipad-hd.png"
	       );
	       
	       $devices["apple"]["device"][] = array(
	            "name"      => "iPad SD",
	            "slug"      => "iphone-sd",
	            "width"     => "768",
	            "height"    => "1024",
	            "thumb"     => "/assets/devices/ipad-sd.png"
	       );	
	       
	       $devices["apple"]["device"][] = array(
	            "name"      => "iPhone 5",
	            "slug"      => "iphone-5",
	            "width"     => "640",
	            "height"    => "1136",
	            "thumb"     => "/assets/devices/iphone-5.png"
	       );	       
	       
	       $devices["apple"]["device"][] = array(
	            "name"      => "iPhone 4/4S",
	            "slug"      => "iphone-4",
	            "width"     => "640",
	            "height"    => "960",
	            "thumb"     => "/assets/devices/iphone-hd.png"
	       );
	       
	       $devices["amazon"]["device"][] = array(
	            "name"      => "Kindle Fire",
	            "slug"      => "kindle-fire",
	            "width"     => "1024",
	            "height"    => "600",
	            "thumb"     => "/assets/devices/kindle-fire-sd.png"
	       );
	       
	       $devices["amazon"]["device"][] = array(
	            "name"      => "Kindle Fire HDX 8.9",
	            "slug"      => "kindle-fire",
	            "width"     => "2560",
	            "height"    => "1600",
	            "thumb"     => "/assets/devices/kindle-fire-hdx-8.png"
	       );
	       
	       $devices["amazon"]["device"][] = array(
	            "name"      => "Kindle Fire HDX 7 / HD 8.9",
	            "slug"      => "kindle-fire",
	            "width"     => "1920",
	            "height"    => "1200",
	            "thumb"     => "/assets/devices/kindle-fire-hdx-8.png"
	       );
	       
	       $devices["amazon"]["device"][] = array(
	            "name"      => "Kindle Fire 7 HD",
	            "slug"      => "kindle-fire",
	            "width"     => "1280",
	            "height"    => "800",
	            "thumb"     => "/assets/devices/kindle-fire-hdx-8.png"
	       );
	       
	       $devices["barnesandnoble"]["device"][] = array(
	            "name"      => "B&N Nook 7in SD",
	            "slug"      => "bn-nook-7-sd",
	            "width"     => "1024",
	            "height"    => "600",
	            "thumb"     => "/assets/devices/bn-7-sd.png"
	       );
	       
	       $devices["barnesandnoble"]["device"][] = array(
	            "name"      => "B&N Nook 7in HD",
	            "slug"      => "bn-nook-7-hd",
	            "width"     => "1440",
	            "height"    => "900",
	            "thumb"     => "/assets/devices/bn-7-hd.png"
	       );
	       
	       $devices["barnesandnoble"]["device"][] = array(
	            "name"      => "B&N Nook 9in HD",
	            "slug"      => "bn-nook-9-hd",
	            "width"     => "1920",
	            "height"    => "1280",
	            "thumb"     => "/assets/devices/bn-9-hd.png"
	       );
	       
	       $devices["google"]["device"][] = array(
	            "name"      => "Google Nexus 4",
	            "slug"      => "google-nexus-4",
	            "width"     => "1280",
	            "height"    => "768",
	            "thumb"     => "/assets/devices/google-nexus-4.png"
	       );
	       
	       $devices["google"]["device"][] = array(
	            "name"      => "Google Nexus 5",
	            "slug"      => "google-nexus-5",
	            "width"     => "1920",
	            "height"    => "1080",
	            "thumb"     => "/assets/devices/google-nexus-5.png"
	       );
           
           $devices["google"]["device"][] = array(
	            "name"      => "Google Nexus 7",
	            "slug"      => "google-nexus-7",
	            "width"     => "1280",
	            "height"    => "800",
	            "thumb"     => "/assets/devices/google-nexus-7.png"
	       );
	       
	       $devices["google"]["device"][] = array(
	            "name"      => "Google Nexus 10",
	            "slug"      => "google-nexus-10",
	            "width"     => "2560",
	            "height"    => "1600",
	            "thumb"     => "/assets/devices/google-nexus-10.png"
	       );
	       	       
	       return $devices;

		}
		
		/**
		 * Prepares site to use the plugin during activation
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param bool $networkWide
		 */
		public function activate( $networkWide ){
			self::createPostType();
            flush_rewrite_rules();
			//self::createTaxonomies();
		}

		/**
		 * Rolls back activation procedures when de-activating the plugin
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function deactivate(){
            flush_rewrite_rules();
		} 
		 
		/**
		 * Initializes variables
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function init()
		{
			if( did_action( 'init' ) !== 1 )
				return;
		}

		/**
		 * Executes the logic of upgrading from specific older versions of the plugin to the current version
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param string $dbVersion
		 */
		public function upgrade( $dbVersion = 0 ){ }
		
		/**
		 * Checks that the object is in a correct state
		 * @mvc Model
		 * @author Ian Dunn <ian@iandunn.name>
		 * @param string $property An individual property to check, or 'all' to check all of them
		 * @return bool
		 */
		protected function isValid( $property = 'all' ){
			return true;
		}
	} // end WPPSCPTExample
}

?>