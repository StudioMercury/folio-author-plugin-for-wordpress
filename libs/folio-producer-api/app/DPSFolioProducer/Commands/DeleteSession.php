<?php
/**
 * DPSFolioProducer\Commands\DeleteSession class
 */
namespace DPSFolioProducer\Commands;

/**
 * API command for deleting a session
 *
 * Ends an acrobat.com session. A client should end a session when it is no
 * longer needed. To end a session, apply the DELETE method to the session’s
 * resource. No parameters are required for this call, and no results other
 * than status are returned.
 *
 * Set `cancelToken` to false to allow future use of the passed token. It
 * defaults to `true`.
 *
 * @category AdobeDPS
 * @package  DPSFolioProducer
 * @author   Jonathan Knapp <jon@coffeeandcode.com>
 * @author   The Brothers Mueller <thebrothersmueller@smny.us>
 * @license  https://github.com/CoffeeAndCode/folio-producer-api/blob/master/LICENSE MIT
 * @version  1.0.0
 * @link     https://github.com/CoffeeAndCode/folio-producer-api
 */
class DeleteSession extends Command
{
    /**
     * Execute the command
     *
     * @return HTTPRequest Returns a HTTPRequest object from the API call
     */
    public function execute()
    {
        $data = array(
            'cancelToken' => true
        );
        $data = array_merge($data, $this->options);

        $request = new \DPSFolioProducer\APIRequest('sessions', $this->config,
            array(
                'data' => json_encode($data),
                'type' => 'delete'
            )
        );

        $request = $request->run();
        if ($request && $request->response) {
            $this->config->reset();
        }
        return $request;
    }
}
