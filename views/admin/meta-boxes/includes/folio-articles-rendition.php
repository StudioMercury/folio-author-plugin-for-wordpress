<?php
    // GET THE PARENT FOLIO
    $folioService = DPSFolioAuthor_Folio::getInstance();
?>

<div class="row">
    <div class="folio-details">
      <h1 class="lead">
          <b><?php echo $folio["device"]["name"]?></b> rendition <small>(<?php echo $folio["meta"]["resolutionWidth"];?> &times; <?php echo $folio["meta"]["resolutionHeight"];?>)</small>
      </h1>
    </div>
</div> 

<br />

<div class="folio-meta row">
    
    <div class="four columns">   
    
        <div class="row">
            
            <div class="seven columns"> 
                <div class="cover-thumbnail ttip" data-tooltip="width: <?php echo $folio["meta"]["resolutionWidth"];?>px &nbsp; height: <?php echo $folio["meta"]["resolutionHeight"];?>px">
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="fileupload-new thumbnail" style="">
                            <?php $horizontal = isset($folio["covers"]["horizontal"]) ? $folio["covers"]["horizontal"] : ""; ?>
                            <?php $image = wp_get_attachment_image_src($horizontal, array(250,250)); ?>
                            <?php $placeholder = 'http://placehold.it/'.$folio["meta"]["resolutionWidth"].'x'.$folio["meta"]["resolutionHeight"].'&text='.$folio["meta"]["resolutionWidth"].'+x+'.$folio["meta"]["resolutionHeight"]; ?>
                            <img src="<?php echo is_array($image) ? $image[0] : $placeholder; ?>" />
                        </div>
                      <div class="fileupload-preview fileupload-exists thumbnail" style=""></div>
                      <div>
                        <span class="btn normal btn-file">
                            <span class="fileupload-new">Upload</span>
                            <span class="fileupload-exists">Change</span>
                            <input type="file" name="<?php echo $fieldSlug;?>_cover_h" value="<?php echo $horizontal; ?>"/>
                        </span>
                        <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Remove</a>
                      </div>
                    </div>
                </div>   
            </div>
        
            <div class="four columns"> 
                <div class="cover-thumbnail ttip" data-tooltip="width: <?php echo $folio["meta"]["resolutionHeight"];?>px &nbsp; height: <?php echo $folio["meta"]["resolutionWidth"];?>px">
                    <div class="fileupload fileupload-new" data-provides="fileupload">
                        <div class="fileupload-new thumbnail" style="">
                            <?php $vertical = isset($folio["covers"]["vertical"]) ? $folio["covers"]["vertical"] : ""; ?>
                            <?php $image = wp_get_attachment_image_src($vertical, array(250,250)); ?>
                            <?php $placeholder = 'http://placehold.it/'.$folio["meta"]["resolutionHeight"].'x'.$folio["meta"]["resolutionWidth"].'&text='.$folio["meta"]["resolutionHeight"].'+x+'.$folio["meta"]["resolutionWidth"]; ?>
                            <img src="<?php echo is_array($image) ? $image[0] : $placeholder; ?>" />
                        </div>
                      <div class="fileupload-preview fileupload-exists thumbnail" style=""></div>
                      <div>
                        <span class="btn normal btn-file">
                            <span class="fileupload-new">Upload</span>
                            <span class="fileupload-exists">Change</span>
                            <input type="file" name="<?php echo $fieldSlug;?>_cover_v" value="<?php echo $vertical; ?>"/>
                        </span>
                        <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Remove</a>
                      </div>
                    </div>
                </div> 
            </div>
            
        </div>           
        
    </div>
        
    
    <div class="four columns">    
    
        <div class="row">
        
            <h5 class="lead folio-metadata">
                <b>Magazine Title</b>
                <?php echo isset($folio["meta"]["magazineTitle"]) ? $folio["meta"]["magazineTitle"] : "" ; ?>
            </h5>        
        
            <h5 class="lead folio-metadata">
                <b>Folio Name</b>
                <?php echo isset($folio["meta"]["folioName"]) ? $folio["meta"]["folioName"] : "" ; ?>
            </h5>
    
            <h5 class="lead folio-metadata">
                <b>Folio Number</b>
                <?php echo isset($folio["meta"]["folioNumber"]) ? $folio["meta"]["folioNumber"] : "" ; ?>
            </h5>
            
            <br />
            
            <h5 class="lead folio-metadata">
                <b>Publication Date</b>
                <?php echo isset($folio["meta"]["publicationDate"]) ? $folio["meta"]["publicationDate"] : "" ; ?>
            </h5>
    
            <h5 class="lead folio-metadata">
                <b>Cover Date</b>
                <?php echo isset($folio["meta"]["coverDate"]) ? $folio["meta"]["coverDate"] : "" ; ?>
            </h5>
            
            <br />
            
            <h5 class="lead folio-metadata">
                <b>Folio Description</b>
                <?php echo isset($folio["meta"]["folioDescription"]) ? $folio["meta"]["folioDescription"] : "" ; ?>
            </h5>
            
            <h5 class="lead folio-metadata">
                <b>Folio Filters</b>
                <?php echo isset($folio["meta"]["filters"]) ? $folio["meta"]["filters"] : "" ; ?>
            </h5>            
            
        </div> 
        
    </div> 
    

    <div class="four columns"> 
    
        <?php
        $hostedDate = new DateTime( $folio["meta"]["modifyDate"] );
        $localDate = new DateTime( get_the_modified_date() . " " . get_the_modified_time( ) );
        ?>
    
        <div class="status-overview">
            <?php if( !empty($folio["hostedID"]) ):?>
                <?php if( isset($folio["meta"]["modifydate"]) ): ?>
                    <i class="fa fa-circle green"></i> Rendition is in the cloud and up to date
                <?php else: ?>
                    <i class="fa fa-dot-circle-o yellow"></i> Rendition is in the cloud and out of sync
                <?php endif;?>
            <?php else: ?>
                <i class="fa fa-circle-o gray"></i> Rendition is local only
            <?php endif; ?>
        </div>
        <BR/>
        
        <?php if( !empty($folio["hostedID"]) ):?>
            <!-- PUBLISHED -->
            <div class="medium normal btn">
                <a href="#" data-action="update_rendition" data-folio="<?php echo $post_id;?>"><i class="fa fa-cloud-upload"></i> Push metadata to the cloud</a>
            </div>
            <BR/><BR/>
            <div class="medium normal btn">
                <a data-action="push_rendition_articles" data-folio="<?php echo $post_id;?>"><i class="fa fa-cloud-upload"></i> Upload all articles</a>
            </div>
            <BR/><BR/>
            <div class="medium normal btn">
                <a data-action="upload_htmlresources" data-folio="<?php echo $post_id;?>"><i class="fa fa-cloud-upload"></i> Upload HTMLResources</a>
            </div>
        <?php else: ?>
            <!-- NOT PUBLISHED -->
            <div class="medium normal btn">
                <a data-action="push_rendition" data-folio="<?php echo $post_id;?>"><i class="fa fa-cloud-upload"></i> Push rendition to the cloud</a>
            </div>
        <?php endif; ?>
        <BR/><BR/>

    
    </div>    
    
      
    
