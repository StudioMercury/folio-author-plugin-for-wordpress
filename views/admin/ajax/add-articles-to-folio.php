<?php
    $articleService = DPSFolioAuthor_Article::getInstance();
    $allArticles = $articleService->get_articles( array(
        'filter'    => 'local', 
        'folioID'   => $_POST['parentfolio'],
        'parent'    => 0
    ));
?>

<form>

    <input type="hidden" name="action" value="add_articles_to_folio"/>
    <input type="hidden" name="folio" value="<?php echo $_POST['folio'];?>"/>
    
    <?php 
        $filterService = DPSFolioAuthor_Filter::getInstance();
        $filterService->show_filter();
    ?>
        
    <div class="text-left">
        <?php if( !empty($allArticles) ): ?>
        <!--
        <div class="medium default btn"><a data-action="select_all" data-boxes=".posts-list">Check All</a></div>
        <div class="medium primary btn"><a class="" data-action="add_articles_to_folio">Add Articles To Folio</a></div>
        -->
        <?php endif; ?>
        <div class="text-center">
            <div class="medium default btn" id="filter-button" data-action="toggle_element" data-toggle="#filter-options">
                <a title="Filter"><i class="fa fa-filter"></i> Filter Articles</a>
            </div>
        </div>
        
    </div>    
    <BR/>
    <div id="article-list" class="posts-list">
        <?php
            $viewsService = DPSFolioAuthor_Views::getInstance();
            echo $viewsService->render( "modal-list-items", array("articles" => $allArticles, "noResults" => empty($allArticles)) ); 
        ?>
    </div>
    
</form>