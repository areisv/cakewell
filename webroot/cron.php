<?php
/* SVN FILE: $Id: index.php 7296 2008-06-27 09:09:03Z gwoo $ */

/*  Cakewell Cron Dispatcher

    A dispatch handler for cron jobs
    See http://bakery.cakephp.org/articles/view/calling-controller-actions-from-cron-and-the-command-line

    If possible, use with

    USAGE:
    php /BASE_PATH/webroot/cron.php /controller/action

    Example using cron controller:
    php /BASE_PATH/webroot/cron.php /cron/test
*/

/**
 * Use the DS to separate the directories in other defines
 */
	if (!defined('DS')) {
		define('DS', DIRECTORY_SEPARATOR);
	}


/* Essential Cake Paths
    I like to use a centralized core cake library for multiple projects on
    my development server and I find the names of the Cake path constants below
    ambiguous.  So I use var names here that I find more explicit that allow
    me to use a single core cake installation with multiple "apps" or projects.
*/
$CakePhpAppDirParent = dirname(dirname(__FILE__));
$CakePhpAppDirName = 'app';
$CakePhpCoreDir = '/home/klenwell/root/qed/cake_core';


/**
 * These defines should only be edited if you have cake installed in
 * a directory layout other than the way it is distributed.
 * When using custom settings be sure to use the DS and do not add a trailing DS.
 */

/**
 * The full path to the directory which holds "app", WITHOUT a trailing DS.
 *
 */
	if (!defined('ROOT')) {
		#default: define('ROOT', dirname(dirname(dirname(__FILE__))));
		define('ROOT', $CakePhpAppDirParent);
	}
/**
 * The actual directory name for the "app".
 *
 */
	if (!defined('APP_DIR')) {
		#default: define('APP_DIR', basename(dirname(dirname(__FILE__))));
		define('APP_DIR', $CakePhpAppDirName);
	}
/**
 * The absolute path to the "cake" directory, WITHOUT a trailing DS.
 *
 */
	if (!defined('CAKE_CORE_INCLUDE_PATH')) {
		#define('CAKE_CORE_INCLUDE_PATH', ROOT);
		define('CAKE_CORE_INCLUDE_PATH', $CakePhpCoreDir);
	}

/**
 * Editing below this line should NOT be necessary.
 * Change at your own risk.
 *
 */
	if (!defined('WEBROOT_DIR')) {
		define('WEBROOT_DIR', basename(dirname(__FILE__)));
	}
	if (!defined('WWW_ROOT')) {
		define('WWW_ROOT', dirname(__FILE__) . DS);
	}
	if (!defined('CORE_PATH')) {
		if (function_exists('ini_set') && ini_set('include_path', CAKE_CORE_INCLUDE_PATH . PATH_SEPARATOR . ROOT . DS . APP_DIR . DS . PATH_SEPARATOR . ini_get('include_path'))) {
			define('APP_PATH', null);
			define('CORE_PATH', null);
		} else {
			define('APP_PATH', ROOT . DS . APP_DIR . DS);
			define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
		}
	}
	if (!include(CORE_PATH . 'cake' . DS . 'bootstrap.php')) {
		trigger_error("CakePHP core could not be found.  Check the value of CAKE_CORE_INCLUDE_PATH in APP/webroot/index.php.  It should point to the directory containing your " . DS . "cake core directory and your " . DS . "vendors root directory.", E_USER_ERROR);
	}

        // Dispatch the controller action given to it
        // CLI exceptioin
        if ( !isset($argc) || $argc != 2 ) {
            $exception_controller = '/cron/exception';
            $Dispatcher= new Dispatcher();
            $Dispatcher->dispatch($exception_controller);
        }
        // everything checks out
        {
            define('CAKEWELL_CRON', true);
            $Dispatcher= new Dispatcher();
            $Dispatcher->dispatch($argv[1]);
        }
?>
