<?php

/*
    A CakePhp Controller Template

    For use with cakewell/webroot/cron.php.  Require php-cli.

    USAGE (from command line):
        $ php cakewell/webroot/cron.php /cron/test

    NOTES
        For more info, see:
        http://bakery.cakephp.org/articles/view/calling-controller-actions-from-cron-and-the-command-line
*/

class CronController extends AppController
{
    var $name = 'Cron';
    var $uses = null; #array('ModelName');   // or: null;
    var $components = array('RequestHandler', 'Gatekeeper');
    var $layout = 'blank';

    function beforeFilter()
    {
        // check CAKEWELL_CRON constant, set in webroot/cron.php
        if ( !defined('CAKEWELL_CRON') )
            $this->Gatekeeper->_restrict('/demo',
                'cron actions are restricted to backend');
    }

    function index()
    {
        $this->test();
    }

    function exception()
    {
        $this->Gatekeeper->_restrict('/demo',
                'cron failure: cron actions are restricted to backend');
    }

    function test()
    {
        $this->set('content_for_layout', 'cron test successful');
        $this->render('/layouts/blank');
    }
}

?>
