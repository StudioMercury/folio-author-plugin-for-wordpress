<?php
/**
 * Class : DPSFolioAuthor_Article
 *
 * A class for constructing and maniuplating articles in the wordpress plugin
 *
 * @license    TBD
 * @version    Release: @package_version@
 * @link       http://www.adobe.com
 */

if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
	die( 'Access denied.' );

if(!class_exists('DPSFolioAuthor_Article')) {

	class DPSFolioAuthor_Article extends DPSFolioAuthor_Module {

        public $folioPrefix;
	    public $articlePrefix;
	    public $folioPostType;
	    public $articlePostType;

		protected function __construct(){
            $this->folioPostType = DPSFolioAuthor_CPT_Folio::POST_TYPE_SLUG;
    		$this->articlePostType = DPSFolioAuthor_CPT_Article::POST_TYPE_SLUG;
    		$this->folioPrefix = $this->folioPostType . "_";
    		$this->articlePrefix = $this->articlePostType . "_";
        }

		/*
		* Constructs an article array
		*
		* @param	string either pass in the local wordpress ID or the hostedID
		* @return	array
		*
		*/
		public function article( $localID ){
		    if( empty($localID) ){
                return new WP_Error('broke', __("No ID supplied"));
		    }else if( filter_var($localID, FILTER_VALIDATE_INT) == FALSE ){
				$localID = $this->get_local_from_hosted( $localID );
				if( !$localID ){ return new WP_Error('broke', __("Article does not exist for hosted ID: $localID")); }
			}
			
			$articlePost = get_post($localID);
            if( !$articlePost ){ return new WP_Error('broke', __("Article does not exist for local article ID: $localID")); }

		    // ARTICLE SETUP
		    $article = array(
        		            "localID"       =>  $localID,                                               // wordpress ID of article
                            "preview"       =>  $this->get_article_field( $localID, 'preview' ),        // TOC preview (post ID of the image)
                            "template"      =>  $this->get_article_field( $localID, 'template' ),       // article template
            		        "linked"        =>  $this->is_article_linked( $localID ),                   // is article editable by WP (or just an IND stack)
            		        "meta"          =>  $this->get_article_meta( $localID ),                    // adobe meta for the article,
            		        "position"      =>  $this->get_article_field( $localID, 'position'),        // article position in folio
            		        "renditions"    =>  $this->get_article_field( $localID, 'renditions' ),     // sub articles (renditions) / duplicates just different sizes
            		        "folio"         =>  $this->get_article_field( $localID, 'folio' ),          // article's attached folio
            		        "status"        =>  $this->get_article_field( $localID, 'status' ),         // status of article
            		        "modifyDate"    =>  $articlePost->post_modified,                            // article modified date
                       );
            if( $this->is_rendition($localID) ){
        		$article["hostedID"] 	    =   $this->get_article_field( $localID, 'hostedID' );       // article ID on adobe hosting
                $article["parent"]          =   $articlePost->post_parent;
            }else{
                $article["origin"]          =   $this->get_article_field( $localID, 'origin' );
            }
		    return $article;
		}

		/*
		* Array of meta that can not be edited by user
		*/
		public function disabledMeta(){
    		return array(
    		    "id"                            => "",
    		    "assetFormat"                   => "",
    		    "flatten"                       => "",
    		    "jpegQuality"                   => "",
    		    "numberOfLandscapeAssets"       => "",
    		    "numberOfPortraitAssets"        => "",
    		    "targetViewer"                  => "",
    		    "uncompressedFolioSize"         => "",
    		    "userData"                      => ""
    		);
		}

		/*
		* Array of meta fields returned by Adobe
		*/
		public function adobeMeta(){
    		$adobe = DPSFolioAuthor_Adobe::getInstance();
    		return $adobe->articleMeta();
		}

		/*
		* Array of meta fields for a parent article
		*/
		public function parentMeta(){
    		return array(
    		    "name"          => "",
                "title"         => "",
                "description"   => "",
                "author"        => "",
                "kicker"        => "",
                "section"       => "",
                "tags"          => ""
    		);
		}

/*  ARTICLE META DATA MANIPULATION  */

		/*
		* Get a specific field for the article
		*
		* @param	string  $localID the wordpress ID of the post for the article
		* @field	string  field name to return
		* @return   returns the stored value for a article field
		*
		*/
		public function get_article_field( $localID, $field ){
    		switch($field){
        		case 'preview'          : return $this->get_article_preview($localID);
        		case 'hostedID'         : return get_post_meta($localID, $this->articlePrefix . "id", true);break;
        		case 'name'             : return get_post_meta($localID, $this->articlePrefix . "name", true);break;
                case 'template'         : return get_post_meta($localID, $this->articlePrefix . "template", true);break;
                case 'meta'             : return $this->get_article_meta($localID);break;
                case 'position'         : return get_post_meta($localID, $this->articlePrefix . "sortNumber", true);break;
                case 'renditions'       : return $this->get_article_renditions($localID);break;
                case 'folio'            : return get_post_meta($localID, $this->articlePrefix . "folio", true);break;
                case 'status'           : return $this->get_article_status($localID);break;
                case 'modifyDate'       : return get_post_meta($localID, $this->articlePrefix . "modifyDate", true);break;
                case 'origin'           : return get_post_meta($localID, $this->articlePrefix . "origin", true);break;
                default: return  new WP_Error('broke', __("No fields found for $field"));
    		}
		}

		/*
		* Update a specific field for an article
		*
		* @localID	string  $localID the wordpress ID of the post for the article
		* @field	string  field name for updating
		* @value    value to put in the field
		* @return   returns result from updating field
		*
		*/
		public function update_article_field( $localID, $field, $value ){
    		switch($field){
                case 'preview'              : $updated = (empty($value)) ? delete_post_meta($localID, $this->articlePrefix . "preview") : update_post_meta($localID, $this->articlePrefix . "preview", $value);break;
                case 'name'                 : $updated = update_post_meta($localID, $this->articlePrefix . "name", $value);break;
                case 'template'             : $updated = $this->update_article_template($localID, $value);break;
                case 'meta'                 : $updated = $this->update_article_meta($localID, $value);break;
                case 'position'             : $updated = (empty($value)) ? delete_post_meta($localID, $this->articlePrefix . "sortNumber") : update_post_meta($localID, $this->articlePrefix . "sortNumber", $value);break;
                case 'folio'                : $updated = (empty($value)) ? delete_post_meta($localID, $this->articlePrefix . "folio") : update_post_meta($localID, $this->articlePrefix . "folio", $value);break;
                case 'hostedID'             : $updated = (empty($value)) ? delete_post_meta($localID, $this->articlePrefix . "id") : update_post_meta($localID, $this->articlePrefix . "id", $value);break;
                case 'modifyDate'           : $updated = (empty($value)) ? delete_post_meta($localID, $this->articlePrefix . "modifyDate") : update_post_meta($localID, $this->articlePrefix . "modifyDate", $value);break;
                case 'origin'               : $updated = update_post_meta($localID, $this->articlePrefix . "origin", $value);break;
                case 'origin-tags'          : $updated = update_post_meta($localID, $this->articlePrefix . "origin-tags", $value);break;
                case 'origin-categories'    : $updated = update_post_meta($localID, $this->articlePrefix . "origin-categories", $value);break;
                default: return  new WP_Error('broke', __("No fields found for $field"));
    		}
    		// update folio mod date for each section
    		if( !empty($updated) ){ $this->update_field_modified_date($localID, $field); }
		}		
		
		public function update_field_modified_date( $localID, $field ){
    		$articlePost = get_post($localID);
            update_post_meta($localID, $field . "_mod", $articlePost->post_modified);
		}
		
		public function update_article_template( $localID, $value ){
			// Get default template
            $settingsMeta = DPSFolioAuthor::PREFIX . 'settings';
            $settings = get_option( $settingsMeta );
            if( empty($value)){
                if( !empty($settings["template"]) ){
                    // if no value default to the default template from the plugin settings
                    $value = $settings["template"];
                }else{
                    // if nothing set and no default template has been selected automatically set the first one (if templates exist)
                    $templateService = DPSFolioAuthor_Templates::getInstance();
                    $templates = $templateService->getTemplates();
                    $value = !empty( $templates[key($templates)] ) ? $templates[key($templates)] : "";
                }
            }
            return update_post_meta($localID, $this->articlePrefix . "template", $value);
		}
		
		public function get_article_preview( $localID ){
            $preview = get_post_meta($localID, $this->articlePrefix . "preview", true);
    		if( !empty($preview) ){
    		    $image = wp_get_attachment_image_src( $preview, "article-toc" );
		        return array( "url" => $image[0], "attachmentID" => $preview );
    		}else{
    		    $settings = get_option( DPSFolioAuthor::PREFIX . 'settings' );
    		    if( isset($settings["automaticPreview"]) && !empty($settings["automaticPreview"]) ){
        		    $autoPreview = $this->get_auto_preview( $localID );
    		    }
    		    $preview = !empty($autoPreview) ? array( "url" => $autoPreview["url"], "attachmentID" => $autoPreview["attachmentID"] ) : array( "url" => DPSFA_URL ."/assets/folio/toc.png", "attachmentID" => null );
    		    $this->update_article_field( $localID, 'preview', $preview["attachmentID"] );
                return $preview;
    		}
		}
		
		public function get_auto_preview( $localID ){
    		// see if there's a featured image
    		$featuredImage = get_post_thumbnail_id($localID);
    		if( empty($featuredImage) ){ 
                $articlePost = get_post($localID);
                if( empty($articlePost->post_content) ){ return false; }
    		    $html = str_get_html($articlePost->post_content);
                $images = $html->find('img');
                if( !empty($images) ){
                    global $wpdb;
                    $prefix = $wpdb->prefix;
                    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM " . $prefix . "posts" . " WHERE guid='%s';", $images[0]->src )); 
                    if( count($attachment) > 0){
                        $image = wp_get_attachment_image_src( $attachment[0], 'article-toc' );
                		if( !empty($image) ){
                    		return array(
                    		    "url" => $image[0],
                    		    "attachmentID" => $attachment[0]
                            );
                		}                		
                    }
                }
    		}else{
    		    $image = wp_get_attachment_image_src( $featuredImage, 'article-toc' );
        		return array(
        		    "url" => $image[0],
        		    "attachmentID" => $featuredImage
                );
    		}
    		return false;
		}

		public function get_article_status( $localID ){
		    return array(
		        "meta" =>       get_post_meta($localID, "meta" . "_mod", true),
		        "device" =>     get_post_meta($localID, "device" . "_mod", true),
		        "published" =>  get_post_meta($localID, "hostedID" . "_mod", true),
		        "preview" =>    get_post_meta($localID, "preview" . "_mod", true),
		        "template" =>   get_post_meta($localID, "template" . "_mod", true),
		        "position" =>   get_post_meta($localID, "position" . "_mod", true),
		        "name" =>       get_post_meta($localID, "name" . "_mod", true),
		    );
		}

		/*
		* Gets all of the adobe metadata for a specific article
		*
		* @param	string  $localID the wordpress ID of the post for the article
		* @return	array of adobe metadata
		*
		*/
		public function get_article_meta( $localID ){
		    $adobeMeta = $this->adobeMeta(); // get all adobe meta fields
		    $postMeta = get_post_meta( $localID ); // get all post meta fields
		    $parentMeta = $this->parentMeta();

            $articlePost = get_post( $localID );
		    if($articlePost->post_parent > 0){
		        $parentArticleMeta = get_post_meta( $articlePost->post_parent );
		    }
		    
		    $return = array(); // create an empty array for the return
		    foreach( $adobeMeta as $key => $value ){
                if( isset($postMeta[$this->articlePrefix . $key]) ){ $return[$key] = $postMeta[$this->articlePrefix . $key][0]; }
                else{ $return[$key] = ""; }
                
                // override with parent meta if it exists:
                if( isset($parentArticleMeta) && array_key_exists($key, $parentMeta) ){
                    $return[$key] = isset($parentArticleMeta[$this->articlePrefix . $key][0]) ? $parentArticleMeta[$this->articlePrefix . $key][0] : "" ;
                }
		    }
		    return $return;
		}
		
		/*
		* Gets all of a article's renditions
		* Renditions are child articles that hold the same meta as their parent but are different sizes
		* Renditions only have to share the same article name
		*
		* @param	string  $localID the wordpress ID of the post for the article
		* @return	array of article arrays
		*
		*/
        public function get_article_renditions( $localID ){
    		$renditionArgs = array(
                'post_parent' => $localID,
                'post_type'   => $this->articlePostType,
                'post_status' => 'any'
            );
		    $renditions = get_children( $renditionArgs );
		    $data = array();
		    if($renditions){
    		    foreach( $renditions as $rendition ){
    		        $article = $this->article( $rendition->ID );
        		    array_push( $data, $article );
    		    }
		    }
		    return $data;
		}

		/*
		* Determines if an article is a rendition (a child post)
		*
		* @param	string  $localID the wordpress ID of the post for the article
		* @return	boolean
		*
		*/
        public function is_rendition( $localID ){
		    $article = get_post($localID);
		    return ( $article->post_parent == 0 ) ? false : true;
        }

		/*
		* Updates adobe metadata field for an article
		*
		* @param	string  $localID the wordpress ID of the post for the article
		* @param	array   $updatedMeta array of adobe values for the article
		* @return	Returns meta_id if the meta doesn't exist, otherwise returns true on success and false on failure.
		*
		*/
		public function update_article_meta( $localID, $updatedMeta = array() ){
		    $currentMeta = $this->get_article_field( $localID, 'meta' );
		    $currentMeta = ( !is_array($currentMeta) ) ? array() : $currentMeta; // return emprt array if no meta value
		    $mergedMeta = array_merge($currentMeta, $updatedMeta); // merge old and new values together
			
			// clean up with defaults
			$adobeMeta = $this->adobeMeta(); // adobe defaults
            foreach($mergedMeta as $key => $value){
                if( empty($mergedMeta[$key]) && !empty($adobeMeta[$key])){
                    $mergedMeta[$key] = $adobeMeta[$key];
                }
            }
            
            $updates = false;
            foreach( $mergedMeta as $key => $value ){
                $updated = update_post_meta($localID, $this->articlePrefix . $key, $value);
                if( !empty($updated) ){ $updates = true; }
            }
    		return $updates;
		}

		/*
		* Updates the hosting ID (adobe article ID) for the article
		*
		* @param	string  $localID the wordpress ID of the post for the article
		* @param	string  $hostedID the adobe ID for the article
		* @return	Returns meta_id if the meta doesn't exist, otherwise returns true on success and false on failure.
		*
		*/
		public function update_article_hosted_id( $localID, $hostedID ){
    		return update_post_meta($localID, $this->articlePrefix . "id", $hostedID);
		}

		/*
		* Determine if an article has been linked to wordpress or if it's only available on Adobe
		*
		* @param	string  $localID the wordpress ID of the post for the article
		* @return	boolean TRUE if it's connected to wordpress
		*
		*/
		public function is_article_linked( $localID ){
            $owner = get_post_meta($localID, $this->articlePrefix . "owner", true);
            if( $owner == "local" ){ return true; }
            else if( $owner == "hosted" ){ return false; }
            else{
                //if no owner, assume it can be edited in WP and link it
                $this->link_article($localID);
                return true;
            }
		}

		/*
		* Updates article to be linked to wordpress
		*
		* @param	string  $localID the wordpress ID of the post for the article
		* @return	Returns meta_id if the meta doesn't exist, otherwise returns true on success and false on failure.
		*
		*/
		public function link_article( $localID ){
    		return update_post_meta($localID, $this->articlePrefix . "owner", "local");
		}

		/*
		* Updates article to be unlinked to wordpress (only available on Adobe)
		*
		* @param	string  $localID the wordpress ID of the post for the article
		* @return	Returns meta_id if the meta doesn't exist, otherwise returns true on success and false on failure.
		*
		*/
		public function unlink_article( $localID ){
    		return update_post_meta($localID, $this->articlePrefix . "owner", "hosted");
		}

/*  ARTICLE ACTIONS  */

       /*
		* Saves article information from adobe POST command
		*
		* @param	string  $localID the wordpress ID of the post for the article
		* @param    $_POST array
		*
		*/
		public function update_article_from_post( $localID ){		    
		            
            $articlePOST = $_POST[ $this->articlePostType ];
            $this->update_article_field( $localID, 'meta', $articlePOST);

			// If article has a preview thumbnail
		    if( isset($_FILES[$this->articlePrefix . "preview"]) ){
			    if($_FILES[$this->articlePrefix . "preview"]["tmp_name"] != ""){
    			    $attachementID = media_handle_upload( $this->articlePrefix . "preview", $localID);
                    $this->update_article_field( $localID, 'preview', $attachementID);
			    }
			}
			if( empty($_POST[ $this->articlePrefix . "preview" ]) ){
                delete_post_meta( $localID, $this->articlePrefix . "preview" );
			}

			// make sure to update defaults:
			$template = !empty($_POST[$this->articlePostType]["template"]) ? $_POST[$this->articlePostType]["template"] : "";
            $this->update_article_field( $localID, 'template', $template);
            
			// make sure post's title is up to date with article number
			
        }

        /*
		* Duplicates articles from one rendition to another
		*
		* @param	string  $folioTo WP ID of the original folio to link the duplciated articles
		* @param	string  $folioFrom WP ID of the folio to grab the articles to copy
		* @return	array of folio arrays
		*
		*/
		public function duplicate_articles_from_rendition( $folio, $articles ){
		    $currentArticles = $this->get_articles( null, $folio );
		    foreach($articles as $article){
		        $add = true;
		        foreach($currentArticles as $currentArticle){
    		        if( $currentArticle["parent"] == $article["parent"] ){ $add = false; }
		        }
		        if($add){
		            $newArticle = $this->duplicate_article( $article["localID"], $article['parent'] );
                    $this->update_article_field( $newArticle, 'hostedID', '');
                    $this->update_article_field( $newArticle, 'folio', $folio);
		        }
		    }
		    return true;
		}

		/*
		* Creates a rendition of an article
		* Renditions are child articles that hold the same basic meta as their parent but are different content
		*
		* @param	string      $localID the wordpress ID of the post for the article
		* @param	array       $metaOverrides overrides to the default meta from adobe
		* @param	string      $folio associated folio
		* @return   string      retuns the wordpress post ID of the rendition
		*
		*/
        public function create_rendition( $localID, $metaOverrides = array()){
            // first duplciate the post
            $rendition = $this->duplicate_article( $localID, $localID );

            // apply overrides to the new article
            $originalMeta = $this->get_article_field( $localID, 'meta' );
            $merged = array_merge( $originalMeta, $metaOverrides );
            $this->update_article_field( $rendition, 'meta', $merged );
            return $rendition;
        }

        /*
		* Duplicates a post as an article
		* This will duplcate all fields associated with the original post / article
		*
		* @param	string      $localID the wordpress ID of the post for the article
		* @return   string      retuns the wordpress post ID of the duplicated article
		*
		*/
        public function duplicate_article( $localID, $parent = 0){
            $originalPost = get_post( $localID );
            $newArticleArgs = array(
              'post_parent'     => $parent,
			  'post_name'       => 'article-created-'.microtime(),
			  'post_title'      => 'article-'.microtime(),
			  'post_type'       => $this->articlePostType,
			  'post_author'     => $originalPost->post_author,
			  'post_content'    => $originalPost->post_content,
			  'post_excerpt'    => $originalPost->post_excerpt,
			  'comment_status'  => $originalPost->comment_status,
              'post_status'     => 'publish',
			  'post_password'   => $originalPost->post_password,
			  'to_ping'         => $originalPost->to_ping,
			  'menu_order'      => $originalPost->menu_order,
			);
			$newArticle = wp_insert_post( $newArticleArgs );
			
			// give post a friendlier title
			$update_post = array();   // create empty array
            $update_post['ID'] = $newArticle;   // set post ID to be updated
            $update_post['post_title'] = "article-$newArticle";  // set new value for title in that post
            $update_post['post_name'] = "article-$newArticle";  // set new value for title in that post
            wp_update_post( $update_post );  // update the post
            
			if($newArticle){
                // new article created duplicate all fields associated with it
                if( $originalPost->post_type != $this->articlePostType ){
                    $this->update_article_field( $newArticle, 'origin', $localID );
                    $this->update_article_field( $newArticle, 'original-tags', wp_get_post_tags($localID) ); // save all tags
                    $this->update_article_field( $newArticle, 'original-categories', wp_get_post_categories($localID) ); // save all tags
                }
                $this->link_article($newArticle); // make sure article is editable by wp

                // update all custom fields for post
                global $wpdb;
    			$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$localID");
        		if (count($post_meta_infos)!=0) {
        			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
        			foreach ($post_meta_infos as $meta_info) {
        				$meta_key = $meta_info->meta_key;
        				$meta_value = addslashes($meta_info->meta_value);
        				$sql_query_sel[]= "SELECT $newArticle, '$meta_key', '$meta_value'";
        			}
        			$sql_query.= implode(" UNION ALL ", $sql_query_sel);
        			$wpdb->query($sql_query);
        		}
    			return $newArticle;
            }else{
                return  new WP_Error('broke', __("Unable to create a duplicate article from post number: $localID"));  // something went wrong
            }
        }


		/*
		* Sync hosted articles in the wordpress instance
		* This funciton will sync new or remove any non existant hosted articles that haven't been linked to wp
		*
		* @param	array       $folio is a folio obj
		* @return	array       returns an array of new articles created
		*
		*/
		public function sync_hosted_articles( $folio ){
            /*
            $return = $this->get_articles_from_adobe( $folio );
            
            if(!is_wp_error($return)){ 
                $adobeArticles = $return->articles;
            }else{ return $return; }

            // first add any new folios from adobe hosting that are not in WP
            $createdArticles = array(); // initial array for created folios
            foreach( $hostedArticles as $hostedArticle ){
                $hostedArticle = $hostedArticle;
                $hostedArticle["articleMetadata"]["name"] = $hostedArticle["name"];
                // first check if local folio exists (if it does move on to the next one)
                $articleReference = $this->has_local_article( $hostedArticle['id'] );
                if( !$articleReference ){
                    // if no local folio exists - create it
	            	$article = $this->create_local_hosted_article( $hostedArticle, $localFolio);
                    array_push($createdArticles, $article);
                }else{
                    // it has a local reference - so update the metadata for it
                    $this->update_article_field($articleReference, 'meta', $hostedArticle);
                }
            }

            return $newArticles;
            */
        }

        /*
		* Creates an article reference to an adobe hosted article
		* Created articles can not be edited until linked with a local wordpress copy
		*
        * @param    array   $hostedArticles is an array of articles from adobe
        * @param    string  $folioLocalID is the local ID for the folio the articles are associated with
		* @return	string  the local ID of the local wordpress article reference created
		*
		*/
		public function create_local_hosted_article( $hostedArticle, $folioLocalID ){
    		$article = $this->create_local_article( false );
        	$adobeMetaKeys = $this->adobeMeta();
            $this->update_article_meta( $article, array_intersect_key($hostedArticle["articleMetadata"], $adobeMetaKeys) );
            $this->update_article_field( $article, 'hostedID', $hostedArticle['id'] );
            $this->update_article_field( $article, 'folio', $folioLocalID );
            return $article;
		}

        /*
		* Creates a local article reference in Wordpress
		*
		* @param    boolean   $hostedID (optional) is an Adobe article ID and will return meta for specific article
		* @param    string    $parent (optional) is an Adobe article ID and will return meta for specific article
		* @return	string    the wordpress ID of the new article post
		*
		*/
        public function create_local_article( $linked = true, $parent = 0){
            $newArticle = array(
              'post_title'    => $this->articlePrefix . " created at: " . microtime(),
              'post_content'  => "",
              'post_status'   => 'publish',
              'post_author'   => get_current_user_id(),
              'post_type'     => $this->articlePostType,
              'post_parent'   => $parent
            );
            $articleID = wp_insert_post( $newArticle );
            if($linked){ $this->link_article($articleID); }
            else{ $this->unlink_article($articleID); }
            return $articleID;
        }

        /*
		* Determines if the hosted ID has a local article associated with it
		*
		* @param    string   $hostedID is the article id of a article hosted on Adobe
		* @return	string   returns the localID (wordpress post ID) for the local article found
		*
		*/
		public function has_local_article( $hostedID ){
    		$args = array(
            	'posts_per_page'   => 1,
            	'post_type'        => $this->articlePostType,
            	'post_status'      => 'publish',
            	'meta_query' => array(
            		array(
            			'key' => $this->articlePrefix.'id',
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
		* Deletes an article (both local and hosted)
		*
		* @param    string or        $article is either a Wordpress ID or article array to delete
		            article array
		* @param    boolean          $deleteHosted whether to delete the hosted article on Adobe
		* @return	WP RETURN        returns the result of wp_delete_post
		*
		*/
        public function delete_article( $article, $deleteHosted = true ){
            if( !is_array($article) ){ $article = $this->article($article); }
            if( is_array($article) && $this->is_article_linked( $article["localID"] ) && $deleteHosted && array_key_exists("hostedID", $article) && !empty($article["hostedID"]) ){
                $folioService = DPSFolioAuthor_Folio::getInstance();
                $folio = $folioService->folio( $article["folio"] );
                if( !is_wp_error($folio) ){
                    $adobe = DPSFolioAuthor_Adobe::getInstance();
                    $return = $adobe->delete_article( $folio["hostedID"], $folio["hostedID"] );
                    if( is_wp_error($return) ){
                        return $return;
                    }
                }
            }

            /* Delete all renditions if this is a parent */
		    if( array_key_exists("renditions", $article) && count($article["renditions"]) > 0){
    		    foreach( $article["renditions"] as $rendition ){
                    $this->delete_article( $rendition );
    		    }
		    }
            if( get_post_type($article["localID"]) == $this->articlePostType){
                wp_delete_post( $article["localID"] , true );
            }
        }

		/*
		* Pulls changes from Adobe for a specific unlinked local article or all unlinked local articles
		*
		* @param    string   $filter can be `local`, `hosted`, or null - not passing a filter returns all articles
		* @param    string   $folioID is a local ID or `unattached` or for a folio or null returns all articles
		*
		*/
        public function get_articles( $filter = null, $folioID = null ){
	        $args = array(
            	'posts_per_page'   => -1,
            	'orderby'          => 'post_date',
                'order'            => 'ASC',
            	'post_type'        => $this->articlePostType,
                'meta_query'       => array(),
            );

			$args['meta_query']['relation'] = 'AND';
			// filter articles by local or hosted
	        if( isset($filter) ){
	            array_push( $args['meta_query'], array(
            			'key' => $this->articlePrefix . 'owner',
            			'value' => $filter,
            		)
                );
	        }

	        // filter articles by local or hosted
	        if( isset($folioID) ){
	            if( $folioID == "unattached" ){
	                array_push( $args['meta_query'], array(
                            'compare' => 'NOT EXISTS',
                			'key' => $this->articlePrefix . 'folio',
                			'value' => $folioID,
                		)
                    );
	            }else{
    	            array_push( $args['meta_query'], array(
                			'key' => $this->articlePrefix . 'folio',
                			'value' => $folioID
                		)
                    );
                    $args['orderby'] = 'meta_value_num';
                    $args['meta_key'] = $this->articlePrefix . 'sortNumber';
	            }
	        }
            if( isset($filter) && isset($folioID) ){ $args['meta_query']['relation'] = 'AND'; }
	        $query = new WP_Query( $args );
            $query->get_posts();
            return $this->get_articles_from_query( $query );
        }

        public function get_article_count( $folioID ){
            $args = array(
            	'posts_per_page'   => -1,
            	'orderby'          => 'post_date',
                'order'            => 'ASC',
            	'post_type'        => $this->articlePostType,
                'meta_query'       => array(),
            );

			$args['meta_query']['relation'] = 'AND';

	        // filter articles by local or hosted
            if( $folioID == "unattached" ){
                array_push( $args['meta_query'], array(
                        'compare' => 'NOT EXISTS',
            			'key' => $this->articlePrefix . 'folio',
            			'value' => $folioID,
            		)
                );
            }else{
	            array_push( $args['meta_query'], array(
            			'key' => $this->articlePrefix . 'folio',
            			'value' => $folioID
            		)
                );
                $args['orderby'] = 'meta_value';
                $args['meta_key'] = $this->articlePrefix . 'sortNumber';
            }
            $args['meta_query']['relation'] = 'AND';
	        $query = new WP_Query( $args );
            $query->get_posts();
	        return $query->post_count;
        }
        
        public function get_article_by_name( $name, $folioID ){
            $args = array(
            	'posts_per_page'   => 1,
            	'orderby'          => 'post_date',
                'order'            => 'ASC',
            	'post_type'        => $this->articlePostType,
                'meta_query'       => array(),
            );

			$args['meta_query']['relation'] = 'AND';
			// filter articles by local or hosted
            array_push( $args['meta_query'], array(
        			'key' => $this->articlePrefix . 'name',
        			'value' => $name,
        		)
            );

	        // filter articles by local or hosted
            array_push( $args['meta_query'], array(
        			'key' => $this->articlePrefix . 'folio',
        			'value' => $folioID
        		)
            );

	        $query = new WP_Query( $args );
            $query->get_posts();
            return $this->get_articles_from_query( $query );
        }

		/*
		* Get a local ID from a hosted ID
		*
		* @param    string        $hostedID adobe hosted ID
		* @return	string        returns a wordpress local ID for the article
		*
		*/
        public function get_local_from_hosted( $hostedID ){
            $args = array(
            	'posts_per_page'   => 1,
            	'post_type'        => $this->articlePostType,
            	'post_status'      => 'publish',
            	'meta_query' => array(
            		array(
            			'key' => $this->articlePrefix.'id',
            			'value' => $hostedID,
            		)
            	)
            );
            $query = new WP_Query( $args );
            $article = $query->get_posts();
            if( $query->have_posts() ){
                $query->the_post();
                return get_the_ID();
            }else{
                return false;
            }
        }

		/*
		* Get an array of articles from a wp_query
		*
		* @param    WP_Query      $query from a WP_Query
		* @return	array         returns an array of article arrays
		*
		*/
        public function get_articles_from_query( $query ){
    		$articles = array();
    		$renditions = array();
    		if( $query->have_posts() ){
    		    while ( $query->have_posts() ) {
                    $query->the_post();
                    $article = $this->article( get_the_ID() );
                    if( count($article["renditions"]) > 0 ){
                        foreach( $article["renditions"] as $rendition ){
                            array_push($renditions, $rendition["localID"]);
                        }
                    }
                    array_push($articles, $article);
            	}
            }
            // unset all renditions (since they're in the renditions field for the article array)
            foreach($articles as $index => $article){
                if( in_array($article["localID"], $renditions) ){ unset($articles[$index]); }
            }
            return $articles;
		}

		/*
		* Get all articles from Adobe (for a specific folio)
		*
		* @param    string   $folio is the folio obj of the folio to retrieve all articles
		* @return	array    returns an array of folio meta (or folio meta for all folios in the account)
		*
		*/
		public function get_articles_from_adobe( $folio ){
            $adobe = DPSFolioAuthor_Adobe::getInstance();
            return $adobe->get_all_article_meta( $folio["hostedID"] );
        }

		/*
		* Take a list of articles and update their positions in the order of the array
		*
		* @param    array   $articles an array of articles in the new order they should be
		*
		*/
		public function update_positions( $articles ){
            $counter = 1;
            foreach( $articles as $article ){
                $this->update_article_field( $article, 'position', ($counter*100) );
                $counter++;
            }
            return true;
        }

		public function import_article_from_post( $postID ){
		    $postTitle = get_the_title($postID);
    		$newArticle = $this->duplicate_article( $postID );
            $this->update_article_field( $newArticle, 'meta', array( "title" => $postTitle ) );
    		return $newArticle;
		}

		public function import_articles( $posts ){
    		foreach( $posts as $post ){
        		$return = $this->import_article_from_post( $post );
                if(is_wp_error($return)){ return $return; }
    		}
    		return true;
		}

		/*
		* Takes an array of posts IDs to import them into the plugin as articles
		*
		* @param    array   $posts an array of post IDs
		* @return   array   returns an array of newly created articles

		*
		*/
		public function import_article_from_posts( $posts ){
		    $newArticles = array();
		    foreach( $posts as $postID ){
    		    $newArticle = $this->duplicate_article( $postID );
    		    array_push( $newArticles, $newArticle );
		    }
    		return $newArticles;
		}

		public function add_article_to_folio( $localID, $folioID ){
    		$rendition = $this->create_rendition( $localID );
            $this->update_article_field( $rendition, 'folio', $folioID );
            return $rendition;
		}

		public function add_articles_to_folio( $articles, $folioID ){
    		foreach( $articles as $article ){
        		$return = $this->add_article_to_folio( $article, $folioID);
        		if(is_wp_error($return)){ return $return; }
    		}
    		return true;
		}
		
		/*
        * Pushes an update for a specific folio to Adobe
        *
        * @param    array         $article - article array
        * @param    array         $folio - folio array
        * @param    array         $meta - array of metadata to update. if empty it will update all metadata fields from the article 
        * @param    boolean       $update - whether to update the article after the metadata has been pushed to the cloud
        * @return	array         returns response from adobe
        *
        */
        public function push_article_meta( $article, $folio, $meta, $update = true ){
            // if options is empty use the article's meta
            if(empty($meta)){ $meta = $article["meta"]; }
            // if the ID exists in the meta it will be removed
            if(!empty($meta["id"])){ unset($meta["id"]); }
            // if there is a targer viewer, replace it with the folio's viewer
            if(!empty($meta["targetViewer"])){$meta["targetViewer"] = $folio["meta"]["targetViewer"]; } // set target viewer to be the folio's viewer
            
            $adobe = DPSFolioAuthor_Adobe::getInstance();
            $return = $adobe->update_article_meta( $article["hostedID"], $folio["hostedID"], $meta);
            if( !is_wp_error($return) && !empty($update) ){
                $this->update_article_field( $article["localID"], "meta", (array)$return->articleInfo );
            }
            return $return;
        }

        /*
        * Pushes an article to Adobe
        *
        * @param    array         $localID wordpress ID of the local article to push to adobe
        * @return	array         returns hosted ID of the article or wp error
        *
        */
		public function push_article( $localID ){
		    // get article as article array
			$article = $this->article( $localID );

		    // get the associated folio for the article
            $folioService = DPSFolioAuthor_Folio::getInstance();
            $folio = $folioService->folio( $article["folio"] );
            
            // preflight article to make sure it's clear to upload to hosting
            $return = $this->article_preflight($article, $folio);
		    if(is_wp_error($return)){ return $return; }

            // bundle the article into a .folio
            $bundler = DPSFolioAuthor_Bundlr::getInstance();
            $bundledArticle = $bundler->bundle( $article ); // creates a zip / folio of the whole article and returns the path to the zip
            clearstatcache();
            
            // push article to hosting
            $adobe = DPSFolioAuthor_Adobe::getInstance();
    		$return = $adobe->create_article( $folio["hostedID"], $bundledArticle, $article["meta"] );
            
            // verify push was successful and update the local article
            if( !is_wp_error($return) ){
                $hostedID = $return->articleInfo->id;
                $this->update_article_field($localID, 'hostedID', $hostedID);
                $this->update_article_field($localID, 'name', $return->articleInfo->name);
                return  $hostedID;
            }else{
                return $return;
            }
		}
		
		/*
        * Article preflight
        * makes sure everything is clear before an article can be pushed to the cloud
        *
        * @param    array         $article - article array
        * @param    array         $folio - folio array
        * @return	array         returns true if everything is clear or wp_error if something went wrong
        *
        */
		public function article_preflight( $article, $folio ){
		    // get current articles in the folio
		    $returnedArticles = $this->get_articles_from_adobe($folio);
            $hostedArticles = !empty($returnedArticles->articles) ? $returnedArticles->articles : array();
		    
		    // delete existing article before uploading new one
		    $foundArticle = $this->article_id_exists_in_hosting($article, $hostedArticles);
    		if( !empty($article["hostedID"]) && $foundArticle !== false ){
		        $return = $this->delete_old_article($article, $folio);
                if(is_wp_error($return)){ return $return; }
                unset($hostedArticles[$foundArticle]);
            }
		    
		    // check article name exists in wordpress and generate article name if it doesn't
            $articleName = $this->article_name_exists_in_hosting($article, $hostedArticles);
            $article["meta"]["name"] = empty($articleName) ? $article["meta"]["name"] : $articleName . "-copy-" . time();
            
            // check article position, if it already exists update the existing article to make space for the one being uploaded
            $return = $this->verify_article_position($article, $folio, $hostedArticles);
		    if(is_wp_error($return)){ return $return; }
		    
		    return true;
		}

		/*
        * Delete Old Article
        * removes an old article in the cloud
        *
        * @param    array         $article - article array
        * @param    array         $folio - folio array
        * @return	boolean       returns true if successfully deleted (or nothing to delete) or wp_error if something went wrong
        *
        */
		public function delete_old_article($article, $folio){
            $adobe = DPSFolioAuthor_Adobe::getInstance();
    		$return = $adobe->delete_article( $article["hostedID"], $folio["hostedID"]);
            if(is_wp_error($return)){ return $return; }
            else{ return true; }
		}
		
		/*
        * Article ID Exists In Hosting
        * checks a list of hosted articles of a folio to see if ID exists 
        *
        * @param    array         $article - article array
        * @param    array         $hostedArticles - array of hosted articles
        * @return	boolean       returns true if id exists or false if it doesn't
        *
        */
		public function article_id_exists_in_hosting( $article, $hostedArticles ){
    		if(empty($article["hostedID"])){ return false; }
    		foreach($hostedArticles as $index => $hostedArticle){
        		if( $article["hostedID"] == $hostedArticle->id){
            		return $index;
        		} 
    		}
    		return false;
		}
		
		/*
        * Article Name Exists In Hosting
        * checks a list of hosted articles of a folio to see if name exists 
        *
        * @param    array         $article - article array
        * @param    array         $hostedArticles - array of hosted articles
        * @return	boolean       returns true if name exists or false if it doesn't
        *
        */
		public function article_name_exists_in_hosting( $article, $hostedArticles ){
    		$name = empty( $article["meta"]["name"] ) ? $article["localID"] : $article["meta"]["name"];
    		foreach($hostedArticles as $hostedArticle){
        		if( $name == $hostedArticle->name){
            		return $name;
        		} 
    		}
    		return false;
		}
		
		/*
        * Verify Article Position
        * checks if existing folio has same sortOrder, if one exists that folio will be changed to it's current position +1 
        *
        * @param    array         $article - article array
        * @param    array         $folio - folio array
        * @param    array         $hostedArticles - array of hosted articles
        * @return	boolean       returns true if article's sortOrder (position) is clear
        *
        */
		public function verify_article_position( $article, $folio, $hostedArticles ){            
    		$position = $article["position"];
    		$updatedID = $this->article_position_exist($position, $hostedArticles);
    		// if there's a folio with the same article position - update that folio to a new position
    		if( $updatedID ){
    		    // keep adding +1 to the folio position until a free position exists
    		    while( $this->article_position_exist($position, $hostedArticles) ){
        		    $position++;
    		    }
    		    // when a free position has been found, update that folio position in the cloud to make room for the new folio
                return $this->push_article_meta( $this->article($updatedID), $folio, array("sortOrder" => $position), false );
    		}
    		return true;
		}
		
		/*
        * Article Position Exists
        * checks if existing folio has same sortOrder, if one exists that folio will be changed to it's current position +1 
        *
        * @param    int           $position - position as int
        * @param    array         $articles - array of hosted articles
        * @return	boolean       returns true if article's sortOrder (position) is clear
        *
        */
		public function article_position_exist($position, $articles){
    		foreach($articles as $article){
        		if($position == $article->articleMetadata->sortNumber){
            		return $article->id;
        		}
    		}
    		return false;
		}
		
		public function registerHookCallbacks(){}
        public function activate( $networkWide ){}
       	public function deactivate(){}
       	public function upgrade( $dbVersion = 0 ){}
       	protected function isValid( $property = 'all' ){}
    	public function init(){}

    } // END class DPSFolioAuthor_Article
}
