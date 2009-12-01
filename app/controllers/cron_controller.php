<?php

/*
    A CakePhp Controller Template

    For use with cakewell/webroot/cron.php.  Require php-cli.

    USAGE (from command line):
        $ php cakewell/webroot/cron.php /cron/test

    NOTES
        Bear in mind, the .htaccess file in webroot contains this line:

            RewriteCond %{REQUEST_FILENAME} !-f

        This means calls to /cron get routed to the webroot/cron.php rather
        than the index.php file.  Redirects, too, get pointed to cron.php,
        which can create redirect loops, if you rely on $this->redirect for
        exception-handling.  Better usually just to die or throw a fatal error.

        For more info, see:
        http://bakery.cakephp.org/articles/view/calling-controller-actions-from-cron-and-the-command-line
*/

class CronController extends AppController
{
    var $name = 'Cron';
    var $uses = null; #array('ModelName');   // or: null;
    var $components = array('RequestHandler');
    var $layout = 'blank';

    function beforeFilter()
    {
        // check CAKEWELL_CRON constant, set in webroot/cron.php
        if ( !defined('CRON_OK') )
            die('cron exception: cron flag not set by dispatcher');
    }

    function index()
    {
        $this->test();
    }

    function exception()
    {
        die("\ncron error: cron must be called from the command line\n\n");
    }

    function test()
    {
        $this->set('content_for_layout', "\ncakewell cron test successful\n\n");
        $this->render('/layouts/blank');
    }
}

?>
