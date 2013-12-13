<?php
/**
 *
 * Digital Publishing Suite Folio Authoring Plugin
 * Class : FOLIO
 *
 */
 

if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

if(!class_exists('DPSFolioAuthor_Ajax')) { 
    
	class DPSFolioAuthor_Ajax extends DPSFolioAuthor_Module {
	    
		protected function __construct(){
    		$this->registerHookCallbacks();
		}
		
		public function registerHookCallbacks(){
            
            // LINK FOLIO
            add_action( 'wp_ajax_nopriv_link_folio',                    array( $this, 'link_folio' ) );
            add_action( 'wp_ajax_link_folio',                           array( $this, 'link_folio' ) );
                        
            // SYNC HOSTED FOLIOS
            add_action( 'wp_ajax_nopriv_sync_hosted_folios',            array( $this, 'sync_hosted_folios' ) );
            add_action( 'wp_ajax_sync_hosted_folios',                   array( $this, 'sync_hosted_folios' ) );
            
            // SYNC HOSTED ARTICLES
            add_action( 'wp_ajax_nopriv_sync_hosted_articles',          array( $this, 'sync_hosted_articles' ) );
            add_action( 'wp_ajax_sync_hosted_articles',                 array( $this, 'sync_hosted_articles' ) );
            
            // DELETE ARTICLE
            add_action( 'wp_ajax_nopriv_delete_article',                array( $this, 'delete_article' ) );
            add_action( 'wp_ajax_delete_article',                       array( $this, 'delete_article' ) );
            
            // DELETE FOLIO
            add_action( 'wp_ajax_nopriv_delete_folio',                  array( $this, 'delete_folio' ) );
            add_action( 'wp_ajax_delete_folio',                         array( $this, 'delete_folio' ) );
                        
            // ADD ARTICLE TO FOLIO
            add_action( 'wp_ajax_nopriv_add_articles_to_folio',         array( $this, 'add_articles_to_folio' ) );
            add_action( 'wp_ajax_add_articles_to_folio',                array( $this, 'add_articles_to_folio' ) );
                        
            // PUBLISH ARTICLE
            add_action( 'wp_ajax_nopriv_publish_article',               array( $this, 'publish_article' ) );
            add_action( 'wp_ajax_publish_article',                      array( $this, 'publish_article' ) );
            
            // ADD RENDITION
            add_action( 'wp_ajax_nopriv_create_new_rendition',          array( $this, 'create_new_rendition' ) );
            add_action( 'wp_ajax_create_new_rendition',                 array( $this, 'create_new_rendition' ) );
            
            // DELETE RENDITION
            add_action( 'wp_ajax_nopriv_delete_rendition',              array( $this, 'delete_rendition' ) );
            add_action( 'wp_ajax_delete_rendition',                     array( $this, 'delete_rendition' ) );
            
            // PUSH RENDITION
            add_action( 'wp_ajax_nopriv_push_rendition',                array( $this, 'push_rendition' ) );
            add_action( 'wp_ajax_push_rendition',                       array( $this, 'push_rendition' ) );
            
            // GET ALL ARTICLES
            add_action( 'wp_ajax_nopriv_get_folio_articles',            array( $this, 'get_folio_articles' ) );
            add_action( 'wp_ajax_get_folio_articles',                   array( $this, 'get_folio_articles' ) );
            
            // UPDATE ARTICLE POSITIONS FOR A FOLIO
            add_action( 'wp_ajax_nopriv_update_article_positions',      array( $this, 'update_article_positions' ) );
            add_action( 'wp_ajax_update_article_positions',             array( $this, 'update_article_positions' ) );
            
            // UPDATE RENDITION META
            add_action( 'wp_ajax_nopriv_update_rendition',              array( $this, 'update_rendition' ) );
            add_action( 'wp_ajax_update_rendition',                     array( $this, 'update_rendition' ) );
            
            // GET POSTS ARRAY
            add_action( 'wp_ajax_nopriv_get_all_posts',                 array( $this, 'get_all_posts' ) );
            add_action( 'wp_ajax_get_all_posts',                        array( $this, 'get_all_posts' ) );
            
            // IMPORT A POST AS AN ARTICLE
            add_action( 'wp_ajax_nopriv_import_post_as_article',        array( $this, 'import_post_as_article' ) );
            add_action( 'wp_ajax_import_post_as_article',               array( $this, 'import_post_as_article' ) );
            
            // GET AJAX FORM
            add_action( 'wp_ajax_nopriv_get_ajax_form',                 array( $this, 'get_ajax_form' ) );
            add_action( 'wp_ajax_get_ajax_form',                        array( $this, 'get_ajax_form' ) );
            
            // CREATE NEW FOLIO
            add_action( 'wp_ajax_nopriv_create_new_folio',              array( $this, 'create_new_folio' ) );
            add_action( 'wp_ajax_create_new_folio',                     array( $this, 'create_new_folio' ) );
            
            // IMPORT POSTS AS ARTICLES
            add_action( 'wp_ajax_nopriv_import_articles',               array( $this, 'import_articles' ) );
            add_action( 'wp_ajax_import_articles',                      array( $this, 'import_articles' ) );
            
            // EDIT FOLIO
            add_action( 'wp_ajax_nopriv_edit_folio',                    array( $this, 'edit_folio' ) );
            add_action( 'wp_ajax_edit_folio',                           array( $this, 'edit_folio' ) );
            
            // IMPORT HTMLRESOURCES
            add_action( 'wp_ajax_nopriv_upload_htmlresources',            array( $this, 'upload_htmlresources' ) );
            add_action( 'wp_ajax_upload_htmlresources',                   array( $this, 'upload_htmlresources' ) );
            
            // DUPLICATE ARTICLES FROM RENDITION
            add_action( 'wp_ajax_nopriv_duplicate_articles_from_rendition',     array( $this, 'duplicate_articles_from_rendition' ) );
            add_action( 'wp_ajax_duplicate_articles_from_rendition',            array( $this, 'duplicate_articles_from_rendition' ) );
            
        }
        
        // simple second defense against missing fields so nothing blows up
        public function verifyRequiredFields( $input, $required ){
            $missing = array();
            foreach( $required as $key => $prettyName ){
                if( !isset($input[$key]) || empty($input[$key]) ){
                    array_push($missing, $prettyName);
                }
            }
            if( count($missing) > 0 ){
                $data = array(
                    "code" => 0,
                    "message" => "Missing the following fields: <BR/><B><BR/>" . implode("<BR/>", $missing) . "</b>"
                );
                $this->return_as_json($data);
            }
        }
        
        public function setupErrorCollecting(){
            $GLOBALS['dpsErrors'] = null;
            global $dpsErrors;
            $dpsErrors = new WP_Error('initialize', __("Error codes for Folio Producer"));
        }
        
        public function checkCollectedErrors(){
            // there should always be 1 (the inidialize code);
            if( count($dpsErrors->get_error_codes()) > 1 ){
                $data = array(
                    "code" => 0,
                    "message" => $this->generate_errors($dpsErrors)
                );
                $this->return_as_json($data);
            }
        }
        
        public function upload_htmlresources(){
            $this->setupErrorCollecting();
            $folioService = DPSFolioAuthor_Folio::getInstance();
            $folio = $folioService->folio( $_POST["folio"] );
            $return = $folioService->upload_htmlresources($folio);
            if( is_wp_error($return) ){
                $data = array(
                    "code" => 0,
                    "message" => $this->generate_errors($return)
                );
            }else{
                $data = array(
                    "code" => 1,
                    "folio" => $_POST["folio"]
                );
            }
            $this->return_as_json($data);
        }
        
        public function create_new_folio(){
            $this->setupErrorCollecting();
            $folioService = DPSFolioAuthor_Folio::getInstance();
            $required = array( 
                "folioName" => "Folio Name", 
                "folioNumber" => "Folio Number", 
                "magazineTitle" => "Magazine Title", 
                "publicationDate" => "Publication Date", 
                "coverDate" => "Cover Date"
            );
            $this->verifyRequiredFields( $_POST[$folioService->folioPostType], $required );
            $return = $folioService->create_local_folio();
            if( is_wp_error($return) ){
                $data = array(
                    "code" => 0,
                    "message" => $this->generate_errors($return)
                );
            }else{
                $folioService->update_folio_field($return, 'meta', $_POST[$folioService->folioPostType] );
                $data = array(
                    "code" => 1,
                    "folio" => $return
                );
            }
            $this->return_as_json($data);
        }
        
        public function get_ajax_form(){
            $this->setupErrorCollecting();
            switch ($_POST['form']) {
                case "create_new_folio":
                    include_once( dirname( __DIR__  ) . '/views/admin/ajax/create-new-folio.php' );
                    break;
                case "create_new_rendition":
                    include_once( dirname( __DIR__  ) . '/views/admin/ajax/create-new-rendition.php' );
                    break;
                case "add_articles_to_folio":
                    include_once( dirname( __DIR__  ) . '/views/admin/ajax/add-articles-to-folio.php' );
                    break;
                case "import_articles":
                    include_once( dirname( __DIR__  ) . '/views/admin/ajax/import-articles.php' );
                    break;
                case "edit_folio":
                    include_once( dirname( __DIR__  ) . '/views/admin/ajax/edit-folio.php' );
                    break;
                case "duplicate_articles_from_rendition":
                    include_once( dirname( __DIR__  ) . '/views/admin/ajax/duplicate-articles-from-rendition.php' );
                    break;
                case "import_sidecar_xml":
                    include_once( dirname( __DIR__  ) . '/views/admin/ajax/import-sidecar-xml.php' );
                    break;
            }
            die();
        }
        
        public function edit_folio(){
            $this->setupErrorCollecting();
            $folioService = DPSFolioAuthor_Folio::getInstance();
            if(empty($_POST["folio"])){ $return = new WP_Error('general', __("No folio referenced.")); }
            if( empty($return) || !is_wp_error($return) ){
                $return = $folioService->update_folio_field($_POST["folio"], 'meta', $_POST[$folioService->folioPostType] );
            }
            
            if( is_wp_error($return) ){
                $data = array(
                    "code" => 0,
                    "message" => $this->generate_errors($return)
                );
            }else{
                $data = array(
                    "code" => 1,
                    "message" => "folio created",
                );
            }
            $this->return_as_json($data);
        }
        
        public function import_articles(){
            $this->setupErrorCollecting();
            $articleService = DPSFolioAuthor_Article::getInstance();
            $return = $articleService->import_articles( $_POST["posts"] );
            if( !is_wp_error($return) ){
                $data = array(
                    "code" => 1,
                    "message" => "articles imported"
                );
            }else{
                $data = array(
                    "code" => 0,
                    "message" => $this->generate_errors($return)
                );
            }
            $this->return_as_json($data);
        }
        
        public function get_all_posts(){
            $this->setupErrorCollecting();
            $allPosts = array();
            $postsQuery = new WP_Query( "posts_per_page=-1&post_type=post" );
            while( $postsQuery->have_posts() ) {
            	$postsQuery->next_post();
            	array_push( $allPosts, array( "ID" => $postsQuery->post->ID, "title" => get_the_title($postsQuery->post->ID) ) );
            }
            $this->return_as_json( array("posts" => $allPosts) );
        }
        
        public function update_article_positions(){
            $this->setupErrorCollecting();
            $articleService = DPSFolioAuthor_Article::getInstance();
            $response = $articleService->update_positions( $_POST['articles'] );
            $this->return_as_json( array("return" => $response) );
        }
        
        public function delete_rendition(){
            $this->setupErrorCollecting();
            $folioService = DPSFolioAuthor_Folio::getInstance();
            $folio = $folioService->folio( $_POST["folio"] );
            $renditionPost = get_post($_POST["folio"]);
            $return = $folioService->delete_folio( $folio );
            if( is_wp_error($return) ){
                $data = array(
                    "code" => 0,
                    "message" => $this->generate_errors($return),
                );
            }else{
                $data = array(
                    "code" => 1,
                    "message" => "rendition deleted",
                    "callbackURL" => "http://localhost/wp/wp-admin/post.php?post=" . $renditionPost->post_parent . "&action=edit"
                );
            }
            $this->return_as_json($data);
        }
        
        public function duplicate_articles_from_rendition(){
            $this->setupErrorCollecting();
            $articleService = DPSFolioAuthor_Article::getInstance();
            $articles = $articleService->get_articles( null, $_POST["rendition"] );
            $return = $articleService->duplicate_articles_from_rendition( $_POST["folio"], $articles );
            if( !is_wp_error($return) ){
                $data = array(
                    "code" => 1,
                    "message" => "articles duplicated"
                );
            }else{
                $data = array(
                    "code" => 0,
                    "message" => $this->generate_errors($return)
                );
            }
            $this->return_as_json($data);
        }
        
        public function push_rendition(){
            $this->setupErrorCollecting();
            $folioService = DPSFolioAuthor_Folio::getInstance();
            $return = $folioService->push_rendition( $_POST["folio"] );
            if( is_wp_error($return) ){
                $data = array(
                    "code" => 0,
                    "message" => $this->generate_errors($return),
                );
            }else{
                $data = array(
                    "code" => 1,
                    "rendition" => $return,
                );
            }
            $this->return_as_json($data);
        }
        
        public function get_folio_articles(){
            $this->setupErrorCollecting();
            $articleService = DPSFolioAuthor_Article::getInstance();
            $return = $articleService->get_articles( null, $_POST["folioID"] );
            $data = array();
            if( is_wp_error($return) ){
                $data["code"] = 0;
                $data["message"] = $this->generate_errors($return);
            }else{
                foreach( $return as $article ){
                    $data["articles"][] = $article["localID"];
                }
                $data["code"] = 1;
            }
            $this->return_as_json($data);
        }
        
        public function create_new_rendition(){
            $this->setupErrorCollecting();
            $required = array( 
                "resolutionWidth" => "Width", 
                "resolutionHeight" => "Height", 
                "folioIntent" => "Orientation"
            );
            $this->verifyRequiredFields( $_POST["rendition"]["meta"], $required );
            
            $folioService = DPSFolioAuthor_Folio::getInstance();
            $return = $folioService->create_rendition( $_POST["folio"], $_POST["rendition"]["meta"], $_POST["rendition"]["renditionLabel"] );
            if( is_wp_error($return) ){
                $data = array(
                    "code" => 0,
                    "message" => $this->generate_errors($return)
                );
            }else{
                $data = array(
                    "code" => 1,
                    "rendition" => $return
                );
            }
            $this->return_as_json($data);
        }
        
        public function publish_article(){
            $this->setupErrorCollecting();
            $articleID = $_POST["articleID"];
            $articleService = DPSFolioAuthor_Article::getInstance();
            $return = $articleService->push_article($articleID);
            if( is_wp_error($return) ){
                $data = array(
                    "code" => 0,
                    "message" => $this->generate_errors($return),
                    "advanced" => $return
                );
            }else{
                $data = array(
                    "code" => 1,
                    "hostedID" => $return
                );
            }
            $this->return_as_json($data);
        }
        
        public function add_articles_to_folio(){
            $this->setupErrorCollecting();
            $folioService = DPSFolioAuthor_Article::getInstance();
            $return = $folioService->add_articles_to_folio($_POST["articles"], $_POST["folio"]);
            if( !is_wp_error($return) ){
                $data = array(
                    "code" => 1,
                    "message" => "articles added to folio: " . $_POST["folio"]
                );
            }else{
                $data = array(
                    "code" => 0,
                    "message" => $this->generate_errors($return)
                );
            }
            $this->return_as_json($data);
        }
                
        public function delete_folio(){
            $this->setupErrorCollecting();
            $folioService = DPSFolioAuthor_Folio::getInstance();
            $folio = $folioService->folio( $_POST["folio"] );
            $return = $folioService->delete_folio($folio);
            $this->checkCollectedErrors();
            if( is_wp_error($return) ){
                $data = array(
                    "code" => 0,
                    "message" => $this->generate_errors($return),
                );
            }else{
                $data = array(
                    "code" => 1,
                    "message" => "folio: " . $_POST["folio"] ." deleted"
                );
            }
            $this->return_as_json($data);
        }
        
        public function delete_article(){
            $this->setupErrorCollecting();
            $articleID = $_POST["article"];
            $articleService = DPSFolioAuthor_Article::getInstance();
            $article = $articleService->article($articleID);
            $return = $articleService->delete_article($articleID);
            if( is_wp_error($return) ){
                $data = array(
                    "code" => 0,
                    "message" => $this->generate_errors($return),
                );
            }else{
                $data = array(
                    "code" => 1,
                    "message" => "article: $articleID deleted"
                );
                if( isset($_POST["redirect"]) ){ $data["redirect"] = isset($article["parent"]) ? "post.php?post=".$article["parent"]."&action=edit" : "admin.php?page=dpsfa_page_articles"; }
            }
            $this->return_as_json($data);
        }
        
        public function update_rendition(){
            $this->setupErrorCollecting();
            $folioService = DPSFolioAuthor_Folio::getInstance();
            $folio = $folioService->folio( $_POST["folio"] );
            $return = $folioService->update_folio_on_adobe($folio);
            
            if(!is_wp_error($return)){
                // updload covers
                $folioService = DPSFolioAuthor_Folio::getInstance();
                $return = $folioService->upload_covers($folio);
            }
            
            if( is_wp_error($return) ){
                $data = array(
                    "code" => 0,
                    "message" => $this->generate_errors($return),
                    "advanced" => $return
                );
            }else{
                $data = array(
                    "code" => 1
                );
            }
            $this->return_as_json($data);
        }
        
        public function update_folio_covers(){
            $this->setupErrorCollecting();
            $folioService = DPSFolioAuthor_Folio::getInstance();
            $folio = $folioService->folio( $_POST["folio"] );
            $return = $folioService->upload_covers($folio);
            if( is_wp_error($return) ){
                $data = array(
                    "code" => 0,
                    "message" => $this->generate_errors($return),
                );
            }else{
                $data = array(
                    "code" => 1
                );
            }
            $this->return_as_json($data);
        }
        
        public function sync_hosted_articles(){
            $this->setupErrorCollecting();
            $folioService = DPSFolioAuthor_Folio::getInstance();
            $folio = $folioService->folio( $_POST["folioID"] );
            
            $articleService = DPSFolioAuthor_Article::getInstance();
            $response = $articleService->sync_hosted_articles( $folio );
            if( is_wp_error($return) ){
                $data = array(
                    "code" => 0,
                    "message" => $this->generate_errors($return),
                );
            }else{
                 $data = array(
                    "code" => 1,
                    "response" => array( "created" => $response )
                );
            }
            $this->return_as_json($data);
        }     
        
        public function sync_hosted_folios(){
            $this->setupErrorCollecting();
            $folioService = DPSFolioAuthor_Folio::getInstance();
            $return = $folioService->sync_hosted_folios();
            if( is_wp_error($return) ){
                $data = array(
                    "code" => 0,
                    "message" => $this->generate_errors($return),
                );
            }else{
                $data = array(
                    "code" => 1
                );
            }
            
            $this->return_as_json($data);
        }
        
        public function link_folio(){
            $this->setupErrorCollecting();
            $folioService = DPSFolioAuthor_Folio::getInstance();
            $return = $folioService->link_folio($_POST["folio"]);
            if( is_wp_error($return) ){
                $data = array(
                    "code" => 0,
                    "message" => $this->generate_errors($return),
                );
            }else{
                $data = array(
                    "code" => 1,
                    "message" => "folio linked"
                );
            }
            $this->return_as_json($data);
        }
        
        public function generate_errors( $wperror ){
            $errors = "";
            $errorCodes = $wperror->get_error_codes();
            foreach( $errorCodes as $code ){
                $message = $wperror->get_error_messages($code);
                $errors = $errors . "<div class=\"error-code\">$code</div><div class=\"error-message\">".$message[0]."</div>";
            }
            return $errors;
        }
        
        public function return_as_json($data){
			header('Content-Type: application/json');
			if(isset($_GET['callback'])){
				echo $_GET['callback']."(".json_encode($data).")";
			}else{
				echo json_encode($data);
			}
			die();
		}
        
        public function activate( $networkWide ){}
	   	public function deactivate(){}
	   	public function upgrade( $dbVersion = 0 ){}
	   	protected function isValid( $property = 'all' ){}
		public function init(){}
			        
    } // END class DPSFolioAuthor_Ajax 
}