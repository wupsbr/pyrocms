<?php

class PyroCMS_TestCase extends CI_TestCase {

	public function prepare()
	{
		// Mock Loader
		$load = $this->getMock('Mock_Core_Loader');

		$load->expects($this->any())
			   ->method('library')
			   ->will($this->returnValue(TRUE));

		$this->load = $load;

		// Mock Lang
		$lang = new Pyro_Mock();
		$this->lang = $lang;

		// Mock Router
		$router = new Pyro_Mock();
		$this->router = $router;

		// Mock Hooks
		$hooks = new Pyro_Mock();
		$this->hooks = $hooks;

		// Mock Config
		$config = new Pyro_Mock();

		$config->item = function() {
			return array( 1 => 'en');
		};

		$this->config = $config;

		// Mock Template
		$template = new Pyro_Mock();
		$template->add_theme_location = function($location = NULL){
			return TRUE;
		};

		$this->template = $template;

		// Mock Migration
		$migration = new Pyro_Mock();
		$migration->current = function(){
			return 1;
		};

		$this->migration = $migration;

		// Mock Ion Auth
		$ion_auth = new Pyro_Mock();
		$ion_auth->get_user = function () {
			return FALSE;
		};

		$this->ion_auth = $ion_auth;
	}

}