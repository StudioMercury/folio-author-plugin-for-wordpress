<?php

/*  Functions for bundling and creating Adobe's folio archive
 *
 *
 *
 */

if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
    die( 'Access denied.' );

if( !class_exists( 'DPSFolioAuthor_Bundlr' ) ){

    class DPSFolioAuthor_Bundlr extends DPSFolioAuthor_Module{
        
        private $tmpDir;
        private $wordpressUploadsURL;

        private $folioService;
        private $articleService;
        private $deviceService; 
        
        private $folioPrefix;
        private $articlePrefix;
        
        public function __construct() {
            $this->registerHookCallbacks();
            
    		$this->tmpDir = (substr(sys_get_temp_dir(), -1) == '/') ? sys_get_temp_dir() : sys_get_temp_dir() . "/";
            $upload_dir = wp_upload_dir();
            $this->wordpressUploadsURL = $upload_dir['baseurl'];
    		$this->folioService = DPSFolioAuthor_Folio::getInstance();
    		$this->articleService = DPSFolioAuthor_Article::getInstance();
    		$this->deviceService = DPSFolioAuthor_Device::getInstance();
    		$this->folioPrefix = $this->folioService-> folioPrefix;
    		$this->articlePrefix = $this->articleService-> articlePrefix;
    		
    		require_once dirname(__DIR__).'/libs/Mustache/Autoloader.php'; // LOAD MUSTACHE TEMPLATING LIBRARY
            Mustache_Autoloader::register();
        }
        
        /*
        * Create a bundle ( zip / folio ) of a given article
        *
        * @param    array         $article article array
        * @return	string         returns the path of the created bundle
        *
        */
        public function bundle( $article ){
            /* Bring together all articles files */
            $files = array();
           
            /* FOLIO THUMBS/PREVIEWS */
            $images = $this->get_article_images($article);
            
            /* ADDITIONAL FILES FROM THE TEMPLATES */
            $templateAdditions = $this->get_files_from_template( $article );
            
            /* HTML */
            $html = $this->get_article_html_content($article);     
            $indexFile = $this->get_article_index_file( $article, $html );
            $files["index.html"] = $indexFile["file"];
            
            /* MERGE ALL FILES TOGETHER */
            $articleFiles = array_merge($files, $images, $indexFile["assets"], $templateAdditions);

            /* Combine files into folio format */
    		$bundle = $this->create_zip( $articleFiles, $article );
    		return $bundle;
        }
    	
    	private function get_article_images( $article ){
        	$files = array();
            $files["toc.png"] = $this->create_toc_png($article);
            //$files["scrubber_p.png"] = $this->create_scrubber_image($article);
            //$files["previewThumbs/thumb_p.png"] = $this->create_thumb($article);
            return $files;
    	}
    	
    	private function get_files_from_template( $article ){
            /* Call filter for getting additional files from a custom template */
        	$folio = $this->folioService->folio( $article["folio"] );
            $templateFiles = apply_filters( 'dpsfa_bundle_article', $article, $folio, $folio["device"] );
            return is_array($templateFiles) ? $templateFiles : array();
    	}
        
        /* TODO: MAKE THIS DYNAMIC */
    	private function create_scrubber_image(){
    	    // scrubber image is 125x166
            $scrubberImage = tempnam($this->tmpDir,"scrubber");
            copy( dirname(__DIR__)."/views/folio/scrubber_p.png" , $scrubberImage);
            return $scrubberImage;
    	}
    	
        /* TODO: MAKE THIS DYNAMIC */
    	private function create_thumb(){
    	    // thumb_p is full size of device
            $previewThumb = tempnam($this->tmpDir,"thumb");
            copy( dirname(__DIR__)."/views/folio/previewThumbs/thumb_p.png" , $previewThumb);
            return $previewThumb;
    	}
    
    	private function create_zip( $files, $article ){
    	    // Create a package property of all the files about to go into the zip
    	    $files["META-INF/pkgproperties.xml"] = $this->create_meta_pkg_properties($files);

    		// Credit to Vito Tardia for using PHP to create ePubs: http://www.sitepoint.com/building-epub-with-php-and-markdown/
            $bundle = tempnam($this->tmpDir,"dpsarticle");
            rename($bundle, $bundle.='.folio');
            copy(dirname(__DIR__)."/views/templates/folioStarter.zip", $bundle);
            
            // Create new Zip archive. MIMETYPE and XML need to go first
            $zip = new ZipArchive();
            $zip->open( $bundle, ZipArchive::CREATE );
            $zip->addFile( dirname(__DIR__)."/views/folio/mimetype", "mimetype" );
            $zip->addFile( $this->create_article_xml($article), "Folio.xml" );
            
            // Add additional files to the bundle
            foreach($files as $filename => $filepath){
                if($filepath){ $zip->addFile( $filepath, $filename ); }
            }
            
            // Close and return the folio
            $zip->close();
            return $bundle;
    	}
    
    	private function create_article_xml( $article ){
    	    // Get article + folio meta and merge together
            $folioMeta = $this->folioService->get_folio_meta( $article["folio"] );
            $articleMeta = $this->articleService->get_article_meta( $article["localID"] );
            $theMeta = array_merge( $folioMeta, $articleMeta );
            
            // Based on metadata set up variables for folio.xml
            $theMeta["paginated"] = ($theMeta["smoothScrolling"] == "Never") ? "true" : "false";
            $theMeta["htmlFileName"] = "index.html";
            $theMeta["folioVersion"] = "2.0.0";
            //$theMeta["folioDescription"] = ($theMeta["folioDescription"] == "") ? "NEEDS DESCRIPTION!" : $theMeta["folioDescription"];
            $theMeta["articleID"] = $article["localID"];
            
            // Determine if there should be a horizontal or vertical layer (or both)
            if($theMeta["folioIntent"] == "LandscapeOnly" ){
                $theMeta["layout"] = "horizontal";
                $theMeta["isHorizontal"] = true;
                $theMeta["isVertical"] = false;
                $theMeta["orientation"] = "landscape";
            }else if($theMeta["folioIntent"] == "PortraitOnly" ){
                $theMeta["layout"] = "vertical";
                $theMeta["isVertical"] = true;
                $theMeta["isHorizontal"] = false;
                $theMeta["orientation"] = "portrait";
            }else{
                $theMeta["isHorizontal"] = true;
                $theMeta["isVertical"] = true;
                $theMeta["layout"] = "both";
                $theMeta["orientation"] = "both";
            }
            
            $theMeta["smoothScrolling"] = strtolower($theMeta["smoothScrolling"]);
            
            // use metadata to contruct the folio.xml file
            $m = new Mustache_Engine;
            $contents = $m->render(file_get_contents( dirname( __DIR__ ) . "/views/templates/folio-xml.mustache" ), $theMeta );
            
            $file = tempnam($this->tmpDir,"dps-");
            $result = file_put_contents($file, $contents);
            return $result ? $file : false;
    	}
    
    
    	private function create_toc_png( $article ){
            return $this->get_image( $article["preview"]["url"] );
    	}    	
    
    	private function create_meta_pkg_properties($files){
    	    $metaFiles = array();
    	    foreach( $files as $key => $value ){
    	        $metaFiles[] = array(
    		        "path" => $key,
    		        "date" => date("Y-m-d\TH:i:s\Z")
    		    );
    	    }
    
    		$m = new Mustache_Engine;
            $contents = $m->render( file_get_contents( dirname( __DIR__ ) . "/views/templates/pkgproperties.mustache" ), array("file" => $metaFiles) );
    
            $file = tempnam($this->tmpDir,"dps-");
            $result = file_put_contents($file, $contents);
            return $result ? $file : false;
    	}
    	
    	private function get_article_html_content( $article ){
    		$file = tempnam($this->tmpDir,"dps-");
    		$URL = get_permalink( $article["localID"] );
            if($URL === FALSE){ return false; }
            if( strpos($URL, get_site_url() ) === FALSE){ $URL = get_site_url() . $URL; }
    	    $articleHTML = file_get_contents( parse_url($URL, PHP_URL_QUERY) ? $URL . "&folioBuilder=true" : $URL . "?folioBuilder=true" );
            return str_get_html($articleHTML);
    	}
    
    	private function get_article_index_file( $article, $htmlString ){
    	    $file = tempnam($this->tmpDir,"index");
            $html = $this->update_html_assets($htmlString);
    	    $result = file_put_contents($file, $html["content"]);
            return array(
                "file"    => $file,
                "assets"  => $html["assets"]
            );
    	}
    
        private function get_image( $url ){
            $file = tempnam($this->tmpDir, 'dps-');
            try{
                $content = @file_get_contents($url);
                $result = file_put_contents($file, $content);
                return $result ? $file : false;
            }catch(Exception $e){ return false; }
        }
    
        private function get_attachment_from_imageURL( $URL ){
            global $wpdb;
            $prefix = $wpdb->prefix;
            $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM " . $prefix . "posts" . " WHERE guid='%s';", $URL )); 
            if( count($attachment) > 0){
                $image = wp_get_attachment_image_src( $attachment[0], 'full' );
        		if( !empty($image) ){
            		return $attachment[0];
        		}else{
                    return false;
        		}              		
            }
        }
    
        private function update_html_assets($html) {
            $assets = array();
            
            $images = $html->find('img');
            $styles = $html->find('link');
            $scripts = $html->find('script');
            
            // update image references to be local for folio
            foreach ($images as $image) {
                $attachmentID = $this->get_attachment_from_imageURL($image->src);
                if($attachmentID){
                    // try to get attachment image
                    $imagePath = get_attached_file($attachmentID);
                    // use WP's new                     
                    $img = wp_get_image_editor( $imagePath );
                    if ( ! is_wp_error( $img ) ) {
                        //$old_size = $img->get_size(); // $old_size['width'], $old_size['height']
                        $resize = $img->resize( 1280, false );
                        $extension = pathinfo($imagePath, PATHINFO_EXTENSION);
                        //$img->set_quality( 100 );
                        if ($resize !== FALSE) {
                            $new_size = $img->get_size();
                            $image->width = $new_size['width'];
                            $image->height = $new_size['height'];
                        }
                        $file = tempnam($this->tmpDir, 'dps-' . basename($image->src) . $extension );
                        
                        $imageDetails = wp_check_filetype($imagePath);
                        $return = $img->save($file,$imageDetails["type"]);
                        $asset = array("path" => $return["path"], "filename" => basename($img->generate_filename()) );
                    }
                }else{
                    // if it can't find the attachment ID then use default image
                    $filePath = $this->get_image( DPSFA_URL . "/assets/folio/notlinked.gif" );
                    $asset = array("path" => $filePath, "filename" => "notlinked.gif");
                }
                
                if( !empty($asset) ){
                    $zipPath = "assets/images/" . $asset["filename"];
                    $image->src = $zipPath;
                    $assets[$zipPath] = $asset["path"];
                }
            }
                
            // update css references to local HTMLResources
            foreach($styles as $style) {
                $haystack = $style->href;
                $needle = 'HTMLResources';
                $style->href = "../" . substr($haystack, strpos($haystack, $needle), (strlen($haystack) - strpos($haystack, $needle) ) );
            }
    
            // update javascript to local HTMLResources
            foreach($scripts as $script) {
                if( isset($script->src) ){
                    $haystack = $script->src;
                    $needle = 'HTMLResources';
                    $path = substr($haystack, strpos($haystack, $needle), (strlen($haystack) - strpos($haystack, $needle) ) );
                    $script->src = "../" . $path;
                }
            }
                        
            return array(
                "content" => $html,
                "assets"  => $assets
            );
        }
    
        private function get_template_htmlresources(){
            $settingsMeta = DPSFolioAuthor::PREFIX . 'settings';
            $url = get_option( $settingsMeta );
            $file = tempnam($this->tmpDir, 'dps-');
            try{
                $content = @file_get_contents($url);
                $result = file_put_contents($file, $content);
                return $result ? $file : false;
            }catch(Exception $e){ return new WP_Error('general', __("Could not get HTMLResources")); }
            return $file;
        }
    
        private function zip_htmlresources( $pathToHTMLResources ){
            $htmlresources = tempnam($this->tmpDir, 'html-resources');
            $zip = new ZipArchive();
            $zip->open($htmlresources, ZIPARCHIVE::CREATE);
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($pathToHTMLResources), RecursiveIteratorIterator::SELF_FIRST);
            foreach ($files as $file){
                $file = str_replace('\\', '/', $file);
    
                // Ignore "." and ".." folders
                if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) ){
                    continue;
                }
    
                $file = realpath($file);
    
                if (is_dir($file) === true){
                    $zip->addEmptyDir(str_replace($pathToHTMLResources . '/', '', $file . '/'));
                }
                else if (is_file($file) === true){
                    $zip->addFromString(str_replace($pathToHTMLResources . '/', '', $file), file_get_contents($file));
                }
            }
            $zip->close();
            return $htmlresources;
        }
    
        private function zip_asset_path($url) {
            $parts = parse_url($url);
            if ($parts === false) {
                throw new Exception('Cannot parse url: '.$url);
            }
    
            $host = $parts['host'];
            if (strpos($url, $this->wordpressUploadsURL) === 0) {
                $host = 'wp';
            }
    
            return 'assets/'.$host.'/'.pathinfo($parts['path'], PATHINFO_BASENAME);
        }
        
        
        public function registerHookCallbacks(){}
        public function activate( $networkWide ){}
       	public function deactivate(){}
       	public function upgrade( $dbVersion = 0 ){}
       	protected function isValid( $property = 'all' ){}
    	public function init(){}

    } // END DPSFolioAuthor_Bundlr
}
