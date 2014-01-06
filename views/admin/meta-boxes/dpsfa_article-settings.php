<?php
    /* META BOXES FOR FOLIOS */
    global $post_id;
?>
<div class="gumby">
<style></style>

<BR/>
<center>
    <?php $preview = isset($article["preview"]["url"]) ? $article["preview"]["attachmentID"] : ""; ?>
    <?php $image = wp_get_attachment_image_src($preview, array(250,250)); ?>
    
    <div class="fileupload <?php echo (!empty($preview)) ? "fileupload-exists" : "fileupload-new"; ?>" data-provides="fileupload">
      <div class="fileupload-new thumbnail" style="width: 200px; height: auto;"><img src="http://www.placehold.it/250x250/EFEFEF/AAAAAA&text=No+TOC+Preview" /></div>
      <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: auto; line-height: 20px;">
        <img src="<?php echo is_array($image) ? $image[0] : "http://placehold.it/250x250&text=250+x+250"; ?>">
      </div>
      <div>
        <span class="btn btn-default btn-file"><span class="fileupload-new">Select Image</span><span class="fileupload-exists">Change</span><input type="file" name="<?php echo $fieldSlug;?>_preview" value="<?php echo $preview; ?>" /></span>
        <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Remove</a>
      </div>
    </div>
    
</center>

<BR/>

<hr />

<BR/>

<div class="field">
    <div class="picker">
    <?php $priority = isset($article["meta"]["downloadPriority"]) ? $article["meta"]["downloadPriority"] : ""; ?>
    <select width="100%" style="width: 100%" name="<?php echo $fieldSlug;?>[downloadPriority]">
        <option disabled <?php echo $priority ? "" : "selected"; ?>>Select Download Priority</option>
        <option value="Low" <?php echo ($priority == "Low") ? "selected" : ""; ?>>Low</option>
        <option value="Medium" <?php echo ($priority == "Medium") ? "selected" : ""; ?>>Medium</option>
        <option value="High" <?php echo ($priority == "High") ? "selected" : ""; ?>>High</option>
    </select>
    </div>
</div>

<div class="field">
    <div class="picker">
    <?php $orientation = isset($article["meta"]["orientation"]) ? $article["meta"]["orientation"] : ""; ?>
    <select width="100%" style="width: 100%" name="<?php echo $fieldSlug;?>[orientation]">
        <option disabled <?php echo ($orientation) ? "" : "selected"; ?>>Select Orientation</option>
        <option value="Landscape" <?php echo ($orientation == "Landscape") ? "selected" : ""; ?>>Landscape</option>
        <option value="Portrait" <?php echo ($orientation == "Portrait") ? "selected" : ""; ?>>Portrait</option>
        <option value="Both" <?php echo ($orientation == "Both") ? "selected" : ""; ?>>Both</option>
    </select>
    </div>
</div>

<div class="field">
    <div class="picker">
    <?php $scrolling = isset($article["meta"]["smoothScrolling"]) ? $article["meta"]["smoothScrolling"] : ""; ?>
    <select width="100%" style="width: 100%" name="<?php echo $fieldSlug;?>[smoothScrolling]">
        <option disabled <?php echo ($scrolling) ? "" : "selected"; ?>>Select Smooth Scrolling</option>
        <option value="Never" <?php echo ($scrolling == "Never") ? "selected" : ""; ?>>Never</option>
        <option value="Landscape" <?php echo ($scrolling == "Landscape") ? "selected" : ""; ?>>Landscape</option>
        <option value="Portrait" <?php echo ($scrolling == "Portrait") ? "selected" : ""; ?>>Portrait</option>
        <option value="Always" <?php echo ($scrolling == "Always") ? "selected" : ""; ?>>Always</option>
    </select>
    </div>
</div>

<hr />

<div class="field">
    <label class="checkbox" for="locked">
        <input type="hidden" value="false" name="<?php echo $fieldSlug;?>[locked]" />
        <input type="checkbox" value="true" name="<?php echo $fieldSlug;?>[locked]" <?php echo ( isset($article["meta"]["locked"]) && filter_var($article["meta"]["locked"], FILTER_VALIDATE_BOOLEAN) === true ) ? "checked" : "" ; ?>/>  
        <span></span> Locked
    </label>
