<?php

/*  Functions for filtering articles and posts
 *
 *
 */

if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
    die( 'Access denied.' );

if( !class_exists( 'DPSFolioAuthor_Filter' ) ){

    class DPSFolioAuthor_Filter extends DPSFolioAuthor_Module{
        
        private $folioService;
        private $articleService;
        
        private $folioPrefix;
        private $articlePrefix;

        
        public function __construct() {
            $this->registerHookCallbacks();
            
    		$this->folioService = DPSFolioAuthor_Folio::getInstance();
    		$this->articleService = DPSFolioAuthor_Article::getInstance();
    		
    		$this->folioPrefix = $this->folioService-> folioPrefix;
    		$this->articlePrefix = $this->articleService-> articlePrefix;
        }
        
        /*
        * Create a bundle ( zip / folio ) of a given article
        *
        * @param    array         $article article array
        * @return	string         returns the path of the created bundle
        *
        */
        public function filter( $args = array() ){
            $defaults = array (
         		'template'         => null,
         		'type'             => null,
         		'search'           => null,
         		'filter'           => 'local'
        	);
            $args = wp_parse_args( $args, $defaults );
            extract( $args, EXTR_SKIP );
            
            // extract key value pairs
            $data = $this->extract_search($search);

            $listItems = array();
            $listItems["search"] = $search;
            
            if( $type == "article" ){
                $data["parent"] = 0;
                $listItems["articles"] = $this->articleService->get_articles( $data );
                if(empty($listItems["articles"])){ $listItems["noResults"] = true; }
            }else if( $type == "folio"){
                $listItems["folios"] = $this->articleService->get_folios( $data );
                if(empty($listItems["folios"])){ $listItems["noResults"] = true; }
            }else{
                return "Couldn't find any results for your search: $search";
            }
            
            $viewsService = DPSFolioAuthor_Views::getInstance();
            return $viewsService->render("modal-list-items",$listItems);            
        }
        
        public function extract_search( $string ){
            $data = array();
            if( strpos($string, ":") !== false ){
                $attributes = explode(",", $string);
                foreach($attributes as $attribute){
                    preg_match('/(?<name>[\w]+):(?<value>.*)/', $attribute, $match);
                    $data[ $match["name"] ] = trim($match["value"]);
                }
            }else{
                // just a search string, just return it.
                $data["search"] = $string;
            }   
            return $data;
        }
        
        public function show_filter( $args = array() ){
            $defaults = array (
         		'includeForm'         => false,
         		'inlineSearch'        => false,
         		'type'                => 'article'
        	);
            $args = wp_parse_args( $args, $defaults );
            extract( $args, EXTR_SKIP );
            
            if( $type == "article" ){
                require_once(DPSFA_DIR . "/views/admin/ajax/filter-articles.php");
            }
        }
        
        public function registerHookCallbacks(){}
        public function activate( $networkWide ){}
       	public function deactivate(){}
       	public function upgrade( $dbVersion = 0 ){}
       	protected function isValid( $property = 'all' ){}
    	public function init(){}

    } // END DPSFolioAuthor_Filter
}
