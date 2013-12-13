<div class="sync-status">
        
    <?php if( $folio["linked"] ):?>
        <div class="status">
            <h4><i class="icon-desktop large"></i> Folio is connected to Wordpress</h4>
        </div>
    <?php else:?>
        <div class="status">
            <h4><i class="icon-cloud large"></i>Folio is hosted on Adobe's servers</h4>
            <p>
                You need to link the folio with Wordpress in order to edit it.<BR/><BR/>
                <a class="button-secondary link" href="#modal" title="Link Folio">Link Folio</a>
            </p>
        </div>
    <?php endif; ?>
    
   <?php 
   
       // TODO: create an ajax call for determining if meta data is up to date or articles are up to date
   
   ?>
    
    
    <!--
    <div class="in-sync">
        <i class="icon-check-sign"></i> Synced with the cloud.
    </div>    
    
    <div class="out-of-sync">
        <BR/><BR/>
        <i class="icon-remove-sign"></i> Out of Sync with the cloud 
            <BR/><BR/>
        <i class="icon-cloud-download large"></i> <div class="button-secondary" onclick="">Pull changes from the cloud</div>
            <BR/><BR/>
        <i class="icon-cloud-upload large"></i> <div class="button-secondary" onclick="">Push changes to the cloud</div>
    </div>
    -->
    
</div>