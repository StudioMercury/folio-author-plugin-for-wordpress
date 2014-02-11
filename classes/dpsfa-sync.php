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
    		
    		$this->folioPrefix = $this->folioService->folioPrefix;
    		$this->articlePrefix = $this->articleService->articlePrefix;
        }
        
        public function status( $rendition, $fields ){
            $status = array();
                    
            foreach($fields as $field){
                $status["hosted"][$field] = $this->in_sync_with_hosted($rendition, $field);
            }
            
            $status["hosted"]["metadata"] = true;
            foreach( $status["hosted"] as $field => $value ){
                if( empty($value) && $field != "content" ){ $status["hosted"]["metadata"] = false; }
            }
            
            $status["parent"] = $this->in_sync_with_parent($rendition);
            
            return $status;
        }
        
        public function sync( $origin, $toUpdate, $fields = "all"){            
            // $toUpdate can be `all` which will sync all renditions
	        if( is_array($fields) ){
	            // if array we'll only update those specific fields
	        }else if( $fields == "meta" ){
    	        // if `meta` we'll update only the adobe meta
	            $return = $this->copy_post_meta($origin,$toUpdate);
	        }else if( $fields == "content" ){
	            // if `content` we'll only update the post_content
	            $return = $this->copy_post_content($origin,$toUpdate);
	        }else if( $fields == "all" ){
	            // if `all` we'll update all content / meta
	            $return = $this->copy_post_content($origin,$toUpdate);
	            $return = $this->copy_post_meta($origin,$toUpdate);
	        }else{
                return new WP_Error('broke', __("No fields specified to update."));
	        }

	        if(!is_wp_error($return)){ return true; }
	        else{ return $return; }
        }
        
        public function copy_post_meta( $origin, $toUpdate ){
            /* TODO: allow post metadata to be copied
            /*
            if(strtolower($toUpdate) == "all"){
                $article = $this->articleService->article($origin);
                foreach($article["renditions"] as $rendition){
                    $originalMeta = $this->articleService->get_article_field($origin,"meta");
                    $this->articleService->update_article_field($rendition["localID"],"meta",$originalMeta);
                }
            }else{
                $originalMeta = $this->articleService->get_article_field($origin,"meta");
                $this->articleService->update_article_field($toUpdate,"meta",$originalMeta);
            }
            return true;
            */
        }
        
        public function copy_post_content( $origin, $toUpdate ){
            $timestamp = $this->articleService->get_article_field( $origin, 'localMod' );
            if(strtolower($toUpdate) == "all"){
                $article = $this->articleService->article($origin);
                if( !empty($article["parent"])  ){
                    $article = $this->articleService->article($article["parent"]);
                    // copy to parent as well
                    $originalContent = $this->articleService->get_article_field($origin, "content");
                    $this->articleService->update_article_field($article["localID"],"content", $originalContent);
                    $this->articleService->update_article_field($article["localID"], 'localMod', $timestamp);
                }
                
                foreach($article["renditions"] as $rendition){
                    $originalContent = $this->articleService->get_article_field($origin, "content");
                    $this->articleService->update_article_field($rendition["localID"],"content",$originalContent);
                    $this->articleService->update_article_field($rendition["localID"], 'localMod', $timestamp);
                }
            }else{
                $originalContent = $this->articleService->get_article_field($origin, "content");
                $this->articleService->update_article_field($toUpdate,"content",$originalContent);
                $this->articleService->update_article_field($toUpdate, 'localMod', $timestamp);
            }
            return true;
        }
        
        public function get_type( $rendition ){
            $renditionPost = get_post( $rendition );
            if($renditionPost->post_type == $this->folioPrefix){
                // rendition is a folio
            }else if($renditionPost->post_type == $this->articlePrefix){
                // endition is a article
            }else{
                return new WP_Error('broke', __("The rendition is not a post type that the plugin can recognize."));
            }
        }
        
        public function in_sync_with_hosted( $rendition, $field ){
            $hostedDate = get_post_meta($rendition, $this->articlePrefix. "hostedMod", true);
            $hostedModDate = new DateTime( $hostedDate );
            
            $renditionDate = get_post_meta($rendition, $this->articlePrefix . $field . "_mod", true);
            $renditionModDate = new DateTime( $renditionDate );
                                    
            if( empty($renditionDate) || empty($hostedDate) ){ return true; }
            return ($renditionModDate <= $hostedModDate);
        }
        
        public function in_sync_with_parent( $rendition ){
            $renditionAsPost = get_post($rendition);
            $renditionParentID = ($renditionAsPost->post_parent == 0) ? $rendition: $renditionAsPost->post_parent;            
            $parentAsPost = get_post($renditionParentID);
            
            $parentDate = new DateTime( $this->articleService->get_article_field($rendition, 'localMod') );
            $renditionDate = new DateTime( $this->articleService->get_article_field($renditionParentID, 'localMod') );
            return ($parentDate == $renditionDate);
        }
                
        public function timestamp_field( $localID, $field ){
            return update_post_meta($localID, $this->articlePrefix . $field . "_mod", date('Y/m/d H:i:s') );
        }
         
        public function registerHookCallbacks(){}
        public function activate( $networkWide ){}
       	public function deactivate(){}
       	public function upgrade( $dbVersion = 0 ){}
       	protected function isValid( $property = 'all' ){}
    	public function init(){}

    } // END DPSFolioAuthor_Sync
}
