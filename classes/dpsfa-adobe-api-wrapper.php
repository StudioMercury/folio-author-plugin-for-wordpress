<?php

// TODO: add class for checking adobe response if ok

// temporarily allow script to run forever
set_time_limit(0);

if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

if( !class_exists( 'DPSFolioAuthor_Adobe' ) )
{

	class DPSFolioAuthor_Adobe extends DPSFolioAuthor_Module{

		public $config;
		public $client;

		protected function __construct(){
		    require dirname( __DIR__ ) . '/libs/folio-producer-api/vendor/autoload.php';
            $this->registerHookCallbacks();
		}

		public function get_plugin_config(){
		    $settingsObj = DPSFolioAuthor_Settings::getInstance();
		    $settings = $settingsObj->get_settings();
    		return array(
                'api_server' => 'https://dpsapi2.acrobat.com',
                'company' => $settings["company"],
                'consumer_key' => $settings["key"],
                'consumer_secret' => $settings["secret"],
                'email' => $settings["login"],
                'password' => $settings["password"]
            );
		}

		public function folioMeta(){
    		return array(
    		    "folioID"               => "",
        		"folioName"             => "",
        		"folioNumber"           => "",
                "magazineTitle"         => "",
                "publicationDate"       => "",
                "folioDescription"      => "",
                "resolutionWidth"       => 0,
                "resolutionHeight"      => 0,
                "defaultAssetFormat"    => "Auto",
                "defaultJPEGQuality"    => "High",
                "bindingRight"          => false,
                "locked"                => false,
                "version"               => "",
                "folioIntent"           => "PortraitOnly", // `LandscapeOnly`, `PortraitOnly`, `Both`
                "coverDate"             => "",
                "targetViewer"          => "26.00.00",
                "filters"               => "",
                "createDate"            => "",
                "modifyDate"            => "",
                "hasHTMLResources"      => false,
                "viewer"                => "" // `web`, ``
            );
		}

		public function articleMeta(){
    		return array(
    		    "access"                        => "Closed", // `Closed`, `Open`, `Free`
        		"assetFormat"                   => "Auto", // `Auto`, `JPEG`, `PNG`, `PDF`
        		"author"                        => "",
                "description"                   => "",
                "flatten"                       => false, // true if DPS treats each page as separate stack
                "hasAudio"                      => false,
                "hasSlideShow"                  => false,
                "hasVideo"                      => false,
                "hideFromTOC"                   => false,
                "isAdvertisement"               => false,
                "jpegQuality"                   => "High", // `Minimum`, `Low`, `Medium`, `High`, `Maximum`
                "kicker"                        => "",
                "locked"                        => false,
                "name"                          => "",
                "numberOfLandscapeAssets"       => 0,
                "numberOfPortraitAssets"        => 0,
                "orientation"                   => "Portrait", // `Landscape`, `Portrait`, `Both`
                "downloadPriority"              => "Low", // `Low`, `Medium`, `High`
                "section"                       => "",
                "smoothScrolling"               => "Always", // `Never`, `Landscape`, `Portrait`, `Always`
                "sortNumber"                    => 0,
                "tags"                          => "",
                "targetViewer"                  => "26.00.00",
                "title"                         => "",
                "uncompressedFolioSize"         => 0,
                "userData"                      => "",
                "canAccessReceipt"              => false
            );
		}

		/*
		* Check the status response from Adobe
		*
		* @param	object     $return complete list of meta fields to send to Adobe
		* @param	string     $prettyError a pretty error to display to the user if there's an error
		* @return   array      returns an true response if ok or WP_ERROR object if something went wrong
		*
		*/
		public function check_adobe_response( $return, $prettyError ){
    		if( $return->response){
        		return true;
    		}else{
    		    $errors = $return->errors();
    		    $wpError = new WP_Error('general', __($prettyError), $errors);
    		    foreach( $errors as $error ){
    		        $status = (isset($error->status)) ? (string)$error->status : "";
            		switch( $status ){
                		case "AccessControlViolation":
                            // The current user does not have access to the resource specified by the URL.
                            $wpError->add( "AccessControlViolation",  __("Your account does not have access to the item you're trying to manipulate. " . $error->message) );
                		    break;
                        case "InvalidContentType":
                            // The Content-Type entity header for the request was not application/json or multipart/form-data (required for requests that include file data).
                            $wpError->add( "InvalidContentType",  __("There was an error while sending your request to the server. If you keep receiving this message, please contact support. " . $error->message) );
                		    break;
                		case "InvalidMessageContent":
                		    // There was an error parsing the JSON message content.
                		    $wpError->add( "InvalidMessageContent",  __("There was an error parsing the JSON message. If you keep receiving this message, please contact support. " . $error->message) );
                		    break;
                        case "InvalidParameter":
                            // An unknown or illegal parameter name or value was supplied.
                		    $wpError->add( "InvalidParameter",  __("Unknown or illegal parameter was supplied in the request. Please check the values for your request and try again. " . $error->message) );
                		    break;
                        case "InvalidTicket":
                            // The current session ticket has expired. The client should create a new session and retry
                		    $wpError->add( "InvalidTicket",  __("The current session ticket has expired and we have been unable to renew the session automatically. You should not see this error. If you keep receiving this message, please contact support. " . $error->message) );
                		    break;
                        case "ServiceRequired":
                            // The user has not signed up a service that is required for this request.
                		    $wpError->add( "ServiceRequired",  __("Your have not signed up for a service that is required for this request. Please check your account status at: <a href=\"https://digitalpublishing.acrobat.com/welcome.html\">https://digitalpublishing.acrobat.com/welcome.html</a> " . $error->message) );
                		    break;
                        case "SpeedLimitExceeded":
                            // The client has made too many requests in a time period.
                		    $wpError->add( "SpeedLimitExceeded",  __("You've made too many requests in a time period. Please try your request again in a little bit. " . $error->message) );
                		    break;
                        case "TOUAcceptanceRequired":
                            // The user has not accepted one or more Terms of Use.
                		    $wpError->add( "TOUAcceptanceRequired",  __("You have not accepted on or more of the Terms of Use in your DPS account. Please log into <a href=\"https://digitalpublishing.acrobat.com/welcome.html\">https://digitalpublishing.acrobat.com/welcome.html</a> to accept the Terms of Use. " . $error->message) );
                		    break;
                        case "unknown":
                            // The server encountered an unexpected error. (Usually server is down)
                		    $wpError->add( "unknown",  __("Unknown server error. The DPS servers could be down. You can check the link <a href=\"http://status.adobedps.com/\">http://status.adobedps.com/</a> to see if any DPS resources are down. " . $error->message) );
                		    break;
                        case "UnknownServerMethod":
                            // The resource specified by the URL does not correspond to a known server request.
                		    $wpError->add( "UnknownServerMethod",  __("You're trying to access a resource the server doesn't know. " . $error->message) );
                		    break;
                        case "VerificationRequired":
                            // The user has not verified their account, and the grace period has expired.
                		    $wpError->add( "VerificationRequired",  __("You have not verified your DPS account and the grace period has expired. Please log into <a href=\"https://digitalpublishing.acrobat.com/welcome.html\">https://digitalpublishing.acrobat.com/welcome.html</a> to resend your verification e-mail. " . $error->message) );
                		    break;
            		}
                }
            return $wpError;
            }
		}

		/*
		* Cleans up the metadata provided by the user and wordpress plugin to make sure it's what Adobe expects
		*
		* @param	array    $meta complete list of meta fields to send to Adobe
		* @param    string   $type is either `article` or `folio`
		* @return   array    returns the array formatted as Adobe's servers expect
		*
		*/
		public function clean_adobe_meta( $meta, $type = "folio" ){
		    if( strtolower($type) == "article" ){
		        $adobeMeta = $this->articleMeta();
		    }else{
		        $adobeMeta = $this->folioMeta();
		    }
    		foreach( $adobeMeta as $key => $value ){
        		if( array_key_exists($key,$meta) ){
        		    if( gettype($value) == "boolean" ){ $meta[$key] = filter_var($meta[$key], FILTER_VALIDATE_BOOLEAN);}
        		    if( $key == "targetViewer" && empty($meta[$key]) ){ $meta[$key] = "20.00.00"; }
            		settype($meta[$key], gettype($value));
        		}
    		}
    		return $meta;
		}

        /**
		 * Formats a date for use with DPS
		 * @date string of date (if no date is passed the current date/time is used)
		 * @return formatted date that Adobe expects
		 */
        public function format_date( $date ){
            return ( !empty($date) ) ? date("Y-m-d\TH:i:s", strtotime($date) ) : date("Y-m-d\TH:i:s");
        }

/* Adobe calls */

		/* Gets folio meta date for specific folio or all if folio ID isn't supplied */
		public function get_folio_metadata( $folio_id = null ){
            if( $folio_id ){
    		    $return = $this->client->execute('get_folio_metadata', array( 'folio_id' => $folio_id ));
		    }else{
    		     $return = $this->client->execute('get_folios_metadata');
		    }

            $errorMessage = "Could not get folio metata";
		    $check = $this->check_adobe_response($return, $errorMessage);
            if( !is_wp_error($check) ){
                return $return->response; // return the response
            }else{
                return $check; // return the WP_Error object
            }
		}

		public function create_folio( $options ){
    		/* REQUIRED OPTIONS
    		 *
    		 * folioName
    		 * folioNumber
    		 * magazineTitle
    		 * resolutionHeight
    		 * resolutionWidth
    		 *
    		 */
            $cleanOptions = $this->clean_adobe_meta( $options, "folio" );
    		$return = $this->client->execute('create_folio', $cleanOptions);
    		$errorMessage = "Could not create a new folio";
		    $check = $this->check_adobe_response($return, $errorMessage);
            if( !is_wp_error($check) ){
                return $return->response; // return the response
            }else{
                return $check; // return the WP_Error object
            }
		}

		public function delete_folio( $folio_id ){
    		$return = $this->client->execute('delete_folio', array(
                'folio_id' => $folio_id
            ));

            $errorMessage = "Could not delete the folio";
		    $check = $this->check_adobe_response($return, $errorMessage);
            if( !is_wp_error($check) ){
                return $return->response; // return the response
            }else{
                return $check; // return the WP_Error object
            }
		}

		public function duplicate_folio( $folio_id ){
    		$return = $this->client->execute('duplicate_folio', array(
                'folio_id' => $folio_id
            ));

            $errorMessage = "Could not duplicate the folio";
		    $check = $this->check_adobe_response($return, $errorMessage);
            if( !is_wp_error($check) ){
                return $return->response; // return the response
            }else{
                return $check; // return the WP_Error object
            }
		}

		public function update_folio ( $folio_id, $options = array() ){
		    $options["folio_id"] = $folio_id;
            $cleanOptions = $this->clean_adobe_meta( $options, "folio" );
    		$return = $this->client->execute('update_folio', $cleanOptions);

            $errorMessage = "Could not update the folio";
		    $check = $this->check_adobe_response($return, $errorMessage);
            if( !is_wp_error($check) ){
                return $return->response; // return the response
            }else{
                return $check; // return the WP_Error object
            }
		}

		public function upload_folio_preview( $folio_id, $imagePath, $orientation ){
    		$return = $this->client->execute('upload_folio_preview_image', array(
                'filepath' => $imagePath,
                'folio_id' => $folio_id,
                'orientation' => $orientation // either 'landscape' or 'portrait'
            ));

            $errorMessage = "Could not upload the folio preview";
		    $check = $this->check_adobe_response($return, $errorMessage);
            if( !is_wp_error($check) ){
                return $return->response; // return the response
            }else{
                return $check; // return the WP_Error object
            }
		}

		public function download_folio_preview( $folio_id, $orientation ){
            $return = $this->client->execute('download_folio_preview_image', array(
                'folio_id' => $folio_id,
                'orientation' => $orientation // either 'landscape' or 'portrait'
            ));

            $errorMessage = "Could not download the folio preview";
		    $check = $this->check_adobe_response($return, $errorMessage);
            if( !is_wp_error($check) ){
                return $return->response; // return the response
            }else{
                return $check; // return the WP_Error object
            }
		}

		public function delete_folio_preview( $folio_id, $orientation ){
    		$return = $this->client->execute('delete_folio_preview_image', array(
                'folio_id' => $folio_id,
                'orientation' => $orientation // either 'landscape' or 'portrait'
            ));

            $errorMessage = "Could not delete the folio";
		    $check = $this->check_adobe_response($return, $errorMessage);
            if( !is_wp_error($check) ){
                return $return->response; // return the response
            }else{
                return $check; // return the WP_Error object
            }
		}

		public function upload_html_resources( $folio_id, $htmlPath ){
    		$return = $this->client->execute('upload_html_resources', array(
                'filepath' => $htmlPath,
                'folio_id' => $folio_id
            ));

            $errorMessage = "Could not upload the HTMLResources zip";
		    $check = $this->check_adobe_response($return, $errorMessage);
            if( !is_wp_error($check) ){
                return $return->response; // return the response
            }else{
                return $check; // return the WP_Error object
            }
		}

		public function delete_html_resources( $folio_id ){
    		$return = $this->client->execute('delete_html_resources', array(
                'folio_id' => $folio_id
            ));

            $errorMessage = "Could not delete the HTMLResources zip";
		    $check = $this->check_adobe_response($return, $errorMessage);
            if( !is_wp_error($check) ){
                return $return->response; // return the response
            }else{
                return $check; // return the WP_Error object
            }
		}

		public function create_article( $folio_id, $folioPath, $options = array() ){
            //$options["name"] = $options["name"];
    		$options["filepath"] = $folioPath;
    		$options["folio_id"] = $folio_id;

            $cleanOptions = $this->clean_adobe_meta( $options, "article" );
    		$return = $this->client->execute('create_article', $cleanOptions);

    		$errorMessage = "Could not create a new article";
		    $check = $this->check_adobe_response($return, $errorMessage);
            if( !is_wp_error($check) ){
                return $return->response; // return the response
            }else{
                return $check; // return the WP_Error object
            }
		}

		public function delete_article( $article_id, $folio_id ){
    		$return = $this->client->execute('delete_article', array(
                'article_id' => $article_id,
                'folio_id' => $folio_id
            ));

            $errorMessage = "Could not upload delete the article";
		    $check = $this->check_adobe_response($return, $errorMessage);
            if( !is_wp_error($check) ){
                return $return->response; // return the response
            }else{
                return $check; // return the WP_Error object
            }
		}

		public function update_article_meta( $article_id, $folio_id, $options = array() ){
		    $options["article_id"] = $article_id;
		    $options["folio_id"] = $folio_id;
            $cleanOptions = $this->clean_adobe_meta( $options, "article" );
    		$return = $this->client->execute('update_article_metadata', $cleanOptions);

    		$errorMessage = "Could not update the article metadata";
		    $check = $this->check_adobe_response($return, $errorMessage);
            if( !is_wp_error($check) ){
                return $return->response; // return the response
            }else{
                return $check; // return the WP_Error object
            }
		}

		public function get_all_article_meta( $folio_id, $resultData = "All" ){
    		$return = $this->client->execute('get_articles_metadata', array(
                'folio_id' => $folio_id,
                'resultData' => $resultData //Core (the default), Head, and All
            ));

            $errorMessage = "Could not get the metadata for all of the articles";
		    $check = $this->check_adobe_response($return, $errorMessage);
            if( !is_wp_error($check) ){
                return $return->response; // return the response
            }else{
                return $check; // return the WP_Error object
            }
		}

		public function return_as_JSON($data){
			header('Content-Type: application/json');
			if($_GET['callback']){
				echo $_GET['callback']."(".json_encode($data).")";
			}else{
				echo json_encode($data);
			}
			exit;
		}

		/**
		 * Initializes variables
		 * @mvc Controller
		 * @author Ian Dunn <ian@iandunn.name>
		 */
		public function init(){
            if( did_action( 'init' ) !== 1 )
				return;

            $this->config = $this->get_plugin_config();
            $this->client = new DPSFolioProducer\Client($this->config);
		}

        /* UNUSED FUNCTIONS */
		public function registerHookCallbacks()
        {
            add_action( 'init', array( $this, 'init' ) );
        }
		public function activate( $networkWide ){ }
		public function deactivate(){ }
		public function upgrade( $dbVersion = 0 ){ }
		protected function isValid( $property = 'all' ){ return true; }


	} // end DPSFolioAuthor_Adobe
}

?>
