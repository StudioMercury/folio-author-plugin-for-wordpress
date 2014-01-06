<?php

/*  Functions for importing sidecar file and updating article fields
 *
 *
 *
 */
// Report all PHP errors (see changelog)
error_reporting(E_ALL);
error_reporting(-1);
ini_set('error_reporting', E_ALL);

if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
    die( 'Access denied.' );

if( !class_exists( 'DPSFolioAuthor_Sidecar_Importer' ) ){

    class DPSFolioAuthor_Sidecar_Importer extends DPSFolioAuthor_Module{
        
        private $tmpDir;
        
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
        
        public function import( $sidecarFile, $folioID ){
            $found = 0;
            $entries = $this->get_entries_from_sidecar( $sidecarFile );
            foreach( $entries as $entry ){
                $foundArticle = $this->articleService->get_article_by_name( $entry["name"], $folioID );
                $article = isset($foundArticle[0]) ? $foundArticle[0] : false;
                if($article){
                    $articleID = $article["localID"];
                    if($this->articleService->is_rendition($articleID)){
                        $renditionAsPost = get_post($articleID);
                        $articleID = $renditionAsPost->post_parent;
                    }
                    $this->articleService->update_article_field( $article["localID"], "position", $entry["position"] );
                    
                    /* Unset fields we don't need */
                    unset( $entry["name"] );
                    unset( $entry["position"] );
                    
                    $this->articleService->update_article_field( $articleID, "meta", $entry );
                    $found++;
                }
            }
            if($found == 0){ return new WP_Error('general', __("No articles matched the articles in the uploaded sidecar.xml. Please make sure the article names match.")); }
            else{ return $found; }
        }
        
        private function get_entries_from_sidecar($sidecarFile){
            // Load the sidecar file
        	$xml = new DOMDocument(); 
        	$xml->recover = TRUE;
        	$xml->formatOutput = true;
        	$xml->load($sidecarFile);
        	$xmlDoc = $xml->documentElement;
        	$counter = 0;
        	$entries = array();
        	
        	// GET ALL OF THE ENTRIES
        	$entriesFromXML = $xmlDoc->getElementsByTagName('entry');
        	foreach($entriesFromXML as $entry) {
        	    $sidecarEntry = array();
        	    $sidecarEntry["position"] = $counter * 100;
        	    if( isset($entry->getElementsByTagName('folderName')->item(0)->nodeValue) ){ $sidecarEntry["name"] = $entry->getElementsByTagName('folderName')->item(0)->nodeValue; }
        	    if( isset($entry->getElementsByTagName('articleTitle')->item(0)->nodeValue) ){ $sidecarEntry["title"] = $entry->getElementsByTagName('articleTitle')->item(0)->nodeValue; }
        	    if( isset($entry->getElementsByTagName('description')->item(0)->nodeValue) ){ $sidecarEntry["description"] = $entry->getElementsByTagName('description')->item(0)->nodeValue; }
        	    if( isset($entry->getElementsByTagName('author')->item(0)->nodeValue) ){ $sidecarEntry["author"] = $entry->getElementsByTagName('author')->item(0)->nodeValue; }
                if( isset($entry->getElementsByTagName('kicker')->item(0)->nodeValue) ){ $sidecarEntry["kicker"] = $entry->getElementsByTagName('kicker')->item(0)->nodeValue; }
                if( isset($entry->getElementsByTagName('tags')->item(0)->nodeValue) ){ $sidecarEntry["tags"] = $entry->getElementsByTagName('tags')->item(0)->nodeValue; }
                if( isset($entry->getElementsByTagName('section')->item(0)->nodeValue) ){ $sidecarEntry["section"] = $entry->getElementsByTagName('section')->item(0)->nodeValue; }
                if( isset($entry->getElementsByTagName('hideFromTOC')->item(0)->nodeValue) ){ $sidecarEntry["hideFromTOC"] = $entry->getElementsByTagName('hideFromTOC')->item(0)->nodeValue; }
                if( isset($entry->getElementsByTagName('isAd')->item(0)->nodeValue) ){ $sidecarEntry["isAdvertisement"] = $entry->getElementsByTagName('isAd')->item(0)->nodeValue; }
                if( isset($entry->getElementsByTagName('isFlattenedStack')->item(0)->nodeValue) ){ $sidecarEntry["flatten"] = $entry->getElementsByTagName('isFlattenedStack')->item(0)->nodeValue; }
                if( isset($entry->getElementsByTagName('smoothScrolling')->item(0)->nodeValue) ){ $sidecarEntry["smoothScrolling"] = $entry->getElementsByTagName('smoothScrolling')->item(0)->nodeValue; }
        	    $entries[] = $sidecarEntry;
        	    $counter++;
        	}

            return $entries;
        }
        
        public function registerHookCallbacks(){}
        public function activate( $networkWide ){}
       	public function deactivate(){}
       	public function upgrade( $dbVersion = 0 ){}
       	protected function isValid( $property = 'all' ){}
    	public function init(){}

    } // END DPSFolioAuthor_Sidecar_Importer
}
