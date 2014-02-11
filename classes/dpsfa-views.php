<?php

/*  Wrapper for Mustache
 *
 *
 *
 */

if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
    die( 'Access denied.' );

if( !class_exists( 'DPSFolioAuthor_Views' ) ){

    class DPSFolioAuthor_Views extends DPSFolioAuthor_Module{
                
        public $templateEngine = "Mustache";
        public $engine;
        
        public function __construct() {
            $this->registerHookCallbacks();
            $this->engine = $this->get_template_engine();
        }
        
        public function get_template_engine(){
            switch ($this->templateEngine) {
                case "Mustache":
                    require_once DPSFA_DIR . '/libs/Mustache/Autoloader.php';
                    Mustache_Autoloader::register();
                    $engine = new Mustache_Engine(array(
                        'loader' => new Mustache_Loader_FilesystemLoader(DPSFA_DIR . '/views/templates/')
                    ));
                    return $engine;
                    break;
            }
        }
        
        public function render( $template, $data){
            $template = $this->load_template( $template );
            return $template->render( $data );
        }
        
        public function load_template( $template ){
            return $this->engine->loadTemplate($template);
        }
                
        public function registerHookCallbacks(){}
        public function activate( $networkWide ){}
       	public function deactivate(){}
       	public function upgrade( $dbVersion = 0 ){}
       	protected function isValid( $property = 'all' ){}
    	public function init(){
        	if( did_action( 'init' ) !== 1 )
				return;
    	}

    } // END DPSFolioAuthor_Views
}
