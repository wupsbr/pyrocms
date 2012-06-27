<?php

class Mock_PyroCMS_Core_Rest extends REST_Controller {

	public function detect_api_key($enable = FALSE)
	{
		if ($enable)
		{
			// Mock Database behaviour
			$db = new Pyro_Mock();

			$db->where = function() use($db) {
				return $db;
			};

			$db->get = function() use($db) {
				return $db;
			};

			$db->row = function() use($db) {
				$db->user_id = 1;
				$db->level = 99999;
				$db->ignore_limits = TRUE;

				return $db;
			};

			$this->rest->db = $db;
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

		throw new BadMethodCallException('Method '.$method.' was not found');
	}
	
}