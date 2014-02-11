<?php
    /* META BOXES FOR FOLIOS */
    global $post;
    $post_id = $post->ID;
    
    if( get_post_ancestors($post_id) ) { 
        $article_parent = get_post_ancestors($post_id); 
        $article_parent = $article_parent[0];
    } else { 
        $article_parent = $post_id;
    }
    
    $articleService = DPSFolioAuthor_Article::getInstance();
    $renditionParent = $articleService->article($article_parent);
?>
<BR/>

<div class="gumby">
    <div class="renditions">
        
        <div class="rendition <?php if($post_id == $renditionParent["localID"]){ echo "active"; } ?>">
                <span class="status"></span>
                <span class="name">Original Article</span>
                <span class="actions">
                    <div class="small normal btn"><a href="<?php echo get_edit_post_link($renditionParent['localID']); ?>"> <i class="fa fa-pencil"></i></a></div>
                </span>
            </div>

        <?php foreach($renditionParent["renditions"] as $rendition): ?>            
            <?php
                $folioService = DPSFolioAuthor_Folio::getInstance();
                $folio = $folioService->folio( $rendition["folio"] );
            ?>
            
            <div class="rendition <?php if($post_id == $rendition["localID"]){ echo "active"; } ?>">
                <span class="status">
                    <?php
                    if( empty($rendition["status"]["parent"]) ){
                        $icon = "fa-times-circle red";
                    }else{
                        $icon = "fa-check-circle green";
                    }
                    ?>
                    <i class="fa <?php echo $icon; ?>"></i>
                </span>
                <span class="name">
                    <?php if( !is_wp_error($folio) ): ?>
                        <?php echo $folio["device"]["name"];?>
                    <?php else: ?>
                        Folio doesn't exist
                    <?php endif; ?>
                </span>
                <span class="actions">
                    <div class="small normal btn"><a href="<?php echo get_edit_post_link($rendition['localID']); ?>"> <i class="fa fa-pencil"></i></a></div>
                </span>
            </div>
        <?php endforeach; ?>
    </div>
    
    <BR/>
    <div class="text-center">
        <div class="medium normal btn" data-action="open_box_rendition_sync" data-articleParent="<?php echo $article_parent;?>" style="width: 100%;">
            <a><i class="fa fa-refresh"></i> &nbsp; Sync Renditions</a>
        </div>
    </div>
    
</div>
