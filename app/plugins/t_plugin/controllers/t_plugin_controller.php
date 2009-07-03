<?php

/*
    A Sample Plugin Controller

    Summary of controller here.

    REFERENCES
        http://book.cakephp.org/view/116/Plugin-Controllers
*/

class TPluginController extends AppController
{
    var $name = 'TPlugin';
    var $uses = array('TPlugin.TPlugin');   // or: null;
    #var $uses = null;
    var $components = array('RequestHandler');

    function index()
    {
        $this->redirect('/t_plugin/smoke_test');
    }

    function smoke_test()
    {
        $this->set('content', 'success');
        $this->render('content');
    }
}

?>
