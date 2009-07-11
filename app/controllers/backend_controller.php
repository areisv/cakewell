<?php

/*
    Cakewell Backend Controller

    Backend operations

    NOTES
        Protect this controller from general public access
*/

class BackendController extends AppController
{
    var $name = 'Backend';
    var $uses = null;   // or: array('ModelName');
    var $components = array('RequestHandler');

    function index()
    {
        // code
        // To Do ApiHelper

        // output
        $this->set('content_for_layout', 'backend controller: to do ApiHelper');
        $this->render('/layouts/blank');
    }
}

?>
