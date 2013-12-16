<?php

/*  Functions to determine if plugin is out of date
 *
 *
 *
 */

if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
    die( 'Access denied.' );

if( !class_exists( 'DPSFolioAuthor_Update' ) ){

    class DPSFolioAuthor_Update extends DPSFolioAuthor_Module{
                
        const GITURL = 'http://folio-author.smny.us.s3.amazonaws.com/releases.json'; // pulls cached file of https://api.github.com/repos/StudioMercury/folio-author-plugin-for-wordpress/releases
        public $metafield;
        public $metatimestamp;
        const INTERVAL = 21600; // 21600 seconds = 6 hrs
        
        public function __construct() {
            $this->metafield = DPSFolioAuthor::PREFIX . '_update';
            $this->metatimestamp = DPSFolioAuthor::PREFIX . '_update_time';
            $this->registerHookCallbacks();
        }
        
        public function get_releases(){
            $now = time();
            $last = $this->get_last_time();
            if ( !$last || (( $now - $last ) > self::INTERVAL ) ) {
                if( function_exists('curl_init') ) {
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 
                    curl_setopt($ch, CURLOPT_URL, self::GITURL);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_USERAGENT, 'curl/' . $ch['version'] );
                    $output = curl_exec($ch);
                }else{
                    $output = file_get_contents(self::GITURL);
                }
                $json = json_decode($output);
            }

            if( !empty($json) ){
                $this->save_json($json);
                return $json;
            }else{
                return $this->get_saved_json();
            }
        }
        
        public function check_for_update(){
            $releases = self::get_releases();
            if( !empty($releases) ){
                $current = $releases[0];
                $version = $current->tag_name;
                $zipDownload = $current->tarball_url;
                $tarDownload = $current->zipball_url;
                
                if( !self::is_current($version) ){
                    $notices = IDAdminNotices::getSingleton();
                    $notices->enqueue(  "<b><i class='fa fa-download'></i>&nbsp;&nbsp;A new version ($version) of Folio Author is available</b><BR/>" . 
                                        "Please update to take advantage of new features and bug fixes. <a href='http://studiomercury.github.io/folio-author-plugin-for-wordpress/'>Visit the plugin site to learn about what's new.</a>".
                                        "<BR/><BR/>".
                                        "<div class='small normal btn'><a href='$zipDownload'>Download ZIP</a></div>".
                                        "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".
                                        "<div class='small normal btn'><a href='$tarDownload'>Download TAR</a></div>".
                                        "<BR/><a style='color:black' href='http://studiomercury.github.io/folio-author-plugin-for-wordpress#upgrade'><i>download instructions</i></a>", 'error', 'danger');
                }
            }
        }
        
        public static function is_current( $currentVersion ){
            $version = str_replace('v', '', $currentVersion);
            if( (float)$version == (float)DPSFolioAuthor::VERSION ){ return true; }
            else{ return false; }
        }
        
        public function save_json( $cache ){
            update_option( $this->metafield, $cache );
            update_option( $this->metatimestamp, time() );
        }
        
        public function get_saved_json(){
            return get_option( $this->metafield );
        }
        
        public function get_last_time(){
            return get_option( $this->metatimestamp );
        }
                
        public function registerHookCallbacks(){
			add_action( 'admin_init', array($this, 'check_for_update') );
        }
        
        public function activate( $networkWide ){}
       	public function deactivate(){}
       	public function upgrade( $dbVersion = 0 ){}
       	protected function isValid( $property = 'all' ){}
    	public function init(){
        	if( did_action( 'init' ) !== 1 )
				return;
    	}

    } // END DPSFolioAuthor_Update
}
