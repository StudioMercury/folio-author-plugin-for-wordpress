<div class="gumby">
<?php
    /* META BOXES FOR FOLIOS */
    global $post_id;
    
    if( $isRendition ){
        include_once( dirname( __DIR__ ) . "/meta-boxes/includes/folio-status-rendition.php" );
    }
    else{
        include_once( dirname( __DIR__ ) . "/meta-boxes/includes/folio-status-parent.php" );
    }
?>
</div>