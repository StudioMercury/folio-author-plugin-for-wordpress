<?php
/**
 * DPSFolioProducer\Commands\UploadHTMLResources class
 */
namespace DPSFolioProducer\Commands;

/**
 * API command for uploading HTMLResource files to a folio
 *
 * @category AdobeDPS
 * @package  DPSFolioProducer
 * @author   Jonathan Knapp <jon@coffeeandcode.com>
 * @author   The Brothers Mueller <thebrothersmueller@smny.us>
 * @license  https://github.com/CoffeeAndCode/folio-producer-api/blob/master/LICENSE MIT
 * @version  1.0.0
 * @link     https://github.com/CoffeeAndCode/folio-producer-api
 */
class UploadHTMLResources extends Command
{
    /**
     * Array of options that are required to make the request
     *
     * @var array
     */
    protected $requiredOptions = array('filepath', 'folio_id');

    /**
     * Execute the command
     *
     * @return HTTPRequest Returns a HTTPRequest object from the API call
     */
    public function execute()
    {
        $filepath = $this->options['filepath'];
        $folioID = $this->options['folio_id'];

        $request = new \DPSFolioProducer\APIRequest('folios/'.$folioID.'/htmlresources', $this->config,
            array(
                'file' => $filepath,
                'type' => 'post'
            )
        );

        return $request->run();
    }
}
