<?php

/*
    Cakewell Gatekeeper Component
    Author: Tom at klenwell@gmail.com
    Last Update: $Date$

    Restricts controller/action access based on different non-ACL-based
    factors.

    USAGE (in controller)
        var $components = array('Gatekeeper');

        print $this->Gatekeeper->test();
*/



class GatekeeperComponent extends Object
{
    public $Ctrl = null;
    var $FlashTuple = array();

    // called before Controller:beforeFilter()
    function initialize()
    {
    }

    // called after Controller::beforeFilter()
    function startup(&$controller)
    {
        $this->Ctrl = $controller;
    }

    function beforeRender(&$controller)
    {
    }

    function restrict_to_domains($DomainList, $redirect_url=null, $message=null)
    {
        /*
            Restricts access *TO* the domains in the DomainList array.
        */
        if ( !$domain = Configure::Read('App.foo') )
            $domain = $_SERVER['SERVER_NAME'];

        if ( in_array($domain, $DomainList) )
            return 1;

        return $this->_restrict($redirect_url, $message);
    }

    function restrict_from_domains($DomainList, $redirect_url=null, $message=null)
    {
        /*
            Restricts access *TO* the domains in the DomainList array.
        */
        if ( !$domain = Configure::Read('App.foo') )
            $domain = $_SERVER['SERVER_NAME'];

        if ( !in_array($domain, $DomainList) )
            return 1;

        return $this->_restrict($redirect_url, $message);
    }


    function _restrict($redirect_url=null, $message=null)
    {
        // if redirect url, message -> flash
        if ( !empty($redirect_url) && !empty($message) )
        {
            #$this->Ctrl->flash($message, '/');
            return 0;
        }


        // if redirect, no message -> redirect
        elseif ( !empty($redirect_url) && empty($message) )
        {
            $this->Ctrl->redirect($redirect_url);
        }

        // if no redirect, message -> die
        elseif ( empty($redirect_url) && !empty($message) )
        {
            #$this->Ctrl->autoRender=false;
            #$this->Ctrl->set('content_for_layout', $message);
            #$this->Ctrl->render('/layouts/ajax', $this->Ctrl->layout);
            #$this->Ctrl->flash($message, '/');
        }

        // if no redirect, no message -> redirect home
        else
        {
            $this->Ctrl->redirect('/');
        }

        return 0;
    }

    function _flash($message, $redirect_url)
    {
        $this->FlashTuple = array($message, $redirect_url);
    }

    function test()
    {
        return sprintf('testing component %s for controller %s', __CLASS__, $this->Ctrl->name);
    }
}
?>
