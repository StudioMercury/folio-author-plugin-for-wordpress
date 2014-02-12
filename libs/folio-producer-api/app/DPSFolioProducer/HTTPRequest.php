<?php
/**
 * DPSFolioProducer\HTTPRequest class
 */
namespace DPSFolioProducer;

/**
 * Makes HTTP requests, collects errors, handles form submissions
 *
 * @category AdobeDPS
 * @package  DPSFolioProducer
 * @author   Jonathan Knapp <jon@coffeeandcode.com>
 * @author   The Brothers Mueller <thebrothersmueller@smny.us>
 * @license  https://github.com/CoffeeAndCode/folio-producer-api/blob/master/LICENSE MIT
 * @version  1.0.0
 * @link     https://github.com/CoffeeAndCode/folio-producer-api
 */
class HTTPRequest
{
    /**
     * Array of options used to make the HTTP request
     *
     * @var array
     */
    public $options = null;

    /**
     * Array of headers captured from the HTTP response
     *
     * @var array
     */
    public $response_headers = array();

    /**
     * Data returned from the HTTP request
     *
     * If the response is json content, it will be returned as an object
     * otherwise it will be returned as a string. If an error is encountered
     * or the call is not made, it will be null.
     *
     * @var object|string|null
     */
    public $response = null;

    /**
     * The full url to make the request to
     *
     * @var string
     */
    public $url = null;

    /**
     * Array of error objects created during HTTP request
     *
     * @var array
     */
    private $errors = array();

    /**
     * Creats the HTTP request object
     *
     * @param string $url    Full url to make request to
     * @param array $options Associative array of options to use in request
     */
    public function __construct($url, $options)
    {
        $this->options = $options;
        $this->url = $url;
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
     * Make the HTTP request, store response info & headers, record any errors
     *
     * @param  string $filename Optional path to a file to send during request
     * @return object|string|null Mixed return type depending on returned content
     *                            type or if an error occured
     */
    public function run($filename=null)
    {
        if ($filename) {
            $this->upload_file($filename);
        }
        $context = stream_context_create($this->options);
        $response = @file_get_contents($this->url, false, $context);
        if ($response === false) {
            $this->errors[] = new \DPSFolioProducer\Errors\Error(isset($php_errormsg) ? $php_errormsg : 'Error retrieving url: '.$this->url);
        }

        if (isset($http_response_header)) {
            $this->response_headers = $http_response_header;
        }

        if ($this->responseContentType() == 'application/json') {
            $this->response = json_decode($response);
            if ($this->response === null) {
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $this->errors[] = new \DPSFolioProducer\Errors\Error(json_last_error());
                }
            }
        } else {
            $this->response = $response;
        }

        if (empty($this->response)) {
            $this->errors[] = new \DPSFolioProducer\Errors\Error('There was no API response.');

        } elseif($this->get_response_code() !== 200 ||
            !$this->isStatusOK()) {

            $this->errors[] = new \DPSFolioProducer\Errors\APIResponseError(
                $this->response->errorDetail,
                $this->response->status,
                $this->get_response_code()
            );
        }

        // clear out the response if an error occured
        if (!empty($this->errors)) {
            $this->response = null;
        }

        return $this->response;
    }

    /**
     * Retrieve the HTTP response code from last request
     *
     * @return int|null Returns response code if found, otherwise null
     */
    public function get_response_code()
    {
        $response_code = null;
        if ($this->response_headers && count($this->response_headers)) {
            preg_match('/^(([a-zA-Z]+)\/([\d\.]+))\s([\d\.]+)\s(.*)$/', $this->response_headers[0], $matches);
            if ($matches) {
                $response_code = intval($matches[4]);
            }
        }

        return $response_code;
    }

    /**
     * Mange the request content and headers for handling a multi-part form
     * submission
     *
     * @param  string $filepath path to file to upload
     * @return void
     */
    public function upload_file($filepath)
    {
        if (!is_file($filepath)) {
            throw new \Exception('File cannot be uploaded: '.$filepath);
        }

        $filename = pathinfo($filepath, PATHINFO_BASENAME);
        $data = '';
        $handle = fopen($filepath, 'rb');
        fseek($handle, 0);
        $binary = fread($handle, filesize($filepath));
        fclose($handle);

        $separator = md5(microtime());

        $eol = "\r\n";
        $data = '';
        $data .=  '--' . $separator . $eol;

        if (array_key_exists('content', $this->options['http'])) {
            $data .= 'Content-Disposition: form-data; name="request"' . $eol;
            $data .= 'Content-Type: text/plain; charset=UTF-8' . $eol;
            $data .= 'Content-Transfer-Encoding: 8bit' . $eol . $eol;
            $data .= $this->options['http']['content'] . $eol;
            $data .=  '--' . $separator . $eol;
        }

        $data .= 'Content-Disposition: form-data; name=""; filename="' . $filename . '"' . $eol;
        $data .= 'Content-Transfer-Encoding: binary' . $eol . $eol;
        $data .= $binary . $eol;
        $data .= '--' . $separator . '--' . $eol;

        $this->replace_content_type_header('Content-Type: multipart/form-data; boundary='.$separator);
        $this->options['http']['content'] = $data;
    }

    /**
     * Replace the Content-Type header with newly specified header
     *
     * @param  string $new_header new header to use for Content-Type
     * @return void
     */
    private function replace_content_type_header($new_header)
    {
        $this->options['http']['header'] = array_map(function ($header) use ($new_header) {
            if (preg_match('/^Content\-Type:/i', $header)) {
                return $new_header;
            }
            return $header;
        }, $this->options['http']['header']);
    }

    /**
     * Retrieve the Content-Type of the http response
     *
     * @return string|null returns the content type if found, otherwise null
     */
    private function responseContentType()
    {
        $contentType = null;
        if ($this->response_headers && count($this->response_headers)) {
            foreach($this->response_headers as $header) {
                if (preg_match('/^Content\-Type:\s*(.*);/i', $header, $matches)) {
                    $contentType = $matches[1];
                }
            }
        }
        return $contentType;
    }

    /**
     * Determine if HTTP response returned an 'ok'
     *
     * @return boolean Return true if status is found and 'ok', otherwise true
     */
    private function isStatusOK()
    {
        if (is_object($this->response) && property_exists($this->response, 'status')) {
            if ($this->response->status === 'ok') {
                return true;
            } else {
                return false;
            }
        }
        return true;
    }
}
