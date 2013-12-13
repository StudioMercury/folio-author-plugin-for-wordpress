<?php
    /* META BOXES FOR ARTICLE META */
    global $post_id;
?>
<div class="gumby">
<BR/>

<div class="field">
    <input type="text" class="input" placeholder="Article Name" name="<?php echo $fieldSlug;?>[name]" value="<?php echo isset($article["meta"]["name"]) ? $article["meta"]["name"] : "" ; ?>" />
</div>

<div class="field">
    <input type="text" class="input" placeholder="Title" name="<?php echo $fieldSlug;?>[title]" value="<?php echo isset($article["meta"]["title"]) ? $article["meta"]["title"] : "" ; ?>" />
</div>

<div class="field">
    <input type="text" class="input" placeholder="Description" name="<?php echo $fieldSlug;?>[description]"  value="<?php echo isset($article["meta"]["description"]) ? $article["meta"]["description"] : "" ; ?>" />
</div>

<div class="field">
    <input type="text" class="input" placeholder="Author" name="<?php echo $fieldSlug;?>[author]"  value="<?php echo isset($article["meta"]["author"]) ? $article["meta"]["author"] : "" ; ?>"/>
</div>

<div class="field">
    <input type="text" class="input" placeholder="Kicker" name="<?php echo $fieldSlug;?>[kicker]"  value="<?php echo isset($article["meta"]["kicker"]) ? $article["meta"]["kicker"] : "" ; ?>" />
</div>

<div class="field">
    <input type="text" class="input" placeholder="Section" name="<?php echo $fieldSlug;?>[section]" value="<?php echo isset($article["meta"]["section"]) ? $article["meta"]["section"] : "" ; ?>" />
</div>

<div class="field">
    <input type="text" class="input" placeholder="Tags ex: tag, tag, tag" name="<?php echo $fieldSlug;?>[tags]" value="<?php echo isset($article["meta"]["tags"]) ? $article["meta"]["tags"] : "" ; ?>" />
</div>

<div class="field">
    <input type="text" class="input" placeholder="User Data (not shown to the user)" name="<?php echo $fieldSlug;?>[userData]" value="<?php echo isset($article["meta"]["userData"]) ? $article["meta"]["userData"] : "" ; ?>" />
</div>
</div>