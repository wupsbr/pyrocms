<?php

class Public_Controller_test extends PyroCMS_TestCase {

	public function set_up()
	{
		$this->prepare();
	}

	public function test_constructor_503()
	{
		$this->enable_modules = TRUE;

		// Expect 503
		$this->setExpectedException(
			'RuntimeException',
			'CI Error: cms_fatal_error'
			);

		$public_controller = new Public_Controller();
	}
}