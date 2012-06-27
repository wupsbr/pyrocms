<?php

class Public_Controller_test extends PyroCMS_TestCase {

	public function set_up()
	{
		$this->mock_class(array(
			'MX_Controller',
			'Settings',
			'Asset',
			'Events',
		));

		$this->prepare();
	}

	public function test_constructor_503()
	{
		$this->enable_modules = TRUE;

		// Expect 503
		$this->setExpectedException(
			'RuntimeException',
			'CI Error: cms_fatal_error'
			);

		$public_controller = new Public_Controller();
	}

	public function test_constructor_get_request_redirect()
	{
		$this->enable_modules = TRUE;

		$this->input = new Pyro_Mock();
		$this->input->is_ajax_request = function() {
			return FALSE;
		};

		// Mock Them Module get_admin method 
		$this->redirect_m = new Pyro_Mock();
		$this->redirect_m->get_from = function(){
			$redirect = new stdClass();
			$redirect->from = 'blog';
			$redirect->to = 'blog';
			$redirect->type = 301;

			return $redirect;
		};

		$_SERVER = array('REQUEST_METHOD' => 'GET');

		// Expect direct Redirection
		$this->setExpectedException(
			'DomainException',
			'CI Redirect: blog'
			);

		$public_controller = new Public_Controller();
	}

	public function test_constructor_get_request_redirect_has_back_reference()
	{
		$this->enable_modules = TRUE;

		$this->input = new Pyro_Mock();
		$this->input->is_ajax_request = function() {
			return FALSE;
		};

		// Mock Them Module get_admin method 
		$this->redirect_m = new Pyro_Mock();
		$this->redirect_m->get_from = function(){
			$redirect = new stdClass();
			$redirect->from = '$blog';
			$redirect->to = 'other';
			$redirect->type = 301;

			return $redirect;
		};

		$_SERVER = array('REQUEST_METHOD' => 'GET');

		// Expect direct Redirection
		$this->setExpectedException(
			'DomainException',
			'CI Redirect: other'
			);

		$public_controller = new Public_Controller();
	}

	public function test_constructor_admin_user_but_slug_theme_fails()
	{
		$this->enable_modules = TRUE;

		$this->mock_module(array(
			'blog' => NULL
		));

		// Mock Ion Auth get_user method
		$this->ion_auth->get_user = function(){
			$user = new stdClass();
			$user->group = 'admin';
			$user->group_id = 1;

			return $user;
		};

		// Mock Themes Module get_admin method 
		$this->theme_m = new Pyro_Mock();
		$this->theme_m->get = function(){
			$theme = new stdClass();
			$theme->slug = NULL;

			return $theme;
		};

		// Expect Exception
		$this->setExpectedException(
			'RuntimeException',
			'CI Error: This site has been set to use a theme that does not exist. If you are an administrator please change the theme.'
			);

		$public_controller = new Public_Controller();
	}

	public function test_constructor_current_user_admin()
	{
		$this->enable_modules = TRUE;
		$this->mock_module(array(
			'blog' => NULL
		));

		// Mock Ion Auth get_user method
		$this->ion_auth->get_user = function(){
			$user = new stdClass();
			$user->group = 'admin';
			$user->group_id = 1;

			return $user;
		};

		// Mock Themes Module get_admin method 
		$this->theme_m = new Pyro_Mock();
		$this->theme_m->get = function(){
			$theme = new stdClass();
			$theme->slug = 'admin';
			$theme->path = 'admin';

			return $theme;
		};

		Settings::$container['get.cdn_domain'] = function() {
			return 'cdn.of.someserver.com';
		};

		$theme_admin = new Mock_PyroCMS_Theme_Admin();

		$admin_controller = new Public_Controller();
	}
}