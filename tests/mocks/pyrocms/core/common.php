<?php

// Set up the global CI functions in their most minimal core representation

if ( ! function_exists('get_instance'))
{
	function &get_instance() 
	{
		$test = CI_TestCase::instance();
		$instance = (empty($test)) ? new stdClass() : $test->ci_instance();
		return $instance;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('get_config'))
{
	function &get_config() {
		$test = CI_TestCase::instance();
		$config = (empty($test)) ? array() : $test->ci_get_config();
			
		return $config;
	}
}


// EOF