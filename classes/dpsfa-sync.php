<?php

/*  Functions for syncing renditions and determining if renditions are out of sync
 *
 *
 *
 */

if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
    die( 'Access denied.' );

if( !class_exists( 'DPSFolioAuthor_Sync' ) ){

    class DPSFolioAuthor_Sync extends DPSFolioAuthor_Module{
        
        private $folioService;
        private $articleService;
        private $deviceService; 
        
        private $folioPrefix;
        private $articlePrefix;
        
        public function status( $rendition ){
            return array(
                "hosted" => $this->is_uptodate_hosted($rendition),
                "parent" => $this->is_uptodate_parent($rendition),
            );
        }
        
        public function __construct() {
            $this->registerHookCallbacks();
            
    		$this->folioService = DPSFolioAuthor_Folio::getInstance();
    		$this->articleService = DPSFolioAuthor_Article::getInstance();
    		$this->deviceService = DPSFolioAuthor_Device::getInstance();
    		
    		$this->folioPrefix = $this->folioService-> folioPrefix;
    		$this->articlePrefix = $this->articleService-> articlePrefix;
        }
        
        public function sync( $origin, $toUpdate, $fields = "all"){
	        if( is_array($fields) ){
	            // if array we'll only update those specific fields
	        }else if( $fields == "meta" ){
    	        // if `meta` we'll update only the adobe meta
	        }else if( $fields == "content" ){
	            // if `content` we'll only update the post_content
	        }else if( $fields == "all" ){
	            // if `all` we'll update all content / meta
	        }else{
                return new WP_Error('broke', __("No fields specified to update."));
	        }
        }
        
        public function get_type( $rendition ){
            $renditionPost = get_post( $rendition["localID"] );
            if($renditionPost->post_type == $this->folioPrefix){
                // rendition is a folio
            }else if($renditionPost->post_type == $this->articlePrefix){
                // endition is a article
            }else{
                return new WP_Error('broke', __("The rendition is not a post type that the plugin can recognize."));
            }
        }
        
        public function is_uptodate_hosted( $rendition ){

        }
        
        public function is_uptodate_parent( $rendition ){
        
        }
         
        public function registerHookCallbacks(){}
        public function activate( $networkWide ){}
       	public function deactivate(){}
       	public function upgrade( $dbVersion = 0 ){}
       	protected function isValid( $property = 'all' ){}
    	public function init(){}

    } // END DPSFolioAuthor_Sync
}
