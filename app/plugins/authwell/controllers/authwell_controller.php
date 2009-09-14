<?php

/*
    Authwell Plugin Controller

    Summary of controller here.

    REFERENCES
        http://book.cakephp.org/view/116/Plugin-Controllers
*/

class AuthwellController extends AppController
{
    var $name = 'Authwell';
    #var $uses = array('Authwell.AuthwellUser');   // or: null;
    var $uses = null;
    var $components = array('RequestHandler');

    function index()
    {
    }

    function smoke_test()
    {
        $this->set('content_for_layout', sprintf('%s smoke test', __CLASS__));
        $this->render('/layouts/blank', 'default');
    }
}

?>
