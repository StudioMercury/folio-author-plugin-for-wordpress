<?php
/*
Article Template Name: Template Example
Description: Very basic example of a template for the DPS Folio Authoring Plugin for Wordpress. This template is based on Bootstrap (http://getbootstrap.com).
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
	    <title><?php wp_title(); ?></title>
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
		<?php wp_head(); ?>

	</head>
	
	<body id="template-example">	

		<div class="container">
                        		    
		    <div id="story">
		    <?php if(get_the_content()) : ?>
		    
		        <?php the_content(); ?>
		        
		    <?php else : ?>
		        <?php /* IF NOTHING EXISTS IN THE CONTENT FILED THEN DISPLAY THIS EXAMPLE */ ?>
                <div class="alert alert-warning alert-dismissable"><button class="close" type="button" data-dismiss="alert">×</button> <strong>NOTE:</strong> You are seeing this because you don't have anything in the content field.</div><h1><strong>WORKING WITH TEMPLATES</strong></h1><h4><em>by</em> The Brothers Mueller</h4><p><br /> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur lobortis, est in blandit dapibus, tortor mauris facilisis neque, in tempus nunc ligula sit amet leo. Pellentesque dignissim ut purus eu auctor. Mauris consectetur rhoncus est, sed cursus ligula molestie nec. Donec ac mauris viverra, varius enim eget, scelerisque neque. Fusce nec gravida lorem. Sed porttitor diam in lacus mollis, non fermentum odio mollis. In pharetra dapibus purus at feugiat. Duis pharetra vehicula blandit. Duis molestie nunc ante, eu mattis nisi consectetur non. Integer dictum imperdiet nulla, et rutrum sem interdum id. Vestibulum interdum neque in tincidunt auctor. Nunc at velit dictum, eleifend mi vel, pulvinar ante. Phasellus blandit, neque vel condimentum placerat, massa mi ultrices ipsum, a tincidunt ante nunc nec eros. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Nunc ultrices faucibus porttitor.</p><p><img class="img-responsive aligncenter" alt="Responsive image" src="http://placehold.it/640x250" /></p><p>Cras dignissim, ante ullamcorper bibendum consequat, velit diam tincidunt erat, sit amet mattis nisl nulla ut tortor. Proin ullamcorper tincidunt nisi, eu congue felis molestie et. Mauris vel mi turpis. Etiam scelerisque ipsum massa, sit amet condimentum diam iaculis id. Duis vulputate tempor nisl id pulvinar. Proin semper erat at quam commodo, non dapibus lorem lobortis. Suspendisse velit dui, ultrices non rhoncus ut, dapibus eu magna. Duis nunc enim, suscipit at dui sed, ultrices bibendum diam.</p><blockquote>Sed placerat lectus semper ipsum pharetra consectetur. Vivamus blandit lacus quis rhoncus scelerisque. Quisque lectus dolor, tincidunt id nunc quis, imperdiet tincidunt libero. Duis interdum lorem eget purus rutrum, et dignissim dolor rhoncus. Maecenas rutrum nibh consequat lectus commodo elementum. Sed nec ullamcorper turpis, id ultrices turpis. Proin ut facilisis leo, nec imperdiet libero. Aliquam libero enim, commodo ac nulla at, dictum tempus felis.</blockquote><p>Nunc sed libero sapien. Aenean lacinia aliquam tellus id dignissim. Mauris lobortis vel tellus facilisis tincidunt. Sed aliquet euismod neque. Quisque vitae arcu a ligula vulputate tempor id auctor dolor. Donec non nulla purus. Mauris sed tempor diam, nec mollis est. Nam tincidunt sem elit, vitae consequat felis pharetra a. Fusce pellentesque neque vel augue venenatis adipiscing. Phasellus varius fringilla quam aliquet porta. Donec tortor elit, aliquam ut dolor vitae, dignissim convallis nulla. Praesent fermentum venenatis cursus. Donec id sollicitudin arcu. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Cras rutrum, orci vitae pharetra condimentum, mauris lacus bibendum diam, et elementum nunc massa a leo. Morbi ultricies in sem et ornare. Aliquam id justo sed mi auctor consequat. Etiam luctus ut mi vel convallis.  </p><hr /><h6>PHOTOGRAPHY BY <strong>JOHN DOE</strong></h6><p>&nbsp;</p>
		    <?php endif; ?>
		    
		    </div>
		    
		</div>	
	  
		<?php wp_footer(); ?>
	  
	</body>
    
</html>
