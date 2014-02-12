<?php
/**
 * DPSFolioProducer\Commands\GetArticlesMetadata class
 */
namespace DPSFolioProducer\Commands;

/**
 * API command for retrieving article metadata from a folio
 *
 * @category AdobeDPS
 * @package  DPSFolioProducer
 * @author   Jonathan Knapp <jon@coffeeandcode.com>
 * @author   The Brothers Mueller <thebrothersmueller@smny.us>
 * @license  https://github.com/CoffeeAndCode/folio-producer-api/blob/master/LICENSE MIT
 * @version  1.0.0
 * @link     https://github.com/CoffeeAndCode/folio-producer-api
 */
class GetArticlesMetadata extends Command
{
    /**
     * Array of options that are required to make the request
     *
     * @var array
     */
    protected $requiredOptions = array('folio_id');

    /**
     * Execute the command
     *
     * @return HTTPRequest Returns a HTTPRequest object from the API call
     */
    public function execute()
    {
        $folioID = $this->options['folio_id'];

        $data = $this->options;
        unset($data['folio_id']);

        $options = array('type' => 'get');

        if (!empty($data)) {
            $options['data'] = json_encode($data);
        };

        $request = new \DPSFolioProducer\APIRequest('folios/'.$folioID.'/articles', $this->config, $options);

        return $request->run();
    }
}
