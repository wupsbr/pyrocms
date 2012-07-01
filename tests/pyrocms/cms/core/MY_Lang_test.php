<?php

class MY_Lang_test extends PyroCMS_TestCase {

	public function test_lang_line()
	{
		// Mock Modules environment
		pyro_class('MX_Lang');

		$my_lang = new MY_Lang();

		// Integrity test
		$this->assertInstanceOf('MX_Lang', $my_lang);
		$this->assertObjectHasAttribute('languange', $my_lang);

		// Line test
		$this->assertFalse($my_lang->line('undefined'));
	}

}