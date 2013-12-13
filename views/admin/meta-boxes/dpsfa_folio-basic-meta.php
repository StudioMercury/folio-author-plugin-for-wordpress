<?php
    /* META BOXES FOR FOLIOS */
    global $post_id;
?>
<div class="gumby">
<style>
    #dps_folio_author_folio-basic-meta input { width: 100%; }
    #dps_folio_author_folio-basic-meta ul li { margin-bottom: 20px; margin-top: 20px; }
</style>


<ul>
    <li>
        <label>Folio Name</label> <br />
        <input type="text" required name="<?php echo $fieldSlug;?>[folioName]" value="<?php echo isset($folio["meta"]["folioName"]) ? $folio["meta"]["folioName"] : "" ; ?>" />
    </li>

    <li>
        <label>Folio Number</label> <br />
        <input type="text" required name="<?php echo $fieldSlug;?>[folioNumber]" value="<?php echo isset($folio["meta"]["folioNumber"]) ? $folio["meta"]["folioNumber"] : "" ; ?>" />
    </li>

    <li>
        <label>Folio Description</label> <br />
        <input type="text" name="<?php echo $fieldSlug;?>[folioDescription]" value="<?php echo isset($folio["meta"]["folioDescription"]) ? $folio["meta"]["folioDescription"] : "" ; ?>" />
    </li>

    <li>
        <label>Publication Date</label> <br />
        <input type="date" required class="datepicker" name="<?php echo $fieldSlug;?>[publicationDate]" value="<?php echo isset($folio["meta"]["publicationDate"]) ? $folio["meta"]["publicationDate"] : "" ; ?>" />
    </li>

    <li>
        <label>Cover Date</label> <br />
        <input type="date" required class="datepicker" name="<?php echo $fieldSlug;?>[coverDate]" value="<?php echo isset($folio["meta"]["coverDate"]) ? $folio["meta"]["coverDate"] : "" ; ?>" />
    </li>

    <li>
        <label>Filters</label> <br />
        <input type="text" name="<?php echo $fieldSlug;?>[filters]" value="<?php echo isset($folio["meta"]["filters"]) ? $folio["meta"]["filters"] : "" ; ?>" />
    </li>

    <li>
        <label>Publication Name / Magazine Title</label> <br />
        <input type="text" required name="<?php echo $fieldSlug;?>[magazineTitle]" value="<?php echo isset($folio["meta"]["magazineTitle"]) ? $folio["meta"]["magazineTitle"] : "" ; ?>" />
    </li>

</ul>

<script>
    jQuery(document).ready(function() {
        jQuery( '[type="date"]' ).datepicker().prop('type', 'text');
    });
</script>
</div>