</div>
    



    <br /><br /><br />
    
    <hr />


<div class="row text-center">

    <h1 class="lead">Articles for this rendition</h1>
    
    <div class="medium normal btn"><a class="" data-action="open_box_add_article" data-folio="<?php echo $post_id; ?>" data-article="#addArticle"><i class="fa fa-plus"></i> Add articles</a></div>
    
    <div class="medium normal btn"><a class="" data-action="open_box_duplicate_articles_from_rendition" data-folio="<?php echo $post_id; ?>"><i class="fa fa-copy"></i> Duplicate from another rendition</a></div>
    
    <div class="medium normal btn"><a class="" data-action="show_import_sidecar" data-folio="<?php echo $post_id; ?>" data-uploader="#sidecar-uploader"><i class="fa fa-file-text-o"></i> Import Sidecar.xml</a></div>
    
    <div class="medium normal btn ttip disabled" data-tooltip="Pull missing articles that might have been created with InDesign"><a class=""><i class="fa fa-refresh"></i> Sync missing articles</a></div>
    
    <div id="sidecar-uploader" class="hidden">
        <div class="row">
            <div class="five columns">
                <h5 class="lead">Please make sure you have edited the article names (folder name) for all of the articles before import. The importer uses the article names to match the article fields with the sidecar.xml file.</h5>
                <BR/>
            </div>
            <div class="six columns">
                <input type="file" name="<?php echo $fieldSlug;?>_sidecar">
                &nbsp; 
                <input type="submit" class="btn normal" value="Import sidecar.xml file" name="publish" id="publish" accesskey="p">
            </div>
            <div class="one columns">
                <div class="close" onClick="jQuery(this).parent().parent().parent().hide();">&times; close</div>
            </div>
        </div>
    </div>
    
    

    <!--<a onclick="" class="add-new-h2"><i class="icon-bolt"></i> IMPORT articles from SIDECAR.XML</a>-->
    <?php if( isset($post_id) ): ?>
        <!--
        <a title="Missing articles you've already added to this rendition? Click the button above to sync this rendition with Adobe and add any missing articles" data-action="sync_articles_from_adobe" data-folio="<?php echo $post_id;?>" class="add-new-h2 help" data-tip="test"><i class="icon-refresh"></i> SYNC articles from Adobe Hosting</a>
        -->
    <?php endif; ?>
    
    
