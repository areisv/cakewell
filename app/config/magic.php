<?php
/**
 * Cakewell Magic Config File
 *
 * This file is called at the end of the Cakewell version of the CakePhp core
 * config file.  It dynamically determines the context that the application
 * is running in based on the $_SERVER['SERVER_NAME'] value and sets App.mode
 * and App.domain values.
 *
 * NOTE WELL: YOU SHOULD NOT NEED TO EDIT THIS FILE.
 */

    /*
        $_SERVER['SERVER_NAME'] not set in some context (e.g. cron jobs)
        In this case, we look for to see if command line parameter gives
        us the value, following the example here:
        http://bakery.cakephp.org/articles/view/one-core-one-app-multiple-domains
    */
    if ( empty($_SERVER['SERVER_NAME']) )
    {
        // look at last cli value
        if ( count($_SERVER['argv']) )
            $_SERVER['SERVER_NAME'] = $_SERVER['argv'][count($_SERVER['argv'])-1];
    }

    // Set App.Mode Value
    // defaults
    Configure::write('App.mode', 'production');     // default value
    Configure::write('App.domain', $_SERVER['SERVER_NAME']);

    // from ConfigDomainMap in core config file
    foreach ( $ConfigDomainMap as $server_name => $app_mode )
    {
        if ( $server_name == $_SERVER['SERVER_NAME'] )
        {
            Configure::write('App.mode', $app_mode);
            Configure::write('App.domain', $server_name);
        }
    }

    /**
     * Mode-Specific Config Files
     * If a file with name match App.mode settings found in config/domains
     * folder, load it.  An example file:
     *
     * config/domains/test.php
     */
    $app_mode_config_file = sprintf( '%s%s/%s.php',
                                       CONFIGS,
                                       'modes',
                                       Configure::read('App.mode') );
    if ( file_exists($app_mode_config_file) )
        require_once($app_mode_config_file);

    /**
     * Domain-Specific Config Files
     * If a file with name match App.domain settings found in config/modes
     * folder, load it.  An example file:
     *
     * config/domains/cakewell.klenwell.com.php
     */
    $app_domain_config_file = sprintf( '%s%s/%s.php',
                                       CONFIGS,
                                       'domains',
                                       Configure::read('App.domain') );
    if ( file_exists($app_domain_config_file) )
        require_once($app_domain_config_file);

?>
