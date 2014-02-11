<?php
/**
 * Class : DPSFolioAuthor_Folio
 *
 * A class for constructing and maniuplating folios in the wordpress plugin
 *
 * @license    TBD
 * @version    Release: @package_version@
 * @link       http://www.adobe.com
 */

/*
    TODO: replace `from Adobe` with found device - create device class

*/

if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

if(!class_exists('DPSFolioAuthor_Folio')) {

	class DPSFolioAuthor_Folio extends DPSFolioAuthor_Module {

	    public $folioPrefix;
	    public $articlePrefix;
	    public $folioPostType;
	    public $articlePostType;
	    public $tmpDir;

		protected function __construct(){
		    $this->folioPostType = DPSFolioAuthor_CPT_Folio::POST_TYPE_SLUG;
    		$this->articlePostType = DPSFolioAuthor_CPT_Article::POST_TYPE_SLUG;
    		$this->folioPrefix = $this->folioPostType . "_";
    		$this->articlePrefix = $this->articlePostType . "_";
    		$this->tmpDir = (substr(sys_get_temp_dir(), -1) == '/') ? sys_get_temp_dir() : sys_get_temp_dir() . "/";
		}

       /*
		* Constructs a folio array
		*
		* @param	string either pass in the local wordpress ID or the hostedID
		* @return	array
		*
		*/
		public function folio( $localID ){

			if( filter_var($localID, FILTER_VALIDATE_INT) !== FALSE ){
				$folioPost = get_post($localID);
				if( !$folioPost ){ return new WP_Error('broke', __("Folio does not exist for local folio ID: $localID")); }
			}else{
				$localID = $this->get_local_from_hosted( $localID );
				if( !$localID ){ return new WP_Error('broke', __("Folio does not exist for hosted ID")); }
				$folioPost = get_post($localID);
			}

		    $folio = array(
        		        "localID"   	=>  $localID,                                                   // wordpress ID of folio
        		        "hostedID"      =>  $this->get_folio_field( $localID, 'hostedID' ),             // folio ID on adobe hosting
        		        "type"          =>  ($folioPost->post_parent > 0) ? "rendition" : "parent",     // type of folio: parent or rendition
        		        "linked"    	=>  $this->is_folio_linked( $localID ),                         // boolean if folio is linked and editable in WP
        		        "status"    	=>  $this->get_folio_field( $localID, 'status' ),               // array of mod dates of fields
                        "renditions"    =>  $this->get_folio_field( $localID, 'renditions' ),           // array of child folios (renditions)
        		        "meta"      	=>  $this->get_folio_field( $localID, 'meta' ),                 // farray of adobe metadata
        		        "covers"        =>  $this->get_folio_field( $localID, 'covers' ),               // cover images for the folio
        		        "account"       =>  $this->get_folio_field( $localID, 'account' ),              // adobe hosting account
        		        "parent"        =>  $folioPost->post_parent,                                    // local ID of wordpress parent
        		        "device"        =>  $this->get_folio_field( $localID, 'device' ),               // target device meta
        		     );
            return $folio;
		}

		// Array of meta that can not be edited by user
		public function disabledMeta(){
    		return array(
    		    "folioID"       => "",
                "createDate"    => "",
                "modifyDate"    => ""
    		);
		}

		// Array of meta fields returned by Adobe
		public function adobeMeta(){
    		$adobe = DPSFolioAuthor_Adobe::getInstance();
    		return $adobe->folioMeta();
		}
        
        // Helper to add Adobe defaults if value for key is null
		public function setFolioDefaults( $meta ){
            // adobe meta and defaults
            $adobeMeta = $this->adobeMeta();
            foreach($meta as $key => $value){
                if( empty($meta[$key]) ){
                    // replace folio value with adobe default if empty
                    $meta[$key] = $adobeMeta[$key];
                }
            }
            return $meta;
        }

        // Parent meta fields
		public function parentMeta(){
    		return array(
    		    "folioName"         => "",
                "folioNumber"       => "",
                "folioDescription"  => "",
                "publicationDate"   => "",
                "coverDate"         => "",
                "filters"           => "",
                "magazineTitle"     => ""
    		);
		}

		/*
		* Get a specific field for the folio
		*
		* @param	string  $localID the wordpress ID of the post for the folio
		* @field	string  field name to return
		* @return   returns the stored value for a folio field
		*
		*/
		public function get_folio_field( $localID, $field ){
    		switch($field){
        		case 'device'       : return $this->get_folio_device($localID);break;
                case 'renditions'   : return $this->get_folio_renditions($localID);break;
                case 'meta'         : return $this->get_folio_meta($localID);break;
                case 'hostedID'     : return get_post_meta($localID, $this->folioPrefix . "folioID", true);break;
                case 'status'       : return $this->get_folio_status($localID);break;
                case 'covers'       : return $this->get_folio_covers($localID);break;
                case 'account'      : return $this->get_folio_account( $localID );break;
                default: return  new WP_Error('broke', __("No fields found for $field"));
    		}
		}

		/*
		* Update a specific field for a folio
		*
		* @localID	string  $localID the wordpress ID of the post for the folio
		* @field	string  field name for updating
		* @value    value to put in the field
		* @return   returns result from updating field
		*
		*/
		public function update_folio_field( $localID, $field, $value ){
    		switch($field){
                case 'device'       : $updated = update_post_meta($localID, $this->folioPrefix . "device", $value);break;
                case 'meta'         : $updated = $this->update_folio_meta($localID, $value);break;    // META VALUE TAKES AN ARRAY TO MERGE
                case 'hostedID'     : $updated = $this->update_folio_hosted_id($localID, $value);break;
                default: return  new WP_Error('broke', __("No fields found for $field"));
    		}
    		// update folio mod date for each section
    		if( !empty($updated) ){ $this->update_field_modified_date($localID, $field); }
		}
		
		public function update_field_modified_date( $localID, $field ){
    		$folioPost = get_post($localID);
            update_post_meta($localID, $field . "_mod", $folioPost->post_modified);
		}

		// Orientation: `horizontal` or `vertical`
		public function update_folio_covers($localID, $cover, $orientation){
		    if($orientation == "horizontal"){
    		    return update_post_meta($localID, $this->folioPrefix . "cover_h", $cover);
		    }else if($orientation == "vertical"){
    		    return update_post_meta($localID, $this->folioPrefix . "cover_v", $cover);
		    }
		}

		public function get_folio_covers( $localID ){
    		return array(
    		    "horizontal" => get_post_meta($localID, $this->folioPrefix . "cover_h", true),
    		    "vertical" => get_post_meta($localID, $this->folioPrefix . "cover_v", true)
            );
		}

		public function get_folio_status( $localID ){
		    return array(
		        "meta" => get_post_meta($localID, "meta" . "_mod", true),
		        "device" => get_post_meta($localID, "device" . "_mod", true),
		        "published" => get_post_meta($localID, "hostedID" . "_mod", true),
		    );
		}
		
		public function get_folio_account(){
    		
		}
		
		public function update_folio_account(){
    		
		}
		
		public function get_folio_device( $localID ){
    		$deviceName = get_post_meta($localID, $this->folioPrefix . "device", true);
    		$deviceService = DPSFolioAuthor_Device::getInstance(); 
    		$device = $deviceService->get_device( "name", $deviceName );
            
            // if no results found, try to find it by width and height
    		if( empty($device) ){
        		$device = $deviceService->get_device_by_dimension( get_post_meta($localID, $this->folioPrefix . "resolutionWidth", true), get_post_meta($localID, $this->folioPrefix . "resolutionHeight", true) );
        		// update device value for resolution with correct device
        		if(!empty($device)){ $this->update_folio_field( $localID, "device", $device["name"] ); }
    		}
    		// if 
    		return $device;
		}

		/*
		* Gets all of the adobe metadata for a specific folio
		*
		* @param	string  $localID the wordpress ID of the post for the folio
		* @return	array of adobe metadata
		*
		*/
		public function get_folio_meta( $localID ){
		    // get all adobe meta fields
		    $adobeMeta = $this->adobeMeta();
		    // get all post meta for the folio
		    $postMeta = get_post_meta( $localID );
		    // get parent meta fields
		    $parentMeta = $this->parentMeta();
		    // check if folio is a rendition. If rendition get the parent folio meta
		    $parentFolio = $this->is_rendition( $localID );
		    if($parentFolio){
		        $parentFolioMeta = get_post_meta( $parentFolio );
		    }
		    $return = array(); // create an empty array for the return
		    foreach( $adobeMeta as $key => $value ){
                if( isset($postMeta[$this->folioPrefix . $key]) ){ $return[$key] = $postMeta[$this->folioPrefix . $key][0]; }
                else{ $return[$key] = ""; }
                // override with parent meta if it exists:
                if( isset($parentFolioMeta) && array_key_exists($key, $parentMeta) ){
                    $return[$key] = $parentFolioMeta[$this->folioPrefix . $key][0];
                }
		    }
		    return $return;
		}

		/*
		* Gets all of a folio's renditions
		* Renditions are child folios that hold the same meta as their parent but are different sizes
		*
		* @param	string  $localID the wordpress ID of the post for the folio
		* @return	array of folio arrays
		*
		*/
        public function get_folio_renditions( $localID ){
    		$renditionArgs = array(
                'post_parent' => $localID,
                'post_type'   => $this->folioPostType,
                'post_status' => 'any'
            );
		    $renditions = get_children( $renditionArgs );
		    $data = array();
		    if($renditions){
    		    foreach( $renditions as $rendition ){
    		        $folio = $this->folio( $rendition->ID );
        		    array_push( $data, $folio );
    		    }
		    }
		    return $data;
		}

        /*
		* Determines if folio is a rendition (a child post)
		*
		* @param	string  $localID the wordpress ID of the post for the folio
		* @return	boolean
		*
		*/
        public function is_rendition( $localID ){
		    $folio = get_post($localID);
		    return ( $folio->post_parent == 0 ) ? false : $folio->post_parent;
        }

        /*
		* Updates adobe metadata field for folio
		*
		* @param	string  $localID the wordpress ID of the post for the folio
		* @param	array   $updatedMeta array of adobe values for the folio
		* @return	Returns meta_id if the meta doesn't exist, otherwise returns true on success and false on failure.
		*
		*/
		public function update_folio_meta( $localID, $updatedMeta = array() ){
		    // get current meta for folio
		    $currentMeta = $this->get_folio_field( $localID, 'meta' );
		    // if current meta is blank make sure it's at least a blank array
		    $currentMeta = ( !is_array($currentMeta) ) ? array() : $currentMeta;
		    // merge old and new meta values together
		    $mergedMeta = array_merge($currentMeta, $updatedMeta);
		    // cleanup dates and us current date if no date is supplied
		    $adobeService = DPSFolioAuthor_Adobe::getInstance();
            $mergedMeta["coverDate"] = $adobeService->format_date( $mergedMeta["coverDate"] );  // clean up date to match what adobe expects
            $mergedMeta["publicationDate"] = $adobeService->format_date( $mergedMeta["publicationDate"] );  // clean up date to match what adobe expects
            // add adobe defaults if field is empty
            $finalMeta = $this->setFolioDefaults($mergedMeta);
            //if folio is not a rendition (parent) only update parent fields
            if(	!$this->is_rendition( $localID ) ){ $finalMeta = array_intersect_key($finalMeta,$this->parentMeta()); }
            // save all post meta into wordpress
            $updates = false;
            foreach( $finalMeta as $key => $value ){
                $updated = update_post_meta($localID, $this->folioPrefix . $key, $value);
                if( !empty($updated) ){ $updates = true; }
            }
    		return $updates;
		}

		/*
		* Updates the hosting ID (adobe folio ID) for the folio
		*
		* @param	string  $localID the wordpress ID of the post for the folio
		* @param	string  $hostedID the adobe ID for the folio
		* @return	Returns meta_id if the meta doesn't exist, otherwise returns true on success and false on failure.
		*
		*/
		public function update_folio_hosted_id( $localID, $hostedID ){
    		return update_post_meta($localID, $this->folioPrefix . "folioID", $hostedID);
		}

        /*
		* Determine if folio has been linked to wordpress or if it's only available on Adobe
		*
		* @param	string  $localID the wordpress ID of the post for the folio
		* @return	boolean TRUE if it's connected to wordpress
		*
		*/
		public function is_folio_linked( $localID ){
            $owner = get_post_meta($localID, $this->folioPrefix . "owner", true);
            if( $owner == "local" ){ return true; }
            else if( $owner == "hosted" ){ return false; }
            else{
                $this->link_folio($localID);
                return true;
            }
		}

        /*
		* Updates folio to be linked to wordpress
		*
		* @param	string  $localID the wordpress ID of the post for the folio
		* @return	Returns meta_id if the meta doesn't exist, otherwise returns true on success and false on failure.
		*
		*/
		public function link_folio( $localID ){
		    $renditions = $this->get_folio_field( $localID, 'renditions' );
		    if( !empty($renditions) ){
		        foreach($renditions as $rendition){
		            update_post_meta($rendition["localID"], $this->folioPrefix . "owner", "local");
		        }
		    }
		    return update_post_meta($localID, $this->folioPrefix . "owner", "local");
		}

        /*
		* Updates folio to be unlinked to wordpress (only available on Adobe)
		*
		* @param	string  $localID the wordpress ID of the post for the folio
		* @return	Returns meta_id if the meta doesn't exist, otherwise returns true on success and false on failure.
		*
		*/
		public function unlink_folio( $localID ){
		    $renditions = $this->get_folio_field( $localID, 'renditions' );
		    if( !empty($renditions) ){
		        foreach($renditions as $rendition){
		            update_post_meta($rendition["localID"], $this->folioPrefix . "owner", "hosted");
		        }
		    }
		    return update_post_meta($localID, $this->folioPrefix . "owner", "hosted");
		}


/*  FOLIO ACTIONS  */

       /*
		* Saves folio information from adobe POST command
		*
		* @param	string  $localID the wordpress ID of the post for the folio
		* @param    $_POST array
		*
		*/
		public function update_folio_from_post( $localID ){
            $folioPOST = isset($_POST[$this->folioPostType]) ? $_POST[$this->folioPostType] : array();
            foreach( $folioPOST as $key => $value ){
                update_post_meta($localID, $this->folioPrefix . $key, $value);
            }
            
            // COVER: vertical
		    if( isset($_FILES[$this->folioPrefix . "cover_v"]) ){
			    if($_FILES[$this->folioPrefix . "cover_v"]["tmp_name"] != ""){
    			    $attachementID = media_handle_upload( $this->folioPrefix . "cover_v", $localID);
    			    if(!is_array($attachementID)){ $this->update_folio_covers( $localID, $attachementID, "vertical"); }
			    }
			}

			// COVER: horizontal
		    if( isset($_FILES[$this->folioPrefix . "cover_h"]) ){
			    if($_FILES[$this->folioPrefix . "cover_h"]["tmp_name"] != ""){
    			    $attachementID = media_handle_upload( $this->folioPrefix . "cover_h", $localID);
    			    if(!is_array($attachementID)){ $this->update_folio_covers( $localID, $attachementID, "horizontal"); }
			    }
			}
			
			// ARTICLE POSITIONS
			if(isset($folioPOST["articles"])){
    			$articleService = DPSFolioAuthor_Article::getInstance();
    			$articles = $folioPOST["articles"];
    			$articleService->update_positions( explode(",",$articles) );
			}
			
			// SIDECAR
			if( isset($_FILES[$this->folioPrefix . "sidecar"]) && !empty($_FILES[$this->folioPrefix . "sidecar"]["tmp_name"]) ){
    			$sidecarService = DPSFolioAuthor_Sidecar_Importer::getInstance();
    			$return = $sidecarService->import( $_FILES[$this->folioPrefix . "sidecar"]["tmp_name"], $localID );
			}

            wp_update_post( array( 'ID' => $localID, 'post_title' => "Folio number: " . $localID) ); // updates the post title as the title of the folio
        }

        /*
		* Creates a rendition of a folio
		* Renditions are child folios that hold the same basic meta as their parent but are different sizes
		*
		* @param	string      $localID the wordpress ID of the post for the folio
		* @param	array       $metaOverrides overrides to the default meta from adobe
		* @param	string      $device slug for rendition
		* @param    boolean     $createHosted - true if the rendition should also be created on Adobe
		*
		*/
        public function create_rendition( $localID, $metaOverrides = array(), $device = "custom", $createHosted = false ){
            $rendition = $this->create_local_folio( true, $localID );

            // get parent metadata
            $parentMeta = $this->get_folio_field( $localID, 'meta' );
            $parentMeta["folioID"] = ""; // don't bring over the folioID

            // update rendition with new metadata
            $merged = array_merge( $parentMeta, $metaOverrides);
            $this->update_folio_field( $rendition, 'meta', $merged );
            $this->update_folio_field( $rendition, 'device', $device );
            if($createHosted){
                // create folio in the cloud
                $adobe = DPSFolioAuthor_Adobe::getInstance();
                $return = $adobe->create_folio( $this->get_folio_field($rendition, 'meta') );
                if( !is_wp_error($return) ){
                    $this->update_folio_field($rendition, 'hostedID', $return->folioID);
                    $this->update_folio_field($rendition, 'meta', $return->folioPostType);
                    return  $rendition;
                }else{
                    return $return;
                }
            }else{
                // don't create folio in the cloud, just return the localID of the rendition
                return $rendition;
            }
        }

        public function push_rendition( $renditionID ){
            $adobe = DPSFolioAuthor_Adobe::getInstance();
            $return = $adobe->create_folio( $this->get_folio_field($renditionID, 'meta') );
            if( !is_wp_error($return) && !empty($return) ){
                $hostedID = $return->folioID;
                $this->update_folio_field($renditionID, 'hostedID', $hostedID);
                return $renditionID;
            }else{
                if(is_wp_error($return)){
                    return $return;
                }else{
                    return new WP_Error('general', __("Could not push rendition. Something happened in our call to Adobe."));
                }
            }
        }

        /*
		* Syncs folios from Adobe with the Wordpress instance
		*
		* @return	array  returns an array of new folios created
		*
		*/
		public function sync_hosted_folios(){
		    // get all folios from Adobe hosting
            $return = $this->get_folios_from_adobe();
            
            // make sure no errors happend while getting the folios
            if(is_wp_error($return)){ return $return; }
            
            $adobeFolios = $return->folios;
            // create local folios from the Adobe folio list
            $this->create_local_folios_from_adobe( $adobeFolios );
                
            // get all local unlinked folios in WP and update existing or delete non-existing unlinked folios
            $localUnlinkedFolios = $this->get_folios( array( 'filter' =>'hosted') );
            foreach( $localUnlinkedFolios as $localUnlinkedFolio ){
            	// loop through all unlinked hosted renditions
            	foreach($localUnlinkedFolio["renditions"] as $index => $rendition){
                	$delete = true; // reset delete flag
                	foreach( $adobeFolios as $adobeFolio ){
    	            	if( $adobeFolio->folioID == $rendition["hostedID"] ){
    	            	    // found an unlinked hosted rendition - update meta and don't delete
    	            	    $this->update_folio_field( $rendition["parent"], "meta", (array)$adobeFolio );
    	            	    $delete = false; 
                        }
                	}
                	// if the rendition isn't linked and not available on adobe hosting - delete the reference to it from 
                	if($delete == true){ $this->delete_folio($rendition, false); }
            	}
            }
            
            // get the new list of unlinked folios
            $localUnlinkedFolios = $this->get_folios( array( 'filter' =>'hosted') );
            foreach( $localUnlinkedFolios as $localUnlinkedFolio ){
                if( count($localUnlinkedFolio["renditions"]) < 1){
                    $this->delete_folio($localUnlinkedFolio["localID"], false); 
                }
            }
            
            return true;
		}

        /*
		* Syncs folios from Adobe with the Wordpress instance
		*
        * @param    array   $hostedFolios is an array of folios from adobe
		* @return	array   returns an array of created local folios
		*
		*/
        public function create_local_folios_from_adobe( $hostedFolios ){
            $createdFolios = array(); // initial array for created folios
            foreach( $hostedFolios as $hostedFolio ){
                $hostedFolio = (array)$hostedFolio;
                // first check if local folio exists (if it does move on to the next one)
                if( !$this->has_local_folio( $hostedFolio['folioID'] ) ){
                     // see if hosted folio has a local rendition
                    $foundFolio = $this->has_local_rendition( $hostedFolio );
                    if( $foundFolio ){
                        // create a rendition
                        $rendition = $this->create_rendition( $foundFolio, $hostedFolio, 'from Adobe'  ); //TODO: replace device name with found device name
                        $this->unlink_folio($rendition);
                        array_push($createdFolios, $rendition["rendition"]);
                    }else{
                        // doesn't have a rendition to link with so create a parent folio + rendition
    	            	$parentFolio = $this->create_local_folio( false );
    	            	$parentMeta = $this->parentMeta();

                        // TODO: IF COVER + PUB DATE DO NOT EXIST USE NOW - weird bug that doesn't show pub date for IND folios without articles
                        $this->update_folio_field( $parentFolio, "meta", array_intersect_key($hostedFolio,$parentMeta) );
                        
    	            	// create rendition for the parent for the hosted folio
    	            	//TODO: replace device name with found device name
                        $rendition = $this->create_rendition( $parentFolio, $hostedFolio, 'Custom'  );
                        $this->update_folio_hosted_id( $rendition, $hostedFolio['folioID'] );
                        $this->unlink_folio( $rendition );
                        array_push($createdFolios, $rendition);
                    }
                }
            }
            return $createdFolios;
        }
        
        public function get_covers_from_adobe($folio){
            $adobe = DPSFolioAuthor_Adobe::getInstance();
            
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');

            $adobe = DPSFolioAuthor_Adobe::getInstance();
            $horizontal = $adobe->download_folio_preview( $folio["hostedID"], "landscape" );
            if( is_wp_error($return) ){ return $return; }
            $file = tempnam($this->tmpDir,"image");
            $size = file_put_contents($file, $horizontal);
            $download = array(
                'name'     => 'cover_h.jpg',
                'type'     => 'image/jpeg',
                'tmp_name' => $file,
                'error'    => array(),
                'size'     => $size,
            );
            $attachmentID = media_handle_sideload($download, 50995, "COVER-H");
            $this->update_folio_covers($folio["localID"], $attachmentID, "horizontal");

            $vertical = $adobe->download_folio_preview( $folio["hostedID"], "portrait" );
            if( is_wp_error($return) ){ return $return; }
            $file = tempnam($this->tmpDir,"image");
            $size = file_put_contents($file, $horizontal);
            $download = array(
                'name'     => 'cover_v.jpg',
                'type'     => 'image/jpeg',
                'tmp_name' => $file,
                'error'    => array(),
                'size'     => $size,
            );
            $attachmentID = media_handle_sideload($download, 50995, "COVER-V");
            $this->update_folio_covers($folio["localID"], $attachmentID, "vertical");
            return true;
        }
        
        /*
		* Searches local folios for matching renditions
		*
        * @param    array   $adobeFolio is an array of a folio returned from call to Adobe
		* @return	array   returns an array of created local folios
		*
		*/
        public function has_local_rendition( $adobeFolio ){
            // first see if there are any folios with matching the folio name
            $args = array(
            	'posts_per_page'   => 1,
            	'post_type'        => $this->folioPostType,
            	'post_status'      => 'publish',
            	'post_parent'      => 0,
            	'meta_query' => array(
            		array(
            			'key' => $this->folioPrefix.'folioName',
            			'value' => $adobeFolio["folioName"],
            		)
            	)
            );
            $query = new WP_Query( $args );
            $query->get_posts();

            if($query->have_posts()){
                $foundFolios = $this->get_folios_from_query( $query );
                foreach( $foundFolios as $localFolio ){
                    if( $localFolio["meta"]["folioNumber"] ==  $adobeFolio["folioNumber"] &&
                        $localFolio["meta"]["magazineTitle"] ==  $adobeFolio["magazineTitle"] ){
                        // TODO : check pub date
                        return $localFolio["localID"];
                    }else{
                        return false;
                    }
                }
            }else{
                return false;
            }
        }

        /*
		* Pulls changes from Adobe for a specific unlinked local folio or all unlinked local folios
		*
		* @param    array    $folio array (optional) If supplied it will update changes for specific folio
		                     if nothin is supplied then all local folios that are unlocked will be updated
		*
		*/
        public function pull_folio_changes( $folio = null ){
        	if( $folio ){
        		// pull changes for single folio
	        	$hostedFolio = $this->get_folios_from_adobe( $folio["hostedID"] );
		        if( !$folio["linked"] ){
		        	// only update meta if unlinked
	                $this->update_folio_field( $folio["localID"], 'meta', (array)$hostedFolio);
		        }
        	}else{
	            $hostedFolios = $this->get_folios_from_adobe();
	            foreach( $hostedFolios as $hostedFolio ){
		            $foundFolio = $this->has_local_folio( $hostedFolio->folioID );
	                if( $foundFolio && !$this->is_folio_linked($foundFolio) ){
		                $this->update_folio_field( $folio["localID"], 'meta', (array)$hostedFolio);
	                }
	            }
        	}
        }

        /*
		* Syncs articles for a specific folio from Adobe
		*
		* @param    array    $folio array
		* @return	array    returns an array of localIDs of created articles
		*
		*/
        public function sync_folio_articles( $folio ){
	        // sync over all articles - reaturns array of created articles
            $articleService = DPSFolioAuthor_Article::getInstance();
            return $articleService->sync_articles_for_folio( $folio );
        }

        /*
		* Get all folios from Adobe
		*
		* @param    string   $hostedID (optional) is an Adobe folio ID and will return meta for specific folio
        *                    if no ID is supplied it will return meta for ALL folios
		* @return	array    returns an array of folio meta (or folio meta for all folios in the account)
		*
		*/
		public function get_folios_from_adobe( $hostedID = null ){
            $adobe = DPSFolioAuthor_Adobe::getInstance();
            return $adobe->get_folio_metadata( $hostedID );
        }

        /*
		* Determines if the hosted ID have a local folio associated with it
		*
		* @param    string   $hostedID is the folioID of a folio hosted on Adobe
		* @return	string   returns the localID (wordpress post ID) for the local folio found
		*
		*/
		public function has_local_folio( $hostedID ){
    		$args = array(
            	'posts_per_page'   => 1,
            	'post_type'        => $this->folioPostType,
            	'post_status'      => 'publish',
            	'meta_query' => array(
            		array(
            			'key' => $this->folioPrefix.'folioID',
            			'value' => $hostedID,
            		)
            	)
            );
            $query = new WP_Query( $args );
            $folios = $query->get_posts();
            if( $query->have_posts() ){
                $query->the_post();
                return get_the_ID();
            }else{
                return false;
            }
		}

        /*
		* Creates a local folio reference in Wordpress
		*
		* @param    boolean   $hostedID (optional) is an Adobe folio ID and will return meta for specific folio
		* @param    string    $parent (optional) is an Adobe folio ID and will return meta for specific folio
		* @return	string    the wordpress ID of the new folio post
		*
		*/
        public function create_local_folio( $linked = true, $parent = 0){
            $newFolio = array(
              'post_title'    => $this->folioPrefix . " created at: " . microtime(),
              'post_content'  => "",
              'post_status'   => 'publish',
              'post_author'   => get_current_user_id(),
              'post_type'     => $this->folioPostType,
              'post_parent'   => $parent
            );
            $folioID = wp_insert_post( $newFolio );
            if($linked){ $this->link_folio($folioID); }
            else{ $this->unlink_folio($folioID); }
            return $folioID;
        }

        /*
		* Deletes a folio (both local and hosted)
		*
		* @param    string or        $folio is either a Wordpress ID or folio array to delete
		            folio array
		* @param    boolean          $deleteHosted whether to delete the hosted folio on Adobe
		* @return	WP RETURN        returns the result of wp_delete_post
		*
		*/
        public function delete_folio( $folio, $deleteHosted = true ){
            global $dpsErrors;
            
            $deleteHosted = ( $deleteHosted && // should the hosted folio be deleted
                              $this->is_folio_linked( $folio["localID"] ) && // make sure folio is actually linked
                              !empty($folio["hostedID"]) ); // check for a reference to the linked folio on hosting
                            
            // delete folio hosted on adobe if $deleteHosted requirements met               
            if( $deleteHosted ){
                $adobe = DPSFolioAuthor_Adobe::getInstance();
                $return = $adobe->delete_folio( $folio["hostedID"] );
                if( is_wp_error($return) ){ $dpsErrors->add("hosted", "Couldn't delete the folio from Adobe Hosting.", $return); }
            }
            
            // delete the articles in the rendition
            $articleService = DPSFolioAuthor_Article::getInstance();
            $articles = $articleService->get_articles( array(
                'filter' => null, 
                'folioID' => $folio["localID"] 
            ));
            foreach( $articles as $article ){
                // double check article is part of the folio and delete
                if($article["folio"] == $folio["localID"]){ $articleService->delete_article( $article["localID"], false ); }
            }
            
            // if renditions exist them
            if( !empty($folio["renditions"]) ){
                foreach( $folio["renditions"] as $rendition ){
                    $this->delete_folio( $rendition, $deleteHosted );
                }
            }
            
            // delete the actual folio in WP
            if( get_post_type($folio["localID"]) == $this->folioPostType){
                $deleted = wp_delete_post( $folio["localID"], true );
                if(!$deleted){ $dpsErrors->add("local", "Couldn't delete the local folio from Wordpress", $deleted); }
            }
            return true;
        }

        /*
		* Pulls changes from Adobe for a specific unlinked local folio or all unlinked local folios
		*
		* @param    string   $filter can be `local`, `hosted`, or null - not passing a filter returns all folios
		*
		*/
        public function get_folios( $args = array() ){
            $defaults = array (
         		'filter'       => null, // either `local` or `hosted` or null returns all folios
         		'limit'        => -1, // max number of folios to retrieve
         		'offset'       => 0, // ability to offset the found posts
         		'orderby'      => 'post_date', // how to order the found folios,
         		'parentOnly'   => false
        	);
            $args = wp_parse_args( $args, $defaults );
            extract( $args, EXTR_SKIP );
        	
	        $queryArgs = array(
            	'posts_per_page'   => $limit,
            	'orderby'          => $orderby,
            	'post_type'        => $this->folioPostType,
            	'post_parent'      => empty($parentOnly) ? null : 0
            );

			// filter folios by local or hosted
	        if( isset($filter) ){
	        	$queryArgs['meta_query'] = array(
            		array(
            			'key' => $this->folioPrefix . 'owner',
            			'value' => $filter,
            		)
            	);
	        }

	        $query = new WP_Query( $queryArgs );
            $query->get_posts();
            return $this->get_folios_from_query( $query );
        }


	    /*
		* Get a local ID from a hosted ID
		*
		* @param    string        $hostedID adobe hosted ID
		* @return	string        returns a wordpress local ID for the folio
		*
		*/
        public function get_local_from_hosted( $hostedID ){
            $args = array(
            	'posts_per_page'   => 1,
            	'post_type'        => $this->folioPostType,
            	'post_status'      => 'publish',
            	'meta_query' => array(
            		array(
            			'key' => $this->folioPrefix.'folioID',
            			'value' => $hostedID,
            		)
            	)
            );
            $query = new WP_Query( $args );
            $folios = $query->get_posts();
            if( $query->have_posts() ){
                $the_query->the_post();
                return get_the_ID();
            }else{
                return false;
            }
        }

        /*
		* Get an array of folios from a wp_query
		*
		* @param    WP_Query      $query from a WP_Query
		* @return	array         returns an array of folio arrays
		*
		*/
        public function get_folios_from_query( $query ){
    		$folios = array();
    		$renditions = array();

    		if( $query->have_posts() ){
    		    while ( $query->have_posts() ) {
                    $query->the_post();

                    $folio = $this->folio( get_the_ID() );
                    if( count($folio["renditions"]) > 0 ){
                        foreach( $folio["renditions"] as $rendition ){
                            array_push($renditions, $rendition["localID"]);
                        }
                    }
                    array_push($folios, $folio);
            	}
            }

            // unset all renditions
            foreach($folios as $index => $folio){
                if( in_array($folio["localID"], $renditions) ){ unset($folios[$index]); }
            }
            return $folios;
		}

		public function upload_covers( $folio ){
            $adobe = DPSFolioAuthor_Adobe::getInstance();

            if(!empty($folio["covers"]["vertical"])){
                $return = $adobe->upload_folio_preview( $folio["hostedID"], get_attached_file($folio["covers"]["vertical"]),"portrait" );
    		    if( is_wp_error($return) ){ return $return; }
            }
            
            if(!empty($folio["covers"]["horizontal"])){
                $return = $adobe->upload_folio_preview( $folio["hostedID"], get_attached_file($folio["covers"]["horizontal"]),"landscape" );
    		    if( is_wp_error($return) ){ return $return; }
            }
            
            return true;
		}

        /*
		* Pushes an update for a specific folio to Adobe
		*
		* @param    array         $folio array
		* @return	array         returns response from adobe
		*
		*/
        public function update_folio_on_adobe( $folio ){
            $adobe = DPSFolioAuthor_Adobe::getInstance();
            unset($folio["meta"]["folioID"]);
            $return = $adobe->update_folio( $folio["hostedID"], $folio["meta"] );
            if(!is_wp_error($return)){
                $this->update_folio_field( $folio["localID"], "meta", (array)$return->folioInfo );
            }
            return $return;
        }

        /*
		* Upload an HTMLResources ZIP for specified folio
		*
		* @param    array         $folio array
		* @return	array         returns response from adobe
		*
		*/
        public function upload_htmlresources( $folio ){
            $settingsMeta = DPSFolioAuthor::PREFIX . 'settings';
            $settings = get_option( $settingsMeta );
            $htmlresources = $settings['htmlresources'];
            if(is_wp_error($htmlresources)){ return new WP_Error('general', __("Could not get the uploaded HTMLResources from your settings page")); }
            try{
                $file = tempnam($this->tmpDir, 'dps-');
                $content = @file_get_contents($htmlresources);
                $result = file_put_contents($file, $content);
            }catch(Exception $e){ return new WP_Error('general', __("Could not get HTMLResources: $htmlresources")); }

            if($result){
                $adobe = DPSFolioAuthor_Adobe::getInstance();
                copy( $file , $this->tmpDir . "HTMLResources.zip");
                $return = $adobe->upload_html_resources( $folio["hostedID"], $this->tmpDir . "HTMLResources.zip" );

                if(!is_wp_error($return)){
                    $this->update_folio_field( $folio["localID"], "meta", array( "hasHTMLResources", "true" ) );
                    return $return;
                }else{
                    return new WP_Error('general', __("Could not upload HTMLResources"));
                }
            }else{
                return new WP_Error('general', __("Could not copy HTMLResources: $result"));
            }
        }

        /*
		* Deletes HTMLResources zip from Adobe's server for specified folio
		*
		* @param    array         $folio array
		* @return	array         returns response from adobe
		*
		*/
        public function delete_htmlresources( $folio, $htmlResources ){
            $adobe = DPSFolioAuthor_Adobe::getInstance();
            $return = $adobe->delete_html_resources( $folio["meta"], $htmlResources );
            if(!is_wp_error($return)){
                $this->update_folio_field( $folio["localID"], "meta", array( "hasHTMLResources", "false" ) );
            }
            return $return;
        }


/* UNUSED ACTIONS */
        // TODO: If items change in versions or callbacks needed
        public function registerHookCallbacks(){}
        public function activate( $networkWide ){}
	   	public function deactivate(){}
	   	public function upgrade( $dbVersion = 0 ){}
	   	protected function isValid( $property = 'all' ){}
		public function init(){}

    } // END class DPSFolioAuthor_Folio
}
