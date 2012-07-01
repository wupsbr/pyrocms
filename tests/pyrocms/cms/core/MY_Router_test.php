<?php

class MY_Router_test extends PyroCMS_TestCase {

	public function test_lang_line()
	{
		// Mock Modules environment
		pyro_class('MX_Router');

		$my_router = new MY_Router();

		// Integrity test
		$this->assertInstanceOf('MX_Router', $my_router);
	}

}