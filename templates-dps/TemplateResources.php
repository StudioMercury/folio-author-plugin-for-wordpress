<?php 
// TEMPLATE RESOURCES
// This file outlines all assets used in the templates and creates a central places for template options etc
// think of it like a theme's functions.php but for templates

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