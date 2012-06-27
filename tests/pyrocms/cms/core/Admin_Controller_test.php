<?php

class Admin_Controller_test extends PyroCMS_TestCase {

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

	public function test_constructor_not_login()
	{
		$this->enable_modules = TRUE;

		// Expect Redirect
		$this->setExpectedException(
			'DomainException',
			'CI Redirect: admin/login'
			);

		$admin_controller = new Admin_Controller();
	}

	public function test_constructor_current_user_ignored_page()
	{
		$this->enable_modules = TRUE;

		// Mock Ion Auth get_user method
		$this->ion_auth->get_user = function(){
			$user = new stdClass();
			$user->group = 'moderator';
			$user->group_id = 2;

			return $user;
		};

		// Mock URI methods
		$this->uri = new Pyro_Mock();
		$this->uri->segment = function($section = 0){
			if ($section == 1)
			{
				return 'admin';
			}
			elseif ($section == 2)
			{
				return 'login';
			}
			else
			{
				return 'index';
			}
		};

		// Mock Router methods
		$this->router->fetch_module = function(){
			return 'blog';
		};

		// Mock Theme Module get_admin method 
		$this->theme_m = new Pyro_Mock();
		$this->theme_m->get_admin = function(){
			$theme = new stdClass();
			$theme->slug = 'admin';
			$theme->web_path = 'admin';

			return $theme;
		};

		$theme_admin = new Mock_PyroCMS_Theme_Admin();

		$admin_controller = new Admin_Controller();
	}

	public function test_constructor_current_user_non_admin_without_correct_theme()
	{
		$this->enable_modules = TRUE;

		// Mock Ion Auth get_user method
		$this->ion_auth->get_user = function(){
			$user = new stdClass();
			$user->group = 'moderator';
			$user->group_id = 2;

			return $user;
		};

		// Mock Permission Module get_group method 
		$this->permission_m = new Pyro_Mock();
		$this->permission_m->get_group = function(){
			return array('blog' => 'moderator');
		};

		// Mock Router methods
		$this->router->fetch_module = function(){
			return 'blog';
		};

		// Expect Error
		$this->setExpectedException(
			'RuntimeException',
			'CI Error: This site has been set to use an admin theme that does not exist.'
			);

		$admin_controller = new Admin_Controller();
	}

	public function test_constructor_current_user_non_admin_but_have_sufficient_permission()
	{
		$this->enable_modules = TRUE;

		// Mock Ion Auth get_user method
		$this->ion_auth->get_user = function(){
			$user = new stdClass();
			$user->group = 'moderator';
			$user->group_id = 2;

			return $user;
		};

		// Mock URI methods
		$this->uri = new Pyro_Mock();
		$this->uri->segment = function($section = 0){
			if ($section == 1)
			{
				return 'admin';
			}
			elseif ($section == 2)
			{
				return 'index';
			}
		};

		// Mock Router methods
		$this->router->fetch_module = function(){
			return 'blog';
		};

		// Mock Permission Module get_group method 
		$this->permission_m = new Pyro_Mock();
		$this->permission_m->get_group = function(){
			return array('blog' => 'moderator');
		};

		// Mock Theme Module get_admin method 
		$this->theme_m = new Pyro_Mock();
		$this->theme_m->get_admin = function(){
			$theme = new stdClass();
			$theme->slug = 'admin';
			$theme->web_path = 'admin';

			return $theme;
		};

		$theme_admin = new Mock_PyroCMS_Theme_Admin();

		$admin_controller = new Admin_Controller();
	}

	public function test_constructor_current_user_admin()
	{
		$this->enable_modules = TRUE;

		// Mock Ion Auth get_user method
		$this->ion_auth->get_user = function(){
			$user = new stdClass();
			$user->group = 'admin';
			$user->group_id = 1;

			return $user;
		};

		// Mock Theme Module get_admin method 
		$this->theme_m = new Pyro_Mock();
		$this->theme_m->get_admin = function(){
			$theme = new stdClass();
			$theme->slug = 'admin';
			$theme->web_path = 'admin';

			return $theme;
		};

		// To strech out debug mode in MY_Controller
		$_GET = array('_debug' => TRUE);

		$theme_admin = new Mock_PyroCMS_Theme_Admin();

		$admin_controller = new Admin_Controller();
	}
}