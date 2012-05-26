<?php

$dir = realpath(dirname(__FILE__));

// Path constants
define('EXT', '.php');
define('PYRO_DEVELOPMENT', 'development');
define('PYRO_STAGING', 'staging');
define('PYRO_PRODUCTION', 'production');

define('PROJECT_BASE',		realpath($dir.'/../').'/');
define('BASEPATH',			PROJECT_BASE.'system/codeigniter/');
define('APPPATH',			PROJECT_BASE.'system/cms/');
define('VIEWPATH',			APPPATH.'views/');
define('SELF', 				pathinfo(PROJECT_BASE.'index'.EXT, PATHINFO_BASENAME));
define('SITE_DOMAIN', 		'localhost');
define('ADDON_FOLDER', 		PROJECT_BASE.'addons/');
define('SITE_REF', 			'default');
define('UPLOAD_PATH', 		'uploads/'.SITE_REF.'/');
define('ADDONPATH', 		ADDON_FOLDER.SITE_REF.'/');
define('SHARED_ADDONPATH', 	ADDON_FOLDER.'shared_addons/');
define('FCPATH', 			PROJECT_BASE);

unset($dir);

include PROJECT_BASE.'tests'.DIRECTORY_SEPARATOR.'Bootstrap'.EXT;