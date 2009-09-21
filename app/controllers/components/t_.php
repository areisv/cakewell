<?php

/*
    A CakePhp Component Template
    Author: Tom at klenwell@gmail.com
    Last Update: $Date$

    A very simple example of a component.  Useful as a template.

    USAGE (in controller)
        var $components = array('Sample');
        ...
        print $this->Sample->test();

    NOTES
        Controllers should be name _controller (e.g. posts_controller)
*/



class SampleComponent extends Object
{
    public $Ctrl = null;

    // called before Controller:beforeFilter()
    function initialize(&$controller)
    {
        $this->Ctrl = $controller;
    }

    // called after Controller::beforeFilter()
    function startup(&$controller)
    {
    }

    function test()
    {
        return sprintf('testing component %s for controller %s', __CLASS__, $this->Ctrl->name);
    }
}
?>
