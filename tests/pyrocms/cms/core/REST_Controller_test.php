<?php

class REST_Controller_test extends PyroCMS_TestCase {

	public function set_up()
	{
		$this->mock_class(array(
			'MX_Controller',
			'Settings',
			'Asset',
			'Events',
		));

		$this->prepare();

		$this->uri = new Pyro_Mock();
		$this->uri->ruri_to_assoc = function() {
			return array();
		};
	}

	public function test_constructor()
	{
		$this->_prepare_restful_env();

		// Mock Config
		$this->ci_set_config('rest_enable_keys', TRUE);
		$this->ci_set_config('rest_keys_table', 'keys');
		$this->ci_set_config('rest_database_group', 'rest');
		$this->ci_set_config('rest_ip_whitelist_enabled', TRUE);

		$rest_controller = new Mock_PyroCMS_Core_Rest(TRUE);
	}

	public function test_remap()
	{
		$this->_prepare_restful_env();

		// Mock Config
		$this->ci_set_config('enable_emulate_request', TRUE);
		$this->ci_set_config('rest_auth', 'basic');

		$rest_controller = new Mock_PyroCMS_Core_Rest();
		$rest_controller->get_1 = function() {
			throw new InvalidArgumentException('You are not allowed to access /get in this server');
		};

		// Expect InvalidArgumentException
		$this->setExpectedException(
			'InvalidArgumentException',
			'You are not allowed to access /get in this server'
			);

		$rest_controller->_remap('get', array());
	}

	public function test_remap_object_called_match_pattern()
	{
		$this->_prepare_restful_env();

		// Mock Config
		$this->ci_set_config('enable_emulate_request', TRUE);

		$rest_controller = new Mock_PyroCMS_Core_Rest();
		$rest_controller->get_1 = function() {
			throw new InvalidArgumentException('You are not allowed to access /get in this server');
		};

		// Expect InvalidArgumentException
		$this->setExpectedException(
			'InvalidArgumentException',
			'You are not allowed to access /get in this server'
			);

		$rest_controller->_remap('get.json', array());
	}

	public function test_remap_invalid_api_key()
	{
		$this->_prepare_restful_env();

		// Mock Config
		$this->ci_set_config('enable_emulate_request', TRUE);

		$rest_controller = new Mock_PyroCMS_Core_Rest();

		$this->ci_set_config('rest_enable_keys', TRUE);
		$this->ci_set_config('rest_enable_logging', TRUE);
		$rest_controller->setExpected_Allow = FALSE;

		$rest_controller->get_1 = function() {
			throw new InvalidArgumentException('Invalid API Key');
		};

		// Expect InvalidArgumentException
		$this->setExpectedException(
			'InvalidArgumentException',
			'Invalid API Key'
			);

		$rest_controller->_remap('get.json', array());
	}

	public function test_remap_test_right_key()
	{
		$this->_prepare_restful_env();

		// Mock Config
		$this->ci_set_config('enable_emulate_request', TRUE);

		$rest_controller = new Mock_PyroCMS_Core_Rest();

		$this->ci_set_config('rest_enable_keys', TRUE);
		$this->ci_set_config('rest_enable_logging', TRUE);

		$rest = new stdClass();
		$rest->key = array('foo');
		$rest->level = 3;
		$rest->db = new Pyro_Mock();
		$rest_controller->setExpectedRest = $rest;

		$rest_controller->get_1 = function() {
			throw new DomainException('Your key is right but you run from unsupported environment');
		};

		// Expect InvalidArgumentException
		$this->setExpectedException(
			'DomainException',
			'Your key is right but you run from unsupported environment'
			);

		$rest_controller->_remap('get.json', array());
	}

