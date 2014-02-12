<?php
/**
 * DPSFolioProducer\Errors\Error class
 */
namespace DPSFolioProducer\Errors;

/**
 * Custom error class for misc application failures
 *
 * @category AdobeDPS
 * @package  DPSFolioProducer
 * @author   Jonathan Knapp <jon@coffeeandcode.com>
 * @author   The Brothers Mueller <thebrothersmueller@smny.us>
 * @license  https://github.com/CoffeeAndCode/folio-producer-api/blob/master/LICENSE MIT
 * @version  1.0.0
 * @link     https://github.com/CoffeeAndCode/folio-producer-api
 */
class Error
{
    /**
     * The error that occured
     *
     * @var string
     */
    public $message;

    /**
     * Error constructor
     *
     * @param string $message Error
     */
    public function __construct($message)
    {
        $this->message = $message;
    }
}