</div>

<div class="field">
    <label class="checkbox" for="flatten">
        <input type="hidden" value="false" name="<?php echo $fieldSlug;?>[flatten]" />
        <input type="checkbox" value="true" name="<?php echo $fieldSlug;?>[flatten]" <?php echo ( isset($article["meta"]["flatten"]) && filter_var($article["meta"]["flatten"], FILTER_VALIDATE_BOOLEAN) === true) ? "checked" : "" ; ?>/>  
        <span></span> Flatten / Horizontal Swipe
    </label>
</div>

<div class="field">
    <label class="checkbox" for="hideFromTOC">
        <input type="hidden" value="false" name="<?php echo $fieldSlug;?>[hideFromTOC]" />
        <input type="checkbox" value="true" name="<?php echo $fieldSlug;?>[hideFromTOC]" <?php echo ( isset($article["meta"]["hideFromTOC"]) && filter_var($article["meta"]["hideFromTOC"], FILTER_VALIDATE_BOOLEAN) === true) ? "checked" : "" ; ?>/>  
        <span></span> Hide From Toc
    </label>
</div>

<div class="field">
    <label class="checkbox" for="isAdvertisement">
        <input type="hidden" value="false" name="<?php echo $fieldSlug;?>[isAdvertisement]" />
        <input type="checkbox" value="true" name="<?php echo $fieldSlug;?>[isAdvertisement]" <?php echo ( isset($article["meta"]["isAdvertisement"]) && filter_var($article["meta"]["isAdvertisement"], FILTER_VALIDATE_BOOLEAN) === true) ? "checked" : "" ; ?>/>  
        <span></span> Advertisement
    </label>
</div>

<hr />

<div class="field">
    <label class="checkbox" for="hasAudio">
        <input type="hidden" value="false" name="<?php echo $fieldSlug;?>[hasAudio]" />
        <input type="checkbox" name="<?php echo $fieldSlug;?>[hasAudio]" value="true" <?php echo ( isset($article["meta"]["hasAudio"]) && filter_var($article["meta"]["hasAudio"], FILTER_VALIDATE_BOOLEAN) === true) ? "checked" : "" ; ?>/>
        
        <span></span> Audio
    </label>
    <i class="icon-note"></i>
</div>

<div class="field">
    <label class="checkbox" for="hasSlideShow">
        <input type="hidden" value="false" name="<?php echo $fieldSlug;?>[hasSlideShow]" />
        <input type="checkbox" name="<?php echo $fieldSlug;?>[hasSlideShow]" value="true" <?php echo ( isset($article["meta"]["hasSlideShow"]) && filter_var($article["meta"]["hasSlideShow"], FILTER_VALIDATE_BOOLEAN) === true) ? "checked" : "" ; ?>/>
         
        <span></span> SlideShow
    </label>
    <i class="icon-camera"></i>
</div>

<div class="field">
    <label class="checkbox" for="hasVideo">
        <input type="hidden" value="false" name="<?php echo $fieldSlug;?>[hasVideo]" />
        <input type="checkbox" name="<?php echo $fieldSlug;?>[hasVideo]" value="true" <?php echo ( isset($article["meta"]["hasVideo"]) && filter_var($article["meta"]["hasVideo"], FILTER_VALIDATE_BOOLEAN) === true) ? "checked" : "" ; ?>/>
        <span></span> Video 
    </label>
    <i class="icon-play"></i>
</div>

<hr />

<div class="field">
    <label class="checkbox" for="canAccessReceipt">
        <input type="hidden" value="false" name="<?php echo $fieldSlug;?>[canAccessReceipt]" />
        <input type="checkbox" name="<?php echo $fieldSlug;?>[canAccessReceipt]" value="true" <?php echo ( isset($article["meta"]["canAccessReceipt"]) && filter_var($article["meta"]["canAccessReceipt"], FILTER_VALIDATE_BOOLEAN) === true) ? "checked" : "" ; ?>/>
        <span></span> Can Access Receipt 
    </label>
