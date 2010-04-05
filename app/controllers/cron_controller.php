<?php

/*
    A CakePhp Controller Template

    For use with cakewell/webroot/cron.php.  Require php-cli.

    USAGE (from command line):
        $ php cakewell/webroot/cron.php /cron/test <domain>

    NOTES
        The final parameter sets the server context based on the
        $ConfigDomainMap settings in the core.php config file.

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
    var $uses = array('SimpleLog');
    var $components = array('RequestHandler');
    var $layout = 'blank';

    function beforeFilter()
    {
        // check CAKEWELL_CRON constant, set in webroot/cron.php
        if ( !defined('CAKEWELL_CRON') )
            die('cron exception: cron flag not set by dispatcher');
    }

    function simple_log($message=NULL)
    {
        $type = 'system';
        $keyword = 'cron';

        if ( empty($message) ) {
            $message = '/cron/simple_log task run';
        }

        $result = $this->SimpleLog->log($type, $keyword, $message);
        $this->set('content_for_layout', sprintf("\n%s\n", print_r($result,1)));
        $this->render('/layouts/blank');
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
        $stdoutf = <<<XSTD
cakewell cron test successful

SERVER:
%s

XSTD;
        $this->set('content_for_layout', sprintf($stdoutf, print_r($_SERVER,1)));
        $this->render('/layouts/blank');
    }
}

?>
