<?php

/*
    A CakePhp Controller Template
    
    Summary of controller here.
    
    NOTES
        Controllers should be name _controller (e.g. posts_controller)
*/

class SampleController extends AppController
{
    var $name = 'Sample';
    var $uses = array('ModelName');   // or: null;
    var $components = array('RequestHandler');    

    function index()
    {
        // code

        // output
        $this->set('foo', 'bar');
        $this->render('view');
    }
}

?>