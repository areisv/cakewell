<?php

/*
    Cakewell Gatekeeper Component
    Author: Tom at klenwell@gmail.com
    Last Update: $Date$

    Restricts controller/action access based on different non-ACL-based
    factors.

    USAGE (in controller)
        var $components = array('Gatekeeper');

        $this->Gatekeeper->restrict_to_app_models(array('test'), '/restrict/');
*/



class GatekeeperComponent extends Object
{
    var $Ctrl = null;
    var $components = array( 'Session' );

    // called before Controller:beforeFilter()
    function initialize(&$controller) {
        $this->Ctrl = $controller;
    }

    // called after Controller::beforeFilter()
    function startup(&$controller) {
    }


    function restrict_to_domains($DomainList, $redirect_url=null, $message=null)
    {
        /*
            Restricts access *TO* the domains in the DomainList array.
        */
        if ( !$domain = Configure::Read('App.server_name') )
            $domain = $_SERVER['SERVER_NAME'];

        if ( in_array($domain, $DomainList) )
            return 1;

        return $this->_restrict($redirect_url, $message);
    }

    function restrict_from_domains($DomainList, $redirect_url=null, $message=null)
    {
        /*
            Restricts access *FROM* the domains in the DomainList array.
        */
        if ( !$domain = Configure::Read('App.server_name') )
            $domain = $_SERVER['SERVER_NAME'];

        if ( !in_array($domain, $DomainList) )
            return 1;

        return $this->_restrict($redirect_url, $message);
    }

    function restrict_to_app_modes($ModeList, $redirect_url=null, $message=null)
    {
        /*
            This method bases restriction on the App.mode configuration setting
            specific to the Cakewell configuration model.
        */
        if ( !$mode = Configure::Read('App.mode') )
        {
            trigger_error('App.mode configuration value not found', E_USER_ERROR);
            die('access denied by gatekeeper');
        }

        if ( in_array($mode, $ModeList) )
            return 1;

        return $this->_restrict($redirect_url, $message);
    }

    function restrict_from_app_modes($ModeList, $redirect_url=null, $message=null)
    {
        /*
            This method bases restriction on the App.mode configuration setting
            specific to the Cakewell configuration model.
        */
        if ( !$mode = Configure::Read('App.mode') )
        {
            trigger_error('App.mode configuration value not found', E_USER_ERROR);
            die('access denied by gatekeeper');
        }

        if ( !in_array($mode, $ModeList) )
            return 1;

        return $this->_restrict($redirect_url, $message);
    }


    function get_controller_methods($CtrlObj, $filter_=1)
    {
        $MethodList = array_values(
            array_diff( get_class_methods($CtrlObj), get_class_methods('AppController') )
        );
        if ( $filter_ )
            foreach ( range(0,count($MethodList)-1) as $i )
                if ( substr($MethodList[$i],0,1) == '_' )
                    unset($MethodList[$i]);
        return $MethodList;
    }

    function get_controller_menu($CtrlObj, $filter=1)
    {
        $menu_html = '';
        $MenuList = $this->get_controller_methods($CtrlObj, $filter);
        foreach ( $MenuList as $m )
            $menu_html .= sprintf('<li><a href="/%s/%s">%s</a></li>%s',
                $CtrlObj->viewPath, $m, $m, "\n");
        return $menu_html;
    }


    function _restrict($redirect_url=null, $message=null)
    {
        // if redirect url, message -> flash
        if ( !empty($redirect_url) && !empty($message) )
        {
            $this->Session->setFlash($message);
            $this->Ctrl->redirect($redirect_url);
        }

        // if redirect, no message -> redirect
        elseif ( !empty($redirect_url) && empty($message) )
        {
            $this->Ctrl->redirect($redirect_url);
        }

        // if no redirect, message -> redirect to home
        elseif ( empty($redirect_url) && !empty($message) )
        {
            $this->Session->setFlash($message);
            $this->Ctrl->redirect('/');
        }

        // if no redirect, no message -> redirect home
        else
        {
            $this->Session->setFlash(sprintf('%s is not accessible', $this->Ctrl->here));
            $this->Ctrl->redirect('/');
        }

        die(sprintf('%s is not accessible', $this->Ctrl->here));
    }

    function test()
    {
        return sprintf('testing component %s for controller %s', __CLASS__, $this->Ctrl->name);
    }
}
?>
