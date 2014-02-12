<?php
/**
 * DPSFolioProducer\ErrorResponse class
 */
namespace DPSFolioProducer;

/**
 * A mostly empty response to mock a HTTPRequest that was never made
 *
 * @category AdobeDPS
 * @package  DPSFolioProducer
 * @author   Jonathan Knapp <jon@coffeeandcode.com>
 * @author   The Brothers Mueller <thebrothersmueller@smny.us>
 * @license  https://github.com/CoffeeAndCode/folio-producer-api/blob/master/LICENSE MIT
 * @version  1.0.0
 * @link     https://github.com/CoffeeAndCode/folio-producer-api
 */
class ErrorResponse
{
    /**
     * Represents HTTP response that was never made, should always be null
     * @var null
     */
    public $reponse = null;

    /**
     * Array of errors that occured when forming the request
     * @var array
     */
    private $errors;

    /**
     * Class constructor that only accepts an array of Error objects
     * @param array $errors array of errors
     */
    public function __construct($errors=array())
    {
        $this->errors = $errors;
    }

    /**
     * Returns the error array
     * @return array the error array
     */
    public function errors()
    {
        return $this->errors;
    }
}
