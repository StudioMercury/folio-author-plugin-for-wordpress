<?php
/**
 * DPSFolioProducer\Commands\Command abstract class
 */
namespace DPSFolioProducer\Commands;

/**
 * Abstract base class for creation of API Command objects
 *
 * @category AdobeDPS
 * @package  DPSFolioProducer
 * @author   Jonathan Knapp <jon@coffeeandcode.com>
 * @author   The Brothers Mueller <thebrothersmueller@smny.us>
 * @license  https://github.com/CoffeeAndCode/folio-producer-api/blob/master/LICENSE MIT
 * @version  1.0.0
 * @link     https://github.com/CoffeeAndCode/folio-producer-api
 */
abstract class Command implements ICommand
{
    /**
     * Array of Error objects encountered making request
     *
     * @var array
     */
    public $errors = array();

    /**
     * Determines if the Command has been retried
     *
     * @var boolean
     */
    public $is_retry = false;

    /**
     * Stored reference to the library client's configuration
     *
     * @var [type]
     */
    protected $config;

    /**
     * Associative array of options to use to execute the command
     *
     * @var [type]
     */
    protected $options;

    /**
     * Array of options that are required to execute the command
     *
     * @var array
     */
    protected $requiredOptions = array();

    /**
     * Command constructor
     *
     * @param array $config   library client configuration
     * @param array $options  optional data needed to execute the command
     */
    public function __construct($config, $options=array())
    {
        $this->config = $config;
        $this->options = $options;
    }

    /**
     * Runs validations on the Command and populates ValidationErrors
     *
     * @return boolean returns true if there are no ValidationErrors, false otherwise
     */
    public function isValid() {
        foreach ($this->requiredOptions as $requiredOption) {
            if (!isset($this->options[$requiredOption])) {
                $this->errors[] = new \DPSFolioProducer\Errors\ValidationError($requiredOption.' is required.');
            }
        }
        return empty($this->errors);
    }

    /**
     * Retry executing a Command if it hasn't already been retried
     *
     * @return HTTPRequest|null return the retried HTTPRequest or null if it
     *                                 has already been retried
     */
    public function retry() {
        if (!$this->is_retry) {
            $this->is_retry = true;
            return $this->execute();
        }
    }
}
