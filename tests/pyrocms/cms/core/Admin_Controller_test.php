<?php

class Admin_Controller_test extends PyroCMS_TestCase {

	public function set_up()
	{
		$this->prepare();
	}

	public function test_constructor_not_login()
	{
		$this->enable_modules = TRUE;

		// Expect Redirect
		$this->setExpectedException(
			'DomainException',
			'CI Redirect: admin/login'
			);

		$admin_controller = new Admin_Controller();
	}
}