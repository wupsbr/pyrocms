<?php

class Mock_PyroCMS_Core_Rest extends REST_Controller {

	public function __construct($enable_rest_key = FALSE)
	{
		if ($enable_rest_key)
		{
			// Mock Loader
			$db = $this->_enable_db();
			$this->load = new Pyro_Mock();
			$this->load->database = function() use($db) {
				return $db;
			};
		}

		parent::__construct();
	}

	public function detect_api_key($enable = FALSE, $params = array())
	{
		if ($enable)
		{
			$this->rest->db = $this->_enable_db($params);
		}
		else
		{
			// Mock Input to false
			$input =& $this->input;
			$input->server = function(){
				return FALSE;
			};

			$this->input = $input;
		}

		return $this->_detect_api_key();
	}

	public function __set($property, $values)
	{
		if (preg_match('/^setExpected.*$/', $property, $match) && count($match) === 1)
		{
			$original_property = strtolower(substr(current($match), 11));
			$this->$original_property = $values;
		}
		else
		{
			$this->$property = $values;
		}

		return $this;
	}

	protected function _enable_db($params = array())
	{
		// Mock Database behaviour
		$db = new Pyro_Mock();

		$db->where = function() use($db) {
			return $db;
		};

		$db->get = function() use($db) {
			return $db;
		};

		$db->row = function() use($db, $params) {

			if ( ! empty($params))
			{
				$db->user_id = $params['user_id'];
				$db->level = $params['level'];
				$db->ignore_limits = $params['ignore_limits'];
			}
			else
			{
				$db->user_id = 1;
				$db->level = 99999;
				$db->ignore_limits = TRUE;
			}

			return $db;
		};

		return $db;
	}

	// Overide inaccesible protected properties
	public function __get($property)
	{
		if ($return = parent::__get($property))
		{
			if ( ! empty($return))
			{
				return $return;
			}

			return isset($this->{'_'.$property}) ? $this->{'_'.$property} : NULL;
		}
	}

	// Overide inaccesible protected method
	public function __call($method, $params)
	{
		if (isset($this->$method) && $this->$method instanceof Closure)
		{
			return call_user_func_array($this->$method, $params);
		}
		elseif (preg_match('/^do.*$/', $method, $match) && count($match) === 1)
		{
			$protected_method = strtolower(substr(current($match), 2));

			return call_user_func_array(array($this,$protected_method), $params);
		}

		throw new BadMethodCallException('Method '.$method.' was not found');
	}
	
}