	public function test_remap_test_limit_enabled()
	{
		$this->_prepare_restful_env();

		// Mock Config
		$this->ci_set_config('enable_emulate_request', TRUE);

		$rest_controller = new Mock_PyroCMS_Core_Rest();

		$this->ci_set_config('rest_enable_keys', TRUE);
		$this->ci_set_config('rest_enable_limits', TRUE);
		$this->ci_set_config('rest_enable_logging', TRUE);
		$this->ci_set_config('rest_limits_table', 'limits');

		// Mock Router
		$rest_controller->setExpectedMethods = array('blog_1' => 'index');

		$rest_controller->detect_api_key(TRUE, array(
			'user_id' => 1,
			'level' => 1,
			'ignore_limits' => FALSE,
		));

		$rest =& $rest_controller->rest;
		$rest->db = new Pyro_Mock();
		$rest->db = new Pyro_Mock();
		$rest->db->where = $rest->db->get = function() use($rest) {
			return $rest->db;
		};
		$rest->db->row = function() {
			$res = new stdClass();
			$res->hour_started = time();
			$res->count = 2;

			return $res;
		};

		$rest->key = array('foo');
		$rest->level = -1;

		$rest_controller->setExpectedRest = $rest;

		$rest_controller->blog_1 = function() {
			echo 'Some Post';
		};

		ob_start();

		$rest_controller->_remap('blog', array());

		$out = ob_get_clean();

		$this->assertEquals('Some Post', $out);
	}

	public function test_response_without_params()
	{
		$this->_prepare_restful_env();

		$rest_controller = new Mock_PyroCMS_Core_Rest();
		
		$response = $rest_controller->response();
	}

	public function test_detect_api_key_fail()
	{
		$this->_prepare_restful_env();

		$rest_controller = new Mock_PyroCMS_Core_Rest();
		
		$this->assertFalse($rest_controller->detect_api_key());
	}

	public function test_detect_api_key_without_key()
	{
		$this->_prepare_restful_env();

		$rest_controller = new Mock_PyroCMS_Core_Rest();
		
		// Mock DB
		$rest =& $rest_controller->rest;
		$rest->db = new Pyro_Mock();
		$rest->db->where = $rest->db->get = function() use($rest) {
			return $rest->db;
		};
		$rest->db->row = function() {
			return FALSE;
		};

		$rest_controller->setExpectedRest = $rest;

		$this->assertFalse($rest_controller->do_detect_api_key());
	}

	public function test_detect_api_key_success()
	{
		$this->_prepare_restful_env();

		$rest_controller = new Mock_PyroCMS_Core_Rest();
		
		$this->assertTrue($rest_controller->detect_api_key(TRUE));
	}

	public function test_get()
	{
		$this->_prepare_restful_env();

		$this->uri->ruri_to_assoc = function() {
			return array('foo' => 'bar');
		};

		$rest_controller = new Mock_PyroCMS_Core_Rest();
		
		$this->assertArrayHasKey('foo', $rest_controller->get());
		$this->assertEquals('bar', $rest_controller->get('foo', FALSE));
	}

	public function test_post()
	{
		$this->_prepare_restful_env();

		// Mock Input
		$this->input = new Pyro_Mock();
		$this->input->server = function() {
			return 'POST';
		};
		$this->input->post = function($key = NULL) {
			if ($key == '_method') return 'post';

			$post = array('foo' => 'bar');

			return ( ! empty($key) && array_key_exists($key, $post)) ? $post[$key] : $post;
		};

		// Mock Config
		$this->ci_set_config('enable_emulate_request', TRUE);

		$rest_controller = new Mock_PyroCMS_Core_Rest();
		
		$this->assertInternalType('array', $rest_controller->post());
		$this->assertEquals('bar', $rest_controller->post('foo', FALSE));
	}

	public function test_put()
	{
		$this->_prepare_restful_env();

		// Mock Input
		$this->input = new Pyro_Mock();
		$this->input->server = function($method = '') {
			return ($method == 'CONTENT_TYPE') ? 'application/xml;' : 'PUT';
		};
		$this->input->post = function() {
			return FALSE;
		};

		// Mock Config
		$this->ci_set_config('enable_emulate_request', TRUE);

		$rest_controller = new Mock_PyroCMS_Core_Rest();
		
		$this->assertInternalType('array', $rest_controller->put());
		$this->assertFalse($rest_controller->put('non_exists_put'));

		// Mock Input
		$this->input = new Pyro_Mock();
		$this->input->server = function() {
			return 'PUT';
		};
		$this->input->post = function() {
			return FALSE;
		};

		$rest_controller = new Mock_PyroCMS_Core_Rest();
		
		$this->assertInternalType('array', $rest_controller->put());
		$this->assertFalse($rest_controller->put('non_exists_put'));
	}

