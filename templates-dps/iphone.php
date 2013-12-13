<?php
/*
Article Template Name: 
Description: iPhone article template for the DPS Folio Authoring Plugin.
*/
global $post;
$articleService = DPSFolioAuthor_Article::getInstance();
$article = $articleService->article( $post->ID );
?>
<h6><small>TEMPLATE:</small> iPhone</h6>

<h1><?php echo $article["meta"]["title"];?></h1>
<h3><?php echo $article["meta"]["author"];?></h3>

<p><?php echo $post->post_content;?></p>
