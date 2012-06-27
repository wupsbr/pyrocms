<?php

class REST_Controller_test extends PyroCMS_TestCase {

	public function set_up()
	{
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
}