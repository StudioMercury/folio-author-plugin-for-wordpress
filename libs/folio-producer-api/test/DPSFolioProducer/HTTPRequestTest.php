<?php
class HTTPRequestTest extends PHPUnit_Framework_TestCase
{
    private $options = array();
    private $url = 'example.com';

    public function test_isStatusOK_is_true_if_status_ok()
    {
        $method = $this->getMethod('isStatusOK');
        $request = new DPSFolioProducer\HTTPRequest($this->url, $this->options);
        $request->response = (object) array('status' => 'ok');
        $this->assertTrue($method->invoke($request));
    }

    public function test_isStatusOK_is_false_if_status_not_ok()
    {
        $method = $this->getMethod('isStatusOK');
        $request = new DPSFolioProducer\HTTPRequest($this->url, $this->options);
        $request->response = (object) array('status' => true);
        $this->assertFalse($method->invoke($request));
    }

    public function test_isStatusOK_is_true_for_nonobjet_response()
    {
        $method = $this->getMethod('isStatusOK');
        $request = new DPSFolioProducer\HTTPRequest($this->url, $this->options);
        $request->response = 'string';
        $this->assertTrue($method->invoke($request));
    }

    private function getMethod($methodName)
    {
        $class = new ReflectionClass('DPSFolioProducer\HTTPRequest');
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);
        return $method;
    }
}
