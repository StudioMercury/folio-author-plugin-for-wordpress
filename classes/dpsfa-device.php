<?php
/**
 * Class : DPSFolioAuthor_Device
 *
 * A class for constructing and maniuplating devices for renditions
 *
 * @license    TBD
 * @version    Release: @package_version@
 * @link       http://www.adobe.com
 */


if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

if(!class_exists('DPSFolioAuthor_Device')) {

	class DPSFolioAuthor_Device extends DPSFolioAuthor_Module {

	    public $devicePrefix;

		protected function __construct(){
		    $this->devicePrefix = DPSFA_SLUG . "_devices";
		}
		
		/*
		* Constructs a device array
		*
		* @return	array of devices
		*
		*/
		public function device(){
    		return array(
    		    'name'          => "",    // device name
    		    'slug'          => "",    // device slug
    		    'width'         => "",    // device width
    		    'height'        => "",    // device height
    		    'type'          => ""     // mobile or tablet
    		);
		}
		
		/*
		* Constructs a folio array of initial devices
		*
		* @return	array of initial devices to prepopulate the settings page
		*
		*/
		public function device_types(){
		    return array(
		        "Mobile"    => "mobile",
		        "Tablet"    => "tablet"
		    );
		}
				
		/*
		* Constructs a folio array of initial devices
		*
		* @return	array of initial devices to prepopulate the settings page
		*
		*/
		public function initial_devices(){
    		$devices  = array();
	       	       
    		$devices[] = array(
	            "name"      => "iPad HD",
	            "slug"      => "iphone-hd",
	            "height"    => "1536",
	            "width"     => "2048",
	            "type"      => "tablet"
    		);
	       
    		$devices[] = array(
	            "name"      => "iPad SD",
	            "slug"      => "iphone-sd",
	            "height"    => "768",
	            "width"     => "1024",
	            "type"      => "tablet"
    		);	
	       
            $devices[] = array(
	            "name"      => "iPhone 5",
	            "slug"      => "iphone-5",
	            "height"    => "640",
	            "width"     => "1136",
	            "type"      => "mobile"
    		);	       
	       
    		$devices[] = array(
	            "name"      => "iPhone 4/4S",
	            "slug"      => "iphone-4",
	            "height"    => "640",
	            "width"     => "960",
	            "type"      => "mobile"
    		);
	       
    		$devices[] = array(
	            "name"      => "Kindle Fire",
	            "slug"      => "kindle-fire",
	            "height"    => "1024",
	            "width"     => "600",
	            "type"      => "tablet"
    		);
	       
    		$devices[] = array(
	            "name"      => "Kindle Fire HDX 8.9",
	            "slug"      => "kindle-fire",
	            "height"    => "2560",
	            "width"     => "1600",
	            "type"      => "tablet"
    		);
	       
    		$devices[] = array(
	            "name"      => "Kindle Fire HDX 7 / HD 8.9",
	            "slug"      => "kindle-fire",
	            "height"    => "1920",
	            "width"     => "1200",
	            "type"      => "tablet"
    		);
	       
    		$devices[] = array(
	            "name"      => "Kindle Fire 7 HD",
	            "slug"      => "kindle-fire",
	            "height"    => "1280",
	            "width"     => "800",
	            "type"      => "tablet"
    		);
	       
    		$devices[] = array(
	            "name"      => "B&N Nook 7in SD",
	            "slug"      => "bn-nook-7-sd",
	            "height"    => "1024",
	            "width"     => "600",
	            "type"      => "tablet"
    		);
	       
    		$devices[] = array(
	            "name"      => "B&N Nook 7in HD",
	            "slug"      => "bn-nook-7-hd",
	            "height"    => "1440",
	            "width"     => "900",
	            "type"      => "tablet"
    		);
	       
    		$devices[] = array(
	            "name"      => "B&N Nook 9in HD",
	            "slug"      => "bn-nook-9-hd",
	            "height"    => "1920",
	            "width"     => "1280",
	            "type"      => "tablet"
    		);
	       
    		$devices[] = array(
	            "name"      => "Google Nexus 4",
	            "slug"      => "google-nexus-4",
	            "height"    => "1280",
	            "width"     => "768",
	            "type"      => "mobile"
    		);
	       
    		$devices[] = array(
	            "name"      => "Google Nexus 5",
	            "slug"      => "google-nexus-5",
	            "height"    => "1920",
	            "width"     => "1080",
	            "type"      => "mobile"
    		);
           
    		$devices[] = array(
	            "name"      => "Google Nexus 7",
	            "slug"      => "google-nexus-7",
	            "height"    => "1280",
	            "width"     => "800",
	            "type"      => "tablet"
    		);
	       
    		$devices[] = array(
	            "name"      => "Google Nexus 10",
	            "slug"      => "google-nexus-10",
	            "height"    => "2560",
	            "width"     => "1600",
	            "type"      => "tablet"
    		);
	       	       
	       return $devices;
		}
		
		/*
		* Gets a full list of saved devices from settings
		*
		* @return	array of devices
		*
		*/
		public function get_devices(){
            $settingsObj = DPSFolioAuthor_Settings::getInstance();
		    $settings = $settingsObj->get_settings();
            return isset($settings["devices"]) ? $settings["devices"] : $this->initial_devices();
		}
		
		/*
		* Gets an array of devices based on the specified field
		*
        * @param    $field device field: name, slug, height, width, type
        * @param    $value what to match
		* @return	array of devices matching the field
		*
		*/
		public function get_device( $field, $value ){
		    foreach( $this->get_devices() as $device ){
        		if($device[$field] == $value){
            		return $device;
        		}
    		}
		}
		
		public function get_devices_by( $field, $value ){
		    $devices = array();
		    foreach( $this->get_devices() as $device ){
        		if($device[$field] == $value){
            		array_push($devices, $device);
        		}
    		}
    		return $devices;
		}
		
        public function get_device_by_dimension( $width, $height ){
            $deviceByWidth = $this->get_devices_by( "width", $width );
            foreach($deviceByWidth as $device){
                if($device["height"] == $height){
                    return $device;
                }
            }
            return false;
        }		
        
/*  BASIC FOLIO ARRAY  */

       /*
		* Constructs a folio array
		*
		* @param	string either pass in the local wordpress ID or the hostedID
		* @return	array
		*
		*/


/* UNUSED ACTIONS */
        // TODO: If items change in versions or callbacks needed
        public function registerHookCallbacks(){}
        public function activate( $networkWide ){
            // TODO ADD saving of settings field for devices if it doesn't exist
        }
	   	public function deactivate(){}
	   	public function upgrade( $dbVersion = 0 ){}
	   	protected function isValid( $property = 'all' ){}
		public function init(){}

    } // END class DPSFolioAuthor_Device
}