	public function test_delete()
	{
		$this->_prepare_restful_env();

		// Mock Input
		$this->input = new Pyro_Mock();
		$this->input->server = function() {
			return 'DELETE';
		};
		$this->input->post = function() {
			return FALSE;
		};

		// Mock Config
		$this->ci_set_config('enable_emulate_request', TRUE);

		$rest_controller = new Mock_PyroCMS_Core_Rest();
		
		$this->assertInternalType('array', $rest_controller->delete());
		$this->assertFalse($rest_controller->delete('non_exists_delete'));
	}

	public function test_detect_lang_null()
	{
		$this->_prepare_restful_env();

		// Mock Input
		$this->input = new Pyro_Mock();
		$this->input->server = function($attr = '') {
			return (bool) ($attr !== 'HTTP_ACCEPT_LANGUAGE');
		};

		$rest_controller = new Mock_PyroCMS_Core_Rest();

		$this->assertNull($rest_controller->do_detect_lang());
	}

	public function test_detect_lang_http_accept_lang()
	{
		$this->_prepare_restful_env();

		// Mock Input
		$this->input = new Pyro_Mock();
		$this->input->server = function($attr = '') {
			if ($attr == 'HTTP_ACCEPT_LANGUAGE')
			{
				return 'en,cz';
			}
		};

		$rest_controller = new Mock_PyroCMS_Core_Rest();

		$langs = $rest_controller->do_detect_lang();

		$this->assertEquals('en', current($langs));
		$this->assertEquals('cz', end($langs));
	}

	public function test_log_request()
	{
		$this->_prepare_restful_env();

		$rest_controller = new Mock_PyroCMS_Core_Rest();
		$rest_controller->detect_api_key(TRUE);

		$this->assertTrue($rest_controller->do_log_request());
	}

	public function test_check_limit_ignore()
	{
		$this->_prepare_restful_env();

		$rest_controller = new Mock_PyroCMS_Core_Rest();
		$rest_controller->detect_api_key(TRUE);

		$this->assertTrue($rest_controller->do_check_limit('blog'));
	}

	public function test_check_limit_no_call_yet()
	{
		$this->_prepare_restful_env();

		// Mock Router
		$rest_controller = new Mock_PyroCMS_Core_Rest();
		$rest_controller->setExpectedMethods = array('blog' => 'index');

		$this->ci_set_config('rest_limits_table', 'limits');

		$rest_controller->detect_api_key(TRUE, array(
			'user_id' => 1,
			'level' => 1,
			'ignore_limits' => FALSE,
		));

		$rest =& $rest_controller->rest;
		$rest->db = new Pyro_Mock();
		$rest->db->where = $rest->db->get = function() use($rest) {
			return $rest->db;
		};
		$rest->db->row = function() {
			return FALSE;
		};

		$rest_controller->setExpectedRest = $rest;

		$this->assertTrue($rest_controller->do_check_limit('blog'));
	}
	/*
	public function test_check_limit_count_exceeds_limit()
	{
		$this->_prepare_restful_env();

		// Mock Router
		$rest_controller = new Mock_PyroCMS_Core_Rest();
		$rest_controller->setExpectedMethods = array('blog' => 'index');

		$this->ci_set_config('rest_limits_table', 'limits');

		$rest_controller->detect_api_key(TRUE, array(
			'user_id' => 1,
			'level' => 1,
			'ignore_limits' => FALSE,
		));

		$rest =& $rest_controller->rest;
		$rest->db = new Pyro_Mock();
		$rest->db->where = $rest->db->get = function() use($rest) {
			return $rest->db;
		};
		$rest->db->row = function() {
			$res = new stdClass();
			$res->hour_started = time();
			$res->count = 2;

			return $res;
		};

		$rest_controller->setExpectedRest = $rest;

		$this->assertFalse($rest_controller->do_check_limit('blog'));
	}
	*/

	public function test_check_limit_count_after_one_hour_and_not_exceeds_limit()
	{
		$this->_prepare_restful_env();

		// Mock Router
		$rest_controller = new Mock_PyroCMS_Core_Rest();
		$rest_controller->setExpectedMethods = array('blog' => 'index');

		$this->ci_set_config('rest_limits_table', 'limits');

		$rest_controller->detect_api_key(TRUE, array(
			'user_id' => 1,
			'level' => 1,
			'ignore_limits' => FALSE,
		));

		$rest =& $rest_controller->rest;
		$rest->db = new Pyro_Mock();
		$rest->db->where = $rest->db->get = $rest->db->set = $rest->db->update = function() use($rest) {
			return $rest->db;
		};
		$rest->db->row = function() {
			$res = new stdClass();
			$res->hour_started = time();
			$res->count = -1;

			return $res;
		};

		$rest_controller->setExpectedRest = $rest;

		$this->assertTrue($rest_controller->do_check_limit('blog'));
	}

