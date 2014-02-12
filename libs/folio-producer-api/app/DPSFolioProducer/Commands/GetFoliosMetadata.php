<?php
/**
 * DPSFolioProducer\Commands\GetFoliosMetadata class
 */
namespace DPSFolioProducer\Commands;

/**
 * API command for retrieving all folio metadata
 *
 * @category AdobeDPS
 * @package  DPSFolioProducer
 * @author   Jonathan Knapp <jon@coffeeandcode.com>
 * @author   The Brothers Mueller <thebrothersmueller@smny.us>
 * @license  https://github.com/CoffeeAndCode/folio-producer-api/blob/master/LICENSE MIT
 * @version  1.0.0
 * @link     https://github.com/CoffeeAndCode/folio-producer-api
 */
class GetFoliosMetadata extends Command
{
    /**
     * Execute the command
     *
     * @return HTTPRequest Returns a HTTPRequest object from the API call
     */
    public function execute()
    {
        $request = new \DPSFolioProducer\APIRequest('folios', $this->config,
            array(
                'type' => 'get'
            )
        );

        return $request->run();
    }
}
