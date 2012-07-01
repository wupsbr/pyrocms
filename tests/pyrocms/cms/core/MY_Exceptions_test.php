<?php

class MY_Exceptions_test extends PyroCMS_TestCase {

	public function test_show_404()
	{
		// Mock Modules environment
		pyro_class('Modules');
		Modules::$container['run'] = function(){
			return 'Page Not Found';
		};

		$my_exception = new MY_Exceptions();

		ob_start();

		$my_exception->show_404();

		$out = ob_get_clean();

		$this->assertEquals('Page Not Found', $out);
	}

}