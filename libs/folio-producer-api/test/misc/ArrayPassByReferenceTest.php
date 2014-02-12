<?php
class PassByReferenceTest_ObjectA
{
    public $config = null;

    public function __construct(&$config)
    {
        $this->config = $config;
    }

    public function change()
    {
        $this->config['hello'] = 'world';
    }
}

class PassByReferenceTest extends PHPUnit_Framework_TestCase
{
    public function test_instances_receive_same_object()
    {
        $config = array();
        $a = new PassByReferenceTest_ObjectA($config);
        $b = new PassByReferenceTest_ObjectA($config);
        $this->assertEquals($a->config, $b->config);
    }

    public function test_config_does_not_retain_outside_changes()
    {
        $config = array();
        $a = new PassByReferenceTest_ObjectA($config);
        $b = new PassByReferenceTest_ObjectA($config);
        $b->change();
        $this->assertNotEquals(count($a->config), count($b->config));
    }
}
