<?php

class PyroCMS_TestCase extends CI_TestCase {

	/**
	 * Preparing minimal PyroCMS components representation
	 */
	public function prepare()
	{
		$language = array(
			'en' => array(
				'name'        => 'English',
				'folder'    => 'english',
				'direction'    => 'ltr',
				'codes'        => array('en', 'english', 'en_US'),
				'ckeditor'    => NULL
			)
		);

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

		$config->item = function($item = '') use($language) {
			if ($item == 'supported_languages')
			{
				return $language;
			}

			return 1;
		};

		$this->config = $config;

		// Mock Template
		$template = new Pyro_Mock();

		$template->add_theme_location = function($location = NULL){
			return TRUE;
		};

		$template->enable_parser = function() use($template) {
			return $template;
		};

		$template->set = function() use($template) {
			return $template;
		};

		$template->set_theme = function() use($template) {
			return $template;
		};

		$template->set_layout = function() use($template) {
			return $template;
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

		// Mock Settings default behaviour
		Settings::$container['get.site_public_lang'] = function() {
			return 'en';
		};

		$this->ion_auth = $ion_auth;
	}

	/**
	 * Mock Classes at runtime
	 *
	 * @param 	array Class names
	 * @return 	void
	 */
	public function mock_class($classes = array())
	{
		if ( ! empty($classes))
		{
			foreach ($classes as $class) pyro_class($class);
		}
	}

	/**
	 * Mock PyroCMS Modules at runtime
	 *
	 * @param 	array Module names
	 * @return 	void
	 */
	public function mock_module($modules = array())
	{
		if ( ! empty($modules))
		{
			foreach ($modules as $name => $obj)
			{
				$this->$name = (empty($obj)) ? new Pyro_Mock() : $obj;
			}
		}
	}

}