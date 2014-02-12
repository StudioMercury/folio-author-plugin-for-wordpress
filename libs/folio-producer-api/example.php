<?php
session_start();

require 'vendor/autoload.php';
include 'config.php';

// print session variables
foreach ($_SESSION as $key => $value) {
    echo $key.': '.print_r($value, true).'<br />';
}
echo '<hr />';

if (!isset($config)) { user_error('Missing configuration.'); }
$client = new DPSFolioProducer\Client($config);


// Get Folios Metadata
$request = $client->execute('get_folios_metadata');
var_dump($request);
var_dump($request->options['http']['header']);
echo '<hr />';

echo '<h1>Folios</h1>';
foreach ($request->response->folios as $folio) {
    print_r($folio);
    echo '<hr />';
}


// Create Session
/*
$request = $client->execute('create_session');
var_dump($request);
echo '<hr />';
*/


// Delete Session
/*
$request = $client->execute('delete_session');
var_dump($request);
echo '<hr />';
*/


// Get New Servers
/*
$request = $client->execute('get_new_servers');
var_dump($request);
echo '<hr />';
*/


// Get Folio Metadata
/*
$request = $client->execute('get_folio_metadata', array('folio_id' => $folio_id));
var_dump($request);
echo '<hr />';
*/


// Upload HTML Resources
/*
$request = $client->execute('upload_html_resources', array(
    'filepath' => 'HTMLResources.zip',
    'folio_id' => $folio_id
));
var_dump($request);
echo '<hr />';
*/


// Delete HTML Resources
/*
$request = $client->execute('delete_html_resources', array(
    'folio_id' => $folio_id
));
var_dump($request);
echo '<hr />';
*/

// Upload Folio Preview Image
/*
$request = $client->execute('upload_folio_preview_image', array(
    'filepath' => 'image.png',
    'folio_id' => $folio_id,
    'orientation' => 'landscape'
));
var_dump($request);
echo '<hr />';
*/


// Delete Folio Preview Image
/*
$request = $client->execute('delete_folio_preview_image', array(
    'folio_id' => $folio_id,
    'orientation' => 'landscape'
));
var_dump($request);
echo '<hr />';
*/


// Download Folio Image
/*
$request = $client->execute('download_folio_preview_image', array(
    'folio_id' => $folio_id,
    'orientation' => 'landscape'
));
var_dump($request);
echo '<hr />';
*/


// Create Folio
/*
$options = array(
    'folioName' => 'Jons Folio',
    'folioNumber' => 'folio-'.time(),
    'magazineTitle' => 'Magazine Title',
    'resolutionHeight' => 1024,
    'resolutionWidth' => 768
);
$request = $client->execute('create_folio', $options);
$folio_id = $request->response->folioID;
var_dump($request);
echo '<hr />';
*/


// Duplicate Folio
/*
$request = $client->execute('duplicate_folio', array('folio_id' => $folio_id));
var_dump($request);
echo '<hr />';
*/


// Update Folio
/*
$request = $client->execute('update_folio', array(
    'folio_id' => $folio_id,
    'folioName' => 'Updated AGAIN Folio Name'
));
var_dump($request);
echo '<hr />';
*/


// Delete Folio
/*
$request = $client->execute('delete_folio', array('folio_id' => $folio_id));
var_dump($request);
echo '<hr />';
*/


// Create Article
/*
$request = $client->execute('create_article', array(
    'filepath' => 'one.folio',
    'folio_id' => $folio_id
));
var_dump($request->options['http']['header']);
var_dump($request);
$article_id = $request->response->articleInfo->id;
echo '<hr />';
*/


// Get Articles Metadata
/*
$request = $client->execute('get_articles_metadata', array(
    'folio_id' => $folio_id
));
echo '<h1>Articles</h1>';
foreach ($request->response->articles as $article) {
    print_r($article);
    echo '<hr />';
}
*/


// Update Article Metadata
/*
$request = $client->execute('update_article_metadata', array(
    'article_id' => $article_id,
    'folio_id' => $folio_id,
    'description' => 'My new description.'
));
var_dump($request);
echo '<hr />';
*/


// Delete Article
/*
$request = $client->execute('delete_article', array(
    'article_id' => $article_id,
    'folio_id' => $folio_id
));
var_dump($request);
*/
