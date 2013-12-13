<div class="gumby">

    
    <div class="cover-thumbnail ttip" style="width: 36%; margin: 1%" data-tooltip="width: <?php echo $folio["meta"]["resolutionHeight"];?>px &nbsp; height: <?php echo $folio["meta"]["resolutionWidth"];?>px">

        <div class="fileupload fileupload-new" data-provides="fileupload">
            <div class="fileupload-new thumbnail" style="">
                <?php $vertical = isset($folio["covers"]["vertical"]) ? $folio["covers"]["vertical"] : ""; ?>
                <?php $image = wp_get_attachment_image_src($vertical, array(250,250)); ?>
                <?php $placeholder = 'http://placehold.it/'.$folio["meta"]["resolutionHeight"].'x'.$folio["meta"]["resolutionWidth"].'&text='.$folio["meta"]["resolutionHeight"].'+x+'.$folio["meta"]["resolutionWidth"]; ?>
                <img src="<?php echo is_array($image) ? $image[0] : $placeholder; ?>" />
            </div>
          <div class="fileupload-preview fileupload-exists thumbnail" style=""></div>
          <div>
            <span class="btn btn-default btn-file">
                <span class="fileupload-new">Upload</span>
                <span class="fileupload-exists">Change</span>
                <input type="file" name="<?php echo $fieldSlug;?>_cover_v" value="<?php echo $vertical; ?>"/>
            </span>
            <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Remove</a>
          </div>
        </div>
    
    </div>
      
    <div class="cover-thumbnail ttip" style="width: 59%; margin: 1%" data-tooltip="width: <?php echo $folio["meta"]["resolutionWidth"];?>px &nbsp; height: <?php echo $folio["meta"]["resolutionHeight"];?>px">
        
        <div class="fileupload fileupload-new" data-provides="fileupload">
            <div class="fileupload-new thumbnail" style="">
                <?php $horizontal = isset($folio["covers"]["horizontal"]) ? $folio["covers"]["horizontal"] : ""; ?>
                <?php $image = wp_get_attachment_image_src($horizontal, array(250,250)); ?>
                <?php $placeholder = 'http://placehold.it/'.$folio["meta"]["resolutionWidth"].'x'.$folio["meta"]["resolutionHeight"].'&text='.$folio["meta"]["resolutionWidth"].'+x+'.$folio["meta"]["resolutionHeight"]; ?>
                <img src="<?php echo is_array($image) ? $image[0] : $placeholder; ?>" />
            </div>
          <div class="fileupload-preview fileupload-exists thumbnail" style=""></div>
          <div>
            <span class="btn btn-default btn-file">
                <span class="fileupload-new">Upload</span>
                <span class="fileupload-exists">Change</span>
                <input type="file" name="<?php echo $fieldSlug;?>_cover_h" value="<?php echo $horizontal; ?>"/>
            </span>
            <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Remove</a>
          </div>
        </div>
        
    </div>
    
    
    
</div>