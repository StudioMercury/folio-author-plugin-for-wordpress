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
        
        public function __construct() {
            $this->registerHookCallbacks();
            
    		$this->folioService = DPSFolioAuthor_Folio::getInstance();
    		$this->articleService = DPSFolioAuthor_Article::getInstance();
    		$this->deviceService = DPSFolioAuthor_Device::getInstance();
    		
    		$this->folioPrefix = $this->folioService-> folioPrefix;
    		$this->articlePrefix = $this->articleService-> articlePrefix;
        }
        
        public function sync( $origin, $toUpdate, $fields ){
	        // get type
	        
        }
        
        public function get_type( $rendition ){
	        // get post type of rendition ["localID"]
	        // if post type ==folioPrefix it's a folio
        }
        
        public function in_sync( $rendition ){
	        // does the current date equal last mod date
        }
        
        public function get_rendition_last_mod( $rendition ){
	        
        }
        
        public function get_rendition_current_mod( $rendition ){
	        
        }
                
        public function sync_all_renditions( $origin ){
	        
        }
        
        public function registerHookCallbacks(){}
        public function activate( $networkWide ){}
       	public function deactivate(){}
       	public function upgrade( $dbVersion = 0 ){}
       	protected function isValid( $property = 'all' ){}
    	public function init(){}

    } // END DPSFolioAuthor_Sync
}
