<?php
use \Mockery as m;

/**
 * Inherit from class we are testing so we can reveal properties
 * in tests that are not accessible in the real class.
 */
class ClientTestWrapper extends DPSFolioProducer\Client
{
    public $config;
    public $download_ticket;
    public $request_server;
    public $session;
    public $ticket;
    public function _getCommandClass($command_name)
    {
        return 'ClientTestCommand';
    }
}

class ClientTestCommand extends DPSFolioProducer\Commands\Command
{
    public function execute() {}
}

class ClientTest extends PHPUnit_Framework_TestCase
{
    private $createSessionResponse =
<<<'EOT'
    {
        "response": {
            "downloadServer": "http://example.com/downloads",
            "downloadTicket": "abcd",
            "server": "http://example.com",
            "status": "ok",
            "ticket": "1234"
        }
    }
EOT;

    private $test_config = array(
        'api_server' => 'https://dpsapi2.acrobat.com',
        'company' => '',
        'consumer_key' => '',
        'consumer_secret' => '',
        'email' => '',
        'password' => '',
        'session_props' => ''
    );

    public function tearDown()
    {
        m::close();
    }

    protected static function unlockMethod($name) {
        $class = new ReflectionClass('MyClass');
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Missing argument 1 for DPSFolioProducer\Client::__construct()
     */
    public function test_throws_exception_if_config_not_passed()
    {
        $client = new ClientTestWrapper();
        $this->assertEquals($client->config, array());
    }

    public function test_stores_passed_config()
    {
        $client = new ClientTestWrapper($this->test_config);
        foreach ($this->test_config as $key => $value) {
            $this->assertEquals($client->config->$key, $this->test_config[$key]);
        }
    }

    public function test_initializes_with_null_download_ticket()
    {
        $client = new ClientTestWrapper($this->test_config);
        $this->assertEquals($client->download_ticket, null);
    }

    public function test_initializes_with_null_request_server()
    {
        $client = new ClientTestWrapper($this->test_config);
        $this->assertEquals($client->request_server, null);
    }

    public function test_initializes_with_null_ticket()
    {
        $client = new ClientTestWrapper($this->test_config);
        $this->assertEquals($client->ticket, null);
    }
}
