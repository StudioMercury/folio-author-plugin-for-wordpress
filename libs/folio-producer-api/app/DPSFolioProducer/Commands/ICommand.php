<?php
/**
 * DPSFolioProducer\Commands\ICommand interface
 */
namespace DPSFolioProducer\Commands;

/**
 * The interface Command objects must follow
 *
 * @category AdobeDPS
 * @package  DPSFolioProducer
 * @author   Jonathan Knapp <jon@coffeeandcode.com>
 * @author   The Brothers Mueller <thebrothersmueller@smny.us>
 * @license  https://github.com/CoffeeAndCode/folio-producer-api/blob/master/LICENSE MIT
 * @version  1.0.0
 * @link     https://github.com/CoffeeAndCode/folio-producer-api
 */
interface ICommand
{
    /**
     * Interface method for executing the Command class
     */
    public function execute();
}
