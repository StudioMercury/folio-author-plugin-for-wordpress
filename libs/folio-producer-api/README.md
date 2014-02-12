# Folio Producer API
[![Build Status](https://magnum.travis-ci.com/CoffeeAndCode/folio-producer-api.png?token=PgRq1y9q1wqEUV2w6sXq&branch=master)](https://magnum.travis-ci.com/CoffeeAndCode/folio-producer-api)

This is a PHP library for wrapping the API calls to the Adobe Digital
Publishing Suite Folio Producer API. The goal is to make your life easier and
encapsulte the nitty gritty details. Specifically it will:

* automatically handle authentication when making API requests
* cache authentication information for making subsequent calls to the API
* automatically update tokens and API servers when provided by API responses
* reauthenticate and retry failed API requests if they return InvalidTicket errors

---

This project is a conglomeration between [Coffee and Code](http://www.coffeeandcode.com/)
and [Studio Mercury](http://www.smny.us/), who have created many customized and
engaging experiences together on the DPS platform for a wide variety of clients.

If you're interested in working with us on your own projects, you can reach out
to us at [thebrothersmueller@smny.us](mailto:thebrothersmueller@smny.us) or
[jon@coffeeandcode.com](mailto:jon@coffeeandcode.com).


## Requirements

This project requires PHP 5.3.3+.

It uses [PHP namespaces](http://www.php.net/manual/en/language.namespaces.rationale.php)
which require version 5.3+. It is highly recommended to use
[PHP Sessions](http://www.php.net/manual/en/book.session.php) as
well so that we can re-use API authentication tokens instead of requesting
a new one for each page request. By simply calling `session_start()`, this
library will cache authentication information in sessions and sync changes
accordingly.


## Usage

The following code initializes your PHP session to re-use API authentication,
loads your API configuration, and initializes the API client for subsequent
calls.

    <?php
    session_start();

    require 'vendor/autoload.php';
    include 'config.php';

    if (!isset($config)) { user_error('Missing configuration.'); }
    $client = new DPSFolioProducer\Client($config);

You can then make API calls by issuing one of the following requests like
the following retrieval of all folio metadata.

    $request = $client->execute('get_folios_metadata');

    // $request->response will be non-null if successful
    if ($request->response) {
        foreach ($request->response->folios as $folio) {
            var_dump($folio);
            echo '<hr />';
        }
    } else {
        // iterate through all errors, including validation errors done
        // without issuing the actual API request
        foreach ($request->errors() as $error) {
            echo $error->message;
        }
    }


### Create Session

This will authenticate with DPS and use your credentials on subsequent requests.

**Note:** You should not have to make this call explicitly as it is
automatically called by the library when needed before executing another API
call.

    $request = $client->execute('create_session');


### Delete Session

This will clear out stored credentials and any used session variables if the
API call is successsful.

    $request = $client->execute('delete_session');


### Get New Servers

This will update stored credentials to use the returned api and download servers
on subsequent API requests.

    $request = $client->execute('get_new_servers');


### Get All Folio Metadata

    $request = $client->execute('get_folios_metadata');

    echo '<h1>Folios</h1>';
    if ($request->response) {
        foreach ($request->response->folios as $folio) {
            print_r($folio);
            echo '<hr />';
        }
    }


### Get Specific Folio Metadata

    $request = $client->execute('get_folio_metadata', array(
        'folio_id' => $folio_id
    ));


### Create a Folio

    $options = array(
        'folioName' => 'Test Folio Name',
        'folioNumber' => 'folio-'.time(),
        'magazineTitle' => 'Magazine Title',
        'resolutionHeight' => 240,
        'resolutionWidth' => 240
    );
    $request = $client->execute('create_folio', $options);


### Delete a Folio

    $request = $client->execute('delete_folio', array(
        'folio_id' => $folio_id
    ));


### Duplicate a Folio

    $request = $client->execute('duplicate_folio', array(
        'folio_id' => $folio_id
    ));


### Update a Folio

    $request = $client->execute('update_folio', array(
        'folio_id' => $folio_id,
        'folioName' => 'Updated Folio Name'
    ));


### Upload Folio Preview Image

    $request = $client->execute('upload_folio_preview_image', array(
        'filepath' => 'images/folio image.jpg',
        'folio_id' => $folio_id,
        'orientation' => 'landscape' // or 'portrait'
    ));


### Download Folio Preview Image

    $request = $client->execute('download_folio_preview_image', array(
        'folio_id' => $folio_id,
        'orientation' => 'landscape' // or 'portrait'
    ));


### Delete Folio Preview Image

    $request = $client->execute('delete_folio_preview_image', array(
        'folio_id' => $folio_id,
        'orientation' => 'landscape' // or 'portrait'
    ));


### Upload HTML Resources

    $request = $client->execute('upload_html_resources', array(
        'filepath' => 'data/HTMLResources.zip',
        'folio_id' => $folio_id
    ));


### Delete HTML Resources

    $request = $client->execute('delete_html_resources', array(
        'folio_id' => $folio_id
    ));


### Create an Article

    $request = $client->execute('create_article', array(
        'filepath' => 'data/example.folio',
        'folio_id' => $folio_id
    ));


### Delete an Article

    $request = $client->execute('delete_article', array(
        'article_id' => $article_id,
        'folio_id' => $folio_id
    ));


### Update Article Metadata

    $request = $client->execute('update_article_metadata', array(
        'article_id' => $article_id,
        'folio_id' => $folio_id,
        'description' => 'My new description.'
    ));


### Get All Article Metadata

    $request = $client->execute('get_articles_metadata', array(
        'folio_id' => $folio_id
    ));


### Errors

There are three types of errors that may be returned from a request. They
are `Error`, `APIResponseError`, and `ValidationError`. They all have a
`message` property that describes the error.

`APIResponseError` also contains the `status` and `httpStatusCode` from
the data returned by Adobe's DPS server.


## Testing

PHPUnit is brought into the project with Composer which requires PHP 5.3.2+ to run.

1. change to directory of project
2. install Composer - http://getcomposer.org/doc/00-intro.md
3. install Composer dependencies with `php composer.phar install`
4. run unit tests with `vendor/bin/phpunit test/`

Note: Test files must end in `*Test.php` and test method names must start with `test*`.


#### Test Coverage

If you have xdebug installed, you can create an html code coverage report by running:

    vendor/bin/phpunit --coverage-html ./coverage
