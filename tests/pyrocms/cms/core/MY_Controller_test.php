<?php

class MY_Controller_test extends PyroCMS_TestCase {

	public function set_up()
	{
		$this->mock_class(array(
			'MX_Controller',
			'Settings',
			'Asset',
			'Events',
		));

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

	public function test_construct_migration_failed()
	{
		$this->enable_modules = TRUE;

		$this->migration->current = function(){
			return FALSE;
		};

		$this->migration->error_string = function(){
			return 'Something wrong with migration';
		};

		// Expect Migration error
		$this->setExpectedException(
			'RuntimeException',
			'CI Error: Something wrong with migration'
			);

		$my_controller = new MY_Controller();
	}

	public function test_construct_auto_lang_is_listed()
	{
		$this->enable_modules = TRUE;

		Settings::$container['get.site_public_lang'] = function() {
			return 'undefined';
		};

		Settings::$container['get.site_lang'] = function() {
			return 'en';
		};

		$my_controller = new MY_Controller();
	}

	public function test_construct_module_details()
	{
		$this->enable_modules = TRUE;

		$this->module_details = array(
			'enabled' => TRUE,
			'skip_xss' => FALSE,
			'path' => 'some_path',
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