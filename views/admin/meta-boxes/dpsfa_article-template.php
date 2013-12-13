<?php
    /* META BOXES FOR FOLIOS */
    global $post_id;
?>
<div class="gumby">
    <br />
    <div class="field">
        <div class="picker">
            <?php $template = isset($article["template"]) ? $article["template"] : ""; ?>
            <select width="100%" style="width: 100%" name="<?php echo $fieldSlug;?>[template]">
                <option disabled selected>Select a Template</option>
                <?php $templates = DPSFolioAuthor_Templates::getInstance(); ?>
                <?php $templates->pageTemplateDropdown( $template ); ?>
            </select>
        </div>
    </div>
    <br />
</div>