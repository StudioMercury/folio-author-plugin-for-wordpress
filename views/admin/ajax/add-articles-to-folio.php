<?php 
    $articleService = DPSFolioAuthor_Article::getInstance();
    $allArticles = $articleService->get_articles( 'local', 'unattached' );
?>

<form>

    <input type="hidden" name="action" value="add_articles_to_folio"/>
    <input type="hidden" name="folio" value="<?php echo $_POST['folio'];?>"/>
    
    <div class="text-center">
        <div class="medium default btn"><a data-action="select_all" data-boxes="#posts-list">Check All</a></div>
        <div class="medium primary btn"><a class="" data-action="add_articles_to_folio">Add Articles To Folio</a></div>
    </div>    
    
    <ul class="text-left checkbox-list" id="posts-list">
        <?php foreach( $allArticles as $article ): ?>
        <li class="field">
        
            <label class="checkbox" for="<?php echo $article["localID"];?>">
                <input id="<?php echo $article["localID"];?>" type="checkbox" value="<?php echo $article["localID"];?>" name="articles[]"/>
                <span></span> 
                <?php 
                     $attr = array(
                        	'class'	=> "thumbnail"
                        );
                    $img = wp_get_attachment_image( $article["preview"], array( 25,25), false, $attr );
                ?>
                <?php if( !isset($img) ): ?>
                    <img class="thumbnail no-thumbnail"/>
                <?php else: ?>
                    <?php echo $img; ?>            
                <?php endif; ?>
                
                <?php echo $article["meta"]["title"];?>
            </label>
        </li>
        <?php endforeach; ?>
    </ul>

    <div class="text-center">
        <div class="medium default btn"><a data-action="select_all" data-boxes="#posts-list">Check All</a></div>
        <div class="medium primary btn"><a class="" data-action="add_articles_to_folio">Add Articles To Folio</a></div>
    </div>

    
    
</form>