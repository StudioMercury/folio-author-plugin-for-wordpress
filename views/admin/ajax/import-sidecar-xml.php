<form class="text-left checkbox-list">
    <input type="hidden" name="action" value="import_sidecar_xml"/>
    <input type="hidden" name="folio" value="<?php echo $_POST['folio']?>"/>
    
    <h5>Please make sure you have edited the article names for all of the articles before import. The importer uses the article names to match the article fields with the sidecar.xml file.</h5>
    <BR/>
    <input type="file" name="sidecar">
    <div class="medium primary btn"><a class="" data-action="import_articles">Import sidecar.xml file</a></div>
</form>