</div>
<BR/><BR/>

<?php 
    $articleService = DPSFolioAuthor_Article::getInstance();
    $articles = $articleService->get_articles( null, $post_id );
?>

<div class="row">

    <table class="rounded striped">
        <thead class="width: 100%;">
            <tr>
                <th class=""><i class="icon-cloud"></i></th>
                <th class="">Preview</th>       
                <th class="">Article Name</th>
                <th class="">Article/Post Title</th>
                <th class="">Orientation</th>
                <th class="">Smooth Scrolling</th>
                <th class=""></th>
            </tr>
        </thead>
        <tbody class="sortable articles" data-folio="<?php echo $post_id;?>">
            <?php foreach($articles as $article): ?>
            <tr id="<?php echo $article["localID"];?>">
                <td class="" style="width: 5%">
                    <?php if( !empty($article["hostedID"]) ): ?>
            	        <i class="icon-cloud"></i> 
        		    <?php else: ?>
            	        <i class="icon-cloud" style="opacity: .15"></i> 
                    <?php endif; ?>
                </td>
                                
                <td class="text-center" style="width: 5%"><img src="<?php echo $article["preview"]["url"];?>" width="30px" height="30px"/></td>
                <td class="" style="width: 15%"><?php echo $article["meta"]["name"];?></td>
                <td class="" style="width: 30%"><?php echo $article["meta"]["title"];?></td>       
                <td class="" style="width: 10%"><?php echo $article["meta"]["orientation"];?></td>
                <td class="" style="width: 15%"><?php echo $article["meta"]["smoothScrolling"];?></td>
                <td class="article-rendition-actions text-right" style="width: 20%;">
                    <div class="medium default btn " title="Edit Article"><a href="<?php echo get_edit_post_link( $article["localID"] );?>"><i class="icon-pencil"></i></a></div>
                    <div class="medium normal btn" title="Delete Article"><a data-action="delete_article" data-article="<?php echo $article["localID"];?>"><i class="fa fa-trash-o"></i></a></div>
                    <?php if( !empty($folio["hostedID"]) ): ?>
                        <div class="medium normal btn " title="Push article to the cloud"><a data-action="push_single_article" data-article="<?php echo $article["localID"];?>"><i class="fa fa-cloud-upload"></i></a></div>
                    <?php else: ?>
                        <div class="medium normal btn disabled" title="Push article to the cloud"><a><i class="fa fa-cloud-upload"></i></a></div>
                    <?php endif; ?>                    
                </td>
            </tr>
            <?php endforeach;?>
        </tbody>      
        
    </table>

</div>


  <?php if( !empty($parent["renditions"]) && count($parent["renditions"]) > 1): ?>
    <div class="other-renditions">
        <h6 class="lead">Other renditions in the folio</h6>
        <ul class="row">
            <?php foreach( $parent["renditions"] as $rendition): ?>
            <li class="four columns">
                <?php if( $rendition["localID"] != $folio["localID"] ): ?>
                <a href="<?php echo get_edit_post_link($rendition["localID"]); ?>"><?php echo $rendition["device"]["name"]?> - <i><?php echo $rendition["meta"]["resolutionWidth"];?> &times; <?php echo $rendition["meta"]["resolutionHeight"];?></i></a>
                <?php else: ?>
                <div class="current"><?php echo $rendition["device"]["name"]?> - <i><?php echo $rendition["meta"]["resolutionWidth"];?> &times; <?php echo $rendition["meta"]["resolutionHeight"];?></i></div>
                <?php endif; ?>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>