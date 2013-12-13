<?php 
    global $post_id;
?>
<div class="gumby">
<style>
.image{
    max-width: 40px;
}

.devices li{
    width: 50px;
    float: left;
}
</style>

<div style="text-align:center; width: 100%;">
    <h4>
        Create a Folio Rendition<BR/>
        <i style="font-weight: normal">Select a device from the list below or enter a custom size.</i>
    </h4>
    <BR/>
</div>

<table class="new-rendition" data-group="add_folio_rendition">
    <tr>        
        <th>    
            <select data-action="select_device">
            <option type="text" selected disabled>Choose your target device</option>
            <?php foreach($deviceList as $key=>$devices): ?>
                <?php foreach($devices["device"] as $device): ?>
                    <option 
                        type="text" 
                        data-slug   = "<?php echo $device["slug"];?>"
                        data-name   = "<?php echo ($device["slug"] == "custom") ? "" : $device["name"]; ?>"
                        data-width  = "<?php echo $device["width"];?>"
                        data-height = "<?php echo $device["height"];?>">
                    <?php echo $device["name"];?>
                    </option>
                <?php endforeach; ?>
            <?php endforeach; ?>
            </select>
            <BR/><BR/>
            <input type="text" name="rendition[renditionLabel]" placeholder="Device (rendition) label" class="hidden"/>

        </th>
       
        <th>
            <select name="rendition[meta][folioIntent]">
                <option value="Both">Both</option>
                <option value="PortraitOnly">Vertical</option>
                <option value="LandscapeOnly">Horizontal</option>
            </select>
        </th>
        <th>
            <input type="text" class="rendition-size" name="rendition[meta][resolutionWidth]" placeholder="width" /> x 
            <input type="text" class="rendition-size" name="rendition[meta][resolutionHeight]" placeholder="height" />    
        </th>
        <th>
            <a data-action="add_folio_rendition" data-folio="<?php echo $post_id;?>" class="btn btn-default"><i class="icon-plus"></i> Add Rendition</a>
        </th>
    </tr>
</table>


<?php if($folio["renditions"]) : ?>

    <table class="widefat">
    <thead>
        <tr>
            <th><center><i class="icon-cloud"></i></center></th>
            <th>Cover</th> 
            <th>Device</th>  
            <th>Orientation</th>
            <th>Width x Height</th>
            <th style="text-align:center">HTMLResources</th>
            <th style="text-align:center"></th>
        </tr>
    </thead>
    
    
    <tbody class="device-table">
    <?php foreach( $folio["renditions"] as $rendition ): ?>
    
        <tr data-device="" class="">
            <th><center><i class="icon-cloud"></i></center></th>
            <th><?php // echo DPSFolioAuthor_CPT_Article::POST_TYPE_SLUG."_tocThumb";?> <img src="http://placehold.it/768x1024&text=768+x+1024" width="auto" height="50" /></th>
            <th><?php echo $rendition["device"];?></th>
            <th><?php echo $rendition["meta"]["folioIntent"];?></th>
            <th><?php echo $rendition["meta"]["resolutionWidth"];?> x <?php echo $rendition["meta"]["resolutionHeight"];?></th>
            <th style="text-align:center">
                <?php if( isset($folio["meta"]["hasHTMLResources"]) && $folio["meta"]["hasHTMLResources"] ): ?>
                    <i class="icon-circle"></i>
    		    <?php else: ?>
    		        <i class="icon-circle-blank"></i>
                <?php endif; ?>
            </th>
            <th style="text-align:center"><a class="btn btn-default" href="<?php echo get_edit_post_link( $rendition["localID"] );?>">Edit</a></th>
        </tr>
           
    <?php endforeach; ?>
    </tbody>

<?php endif; ?>




<!--
<?php /* ?>
<tbody class="device-table">
    <?php foreach($folio["renditions"] as $rendition): ?>
       <tr data-device="<?php echo ;?>" class="hidden">
            <th><?php ;?></th>
            <th><center>-<i class="icon-ok"></i></center></th>
            <th class="cover-uploader portrait">
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="fileupload-new thumbnail" style="width: 50px; height: 38px;">
                        <img src="http://placehold.it/50x38&text=1024+x+768" />
                    </div>
                  <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 50px; max-height: 38px; line-height: 20px;"></div>
                  <div class="fileupload-button">
                    <span class="btn btn-default btn-file">
                        <span class="fileupload-new"><small>Upload Cover<br /> <?php ?> x <?php ?></small></span>
                        <span class="fileupload-exists">Change</span>
                        <input type="file" name="<?php echo DPSFolioAuthor_CPT_Article::POST_TYPE_SLUG."_tocThumb";?>" /></span>
                        <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="icon-remove"></i></a>
                  </div>
                </div>  
                  </div>
                </div>        
            </th>       
            <th><center></center></th>
            
            <th class="cover-uploader landscape">
                <div class="fileupload fileupload-new" data-provides="fileupload">
                    <div class="fileupload-new thumbnail" style="width: 50px; height: 38px;">
                        <img src="http://placehold.it/50x38&text=1024+x+768" />
                    </div>
                  <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 50px; max-height: 38px; line-height: 20px;"></div>
                  <div class="fileupload-button">
                    <span class="btn btn-default btn-file">
                        <span class="fileupload-new"><small>Upload Cover<br /> <?php  ?> x <?php ?></small></span>
                        <span class="fileupload-exists">Change</span>
                        <input type="file" name="<?php echo DPSFolioAuthor_CPT_Article::POST_TYPE_SLUG."_tocThumb";?>" /></span>
                        <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="icon-remove"></i></a>
                  </div>
                </div>     
            </th>  
            <th><div class="button-secondary" onclick="">Publish</div></th>
            <th><center><i class="icon-cloud"></i></center></th>
        </tr>

    <?php endforeach; ?>    
</tbody>
<?php */ ?>
-->

</table>

<script>
/* BIND ACTIONS */
jQuery( document ).ready( function(){
    
    jQuery('[data-action="select_device"]').change(function(){
        var name = jQuery(this).find("option:selected").attr("data-name");
        var width = jQuery(this).find("option:selected").attr("data-width")
        var height = jQuery(this).find("option:selected").attr("data-height");
        
        if( name == "" ){ jQuery('[name="rendition\[renditionLabel\]"]').show(); }
        
        jQuery('[name="rendition\[renditionLabel\]"]').val( name );
        jQuery('[name="rendition\[meta\]\[resolutionWidth\]"]').val( width );
        jQuery('[name="rendition\[meta\]\[resolutionHeight\]"]').val( height );

    });
        
});
</script>
</div>