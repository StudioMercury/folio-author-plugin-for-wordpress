<style>
    .form-table th[scope="row"]{
        display: none;
    }
</style>

<div class="row">

    <div class="seven columns">
        
        <br />
        
          <ul>
            <li class="field text-right">
              <label class="inline" for="text1">Company Name</label>
              <input type="text" class="wide text input" name="<?php echo $settingsMeta;?>[company]" placeholder="ie: Adobe" value="<?php echo isset($settings["company"]) ? $settings["company"] : ''; ?>" />
            </li>
          </ul>

        <br />
        <hr />
        <br />
       
       
          <ul>
            <li class="field text-right">
              <label class="inline" for="text1">Adobe DPS <b>Key</b></label>
              <input type="text" class="wide text input" name="<?php echo $settingsMeta;?>[key]" placeholder="Provided by Adobe" value="<?php echo isset($settings["key"]) ? $settings["key"] : ''; ?>" />
            </li>
            <li class="field text-right">
              <label class="inline" for="text1">Adobe DPS <b>Secret</b></label>
              <input type="password" class="wide text input" name="<?php echo $settingsMeta;?>[secret]" placeholder="Provided by Adobe" value="<?php echo isset($settings["secret"]) ? $settings["secret"] : ''; ?>" />
            </li>
          </ul>

        <br />
        <hr />
        <br />

          <ul>
            <li class="field text-right">
              <label class="inline" for="text1">Adobe DPS <b>Login</b></label>
              <input type="text" class="wide text input" name="<?php echo $settingsMeta;?>[login]" placeholder="username@domain.com" value="<?php echo isset($settings["login"]) ? $settings["login"] : ''; ?>" />
            </li>
            <li class="field text-right">
              <label class="inline" for="text1">Adobe DPS <b>Password</b></label>
              <input type="password" class="wide password input" name="<?php echo $settingsMeta;?>[password]" placeholder="" value="<?php echo isset($settings["password"]) ? $settings["password"] : ''; ?>" />

            </li>
          </ul>

        <br />
        <hr />
        

        <br />

    </div>
    
    <div class="one columns"></div>
    
    <div class="four columns">
        <br />
             <div class="field">
              <label class="checkbox" for="disableHints">
                <input type="hidden" value="false" name="<?php echo $settingsMeta;?>[disableHints]" />
                <input type="checkbox" id="disableHints" name="<?php echo $settingsMeta;?>[disableHints]" value="true" <?php echo ( isset($settings["disableHints"]) && $settings["disableHints"] == "true") ? "checked" : "" ; ?> />
                <span></span> Disable tooltips around the plugin
              </label>
            </div>
        
        
        <hr />
        
        <div class="ttip" data-tooltip="if no TOC preview has been added, use the featured image or first image associate with the article (otherwise featured image is gray square)">
            <div class="field">
                <label class="checkbox" for="automaticPreview">
                    <input type="hidden" value="false" name="<?php echo $settingsMeta;?>[automaticPreview]" />
                    <input type="checkbox" id="automaticPreview" name="<?php echo $settingsMeta;?>[automaticPreview]" value="true" <?php echo ( isset($settings["automaticPreview"]) && $settings["automaticPreview"] == "true") ? "checked" : "" ; ?> >
                    <span></span> Automatically make a TOC preview image
                </label>
            </div>
        </div>
        
        <hr />
        
          <ul>

            <li class="field">
                <label class="inline" for="text1">Default Template:</label>
                <div class="picker">
                    <?php $template = isset($settings["template"]) ? $settings["template"] : ""; ?>
                    <select width="100%" style="width: 100%" name="<?php echo $settingsMeta;?>[template]">
                        <option disabled selected>Select a Template</option>
                        <?php $templates = DPSFolioAuthor_Templates::getInstance(); ?>
                        <?php $templates->pageTemplateDropdown( $template ); ?>
                    </select>
                </div>
                <br /><br />
                <h6 class="lead">
                We supply you with some sample templates to get started. To add your own templates simple copy the <b>dps-templates</b> folder from the plugin and put it into your theme folder. To learn more about making your own templates you can read about the template engine here <a target="_blank" href="http://studiomercury.github.io/folio-author-plugin-for-wordpress/">Working with the Template Engine.</a>
                </h6>
            </li>
            
            <hr />

            <li class="field">
              <label class="inline">HTMLResources for templates</label>
              <div class="fileupload fileupload-new" data-provides="fileupload" style="display:inline-block;">


                  <span class="medium default btn btn-file">
                      <a class="fileupload-new">Select HTMLResources</a>
                      <a class="fileupload-exists">Change</a>
                      <input type="file" name="<?php echo $settingsMeta;?>_htmlresources" value="test"/></span>
                      <a class="fileupload-exists" data-dismiss="fileupload">Remove</a>
                  </div>

                  <?php $resources = ( isset($settings["htmlresources"]) && $settings["htmlresources"] ) ? $settings["htmlresources"] : ""; ?>
                  <input type="hidden" value="<?php echo $resources;?>" name="<?php echo $settingsMeta;?>[htmlresources]" />
                  <?php if( isset($settings["htmlresources"]) && $settings["htmlresources"] ): ?>
                    <div class="file-path">HTMLResources Path: <a href="<?php echo $resources;?>">Click to download HTMLResources.zip</a></div>
                  <?php else: ?>
                    <div class="file-path"><h6 class="lead"><i>No HTMLResources uploaded</i></h6></div>
                  <?php endif; ?>
                  <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 150px; max-height: 150px; line-height: 20px;"></div>
                  <div>

              </div>
              
              <br />
              <h6 class="lead"><i>This HTMLResources.zip archive will be uploaded automatically when renditions are uploaded to the cloud.</i></h6>
              
            </li>

          </ul>
    
    </div>
    
