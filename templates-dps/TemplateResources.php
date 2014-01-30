<?php 
// TEMPLATE RESOURCES
// This file outlines all assets used in the templates and creates a central places for template options etc
// think of it like a theme's functions.php but for templates


remove_action( 'wp_head', 'feed_links_extra'); // Display the links to the extra feeds such as category feeds
remove_action( 'wp_head', 'feed_links'); // Display the links to the general feeds: Post and Comment Feed
remove_action( 'wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
remove_action( 'wp_head', 'index_rel_link' ); // index link
remove_action( 'wp_head', 'parent_post_rel_link', 10); // prev link
remove_action( 'wp_head', 'start_post_rel_link', 10); // start link
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10); // Display relational links for the posts adjacent to the current post.
remove_action( 'wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action( 'wp_head', 'locale_stylesheet' );
remove_action( 'wp_head', 'noindex' );
remove_action( 'wp_head', 'wp_print_styles' );
remove_action( 'wp_head', 'wp_print_head_scripts' );
remove_action( 'wp_head', 'rel_canonical' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
//add_action('wp_head', 'wp_enqueue_scripts', 99);


$HTMLResources = get_template_directory_uri() . "/templates-dps/HTMLResources";


/* Register all scripts and all versions */
wp_register_script(
	'jQuery', // NAME
	"$HTMLResources/js/jquery/jquery-1.10.2.min.js", // LOCATION IN HTMLRESOURCES
	array(),  // ARRAY OF DEPENDENCIES
	'2.0.3', // version
	true    // should it be in the footer?
);

wp_register_script(
	'Bootstrap', // NAME
	"$HTMLResources/js/bootstrap/bootstrap-3.0.2.min.js", // LOCATION IN HTMLRESOURCES
	array( 'jQuery' ),  // ARRAY OF DEPENDENCIES
	'3.0.2', // version
	true    // should it be in the footer?
);

wp_register_script(
	'Main', // NAME
	"$HTMLResources/js/main.1.0.js", // LOCATION IN HTMLRESOURCES
	array( 'jQuery', 'Bootstrap' ),  // ARRAY OF DEPENDENCIES
	'1.0', // version
	true    // should it be in the footer?
);

wp_register_script(
	'Modernizr', // NAME
	"$HTMLResources/js/modernizr/modernizr-2.6.2.min.js", // LOCATION IN HTMLRESOURCES
	array( 'jQuery', 'Bootstrap' ),  // ARRAY OF DEPENDENCIES
	'2.6.3', // version
	true    // should it be in the footer?
);


/* Register all styles and all versions */
wp_register_style(
    'FontAwesome', // name
	"$HTMLResources/css/font-awesome/font-awesome-4.0.3.min.css", // LOCATION IN HTMLRESOURCES
    array(), // dependencies
    '4.0.3', // version
    'all' // media tyle
);

wp_register_style(
    'Bootstrap', // name
	"$HTMLResources/css/bootstrap/bootstrap-3.0.2.min.css", // LOCATION IN HTMLRESOURCES
    array(), // dependencies
    '3.0.2', // version
    'all' // media tyle
);

wp_register_style(
    'Stylesheet', // name
	"$HTMLResources/css/style-1.0.css", // LOCATION IN HTMLRESOURCES
    array(), // dependencies
    '1.0', // version
    'all' // media tyle
);
    


?>