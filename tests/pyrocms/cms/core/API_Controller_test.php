<?php

class API_Controller_test extends PyroCMS_TestCase {

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

	public function test_constructor_api_enabled()
	{
		$this->enable_modules = TRUE;
		$this->enable_restful = TRUE;

		$api_controller = new API_Controller();
	}

	public function test_constructor_api_disabled()
	{
		$this->enable_modules = TRUE;
		$this->enable_restful = TRUE;
		Settings::$container['get'] = function() {
			return FALSE;
		};

		$api_controller = new API_Controller();
	}
}