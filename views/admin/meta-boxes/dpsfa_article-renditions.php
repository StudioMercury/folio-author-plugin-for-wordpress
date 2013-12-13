<?php 
    global $post;
    $post_id = $post->ID;
    if( get_post_ancestors($post_id) ) { 
        $article_parent = get_post_ancestors($post_id); 
        $article_parent = $article_parent[0];
    } else { 
        $article_parent = $post_id;
    }
    
    $articleService = DPSFolioAuthor_Article::getInstance();
    $article = $articleService->article($article_parent);
?>

<div class="gumby">
    <div class="row">
    
        <div class="seven columns centered">
        <div class="row">
        
            <div class="columns <?php if($article["renditions"]) { echo 'seven'; } else { echo 'twelve'; } ?>">
                <section class="tabs pill">
                    <ul class="tab-nav">
                        <li class="active" onclick="jQuery('#postdivrich').addClass('normal').removeClass('mobile tablet');">
                            <a href="#"><i class="fa fa-desktop" style="font-size: 1.25em;"></i> Normal View</a>
                        </li>
                        <li onclick="jQuery('#postdivrich').addClass('mobile').removeClass('normal tablet');">
                            <a href="#"><i class="fa fa-mobile" style="font-size: 1.5em;"></i> Mobile View</a>
                        </li>
                        <li onclick="jQuery('#postdivrich').addClass('tablet').removeClass('mobile normal');">
                            <a href="#"><i class="fa fa-tablet" style="font-size: 1.5em;"></i> Tablet View</a>
                        </li>
                    </ul>
                </section>
            </div>
            <?php if($article["renditions"]) : ?>
            <div class="five columns text-center">

                <div class="field">
                    <span class="picker">
                            
                        <select onchange="if (this.value) window.location.href=this.value;" name="options[foo]">
                          <option value="#">Select a rendition</option>
                          <option value="<?php echo get_edit_post_link($article["localID"]);?>">Original Article</option>
                          <?php foreach( $article["renditions"] as $rendition ): ?>
                          <?php 
                            $articleService = DPSFolioAuthor_Article::getInstance();
                            $folioService = DPSFolioAuthor_Folio::getInstance();
                            $folio = $folioService->folio( $rendition["folio"] );
                            if( is_wp_error($folio) ){ $folio = false;}
                          ?>
                          <option value="<?php echo get_edit_post_link($rendition['localID']); ?>"><?php echo $folio["device"]["name"];?></option>
                          <?php endforeach; ?>
                        </select>

                    </span>
                </div>
                
            </div>
            <?php endif; ?>
        
        </div>
        </div>
        
    </div>
</div>

<br /><br />