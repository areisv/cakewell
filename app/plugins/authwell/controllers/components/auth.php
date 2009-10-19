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

    public $UserData            = array();
    public $checkout_max        = 3600;
    public $invalidLoginFields  = array();
    public $max_attempts        = 8;

    # message
    public $msg_login           = 'please login';

    private $beforeRender_block = 0;

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
    function require_privilege($PrivilegeList, $conjunction='or')
    {
        if ( !$this->user_is_logged_in() ) {
            $this->require_login($this->msg_login);
        }

        if ( !$this->user_has_privilege($PrivilegeList, $conjunction) ) {
            $this->_block_user();
        }

        return;
    }

    function require_login($flash='')
    {
        if ( $flash ) $this->flash($flash);
        $this->set_login_callback_url($this->Ctrl->here);
        $this->Ctrl->redirect('/authwell/login');
        die();
    }

    function user_is_logged_in()
    {
        return (bool) $this->Session->read('Authwell.user_id');
    }

    function user_has_privilege($PrivilegeList, $conjunction='or')
    {
        /*
            Privilege List is a list of privilege notation strings (e.g.
            perqs.expense_account or perqs.company_car) that this function checks
            to see if the user possesses.  conjunction argument determines
            and/or logic of the PrivilegeList
        */
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

        trigger_error('logic error', E_USER_ERROR);
    }

    function user_has_role($RoleNameList, $conjunction='or')
    {
        if ( !$this->user_is_logged_in() ) return $this->turn_away('you must be logged in');
        if ( !is_array($RoleNameList) ) $RoleNameList = array( $RoleNameList );

        if ( $conjunction == 'or' )
            foreach ( $RoleNameList as $role )
                if ( $this->_user_has_this_role_name($role) ) return 1;

        else
            foreach ( $RoleNameList as $role )
                if ( !$this->_user_has_this_role_name($role) ) return 0;

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

    function redirect_login_callback()
    {
        $this->Ctrl->redirect($this->get_login_callback_url());
        die();
    }

    function set_login_callback_url($url=null)
    {
        if ( empty($url) ) $url = $this->Ctrl->here;
        $this->Session->write('Authwell.login_callback_url', $url);
    }

    function get_login_callback_url()
    {
        $url = $this->Session->read('Authwell.login_callback_url');
        $this->Session->delete('Authwell.login_callback_url');
        return $url;
    }


    // login methods
    function logout()
    {
        $this->_clear_user_session();
    }

    function show_login()
    {
        $this->Session->save('Authwell.login_redirect', $this->here);
        $this->Ctrl->redirect($this->Ctrl->login_url);
        die();
    }

    function turn_away($message=null)
    {
        if ( is_null($message) ) $message = 'page is not currently available';
        return $this->Ctrl->flash($this->Ctrl->lockout_url, $message);
    }

    function lockout($message=null)
    {
        if ( !empty($message) ) $this->flash($message);
        $this->Ctrl->redirect('/authwell/unavailable');
        die();
    }

    function flash($message)
    {
        $this->Session->write('Authwell.flash', $message);
    }


    /* Private Methods */
    function _user_has_this_role_name($role_name)
    {
        $UserData = $this->get_user_data();
        $UserRoleNames = Set::extract($UserData['Roles'], '{n}.name');
        return in_array($role_name, $UserRoleNames);
    }

    function _user_has_privilege_by_dotpath($lock_dotpath)
    {
        $User = $this->get_user_data();
        foreach ( $User['Privileges'] as $Record ) {
            $key_dotpath = $Record['dotpath'];
            if ( $this->_privilege_has_access($key_dotpath, $lock_dotpath))
                return 1;
        }
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

    function login_user_to_session($UserDb)
    {
        $this->Session->write('Authwell.user_id', $UserDb['User']['id']);
        $this->Session->write('Authwell.User', $UserDb['User']);
        $this->Session->write('Authwell.UserRoles', $UserDb['Roles']);
        $this->Session->write('Authwell.UserPrivileges', $UserDb['Privileges']);
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

    function is_login_attack()
    {
        $attempt_ = ( ! $this->Session->check('Authwell.login_attempt') ) ? 0
            : $this->Session->read('Authwell.login_attempt');
        if ( $attempt_ > $this->max_attempts )
            return 1;
        $this->Session->write('Authwell.login_attempt', $attempt_++);
        return 0;
    }

    function _catch_blocked_user()
    {
        if ( !$this->_user_is_blocked() ) {
            debug('not blocked');
            return;
        }
    }

    function cake_error($ParamList=array())
    {
        $view_dir = dirname(dirname(dirname(__FILE__))) . DS . 'views' . DS;
        $render_file = '/authwell/error';

        # defaults
        $header = 'Page Unavailable';
        $message = 'You are not authorized to view this page.';
        $code = 200;
        extract($ParamList, EXTR_OVERWRITE);

        # header (does this work?)
        $this->Ctrl->header($code);

        # set message and $header
        $this->Ctrl->set('header', $header);
        $this->Ctrl->set('message', $message);

        # must hack the view path here to add plugin view dir because I can't
        # find any other way to set an absolute path for the view path
        $ViewPathList = Configure::read('viewPaths') + array( $view_dir );
        Configure::write('viewPaths', $ViewPathList);

        # mimics error output to shortcut view output by the controller action
        $this->Ctrl->render(null, null, $render_file);
	$this->Ctrl->afterFilter();
	echo $this->Ctrl->output;
        $this->Ctrl->_stop();
    }


    function _block_user()
    {
        $ParamList = array(
            'code'      => 403,
            'header'    => 'Page Unavailable',
            'message'   => 'You are not authorized to view this page',
        );
        $this->cake_error($ParamList);
    }

    function _user_is_blocked()
    {
        debug('is blocked?');
        debug($this->Session->read('Authwell'));

        if ( !$this->Session->check('Authwell.block_user') )
            $this->Session->write('Authwell.block_user', 0);

        $is_blocked = $this->Session->read('Authwell.block_user');
        $this->Session->write('Authwell.block_user', 0);

        return $is_blocked;
    }


    /* Debug Methods */
    function test()
    {
        return sprintf('testing component %s for controller %s', __CLASS__, $this->Ctrl->name);
    }
}
?>
