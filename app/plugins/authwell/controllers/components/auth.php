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

App::import('Sanitize');

class AuthComponent extends Object
{
    public $name = 'Auth';
    public $Ctrl = null;

    public $UserData = array();
    public $checkout_max = 3600;
    public $invalidLoginFields = array();
    public $max_attempts = 8;

    // called before Controller:beforeFilter()
    function initialize(&$controller)
    {
        $this->Ctrl = $controller;
        $this->Session = $controller->Session;
        #$this->User = $this->_load_user();
    }

    // called after Controller::beforeFilter()
    function startup(&$controller)
    {
    }

    // Authwell Auth API (still in development)
    function user_is_logged_in()
    {
        return $this->Session->check('Authwell.user_id');
    }

    function user_has_privilege($PrivilegeList, $conjunction='or')
    {
        /*
            Privilege List is a list of privilege notation strings (e.g.
            perqs.expense_account or perqs.company_car) that this function checks
            to see if the user possesses.  conjunction argument determines
            and/or logic of the PrivilegeList
        */
        if ( !$this->is_logged_in() ) return $this->turn_away('please log in');

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

    function user_has_role($RoleList, $conjunction='or')
    {
        if ( !$this->is_logged_in() ) return $this->turn_away('you must be logged in');
        if ( !is_array($RoleList) ) $RoleList = array( $RoleList );

        if ( $conjunction == 'or' )
            foreach ( $RoleList as $role )
                if ( $this->_user_has_this_role($role) ) return 1;

        else
            foreach ( $RoleList as $role )
                if ( !$this->_user_has_this_role($role) ) return 0;

        trigger_error('logic error', E_USER_ERROR);
    }

    function get_user_data()
    {
        $UserData = array();
        if ( !empty($this->UserData) ) return $this->UserData;

        $UserData['id'] = ( $this->Ctrl->Session->check('Authwell.user_id') ) ?
            $this->Ctrl->Session->read('Authwell.user_id') : 0;
        $UserData['User'] = ( $this->Ctrl->Session->check('Authwell.User') ) ?
            $this->Ctrl->Session->read('Authwell.User') : array();
        $UserData['Roles'] = ( $this->Ctrl->Session->check('Authwell.UserRoles') ) ?
            $this->Ctrl->Session->read('Authwell.UserRoles') : array();
        $UserData['Privileges'] = ( $this->Ctrl->Session->check('Authwell.UserPrivileges') ) ?
            $this->Ctrl->Session->read('Authwell.UserPrivileges') : array();

        $this->UserData = $UserData;
        return $UserData;
    }


    // login methods
    function login_request($FormData)
    {
        // try to limit attacks
        if ( $this->_is_login_attack() ) $this->turn_away('you are being redirected');

        // validate form
        $CharWhiteList = str_split('.@_ ');
        $email_ = Sanitize::paranoid($FormData['AuthwellUser']['email'], $CharWhiteList);
        $pass_ = $FormData['AuthwellUser']['password'];
        if ( empty($email_) ) return $this->invalidate_login('AuthwellUser.email', 'please fill in both fields');
        if ( empty($pass_) ) return $this->invalidate_login('AuthwellUser.password', 'please fill in both fields');

        // check db
        $UserDb = $this->Ctrl->AuthwellUser->findByEmail($email_);
        if ( empty($UserDb) )
            return $this->invalidate_login('AuthwellUser.email', 'user not found');
        if ( $UserDb['AuthwellUser']['password'] != $this->Ctrl->AuthwellUser->password($pass_) )
            return $this->invalidate_login('AuthwellUser.password', 'incorrect password');

        // still here: login
        return $this->_login_user_to_session($UserDb);
    }

    function show_login()
    {
        $this->Session->save('Authwell.login_redirect', $this->here);
        $this->redirect($this->Ctrl->login_url);
    }

    function turn_away($message=null)
    {
        if ( is_null($message) ) $message = 'page is not currently available';
        return $this->Ctrl->flash($this->Ctrl->lockout_url, $message);
    }

    function invalidate_login($key, $message)
    {
        $this->invalidLoginFields[$key] = $message;
        return 0;
    }


    /* Private Methods */
    function _user_has_this_role($role)
    {
        $User = $this->get_user_data();
        return in_array($role, $User['Roles']);
    }

    function _user_has_privilege_by_dotpath($lock_dotpath)
    {
        $User = $this->get_user_data();
        foreach ( $User['Privileges'] as $key_dotpath )
            if ( $this->_privilege_has_access($key_dotpath, $lock_dotpath))
                return 1;
        return 0;
    }

    function _privilege_has_access($key_dotpath, $lock_dotpath)
    {
        return !(int)(bool)$this->_diff_dotpaths($key_dotpath, $lock_dotpath);
    }

    function _diff_dotpaths($key_dotpath, $lock_dotpath)
    {
        $DiffSet = array();
        $KeySet = explode('.', $key_dotpath);
        $LockSet = explode('.', $lock_dotpath);
        $depth_delta = count($KeySet) - count($LockSet);

        if ( $depth_delta > 0 )
            foreach( range(1,$depth_delta) as $n )
                $LockSet[] = ( $LockSet[count($LockSet)-1] == '*' ) ? '*' : '!';

        $SetDiff = Set::diff($KeySet, $LockSet);
        if ( $SetDiff )
            foreach ( $KeySet as $n => $x )
                if ( isset($SetDiff[$n]) && $LockSet[$n] != '*' )
                    $DiffSet[$n] = $x;

        return $DiffSet;
    }

    function _extract_privileges_from_role_list($RoleList)
    {
        return array_unique(Set::extract('/dotpath.', $RoleList));
    }

    function _login_user_to_session($UserDb)
    {
        $this->Session->write('Authwell.user_id', $UserDb['AuthwellUser']['id']);
        $this->Session->write('Authwell.User', $UserDb['AuthwellUser']);
        $this->Session->write('Authwell.UserRoles', $UserDb['AuthwellRole']);
        $this->Session->write('Authwell.UserPrivileges',
            $this->_extract_privileges_from_role_list($UserDb['AuthwellRole']));
        $this->Session->write('Authwell.login_attempt', 0);
        return 1;
    }

    function _clear_user_session()
    {
        $this->Session->write('Authwell.user_id', 0);
        $this->Session->write('Authwell.User', array());
        $this->Session->write('Authwell.UserRoles', array());
        $this->Session->write('Authwell.UserPrivileges', array());
        $this->Session->write('Authwell.login_attempt', 0);
        return 1;
    }

    function _is_login_attack()
    {
        $attempt_ = ( ! $this->Session->check('Authwell.login_attempt') ) ? 0
            : $this->Session->read('Authwell.login_attempt');
        if ( $attempt_ > $this->max_attempts )
            return 1;
        $this->Session->write('Authwell.login_attempt', $attempt_++);
        return 0;
    }


    /* Debug Methods */
    function test()
    {
        return sprintf('testing component %s for controller %s', __CLASS__, $this->Ctrl->name);
    }
}
?>
