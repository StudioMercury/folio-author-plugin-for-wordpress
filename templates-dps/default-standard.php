<?php
/*
Article Template Name:
Max Image Width: 640
Description: iPhone article template for the DPS Folio Authoring Plugin.
*/

include_once('TemplateResources.php');

global $post;
setup_postdata($post); 

// REGISTER SCRIPTS
wp_enqueue_script('Modernizr');
wp_enqueue_script('jQuery');
wp_enqueue_script('Bootstrap');
wp_enqueue_script('Main');

// REGISTER STYLES
wp_enqueue_style('FontAwesome');
wp_enqueue_style('Bootstrap');
wp_enqueue_style('Stylesheet');

?>

<!doctype html>
<html <?php language_attributes(); ?>>
   
	<head>
	    <meta charset="utf-8">
	    <title><?php wp_title(' - ',true,'right'); bloginfo('name'); ?></title>
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
		<?php wp_head(); ?>
	</head>
	
	<body class="">	

		<div class="container">	
		
		    <div id="story">
		    <?php the_content(); ?>
		    </div>
		    
		</div>	
	  
		<?php wp_footer(); ?>
	  
	</body>
    
</html>
