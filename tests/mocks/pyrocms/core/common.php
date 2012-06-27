<?php

// Set up the global CI functions in their most minimal core representation
// and minimal PyroCMS environments representation

if ( ! function_exists('get_instance'))
{
	function &get_instance() 
	{
		$test = PyroCMS_TestCase::instance();
		$instance = (empty($test)) ? new stdClass() : $test;
		return $instance;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('get_config'))
{
	function &get_config() {
		$test = PyroCMS_TestCase::instance();
		$config = (empty($test)) ? array() : $test->ci_get_config();
			
		return $config;
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('redirect'))
{
	function redirect($url) {
		throw new DomainException('CI Redirect: '.$url);
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('lang'))
{
	function lang($item) {
		return $item;
	}
}


// --------------------------------------------------------------------

if ( ! class_exists('MX_Controller'))
{
	class MX_Controller {

		public $benchmark;
		
		function __construct()
		{
			$this->benchmark = new Mock_Core_Benchmark();

			$config = Mock_Database_DB::config(DB_DRIVER);
			$connection = new Mock_Database_DB($config);
			$this->db = Mock_Database_DB::DB($connection->set_dsn(DB_DRIVER), TRUE);

			// Set module flag
			if (isset(PyroCMS_TestCase::instance()->enable_modules))
			{
				$this->module_m = new Pyro_Mock();
				$this->module_m->get = function() {
					return array(
						'enabled' => TRUE,
						'skip_xss' => FALSE,
					);
				};
			}

			// Set REST Properties
			if (isset(PyroCMS_TestCase::instance()->enable_restful))
			{
				$this->request = new Pyro_Mock();
				$this->response = new Pyro_Mock();
				$this->rest = new Pyro_Mock();
			}
		}

		function __get($prop)
		{
			if (isset(PyroCMS_TestCase::instance()->$prop))
			{
				return PyroCMS_TestCase::instance()->$prop;
			}

			return new Pyro_Mock();
		}

	}
}

// --------------------------------------------------------------------

if ( ! class_exists('Pyro_Mock'))
{
	class Pyro_Mock {

		function __get($prop)
		{
			return NULL;
		}
		
		function __call($method, $args)
		{
			if (isset($this->$method) && $this->$method instanceof Closure)
			{
				return call_user_func_array($this->$method, $args);
			}
			else
			{
				return TRUE;
			}
		}

		static function __callStatic($method, $args)
		{
			$my_name = __CLASS__;

			if (isset($my_name::$method) && $my_name::$method instanceof Closure)
			{
				return call_user_func_array(array($my_name, $method), $args);
			}
			else
			{
				return TRUE;
			}
		}
	}

	class Settings extends Pyro_Mock {}

	class Events extends Pyro_Mock {}
}


// EOF