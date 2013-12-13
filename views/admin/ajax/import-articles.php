<?php 
    $args = array(
	    'post_type' => 'post',
	    'posts_per_page' => -1
    );
    $the_query = new WP_Query( $args );
?>

<form class="text-left checkbox-list">
    <input type="hidden" name="action" value="import_articles"/>
    
    <div class="text-center">
        <div class="medium default btn"><a data-action="select_all" data-boxes="#posts-list">Check All</a></div>
        <div class="medium primary btn"><a class="" data-action="import_articles" data-boxes="#posts-list">Import posts as articles</a></div> 
    </div>
    
    <?php if( $the_query->have_posts() ): ?>
    <ul class="" id="posts-list">
        <?php while ( $the_query->have_posts() ): ?>
            <?php $the_query->the_post(); ?>
            <li class="field">
                <label class="checkbox" for="<?php echo get_the_ID();?>">
                    <input id="<?php echo get_the_ID();?>" type="checkbox" value="<?php echo get_the_ID();?>" name="posts[]"/>  
                    <span></span> <?php echo get_the_title();?>
                    <BR/>
                    <small>CATEGORIES:<?php echo get_the_category_list( ",", "multiple", get_the_ID() ); ?></small>
                </label>
            </li>
            <BR/>
        <?php endwhile;?>
    </ul>
    
    <div class="text-center">
        <div class="medium default btn"><a data-action="select_all" data-boxes="#posts-list">Check All</a></div>
        <div class="medium primary btn"><a class="" data-action="import_articles" data-boxes="#posts-list">Import posts as articles</a></div> 
    </div>
    
    <?php else:?>
        <h5 class="error">No Posts found to import.</h5>
    <?php endif;?>
</form>