</div>
    
    
    <h2>Device List</h2>
    <hr />
    
    <div class="row">
    
        <div class="five columns">

          <?php $deviceService = DPSFolioAuthor_Device::getInstance(); ?>
            
            <div class="add-new-device">
                <p>If you don't see a device in the drop down menu when creating a rendition, you can add one here.</p>
                <div class="field">
                    <input class="wide input" type="text" data-new="device" data-key="<?php echo $settingsMeta;?>" data-name="name" placeholder="Name" />
                    <input class="narrow input" type="text" data-new="device" data-key="<?php echo $settingsMeta;?>" data-name="slug" placeholder="Slug" />
                </div>
                <br />
                <div class="field">
                    <input class="xnarrow input" type="text" data-new="device" data-key="<?php echo $settingsMeta;?>" data-name="width" placeholder="Width" /> X &nbsp; &nbsp; &nbsp;
                    <input class="xnarrow input" type="text" data-new="device" data-key="<?php echo $settingsMeta;?>" data-name="height" placeholder="Height" />

                    <div class="picker pull-right">
                        <select type="text" data-new="device" data-key="<?php echo $settingsMeta;?>" data-name="type">
                            <option disabled selected>Select a device type</option>
                            <?php foreach( $deviceService->device_types() as $deviceTypeName => $deviceTypeSlug ):?>
                            <option value="<?php echo $deviceTypeSlug;?>"><?php echo $deviceTypeName;?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <br />
                <div class="medium default btn"><a data-action="add_device" data-list="#devices"><i class="fa fa-plus"></i> Add Device to list</a></div>
            </div>
            
            
        </div>
        
        <div class="one columns"></div>
        <div class="six columns">
        
            <?php $devices = $deviceService->get_devices();?>
            <div class="table">
                <div class="thead">
                    <div class="name">Name</div>
                    <div class="slug">Slug</div>
                    <div class="width">Width</div>
                    <div class="height">Height</div>
                    <div class="type">Type</div>
                    <div class="device-actions"></div>
                </div>
            </div>
            <ul id="devices" class="sortable devices table rounded striped">
            <?php 
                if( empty($devices) ){ $devices = $deviceService->initial_devices(); }
                else{ $devices = array_values($devices); }
            ?>
            <?php foreach($devices as $key => $device) : ?>
                <li id="device-<?php echo $key;?>">
                    <?php foreach( $device as $name => $value ): ?>
                    <div style="width: 18%;" class="<?php echo $name;?>"><?php echo $value;?></div>
                    <input type="hidden" data-device-field="<?php echo $name;?>" name="<?php echo $settingsMeta;?>[devices][<?php echo $key;?>][<?php echo $name;?>]" value="<?php echo $value;?>" />
                    <?php endforeach;?> 
                    <div class="device-actions">
                        <div class="remove btn danger small" data-action="remove_device" data-device="#device-<?php echo $key;?>"><a>REMOVE</a></div>  
                    </div>                  
                </li>
            <?php endforeach; ?>
            </ul>        
        
        </div>
    </div>
    
<br />    
<hr /> 

<div class="row">

        <div class="about">
            <h2>About the plugin</h2>
            <p>This plugin is a collaboration between Studio Mercury and Coffee and Code, who have created many customized and engaging experiences together on the DPS platform for a wide variety of clients.</p><BR/>
                <div class="row">
                <div class="one columns text-center">
                    <img src="<?php echo plugins_url('/adobe-folio-author-wp-plugin/assets/admin/logo-smny.png');?>" alt="" width="50%" height="50%">
                </div>
                <div class="four columns">
                    <b>Mercury</b>
                    <h6>Studio Mercury is a New York-based multimedia design firm offering services in film, photography, branding, print, interaction design, iPad, eBook and tablet application design. The Studio delivers media-rich experiences in entertainment, fine arts, design, education, advertising and publishing industries. Established in 2008, Studio Mercury has worked with Martha Stewart Living, Cond&eacute; Nast and the Guggenheim Museum in creating a multitude of innovative multimedia projects.</h6>
                                        
                </div>
                <div class="one columns text-center">
                    <img src="<?php echo plugins_url('/adobe-folio-author-wp-plugin/assets/admin/logo-cc.png');?>" alt="" width="50%" height="50%">
                </div>
                <div class="four columns">
                    <b>Coffee and Code</b>
                    <h6>Coffee & Code is a passionate, experienced, well-connected digital consultancy and development studio.<BR/><BR/>In the past 8 years, we've helped startups succeed, built solutions for major players in the digital print industry, created web brands for new companies, and supported existing teams to get better results, faster.
and we can hyperlink the company names / logos</h6>
                </div>
                <div class="one columns"></div>
            </div>
        </div>

</div>


