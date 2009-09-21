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



class AuthComponent extends Object
{
    public $Ctrl = null;

    // called before Controller:beforeFilter()
    function initialize(&$controller)
    {
        $this->Ctrl = $controller;
        $this->User = $this->_load_user();
    }

    // called after Controller::beforeFilter()
    function startup(&$controller)
    {
    }

    // Authwell Auth API (still in development)
    function user_has_privilege($PrivilegeList, $conjunction='or')
    {
        /*
            Privilege List is a list of privilege notation strings (e.g.
            perqs.expense_account or perqs.company_car) that this function checks
            to see if the user possesses.  conjunction argument determines
            and/or logic of the PrivilegeList
        */
        if ( !$this->User ) $this->show_login();

        if ( !is_array($PrivilegeList) )
            $PrivilegeList = array( $PrivilegeList );

        if ( $conjunction == 'or' )
        {
            foreach ( $PrivilegeList as $dotpath )
                if ( $this->_user_has_privilege_by_dotpath($dotpath) )
                    return 1;
            return 0;
        }
        else # ( $conjunction == 'and' )
        {
            foreach ( $PrivilegeList as $dotpath )
                if ( !$this->_user_has_privilege_by_dotpath($dotpath) )
                    return 0;
            return 1;
        }

        trigger_error('in dev', E_USER_ERROR);
    }

    function user_has_role($role, $conjunction='or')
    {
        trigger_error('in dev', E_USER_ERROR);
    }

    function _user_has_privilege_by_dotpath($dotpath)
    {
        trigger_error('in dev', E_USER_ERROR);
    }

    function show_login()
    {
        $this->Session->save('Authwell.login_redirect', $this->here);
        $this->redirect('/authwell/login');
    }

    function _load_user()
    {
        $UserData = array();
        if ( $this->Ctrl->Session->check('Authwell.ActiveUser') )
           $UserData = $this->Ctrl->Session->read('Authwell.ActiveUser');
        return $UserData;
    }

    function test()
    {
        return sprintf('testing component %s for controller %s', __CLASS__, $this->Ctrl->name);
    }
}
?>
