<?php
/**
 * DPSFolioProducer\APIRequest class
 */
namespace DPSFolioProducer;

/**
 * Client class that handles communication with the DPS API
 *
 * @category AdobeDPS
 * @package  DPSFolioProducer
 * @author   Jonathan Knapp <jon@coffeeandcode.com>
 * @author   The Brothers Mueller <thebrothersmueller@smny.us>
 * @license  https://github.com/CoffeeAndCode/folio-producer-api/blob/master/LICENSE MIT
 * @version  1.0.0
 * @link     https://github.com/CoffeeAndCode/folio-producer-api
 */
class APIRequest
{
    /**
     * Array of Error objects from an API request
     *
     * @var array
     */
    private $errors = array();

    /**
     * An associative array of information for making API request
     *
     * @var array
     */
    private $settings;

    /**
     * The full url to make the API request to
     *
     * @var string
     */
    private $url;

    /**
     * Constructor for the object
     *
     * @param string $path    path to use for determining what url to make the
     *                        request to
     * @param array $config   library configuration passed from Client
     * @param array $settings associative array of data to use to construct
     *                        the request
     */
    public function __construct($path, $config, $settings)
    {
        $this->config = $config;
        $this->settings = $settings;
        $this->url = $this->url($path);
    }

    /**
     * Returns an array of errors encountered during the request
     *
     * @return array Array of Error objects
     */
    public function errors() {
        return $this->errors;
    }

    /**
     * Create an HTTP request for the API call and retrieve any errors
     *
     * @return HTTPRequest The request made to the API server
     */
    public function run() {
        $options = array(
            'http' => array(
                'header'  => $this->getHeaders(),
                'method'  => strtoupper($this->settings['type']),
                //'proxy' => 'tcp://localhost:8888',
                'protocol_version' => 1.1
            )
        );

        if (isset($this->settings['data'])) {
            $options['http']['content'] = $this->settings['data'];
        }

        $request = new HTTPRequest($this->url, $options);
        if (isset($this->settings['file'])) {
            $request->run($this->settings['file']);
        } else {
            $request->run();
        }
        $this->errors = $request->errors();
        return $request;
    }

    /**
     * Create an authentication header for API requests
     *
     * @return string The content portion of the Authentication header
     */
    private function authHeader()
    {
        if (isset($this->settings['urlType']) &&
            $this->settings['urlType'] === 'download') {
            return 'AdobeAuth ticket="'.urlencode($this->config->download_ticket).'"';
        }
        return 'AdobeAuth ticket="'.urlencode($this->config->ticket).'"';
    }

    /**
     * Collect an array of HTTP headers to use for the API request
     *
     * @return array Associative array of headers where header type is the key
     */
    private function getHeaders() {
        $headers = array();
        $headerHash = array();

        // use custom headers if specified
        if (isset($this->settings['headers'])) {
            $headerHash = $this->settings['headers'];
        }

        // set Content-Length if 'data' is present and not uploading file
        if (isset($this->settings['data']) && !isset($this->settings['file'])) {
            $headerHash['Content-Length'] = strlen($this->settings['data']);
        }

        if (!isset($this->settings['dataType']) ||
            $this->settings['dataType'] === 'json') {
            $headerHash['Content-Type'] = 'application/json; charset=utf-8';
        }

        if (!isset($headerHash['Authorization'])) {
            $headerHash['Authorization'] = $this->authHeader();
        }
        
        $headerHash['Connection'] = "close";
        
        // collapse array keys with differing case
        $headerHash = array_change_key_case($headerHash);

        // return array of header strings
        foreach (array_keys($headerHash) as $header) {
            $headers[] = ucwords($header).': '.$headerHash[$header];
        }
        return $headers;
    }

    /**
     * Create a full url based on the api path and other request settings
     *
     * @param  string $path A path to use for determining API request url
     * @return string       The full url to make the API request to
     */
    private function url($path) {
        $server = $this->config->api_server;

        // check if attempting to make create session API call
        if (isset($this->settings['headers']) &&
            isset($this->settings['headers']['Authorization'])) {
            $server = $this->config->api_server;
        // update url depending on urlType if set
        } elseif (isset($this->config->download_server) &&
            $this->config->download_server &&
            isset($this->settings['urlType']) &&
            $this->settings['urlType'] === 'download') {
            $server = $this->config->download_server;
        // set to reqest_server for API requests
        } elseif (isset($this->config->request_server) &&
            $this->config->request_server) {
            $server = $this->config->request_server;
        }

        return $server.'/webservices/'.$path;
    }
}
