<?php
/**
 * DPSFolioProducer\Commands\DownloadFolioPreviewImage class
 */
namespace DPSFolioProducer\Commands;

/**
 * API command for downloading a folio preview image
 *
 * @category AdobeDPS
 * @package  DPSFolioProducer
 * @author   Jonathan Knapp <jon@coffeeandcode.com>
 * @author   The Brothers Mueller <thebrothersmueller@smny.us>
 * @license  https://github.com/CoffeeAndCode/folio-producer-api/blob/master/LICENSE MIT
 * @version  1.0.0
 * @link     https://github.com/CoffeeAndCode/folio-producer-api
 */
class DownloadFolioPreviewImage extends Command
{
    /**
     * Array of options that are required to make the request
     *
     * @var array
     */
    protected $requiredOptions = array('folio_id', 'orientation');

    /**
     * Execute the command
     *
     * @return HTTPRequest Returns a HTTPRequest object from the API call
     */
    public function execute()
    {
        $folioID = $this->options['folio_id'];
        $orientation = $this->options['orientation'];
        $request = new \DPSFolioProducer\APIRequest('folios/'.$folioID.'/previews/'.$orientation, $this->config,
            array(
                'type' => 'get',
                'urlType' => 'download'
            )
        );

        return $request->run();
    }
}
