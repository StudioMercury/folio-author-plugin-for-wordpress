<?php
    $folioObj = DPSFolioAuthor_Folio::getInstance();
    $folios = $folioObj->get_folios();
    
    $articleObj = DPSFolioAuthor_Article::getInstance();
?>
<div class="gumby">
<div id="dialog" title="Dialog Window"><p></p></div>

<div id="wpbody-content" aria-label="Main content" tabindex="0">
    <div class="wrap row">
        
    <!-- PLUGIN MESSAGING -->
    <div id="dps-message">
        <br />
		<div class="message">
             <h2>
                <!-- <img class="dps-icon" src="http://placehold.it/50x50"> -->
                Here are all of your issues.<br />
    			You can <div class="medium normal btn"><a data-action="open_box_new_folio"><i class="fa fa-plus"></i> Create New Issue</a></div>
    			or if you are missing issues
    			<div class="medium normal btn ttip" data-tooltip="Pulls missing issues that might have been created through InDesign"><a href="#" data-action="sync_hosted_folios"><i class="fa fa-cloud-download"></i> Pull Missing Issues from Cloud</a></div>
		    </h2>
		</div>
	</div>

	<br /><br /><br />


    <!-- FOLIO LIST -->
    <?php if( $folios ): ?>
    
    
    <!--
    <div class="row">
        <div class="twelve columns text-right">
                
                <div class="field">
                    
                    Showing &nbsp;
                    
                    <div class="picker">
                        <select>
                          <option value="#">All Folios</option>
                          <option value="#">Unlinked Folios</option>
                          <option value="#">Linked Folios</option>                         
                        </select>
                    </div>
                    
                    <?php /*
                    &nbsp; &nbsp;
                    
                    Filter Folios by
                    <div class="picker">
                        <select>
                          <option value="#">Month</option>
                          <option value="#">January</option>
                          <option value="#">February</option>
                          <option value="#">March</option>
                          <option value="#">April</option>
                          <option value="#">May</option>
                          <option value="#">June</option>
                          <option value="#">July</option>
                          <option value="#">August</option>
                          <option value="#">September</option>
                          <option value="#">October</option>
                          <option value="#">November</option>
                          <option value="#">December</option>                          
                        </select>
                    </div>
                    
                    <div class="picker">
                        <select>
                          <option value="#">Day</option>
                        </select>
                    </div>        
                    
                    <div class="picker">
                        <select>
                          <option value="#">Year</option>
                        </select>
                    </div>                    
                    
                    */?>
                    
                </div>
                
        </div>    
    </div>    
    -->
        

    <hr />
    <?php foreach ($folios as $folio ): ?>
        
         <?php if( !$folio["linked"] ): ?>
         <div class="row">
         <div class="eight columns centered">
             <div class="warning alert text-center">
                <i class="fa fa-chain-broken"></i> &nbsp; This folio is currently unlinked &nbsp;
                <a data-action="<?php echo $folio["linked"] ? "" : "link_folio";?>" data-folio="<?php echo $folio["localID"]; ?>">Click here to start edit this folio through wordpress.</a>
             </div>
         </div>
         </div>
         <br />
         <?php endif; ?>
         
	     <div class="row single-issue <?php if( !$folio["linked"] ): ?>unlinked<?php endif; ?>">
         <div class="four columns">
         
                <h5 class="lead"><b><?php echo $folio["meta"]["folioNumber"]; ?></b></h5>
                <br />
                <div class="row">
        	        <div class="four columns">
        	            <?php 
        	                $cover = "";
        	                foreach( $folio["renditions"] as $rendition ){
                                if( !empty($rendition["covers"]) ){
                                    $cover = wp_get_attachment_image_src( $rendition["covers"]["vertical"], array($rendition["meta"]["resolutionHeight"],$rendition["meta"]["resolutionWidth"]) );
                                    $cover = $cover[0]; break;
                                }
        	                }
        	            ?>
        	            <?php if( !empty($cover) ): ?>
        	                <img class="cover"src="<?php echo $cover;?>">
        	            <?php else: ?>
        	                <img class="cover"src="http://placehold.it/768x1024&text=NO+COVER">
        	            <?php endif; ?>
        	        </div>
        	        <div class="eight columns folio-info">
        	            <?php echo $folio["meta"]["magazineTitle"]; ?>
        	            <br />
        	            
        	            <h6 class="lead"><?php echo $folio["meta"]["folioName"]; ?></h6>
        
        	            <h6 class="lead">Pub Date: <?php echo date('m\/d\/Y', strtotime($folio["meta"]["publicationDate"])); ?></h6>
        	            <h6 class="lead">Cover Date: <?php echo date('m\/d\/Y', strtotime($folio["meta"]["coverDate"])); ?></h6>
        	            <h6 class="lead"><i><?php echo $folio["meta"]["folioDescription"]; ?></i></h6>
        	            <br />
        		        <a class="btn normal" data-action="open_box_edit_folio" data-folio="<?php echo $folio["localID"];?>"><i class="fa fa-pencil"></i> Edit Issue</a>
                        <a class="btn normal" data-action="delete_folio" data-folio="<?php echo $folio["localID"]; ?>"><i class="fa fa-trash-o"></i> Delete</a>
        	        </div>	       
                </div> 
	     </div>
            
            <div class="eight columns">
            
            <div class="row">
                <div class="six columns">
                    <h5 class="lead"> <span class="light badge"><?php echo count($folio["renditions"]); ?></span> Renditions in this issue &nbsp; </h5>
                </div>
                <div class="six columns text-right">
                    <a class="btn normal" data-action="open_box_new_rendition" data-folio="<?php echo $folio["localID"]; ?>"><i class="fa fa-plus"></i> New Rendition</a>
                </div>
            </div>            
            
            
            <?php if($folio["renditions"]) : ?>

            <div class="rendition-list">
            <?php foreach( $folio["renditions"] as $rendition ): ?>
    	        <div class="row rendition">
    		    	<div class="two columns device"><?php echo $rendition["device"]["name"];?></div>
    		    	<div class="two columns size"><?php echo $rendition["meta"]["resolutionWidth"];?> &times; <?php echo $rendition["meta"]["resolutionHeight"];?></div>
    		    	<div class="two columns intent text-center">
    		    	<?php  $deviceService = DPSFolioAuthor_Device::getInstance();  $device = $deviceService->get_device( "name", $rendition["device"]["name"] ); ?>
    		    	<?php if ( $rendition["meta"]["folioIntent"] == 'PortraitOnly' || $rendition["meta"]["folioIntent"] == 'Both' ) : ?>
    		    	    <i class="fa fa-<?php echo strtolower($device["type"]); ?>" style="font-size: 22px;"></i>
    		    	<?php endif; ?>
                    <?php if ( $rendition["meta"]["folioIntent"] == 'LandscapeOnly' || $rendition["meta"]["folioIntent"] == 'Both' ) : ?>
    		    	    <i class="fa fa-rotate-90 fa-<?php echo strtolower($device["type"]); ?>" style="font-size: 22px;"></i>
                    <?php endif; ?>
    		    	</div>
    		    	<div class="two columns intent"><b><?php echo $articleObj->get_article_count($rendition['localID']);?></b> &nbsp; Articles</div>
    
    		    	<div class="one columns status text-center ttip" data-tooltip="This folio has <?php if( empty($rendition["hostedID"]) ): ?> NOT <?php endif; ?> been pushed to the cloud.">
    		    	    <?php if( !empty($rendition["hostedID"]) ): ?>
    		    	        <i class="fa fa-cloud"></i>
    	    		    <?php else: ?>
    		    	        <i class="fa fa-cloud disabled"></i>
    	                <?php endif; ?>
    		    	</div>
    
    
    		    	<div class="three columns action text-right article-edit">    
    				    <a class="btn normal" href="<?php echo get_edit_post_link($rendition["localID"]); ?>" title="Edit Rendition"><i class="fa fa-pencil"></i></a>
                        <a class="btn normal" data-action="delete_rendition" data-folio="<?php echo $rendition["localID"];?>" title="Delete Rendition"><i class="fa fa-trash-o"></i></a>
    		    	</div>
    
    	        </div>
			<?php endforeach; ?>
            </div>

			<?php endif; ?>
            </div>

	    </div>
	    <hr />
    <?php endforeach; ?>
    <?php else: ?>
        <h5 class="text-center">No folios found. Use the actions above to get started.</h5>
    <?php endif; ?>
    <!-- INFINITE SCROLL MORE -->



    </div>
</div>

<script>
jQuery(".child-link").click(function( event ){
    event.stopImmediatePropagation();
    //event.preventDefault();
    
});
</script>

<script type="text/javascript" src="<?php echo DPSFA_URL.'/js/mustache/mustache.js'; ?>"></script>
</div>
