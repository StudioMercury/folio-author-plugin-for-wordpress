<?php

/* UTILITIES */

if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
    die( 'Access denied.' );

if( !class_exists( 'DPSFolioAuthor_Utilities' ) ){

    class DPSFolioAuthor_Utilities extends DPSFolioAuthor_Module{
                        
        public function __construct() { }
        
        public function parse_args( $args, $defaults = '' ){
	        	if ( is_object( $args ) )
					$r = get_object_vars( $args );
				elseif ( is_array( $args ) )
					$r =& $args;
				else
					wp_parse_str( $args, $r );
			
				if ( is_array( $defaults ) )
					return array_merge( $defaults, $r );
				return $r;
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