</div>

<hr />

<h6 class="lead">Associated Folio</h6>
<?php if(!empty($article["folio"])): ?>
    <div class="medium btn default"> 
        <a href="<?php echo get_edit_post_link($article["folio"]); ?>" class="">Go To Associated Folio</a>
    </div>
<?php else: ?>
    No Associated Folio
<?php endif; ?>
<BR/><BR/>

<div class="text-center">
    <br />
    <div class="small btn default" onclick="jQuery('.advanced').toggle();"><a><b>Show Advanced Article Info</b></a></div>
    <br /><br />
</div>

<div class="advanced hidden">

<BR/><BR/>


<ul>    
    <li class="field">
        <label class="checkbox">Article ID</label> <br>
        <input class="input" disabled type="text" value="<?php echo isset($article["hostedID"]) ? $article["hostedID"] : "" ; ?>" />
    </li>

    <li class="field">
        <label class="checkbox">Access</label> <br>
        <input class="input" disabled type="text" value="<?php echo isset($article["meta"]["access"]) ? $article["meta"]["access"] : "" ; ?>" />
    </li>

    <li class="field">
        <label class="checkbox">Asset Format</label> <br>
        <input class="input" disabled type="text" value="<?php echo isset($article["meta"]["assetFormat"]) ? $article["meta"]["assetFormat"] : "" ; ?>" />
    </li>

     <li class="field">
        <label class="checkbox">Flatten</label> <br>
        <input class="input" disabled type="text" value="<?php echo isset($article["meta"]["flatten"]) ? $article["meta"]["flatten"] : "" ; ?>" />
    </li>

     <li class="field">
        <label class="checkbox">jpegQuality</label> <br>
        <input class="input" disabled type="text" value="<?php echo isset($article["meta"]["jpegQuality"]) ? $article["meta"]["jpegQuality"] : "" ; ?>" />
    </li>

     <li class="field">
        <label class="checkbox">numberOfLandscapeAssets</label> <br>
        <input class="input" disabled type="text" value="<?php echo isset($article["meta"]["numberOfLandscapeAssets"]) ? $article["meta"]["numberOfLandscapeAssets"] : "" ; ?>" />
    </li>

     <li class="field">
        <label class="checkbox">numberOfPortraitAssets</label> <br>
        <input class="input" disabled type="text" value="<?php echo isset($article["meta"]["numberOfPortraitAssets"]) ? $article["meta"]["numberOfPortraitAssets"] : "" ; ?>" />
    </li>

     <li class="field">
        <label class="checkbox">resolutionHeight</label> <br>
        <input class="input" disabled type="text" value="<?php echo isset($article["meta"]["resolutionHeight"]) ? $article["meta"]["resolutionHeight"] : "" ; ?>" />
     </li>
     
     <li class="field">
        <label class="checkbox">resolutionWidth</label> <br>
        <input class="input" disabled type="text" value="<?php echo isset($article["meta"]["resolutionWidth"]) ? $article["meta"]["resolutionWidth"] : "" ; ?>" />
    </li>

     <li class="field">
        <label class="checkbox">sortNumber</label> <br>
        <input class="input" disabled type="text" value="<?php echo isset($article["meta"]["sortNumber"]) ? $article["meta"]["sortNumber"] : "" ; ?>" />
    </li>

     <li class="field">
        <label class="checkbox">targetViewer</label> <br>
        <input class="input" disabled type="text" value="<?php echo isset($article["meta"]["targetViewer"]) ? $article["meta"]["targetViewer"] : "" ; ?>" />
    </li>

     <li class="field">
        <label class="checkbox">uncompressedFolioSize</label> <br>
        <input class="input" disabled type="text" value="<?php echo isset($article["meta"]["uncompressedFolioSize"]) ? $article["meta"]["uncompressedFolioSize"] : "" ; ?>" />
    </li>
    
</ul>

<br />


</div>
</div>