<?php
    $hostedDate = new DateTime( $folio["meta"]["modifyDate"] );
    $localDate = new DateTime( get_the_modified_date() . " " . get_the_modified_time( ) );
?>


    <BR/>
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
        <div class="medium default btn">
            <a href="#" data-action="update_rendition" data-folio="<?php echo $post_id;?>"><i class="fa fa-cloud-upload"></i> Push metadata to the cloud</a>
        </div>
        <BR/><BR/>
        <div class="medium default btn">
            <a data-action="push_rendition_articles" data-folio="<?php echo $post_id;?>"><i class="fa fa-cloud-upload"></i> Upload all articles</a>
        </div>
        <BR/><BR/>
        <div class="medium default btn">
            <a data-action="upload_htmlresources" data-folio="<?php echo $post_id;?>"><i class="fa fa-cloud-upload"></i> Upload HTMLResources</a>
        </div>
    <?php else: ?>
        <!-- NOT PUBLISHED -->
        <div class="medium default btn">
            <a data-action="push_rendition" data-folio="<?php echo $post_id;?>"><i class="fa fa-cloud-upload"></i> Push rendition to the cloud</a>
        </div>
    <?php endif; ?>
    <BR/><BR/>

