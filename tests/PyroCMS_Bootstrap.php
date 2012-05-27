<?php

$dir = realpath(dirname(__FILE__));

// Path constants
define('EXT', '.php');
define('PYRO_DEVELOPMENT', 'development');
define('PYRO_STAGING', 'staging');
define('PYRO_PRODUCTION', 'production');
define('ENVIRONMENT', 'testing');

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

// Set SERVER environment
$_SERVER['SERVER_NAME'] = 'localhost';
$_SERVER['REQUEST_URI'] = '/';

// Include Pyro Core Mock classes
include_once $dir.'/mocks/pyrocms/core/common.php';
include_once $dir.'/mocks/pyrocms/autoloader.php';

unset($dir);

spl_autoload_register('pyrocms_autoload');

// Include the CodeIgniter bootstrap
include PROJECT_BASE.'tests'.DIRECTORY_SEPARATOR.'Bootstrap'.EXT;