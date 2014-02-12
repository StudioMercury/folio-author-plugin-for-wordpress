<?php
class ObjectPassByReferenceTest_Config
{
    public $config = array();
}

class ObjectPassByReferenceTest_ObjectA
{
    public $config = null;

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function change()
    {
        $this->config->config['hello'] = 'world';
    }
}

class ObjectPassByReferenceTest extends PHPUnit_Framework_TestCase
{
    public function test_instances_receive_same_object()
    {
        $config = new ObjectPassByReferenceTest_Config();
        $a = new ObjectPassByReferenceTest_ObjectA($config);
        $b = new ObjectPassByReferenceTest_ObjectA($config);
        $this->assertEquals(spl_object_hash($a->config), spl_object_hash($b->config));
    }

    public function test_config_does_not_retain_outside_changes()
    {
        $config = new ObjectPassByReferenceTest_Config();
        $a = new ObjectPassByReferenceTest_ObjectA($config);
        $b = new ObjectPassByReferenceTest_ObjectA($a->config);
        $b->change();
        $this->assertEquals(count($a->config), count($b->config));
    }
}
