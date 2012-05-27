<?php

class Setup_test extends PHPUnit_Framework_TestCase {
	
	function test_bootstrap_constants()
	{
		$this->assertTrue(defined('PROJECT_BASE'));
		$this->assertTrue(defined('BASEPATH'));
		$this->assertTrue(defined('APPPATH'));
		$this->assertTrue(defined('VIEWPATH'));
	}

	function test_pyro_constants()
	{
		$this->assertTrue(defined('PYRO_DEVELOPMENT'));
		$this->assertTrue(defined('PYRO_STAGING'));
		$this->assertTrue(defined('PYRO_PRODUCTION'));
		$this->assertTrue(defined('SITE_DOMAIN'));
		$this->assertTrue(defined('ADDON_FOLDER'));
		$this->assertTrue(defined('SITE_REF'));
		$this->assertTrue(defined('UPLOAD_PATH'));
		$this->assertTrue(defined('ADDONPATH'));
		$this->assertTrue(defined('SHARED_ADDONPATH'));
	}
	
}