<?php 
    $folioService = DPSFolioAuthor_Folio::getInstance();
    $fieldSlug = $folioService->folioPostType;
    $folio = $folioService->folio( $_POST['folio'] );
?>
<div class="create-new-folio text-right">
    <form>
        
        <input type="hidden" name="action" value="edit_folio"/>
        <input type="hidden" name="folio" value="<?php echo $_POST['folio'];?>"/>
        <div class="field">
            <label class="inline" for="folioName">Folio Name</label>
            <input class="wide text input" id="folioName" type="text" required name="<?php echo $fieldSlug;?>[folioName]" placeholder="Folio Name" value="<?php echo $folio["meta"]["folioName"];?>" />
        </div>
        <br />
        <div class="field">
            <label class="inline" for="folioNumber">Folio Number</label>
            <input class="wide text input" id="folioNumber" type="text" required name="<?php echo $fieldSlug;?>[folioNumber]" placeholder="Folio Number" value="<?php echo $folio["meta"]["folioNumber"];?>"/>
        </div>
        <br />
        <div class="field">
            <label class="inline" for="magazineTitle">Magazine Title</label>
            <input class="wide text input" id="magazineTitle" type="text" required name="<?php echo $fieldSlug;?>[magazineTitle]" placeholder="Publication Name / Magazine Title" value="<?php echo $folio["meta"]["magazineTitle"];?>"/>
        </div>
        <br />
        <div class="field">
            <label class="inline" for="folioDescription">Folio Description</label>
            <input class="wide text input" id="folioDescription" type="text" required name="<?php echo $fieldSlug;?>[folioDescription]" placeholder="Folio Description" value="<?php echo $folio["meta"]["folioDescription"];?>"/>
        </div>
        <br />
        <div class="field">
            <label class="inline" for="publicationDate">Publication Date</label>
            <input class="wide text input datepicker" id="publicationDate" type="text" required name="<?php echo $fieldSlug;?>[publicationDate]" placeholder="Publication Date" value="<?php echo $folio["meta"]["publicationDate"];?>"/>
        </div>
        <br />
        <div class="field">
            <label class="inline" for="coverDate">Cover Date</label>
            <input class="wide text input datepicker" id="coverDate" type="text" name="<?php echo $fieldSlug;?>[coverDate]" placeholder="Cover Date" value="<?php echo $folio["meta"]["coverDate"];?>"/>
        </div>
        <br />
        <div class="field">
            <label class="inline" for="filters">Filters</label>
            <input class="wide text input" id="filters" type="text" required name="<?php echo $fieldSlug;?>[filters]" placeholder="Filters" value="<?php echo $folio["meta"]["filters"];?>"/>
        </div>
        
        <br /><br />
        <div class="medium primary btn"><a class="" data-action="edit_folio">Update Folio</a></div>
        <br />
        
    </form>
</div>