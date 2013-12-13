<?php 
    $folioService = DPSFolioAuthor_Folio::getInstance();
    $articlePost = get_post( $_POST['folio'] ); 
    $folioParent = $folioService->folio( $articlePost->post_parent );
?>
<form class="text-left checkbox-list">
    <?php if( count($folioParent["renditions"]) > 1 ): ?>
    <input type="hidden" name="action" value="duplicate_articles_from_rendition"/>
    <input type="hidden" name="folio" value="<?php echo $_POST['folio'];?>"/>
    
    <h5>
        <B>Select a rendition</B><BR/>
        Use the form below to duplicate the articles from the selected rendition.
    </h5>
    
    <select name="rendition">
        <option disabled selected>Select a folio rendition</option>
        <?php foreach( $folioParent["renditions"] as $rendition ): ?>
            <?php if( $rendition["localID"] != $_POST['folio'] ): ?>
            <option value="<?php echo $rendition["localID"];?>"><?php echo $rendition["device"]["name"]; ?></option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>
    <div class="medium primary btn"><a data-action="duplicate_articles_from_rendition">Duplicate Articles</a></div>
    <?php else: ?>
     <h5>
        <B>No renditions to duplicate</B><BR/>
        You need to have more than one rendition if you want to duplicate articles.
    </h5>
    <?php endif;?>
</form>