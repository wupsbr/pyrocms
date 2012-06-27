<?php

class MY_Controller_test extends PyroCMS_TestCase {

	public function set_up()
	{
		$this->prepare();
	}

	public function test_construct_404()
	{
		// Expect 404
		$this->setExpectedException(
			'RuntimeException',
			'CI Error: 404'
			);

		$my_controller = new MY_Controller();
	}

	public function test_construct_success()
	{
		$this->enable_modules = TRUE;

		$my_controller = new MY_Controller();
	}

	public function test_function_ci()
	{
		$ci = ci();

		$this->assertInstanceOf('PyroCMS_TestCase', $ci);
	}
}