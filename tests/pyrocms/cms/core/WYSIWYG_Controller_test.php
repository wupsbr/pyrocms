<?php

class WYSIWYG_Controller_test extends PyroCMS_TestCase {

	public function set_up()
	{
		$this->prepare();
	}

	public function test_constructor()
	{
		$this->enable_modules = TRUE;

		// Expect Bad Slug Error
		$this->setExpectedException(
			'RuntimeException',
			'CI Error: This site has been set to use an admin theme that does not exist.'
			);

		$wysiwyg_controller = new WYSIWYG_Controller();
	}
}