<?php
/**
 *  Cakewell Domain-Specific CakePHP Core Config File
 *  sample production domain config file
 *
 *  Replace my-domain.com in file name with your actual domain name (as it
 *  appears in the $_SERVER['SERVER_NAME'] setting
 *
 *  See the core.php.default config file comments for more info.
 *
 *  Be careful regarding define('CONSTANT') conflicts.  If in doubt, use:
 *      if ( !defined('CONSTANT') ) define('CONSTANT', 'foo');
 */

    Configure::write('App.domain_config_file', __FILE__);
    Configure::write('debug', 0);           // note: this overrides the mode config file setting

?>
