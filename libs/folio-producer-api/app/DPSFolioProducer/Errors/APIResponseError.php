<?php
/**
 * DPSFolioProducer\Errors\APIResponseError class
 */
namespace DPSFolioProducer\Errors;

/**
 * Custom error class for API response errors
 *
 * @category AdobeDPS
 * @package  DPSFolioProducer
 * @author   Jonathan Knapp <jon@coffeeandcode.com>
 * @author   The Brothers Mueller <thebrothersmueller@smny.us>
 * @license  https://github.com/CoffeeAndCode/folio-producer-api/blob/master/LICENSE MIT
 * @version  1.0.0
 * @link     https://github.com/CoffeeAndCode/folio-producer-api
 */
class APIResponseError extends Error
{
    /**
     * The http status code returned with the error
     *
     * @var int
     */
    public $httpStatusCode;

    /**
     * The http status returned from the API server
     *
     * @var int
     */
    public $status;

    /**
     * Error constructor
     *
     * @param string $message        error description from the API server
     * @param string $status         error status from the API server
     * @param int    $httpStatusCode http status code returned with error data
     */
    public function __construct($message, $status, $httpStatusCode)
    {
        parent::__construct($message);
        $this->httpStatusCode = $httpStatusCode;
        $this->status = $status;
    }
}
