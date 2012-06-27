<?php

// This autoloader provide convinient way to working with mock object
// make the test looks natural. This autoloader support cascade file loading as well
// within mocks directory.
//
// Prototype :
//
// $mock_table = new Mock_Libraries_Table(); 			// Will load ./mocks/libraries/table.php
// $mock_database_driver = new Mock_Database_Driver();	// Will load ./mocks/database/driver.php 
// and so on...
function pyrocms_autoload($class) 
{
	$dir = realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR;

	$pyro_cms_core = array(
		'API_Controller', 'Admin_Controller', 'MY_Config', 'MY_Controller',
		'MY_Exceptions', 'MY_Lang', 'MY_Loader', 'MY_Model', 'MY_Router',
		'Public_Controller', 'REST_Controller', 'WYSIWYG_Controller'
	);

	if ($class == 'PyroCMS_TestCase')
	{
		include_once(__DIR__.DIRECTORY_SEPARATOR.'testcase.php');
	}
	elseif (in_array($class, $pyro_cms_core))
	{
		$dir = APPPATH.'core'.DIRECTORY_SEPARATOR;

		$file = (isset($file)) ? $file : $dir.$class.'.php';

		if ( ! file_exists($file))
		{
			$trace = debug_backtrace();

			// If the autoload call came from `class_exists` or `file_exists`, 
			// we skipped and return FALSE
			if ($trace[2]['function'] == 'class_exists' OR $trace[2]['function'] == 'file_exists')
			{
				return FALSE;
			}
			
		    throw new InvalidArgumentException("Unable to load $class.");
		}

		include_once($file);
	}
}