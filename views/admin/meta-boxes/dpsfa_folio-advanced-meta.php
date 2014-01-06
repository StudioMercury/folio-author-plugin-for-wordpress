<?php
    /* META BOXES FOR FOLIOS */
    global $post_id;
?>
<div class="gumby">
<style>
    #dps_folio_author_folio-advanced-meta input[type=text] { width: 100%; }
    #dps_folio_author_folio-advanced-meta ul li { margin-bottom: 20px; margin-top: 20px; }
</style>

<ul>
    <!--
    <li class="field">
        <h6 class="lead">Folio Orientation (intent)</h6>
        <div class="picker">
            <?php $folioIntent = isset($folio["meta"]["folioIntent"]) ? $folio["meta"]["folioIntent"] : "";?>
            <select name="<?php echo $fieldSlug;?>[folioIntent]">
                <option value="LandscapeOnly" <?php echo ( $folioIntent == "LandscapeOnly") ? "selected" : "" ; ?>>Landscape Only</option>
                <option value="PortraitOnly" <?php echo ( $folioIntent == "PortraitOnly") ? "selected" : "" ; ?>>Portrait Only</option>
                <option value="Both" <?php echo ( $folioIntent == "Both") ? "selected" : "" ; ?>>Both</option>
            </select>
        </div>
    </li>
    
    <li class="field">   
        <h6 class="lead">Width x Height</h6>
        <input type="text" placeholder="width" class="narrow text input" name="<?php echo $fieldSlug;?>[resolutionWidth]" value="<?php echo isset($folio["meta"]["resolutionWidth"]) ? $folio["meta"]["resolutionWidth"] : "" ; ?>" /> 
        x &nbsp;
        <input type="text" placeholder="height" class="narrow text input" name="<?php echo $fieldSlug;?>[resolutionHeight]" value="<?php echo isset($folio["meta"]["resolutionHeight"]) ? $folio["meta"]["resolutionHeight"] : "" ; ?>" />
    </li>
    -->
    
 
    <li class="viewer">
        <h6 class="lead">Viewer</h6>
        <div class="picker">
            <?php $viewer = isset($folio["meta"]["viewer"]) ? $folio["meta"]["viewer"] : "";?>
            <select name="<?php echo $fieldSlug;?>[viewer]">
                <option value="" <?php echo ( $viewer == "") ? "selected" : "" ; ?>>All</option>
                <option value="web" <?php echo ( $viewer == "web") ? "selected" : "" ; ?>>Web</option>
            </select>
        </div>
    </li>
    
    
    <li class="field">
        <label class="checkbox" for="locked">
            <input type="hidden" value="false" name="<?php echo $fieldSlug;?>[locked]" />
            <input type="checkbox" id="locked" name="<?php echo $fieldSlug;?>[locked]" value="true" <?php echo ( isset($folio["meta"]["locked"]) && filter_var($folio["meta"]["locked"], FILTER_VALIDATE_BOOLEAN) === true ) ? "checked" : "" ; ?> />
            <span></span> Locked
        </label>
    </li>
    
    <li class="field">
        <label class="checkbox" for="bindingRight">
            <input type="hidden" value="false" name="<?php echo $fieldSlug;?>[bindingRight]" />
            <input type="checkbox" id="bindingRight" name="<?php echo $fieldSlug;?>[bindingRight]" value="true" <?php echo ( isset($folio["meta"]["bindingRight"]) && filter_var($folio["meta"]["bindingRight"], FILTER_VALIDATE_BOOLEAN) === true ) ? "checked" : "" ; ?>/>      
            <span></span> Binding Right        
        </label>
    </li>
    
    <li class="field">
        <h6 class="lead">Target Viewer</h6>
        <input class="input" type="text" name="<?php echo $fieldSlug;?>[targetViewer]" value="<?php echo isset($folio["meta"]["targetViewer"]) ? $folio["meta"]["targetViewer"] : "" ; ?>" placeholder="xx.xx.xx" />  
    </li>
    
    <hr />
    
        
    <li class="field">  
        <h6 class="lead">Version</h6>
        <input class="input" type="text" name="<?php echo $fieldSlug;?>[version]" value="<?php echo isset($folio["meta"]["version"]) ? $folio["meta"]["version"] : "" ; ?>" disabled />        
    </li>    

    <li class="field">
        <h6 class="lead">HTMLResources?</h6>
        <input class="input" type="text" name="<?php echo $fieldSlug;?>[hasHTMLResources]" value="<?php echo isset($folio["meta"]["hasHTMLResources"]) ? $folio["meta"]["hasHTMLResources"] : "" ; ?>" disabled />       
    </li>
    
    <li class="field">
        <h6 class="lead">Folio ID?</h6>
        <input class="input" type="text" name="<?php echo $fieldSlug;?>[folioID]" value="<?php echo isset($folio["meta"]["folioID"]) ? $folio["meta"]["folioID"] : "" ; ?>" disabled />      
    </li> 

    <li class="field">
        <h6 class="lead">Asset Format</h6>
        <input class="input" type="text" name="<?php echo $fieldSlug;?>[defaultAssetFormat]" value="<?php echo isset($folio["meta"]["defaultAssetFormat"]) ? $folio["meta"]["defaultAssetFormat"] : "" ; ?>" disabled />  
    </li>
    
    <li class="field">   
        <h6 class="lead">JPEG Quality</h6>
        <div class="picker">
            <?php $quality = isset($folio["meta"]["defaultJPEGQuality"]) ? $folio["meta"]["defaultJPEGQuality"] : "";?>
            <select name="<?php echo $fieldSlug;?>[defaultJPEGQuality]" disabled>
                <option value="Minimum" <?php echo ( $quality == "Minimum") ? "selected" : "" ; ?>>Minimum</option>
                <option value="Low" <?php echo ( $quality == "Low") ? "selected" : "" ; ?>>Low</option>
                <option value="Medium" <?php echo ( $quality == "Medium") ? "selected" : "" ; ?>>Medium</option>
                <option value="High" <?php echo ( $quality == "High") ? "selected" : "" ; ?>>High</option>
                <option value="Maximum" <?php echo ( $quality == "Maximum") ? "selected" : "" ; ?>>Maximum</option>
            </select>
        </div>
    </li>     

    <li class="field"> 
        <h6 class="lead">Create Date</h6>    
        <input class="input" type="text" name="<?php echo $fieldSlug;?>[createDate]" value="<?php echo isset($folio["meta"]["createDate"]) ? $folio["meta"]["createDate"] : "" ; ?>" disabled />
    </li>  
    
    <li class="field"> 
        <h6 class="lead">Modify Date</h6>    
        <input class="input" type="text" name="<?php echo $fieldSlug;?>[modifyDate]" value="<?php echo isset($folio["meta"]["modifyDate"]) ? $folio["meta"]["modifyDate"] : "" ; ?>" disabled />
    </li>
        
    
</ul>    
</div>  