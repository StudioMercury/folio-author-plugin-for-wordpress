<?php 
    $folioService = DPSFolioAuthor_Folio::getInstance();
    $fieldSlug = $folioService->folioPostType;
    
    $deviceService = DPSFolioAuthor_Device::getInstance();
    $devices = $deviceService->get_devices();
?>

<div class="create-new-rendition">
    <form>
        <input type="hidden" name="action" value="create_new_rendition"/>
        <input type="hidden" name="folio" value="<?php echo $_POST['folio'];?>"/>
        <input type="hidden" name="rendition[meta][defaultAssetFormat]" value="Auto" />
        <input type="hidden" name="rendition[meta][defaultJPEGQuality]" value="High" />
        <input type="hidden" name="rendition[meta][bindingRight]" value="false" />
        <input type="hidden" name="rendition[meta][locked]" value="false" />
        
        <br />
        
        <div class="field">
            <div class="picker">
                <select data-action-change="select_device">
                <option type="text" selected disabled>Select the target device</option>
                <?php foreach($devices as $device): ?>
                    <option 
                        type="text" 
                        data-slug   = "<?php echo $device["slug"];?>"
                        data-name   = "<?php echo ($device["slug"] == "custom") ? "" : $device["name"]; ?>"
                        data-width  = "<?php echo $device["width"];?>"
                        data-height = "<?php echo $device["height"];?>">
                    <?php echo $device["name"];?>
                    </option>
                <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <br />
        
        
        <div class="field">
            <input type="text" class="narrow input rendition-size" name="rendition[meta][resolutionWidth]" placeholder="width" /> x &nbsp; &nbsp;
            <input type="text" class="narrow input rendition-size" name="rendition[meta][resolutionHeight]" placeholder="height" />
        </div>        
        
        <br /><br />
        
        <input type="text" name="rendition[renditionLabel]" placeholder="Device (rendition) label" class="hidden"/>
        
        
        <div class="field">
            <div class="picker">
                <select name="rendition[meta][folioIntent]">
                    <option type="text" selected disabled>Select the target orientation</option>
                    <option value="Both">Both</option>
                    <option value="PortraitOnly">Vertical</option>
                    <option value="LandscapeOnly">Horizontal</option>
                </select>
            </div>
        </div>
        
        
        <br /><br /><br />
        
        <div class="medium primary btn"><a class="" data-action="create_new_rendition"><i class="icon icon-plus"></i> Add Rendition</a></div>
        
    </form>
</div>