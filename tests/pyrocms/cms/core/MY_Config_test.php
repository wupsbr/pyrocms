<?php

class MY_Config_test extends PyroCMS_TestCase {

	public function test_site_url_no_parameter()
	{
		$my_config = new MY_Config();

		$this->assertEquals('http://localhost/', $my_config->site_url());
	}

	public function test_site_url_array_parameter()
	{
		$my_config = new MY_Config();

		$this->assertEquals('http://localhost/user/1', $my_config->site_url(array('user', '1')));
	}

	public function test_site_url_string_parameter()
	{
		$my_config = new MY_Config();

		$this->assertEquals('http://localhost/user', $my_config->site_url('user'));
		$this->assertEquals('http://localhost/user.html', $my_config->site_url('user|html'));
	}

	public function test_set_item()
	{
		$my_config = new MY_Config();

		$my_config->set_item('foo', 'bar');
		$my_config->set_item('foo', 'bar', 'superfoo');
		$this->assertArrayHasKey('foo', $my_config->config);
		$this->assertArrayHasKey('superfoo', $my_config->config);
	}

}