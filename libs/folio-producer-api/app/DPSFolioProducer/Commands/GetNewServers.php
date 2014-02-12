<?php
/**
 * DPSFolioProducer\Commands\GetNewServers class
 */
namespace DPSFolioProducer\Commands;

/**
 * API command for retrieving new api request and download request servers
 *
 * Acrobat.com operates in a cluster environment and requests are handled
 * by multiple servers. If a client receives an HTTP Service Unavailable
 * (503) error response or a request times out, it may be because the
 * addressed server is down or otherwise unavailable. In this case, you
 * can apply the GET method to the session’s resource in an attempt to
 * obtain a new server and download server.
 *
 * No parameters are required, but the request must be authenticated with
 * the current session. This request returns two results in addition to
 * the standard status results.
 *
 * If the HTTP status result is 503 (or the request times out), then it
 * is likely that the entire Acrobat.com service is temporarily unavailable.
 *
 * @category AdobeDPS
 * @package  DPSFolioProducer
 * @author   Jonathan Knapp <jon@coffeeandcode.com>
 * @author   The Brothers Mueller <thebrothersmueller@smny.us>
 * @license  https://github.com/CoffeeAndCode/folio-producer-api/blob/master/LICENSE MIT
 * @version  1.0.0
 * @link     https://github.com/CoffeeAndCode/folio-producer-api
 */
class GetNewServers extends Command
{
    /**
     * Execute the command
     *
     * @return HTTPRequest Returns a HTTPRequest object from the API call
     */
    public function execute()
    {
        $request = new \DPSFolioProducer\APIRequest('sessions', $this->config,
            array(
                'type' => 'get'
            )
        );

        $request = $request->run();
        if ($request && $request->response) {
            $this->config->download_server = $request->response->downloadServer;
            $this->config->request_server = $request->response->server;
        }
        return $request;
    }
}