	public function test_auth_override_check_empty()
	{
		$this->_prepare_restful_env();

		$rest_controller = new Mock_PyroCMS_Core_Rest();

		// Mock Config
		$this->ci_set_config('auth_override_class_method', NULL);

		$this->assertFalse($rest_controller->do_auth_override_check());
	}

	public function test_auth_override_check_empty_router_attr_or_unvavailable()
	{
		$this->_prepare_restful_env();

		$rest_controller = new Mock_PyroCMS_Core_Rest();

		// Mock Config
		$this->ci_set_config('auth_override_class_method', array('blog' => array('index' => TRUE)));
		$this->assertFalse($rest_controller->do_auth_override_check());

		// Mock Router
		$this->router = new Pyro_Mock();
		$this->router->class = 'blog';
		$this->router->method = 'index';

		$this->ci_set_config('auth_override_class_method', array('blog' => array('index' => 'undefined')));
		$this->assertFalse($rest_controller->do_auth_override_check());
	}

	public function test_auth_override_check_router_available()
	{
		$this->_prepare_restful_env();

		$rest_controller = new Mock_PyroCMS_Core_Rest();

		// Mock Config
		$this->ci_set_config('auth_override_class_method', array('blog' => array('index' => 'none')));

		// Mock Router
		$this->router = new Pyro_Mock();
		$this->router->class = 'blog';
		$this->router->method = 'index';

		$this->assertTrue($rest_controller->do_auth_override_check());

		$this->ci_set_config('auth_override_class_method', array('blog' => array('index' => 'whitelist')));
		$this->assertTrue($rest_controller->do_auth_override_check());

		$this->ci_set_config('auth_override_class_method', array('blog' => array('index' => 'basic')));
		$this->assertTrue($rest_controller->do_auth_override_check());

		$this->ci_set_config('auth_override_class_method', array('blog' => array('index' => 'digest')));
		$this->setExpectedException(
			'RuntimeException',
			'CI Error: Unauthorized Access'
		);
		$rest_controller->do_auth_override_check();
	}

	public function test_xss_clean()
	{
		$this->_prepare_restful_env();

		$rest_controller = new Mock_PyroCMS_Core_Rest();

		// Mock Old CI Input
		$this->input = new Pyro_Mock();
		$this->input->xss_clean = function($something) {
			return 'Something that more clean';
		};

		defined('CI_VERSION') or define('CI_VERSION', 1.7);
		$xss_clean = $rest_controller->do_xss_clean('Something', TRUE);

		$this->assertEquals('Something that more clean', $xss_clean);
	}

	public function test_validation_errors()
	{
		$this->_prepare_restful_env();

		$rest_controller = new Mock_PyroCMS_Core_Rest();

		// Mock Form Validation
		$this->form_validation = new Pyro_Mock();
		$this->form_validation->error_string = function() {
			return 'Something wrong in <a href="someurl.php">your eye</a>'."\n".'And that is serious!';
		};

		$validation_errors = $rest_controller->validation_errors();

		$this->assertCount(2, $validation_errors);
		$this->assertEquals('Something wrong in your eye', current($validation_errors));
		$this->assertEquals('And that is serious!', end($validation_errors));
	}

	public function test_check_login()
	{
		$this->_prepare_restful_env();

		$rest_controller = new Mock_PyroCMS_Core_Rest();

		$this->assertFalse($rest_controller->do_check_login());

		// Mock config
		$this->ci_set_config('rest_valid_logins', array('foo' => 'bar'));

		$this->assertTrue($rest_controller->do_check_login('foo'));
	}

