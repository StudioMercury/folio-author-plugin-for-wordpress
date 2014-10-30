<?php
    /* META BOXES FOR FOLIOS */
    global $post;
    $post_id = $post->ID;
?>
<BR/>

<div class="gumby text-center">

    <div class="medium primary btn" style="width: 51%;"><input type="submit" value="Save Article" name="publish" id="publish" class="" accesskey="p" /> </div>

    <div class="medium danger btn" style="width: 40%;"><a data-action="delete_article" data-article="<?php echo $post_id;?>" data-redirect="true" class="">Delete</a></div>

    <BR/><BR/>

    <div class="medium default btn" style="width: 100%;"><a href="<?php echo get_permalink($post_id);?>" target="_BLANK" class="">Preview Article</a></div>
    
    <BR/><BR/>
    
    <div class="medium default btn" style="width: 100%;"><a data-action="download_zip" data-article="<?php echo $post_id;?>" target="_BLANK" class="">Download Archive</a></div>

</div>

<BR/><BR/>