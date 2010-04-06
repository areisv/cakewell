<?php
/**
 *  Script for CakePhp command line/cron request
 *  See http://bit.ly/8o58B8 (bakery.cakephp.org)
 *
 *  USAGE
 *      php path/to/cron.php /controller/action domain
 *
 *      nfs server:
 *      php cron.php /controller/action nfs
 *
 *  NOTES
 *      The domain parameter maps the domain settings from the $ConfigDomainMap
 *      settings to the $_SERVER['SERVER_NAME'] setting.  This allows the
 *      database settings to be properly configured.
 */

/**
 * Cron flag: this can be checked in the controller beforefilter
 */
define('CAKEWELL_CRON', TRUE);

/**
 * Use the DS to separate the directories in other defines
 */
if ( !defined('DS') ) {
    define('DS', DIRECTORY_SEPARATOR);
}


/**
 * Essential Cake Paths
 *
 *  I like to use a centralized core cake library for multiple projects on
 *  my development server and I find the names of the Cake path constants below
 *  ambiguous.  So I use var names here that I find more explicit that allow
 *  me to use a single core cake installation with multiple "apps" or projects.
 *
 *  This is how the vars map:
 *
 *      $CakePhpAppDirParent  ->  ROOT
 *      $CakePhpAppDirName    ->  APP_DIR
 *      $CakePhpCoreDir       ->  CAKE_CORE_INCLUDE_PATH
 *
 *  In this section, I can also vary settings by server, as is necessary
 *  for my current host, nearlyfreespeech.net. nearlyfreespeech.net filetree:
 *
 *      /f5/cakewell/public/webroot/index.php
 *      /f5/cakewell/protected/app
 *      /f5/cakewell/protected/cake_core
 */
if ( isset($_SERVER['argv']) &&
    ( $_SERVER['argv'][2] == 'nfs' || $_SERVER['argv'][2] == 'cakewell.klenwell.com' ) ) {
    $_SERVER['SERVER_NAME'] = 'cakewell.klenwell.com';
    $nsfn_root = dirname(dirname(__FILE__));
    if ( substr($nsfn_root, -1) == '/' ) {
        $nsfn_root = substr($nsfn_root, 0, -1);
    }
    $CakePhpAppDirParent = sprintf('%s/protected', $nsfn_root);
    $CakePhpAppDirName = 'app';
    $CakePhpCoreDir = sprintf('%s/cake_core', $CakePhpAppDirParent);
}
else {
    $CakePhpAppDirParent = dirname(dirname(__FILE__));
    $CakePhpAppDirName = 'app';
    $CakePhpCoreDir = sprintf('%s/cake_core', dirname($CakePhpAppDirParent));
}


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
    if ( function_exists('ini_set') &&
        ini_set( 'include_path', sprintf('%s%s%s%s%s',
                                         CAKE_CORE_INCLUDE_PATH,
                                         PATH_SEPARATOR,
                                         ROOT . DS . APP_DIR . DS,
                                         PATH_SEPARATOR,
                                         ini_get('include_path')) ) ) {
        define('APP_PATH', null);
        define('CORE_PATH', null);
    }
    else {
        define('APP_PATH', ROOT . DS . APP_DIR . DS);
        define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
    }
}
if ( !include(CORE_PATH . 'cake' . DS . 'bootstrap.php') ) {
    trigger_error("CakePHP core could not be found.  Check the value of CAKE_CORE_INCLUDE_PATH in APP/webroot/index.php.  It should point to the directory containing your " . DS . "cake core directory and your " . DS . "vendors root directory.", E_USER_ERROR);
}

/**
 * Dispatch the controller action
 * If argc missing, not a command line call: handle as desired in else branch
 */
if (isset($argc) && $argc > 1) {
    $Dispatcher= new Dispatcher();
    $Dispatcher->dispatch($argv[1]);
}
else {
    die('cron available only from command line');
}
