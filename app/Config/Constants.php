<?php

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
 |-------------------------------------------------------------------------- 
 | Timing Constants
 |-------------------------------------------------------------------------- 
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2592000);
defined('YEAR')   || define('YEAR', 31536000);
defined('DECADE') || define('DECADE', 315360000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 */
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0);
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1);
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3);
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4);
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5);
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6);
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7);
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8);
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9);
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125);

/*
 | --------------------------------------------------------------------------
 | ERROR DISPLAY
 | --------------------------------------------------------------------------
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');

/*
 |-------------------------------------------------------------------------- 
 | DEBUG BACKTRACES
 |-------------------------------------------------------------------------- 
 */
defined('SHOW_DEBUG_BACKTRACE') || define('SHOW_DEBUG_BACKTRACE', true);

/*
 |-------------------------------------------------------------------------- 
 | DEBUG MODE
 |-------------------------------------------------------------------------- 
 */
defined('CI_DEBUG') || define('CI_DEBUG', true);

/*
 |-------------------------------------------------------------------------- 
 | EXECUTION CONTEXT GUARD (CRITICAL FIX)
 |-------------------------------------------------------------------------- 
 */
defined('IS_CLI_MODE') || define('IS_CLI_MODE', PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg');
defined('IS_SPARK')    || define('IS_SPARK', IS_CLI_MODE && defined('STDIN'));