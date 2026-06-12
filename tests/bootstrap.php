<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| CI4 TEST BOOTSTRAP (STABLE VERSION)
|--------------------------------------------------------------------------
| DO NOT manually load Config\Paths.
| DO NOT redefine CI4 constants.
*/

define('ROOTPATH', realpath(__DIR__ . '/../') . DIRECTORY_SEPARATOR);
define('FCPATH', ROOTPATH . 'public' . DIRECTORY_SEPARATOR);

require ROOTPATH . 'vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| BOOT CI4 TEST LAYER (SINGLE SOURCE OF TRUTH)
|--------------------------------------------------------------------------
| This internally loads:
| - Config\Paths
| - ENVIRONMENT
| - SYSTEMPATH
| - APPPATH
| - WRITEPATH
*/

require_once ROOTPATH . 'vendor/codeigniter4/framework/system/Test/bootstrap.php';