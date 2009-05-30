<?php

/** Documentation

Sample CakePhp Component Class
Last Update: $Date$

A very simple example of a component.  Useful as a template.

Usage (in controller):
    var $components = array('Sample');
    
    print $this->Sample->test();

______________________________________________________________________________*/

class SampleComponent extends Object 
{
    public $Ctrl = null;
  
    // called before Controller:beforeFilter()
    function initialize() 
    {
    }

    // called after Controller::beforeFilter()
    function startup(&$controller) 
    {
        $this->Ctrl = $controller;
    }

    function test() 
    {
        return sprintf('testing component %s for controller %s', __CLASS__, $this->Ctrl->name);
    }    
}
?>
