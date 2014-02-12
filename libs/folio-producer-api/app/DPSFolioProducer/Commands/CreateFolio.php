<?php
/**
 * DPSFolioProducer\Commands\CreateFolio class
 */
namespace DPSFolioProducer\Commands;

/**
 * API command for creating a new folio
 *
 * @category AdobeDPS
 * @package  DPSFolioProducer
 * @author   Jonathan Knapp <jon@coffeeandcode.com>
 * @author   The Brothers Mueller <thebrothersmueller@smny.us>
 * @license  https://github.com/CoffeeAndCode/folio-producer-api/blob/master/LICENSE MIT
 * @version  1.0.0
 * @link     https://github.com/CoffeeAndCode/folio-producer-api
 */
class CreateFolio extends Command
{
    /**
     * Array of options that are required to make the request
     *
     * @var array
     */
    protected $requiredOptions = array(
        'folioName',
        'folioNumber',
        'magazineTitle',
        'resolutionWidth',
        'resolutionHeight'
    );

    /**
     * Execute the command
     *
     * @return HTTPRequest Returns a HTTPRequest object from the API call
     */
    public function execute()
    {
        $request = new \DPSFolioProducer\APIRequest('folios', $this->config,
            array(
                'data' => json_encode($this->options),
                'type' => 'post'
            )
        );

        return $request->run();
    }
}