	public function test_prepare_basic_auth()
	{
		$this->_prepare_restful_env();

		$rest_controller = new Mock_PyroCMS_Core_Rest();

		// Mock config
		$this->ci_set_config('rest_ip_whitelist_enabled', TRUE);
		$this->ci_set_config('rest_auth', 'basic');
		$this->ci_set_config('rest_valid_logins', array('foo' => 'bar'));

		// Mock input server
		$this->input = new Pyro_Mock();
		$this->input->server = function(){
			return 'username=foo,blah...blah...';
		};

		$rest_controller->do_prepare_basic_auth();

		// Mock input server
		$this->input = new Pyro_Mock();
		$this->input->server = function($method = ''){
			if ($method == 'HTTP_AUTHENTICATION')
			{
				return 'basic';
			}
			elseif ($method == 'HTTP_AUTHORIZATION')
			{
				return 'realms'.base64_encode('foo:s3cr3tp4ssw0rd');
			}
		};

		$rest_controller->do_prepare_basic_auth();
	}

	public function test_prepare_digest_auth_empty_auth_method()
	{
		$this->_prepare_restful_env();

		$rest_controller = new Mock_PyroCMS_Core_Rest();

		// Mock config
		$this->ci_set_config('rest_ip_whitelist_enabled', TRUE);
		$this->ci_set_config('rest_valid_logins', array('foo' => 'bar'));

		// Mock input server
		$this->input = new Pyro_Mock();
		$this->input->server = function(){
			return FALSE;
		};

		$this->setExpectedException(
			'RuntimeException',
			'CI Error: Unauthorized Access'
		);

		$rest_controller->do_prepare_digest_auth();
	}

	public function test_prepare_digest_auth_username_mismatch()
	{
		$this->_prepare_restful_env();

		$rest_controller = new Mock_PyroCMS_Core_Rest();

		// Mock config
		$this->ci_set_config('rest_ip_whitelist_enabled', TRUE);
		$this->ci_set_config('rest_valid_logins', array('foo' => 'bar'));

		// Mock input server
		$this->input = new Pyro_Mock();
		$this->input->server = function(){
			return 'username=notfoo,uri=index,nonce=s3,nc=n,cnonce=cr3,qop=ts,response=26dfd72d5f42a029c82b487114d12978';
		};

		$rest_controller->do_prepare_digest_auth();
	}

	public function test_prepare_digest_auth_success()
	{
		$this->_prepare_restful_env();

		$rest_controller = new Mock_PyroCMS_Core_Rest();

		// Mock config
		$this->ci_set_config('rest_ip_whitelist_enabled', TRUE);
		$this->ci_set_config('rest_auth', 'digest');
		$this->ci_set_config('rest_valid_logins', array('foo' => 'bar'));

		// Mock input server
		$this->input = new Pyro_Mock();
		$this->input->server = function(){
			return 'username=foo,uri=index,nonce=s3,nc=n,cnonce=cr3,qop=ts,response=26dfd72d5f42a029c82b487114d12978';
		};

		$rest_controller->do_prepare_digest_auth();

		$rest_controller = new Mock_PyroCMS_Core_Rest();

		// Mock input server
		$this->input = new Pyro_Mock();
		$this->input->server = function($method = ''){
			return ($method !== 'HTTP_AUTHORIZATION') ? '' : 'username=foo,uri=index,nonce=s3,nc=n,cnonce=cr3,qop=ts,response=26dfd72d5f42a029c82b487114d12978';
		};

		$rest_controller->do_prepare_digest_auth();
	}

	public function test_check_whitelist_auth()
	{
		$this->_prepare_restful_env();

		$rest_controller = new Mock_PyroCMS_Core_Rest();

		// Mock config
		$this->ci_set_config('rest_ip_whitelist', '9.9.9.9,8.8.8.8');

		// Mock input and ip address
		$this->input = new Pyro_Mock();
		$this->input->ip_address = function(){
			return '1.1.1.1';
		};
		
		$rest_controller->do_check_whitelist_auth();
	}

	public function test_force_loopable()
	{
		$this->_prepare_restful_env();

		$rest_controller = new Mock_PyroCMS_Core_Rest();
		
		$this->assertInternalType('array', $rest_controller->do_force_loopable('foo'));
	}

	public function test_format_jsonp()
	{
		$this->_prepare_restful_env();

		$rest_controller = new Mock_PyroCMS_Core_Rest();
		
		$this->assertEquals('({"foo":"bar"})', $rest_controller->do_format_jsonp(array('foo' => 'bar')));
	}

	protected function _prepare_restful_env()
	{
		$this->enable_modules = TRUE;
		$this->enable_restful = TRUE;

		return $this;
	}
}