<?php
class ConfigTest extends PHPUnit_Framework_TestCase
{
    public function test_defaults_to_empty_array()
    {
        $class = new ReflectionClass('DPSFolioProducer\Config');
        $property = $class->getProperty('data');
        $property->setAccessible(true);

        $config = new DPSFolioProducer\Config();
        $this->assertEquals(count($property->getValue($config)), 0);
    }

    public function test_will_save_whitelisted_properties()
    {
        $config = new DPSFolioProducer\Config();
        $config->email = 'email@example.com';
        $this->assertTrue(isset($config->email));
    }

    public function test_will_not_save_nonwhitelisted_properties()
    {
        $config = new DPSFolioProducer\Config();
        $config->non_whitelisted = 'text';
        $this->assertFalse(isset($config->non_whitelisted));
    }

    public function test_can_retrieve_stored_configs()
    {
        $config = new DPSFolioProducer\Config();
        $config->email = 'email@example.com';
        $this->assertEquals($config->email, 'email@example.com');
    }

    public function test_can_overwrite_stored_configs()
    {
        $config = new DPSFolioProducer\Config();
        $config->email = 'email@example.com';
        $config->email = 'new.email@example.com';
        $this->assertEquals($config->email, 'new.email@example.com');
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Undefined index: hello
     */
    public function test_retrieving_unset_property_throws_exception()
    {
        $config = new DPSFolioProducer\Config();
        $this->assertTrue($config->hello);
    }

    public function test_initalize_config_with_valid_properties()
    {
        $config = new DPSFolioProducer\Config(array(
            'email' => 'email@example.com',
            'password' => 'pass'
        ));
        $this->assertEquals($config->email, 'email@example.com');
        $this->assertEquals($config->password, 'pass');
    }

    public function test_initalize_config_with_invalid_properties()
    {
        $config = new DPSFolioProducer\Config(array(
            'another' => 'property',
            'non_whitelisted' => 'hello'
        ));
        $this->assertFalse(isset($config->non_whitelisted));
        $this->assertFalse(isset($config->another));
    }

    public function test_initalize_config_with_mixed_properties()
    {
        $config = new DPSFolioProducer\Config(array(
            'another' => 'property',
            'email' => 'email@example.com',
            'non_whitelisted' => 'hello'
        ));
        $this->assertFalse(isset($config->non_whitelisted));
        $this->assertFalse(isset($config->another));
        $this->assertEquals($config->email, 'email@example.com');
    }

    public function test_isset_returns_true_for_existing_value()
    {
        $config = new DPSFolioProducer\Config(array(
            'email' => 'email@example.com'
        ));
        $this->assertTrue(isset($config->email));
    }

    public function test_isset_returns_false_for_nonexistant_value()
    {
        $config = new DPSFolioProducer\Config();
        $this->assertFalse(isset($config->email));
    }

    public function test_reset_clears_all_synched_properties()
    {
        $config = new DPSFolioProducer\Config(array(
            'email' => 'email@example.com',
            'ticket' => '1234abcd'
        ));
        $this->assertTrue(isset($config->ticket));
        $config->reset();
        $this->assertTrue(isset($config->email));
        $this->assertFalse(isset($config->ticket));
    }
}
