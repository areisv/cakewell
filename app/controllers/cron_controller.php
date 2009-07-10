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
    #var $components = array('RequestHandler');
    var $layout = 'blank';

    function index()
    {
        $this->test();
    }

    function test()
    {
        $this->set('content_for_layout', 'cron test successful');
        $this->render('/layouts/blank');
    }
}

?>
