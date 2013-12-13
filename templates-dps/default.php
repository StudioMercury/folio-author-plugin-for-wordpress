<?php
/*
Article Template Name: 
Max Image Width: 200
Description: iPhone article template for the DPS Folio Authoring Plugin.
*/
global $post;
setup_postdata($post); ?>

<?php get_header(); ?>

    <header>
        <?php if(get_post_meta( $post->ID, 'rd_template_options_slug', true )) : ?>
        <div class="section"><?php echo get_post_meta( $post->ID, 'rd_template_options_slug', true ); ?></div>
        <?php endif; ?>
    </header>


    <?php the_content(); ?>


<?php get_footer(); ?>
