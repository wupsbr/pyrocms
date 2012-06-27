<?php

class REST_Controller_test extends PyroCMS_TestCase {

	public function set_up()
	{
		$this->mock_class(array(
			'MX_Controller',
			'Settings',
			'Asset',
			'Events',
		));

		$this->prepare();

		$this->uri = new Pyro_Mock();
		$this->uri->ruri_to_assoc = function() {
			return array();
		};
	}

	public function test_constructor()
	{
		$this->enable_modules = TRUE;
		$this->enable_restful = TRUE;

		$rest_controller = new REST_Controller();
	}

	public function test_remap()
	{
		$this->enable_modules = TRUE;
		$this->enable_restful = TRUE;

		$rest_controller = new Mock_PyroCMS_Core_Rest();
		$rest_controller->get_1 = function() {
			throw new InvalidArgumentException('You are not allowed to access /get in this server');
		};

		// Expect InvalidArgumentException
		$this->setExpectedException(
			'InvalidArgumentException',
			'You are not allowed to access /get in this server'
			);

		$rest_controller->_remap('get', array());
	}

	public function test_detect_api_key_fail()
	{
		$this->enable_modules = TRUE;
		$this->enable_restful = TRUE;

		$rest_controller = new Mock_PyroCMS_Core_Rest();
		
		$this->assertFalse($rest_controller->detect_api_key());
	}

	public function test_detect_api_key_success()
	{
		$this->enable_modules = TRUE;
		$this->enable_restful = TRUE;

		$rest_controller = new Mock_PyroCMS_Core_Rest();
		
		$this->assertTrue($rest_controller->detect_api_key(TRUE));
	}
}