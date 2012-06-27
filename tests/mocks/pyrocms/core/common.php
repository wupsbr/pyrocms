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

if ( ! function_exists('site_url'))
{
	function site_url() {
		return 'http://localhost';
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('uri_string'))
{
	function uri_string() {
		return 'blog';
	}
}

// --------------------------------------------------------------------

if ( ! function_exists('anchor'))
{
	function anchor($uri_segments = '', $text = '', $attributes = array()) {
		return $text;
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

if ( ! function_exists('module_exists'))
{
	function module_exists($module) {
		return (bool) isset(PyroCMS_TestCase::instance()->$module);
	}
}

// --------------------------------------------------------------------

if ( ! class_exists('Pyro_Mock'))
{
	class Pyro_Mock {

		public static $container = array();

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
			$first_param = current($args);

			if (isset($my_name::$container[$method.'.'.$first_param]) 
			    && $my_name::$container[$method.'.'.$first_param] instanceof Closure)
			{
				return call_user_func_array($my_name::$container[$method.'.'.$first_param], $args);
			}
			elseif (isset($my_name::$container[$method]) && $my_name::$container[$method] instanceof Closure)
			{
				return call_user_func_array($my_name::$container[$method], $args);
			}
			else
			{
				return TRUE;
			}
		}
	}

}

// --------------------------------------------------------------------

if ( ! function_exists('pyro_class'))
{
	function pyro_class($name = '')
	{
		if ($name == 'MX_Controller' && ! class_exists('MX_Controller'))
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
						$module_details = isset(PyroCMS_TestCase::instance()->module_details) 
						                  ? PyroCMS_TestCase::instance()->module_details : array();

						$this->module_m = new Pyro_Mock();
						$this->module_m->get = function() use($module_details) {
							return ( ! empty($module_details)) ? $module_details : array(
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
		elseif ($name == 'Settings' && ! class_exists('Settings'))
		{
			class Settings extends Pyro_Mock {}
		}
		elseif ($name == 'Events' && ! class_exists('Events'))
		{
			class Events extends Pyro_Mock {}
		}
		elseif ($name == 'Asset' && ! class_exists('Asset'))
		{
			class Asset extends Pyro_Mock {}
		}
	}
}

// EOF