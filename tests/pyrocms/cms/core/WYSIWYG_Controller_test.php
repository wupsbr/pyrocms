<?php

class WYSIWYG_Controller_test extends PyroCMS_TestCase {

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

	public function test_constructor_bad_slug()
	{
		$this->enable_modules = TRUE;

		// Expect Bad Slug Error
		$this->setExpectedException(
			'RuntimeException',
			'CI Error: This site has been set to use an admin theme that does not exist.'
			);

		$wysiwyg_controller = new WYSIWYG_Controller();
	}

	public function test_constructor_current_user_non_admin()
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
			$files = new stdClass();
			$files->wysiwyg = FALSE;

			return array('files' => $files);
		};

		// Expect Insufficient file permission
		$this->setExpectedException(
			'RuntimeException',
			'CI Error: files:no_permissions'
			);

		$wysiwyg_controller = new WYSIWYG_Controller();
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

		// Mock Template methods
		$template =& $this->template;
		$template->append_css = function() use($template) {
			return $template;
		};
		$template->append_js = function() use($template) {
			return $template;
		};

		$wysiwyg_controller = new WYSIWYG_Controller();
	}
}