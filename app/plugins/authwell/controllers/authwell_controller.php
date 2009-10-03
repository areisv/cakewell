<?php

/*
    Authwell Plugin Controller

    The Cakewell Authentication Handler

    NOTES
        Authentication databases has three entities: users, roles, privileges.
        Roles are groups of 1 or more privileges and users can have multiple (or
        no) roles.  Access to a web page can be restricted on the bases of
        roles or privileges.  And privilges are hierarchical with a dot
        notation syntax.

        So, for instance, imagine a user with a single role that include the
        single privilege: 'a.b.c'.

        An access chart for other privilege-protect page:

        'a'         : 0
        'a.b'       : 0
        'a.b.c'     : 1
        'a.b.c.x'   : 1
        'a.b.x'     : 0
        'a.x'       : 0
        'x'         : 0
        'b'         : 0
        'b.c'       : 0
        '*'         : 1
        '*.b.c'     : 1
        '*.x'       : 0


    REFERENCES
        http://book.cakephp.org/view/116/Plugin-Controllers
*/

class AuthwellController extends AppController
{
    var $name = 'Authwell';
    var $uses = array('Authwell.AuthwellUser',
                      'Authwell.AuthwellRole',
                      'Authwell.AuthwellPrivilege');   // or: null;
    var $components = array('RequestHandler', 'Gatekeeper', 'Authwell.Auth');

    var $login_url      = '/authwell/login';
    var $lockout_url    = '/authwell/locked';

    function index()
    {
        $this->redirect('authwell/login');
    }

    function login()
    {
        trigger_error('in dev', E_USER_ERROR);

        # login request
        # if POST:
            # $this->Auth->
    }

    function logout()
    {
        # logout

        # redirect to login page
    }

    function locked()
    {

    }

    /* Private Methods */
    function _process_login_request($FormData)
    {
        // try to limit attacks
        $attempt_ = ( ! $this->Ctrl->Session->check('login_attempt') ) ? 0
            : $this->Ctrl->Session->read('login_attempt');
        if ( $attempt_ > $this->max_attempts ) return $this->turn_away();
        $this->Ctrl->Session->write('login_attempt', $attempt_++);

        // validate form
        $CharWhiteList = str_split('.@_ ');
        $email_ = Sanitize::paranoid($FormData['Editor']['email'], $CharWhiteList);
        $pass_ = Sanitize::paranoid($FormData['Editor']['password'], $CharWhiteList);
        if ( empty($email_) ) return $this->invalidate_login('Editor.email', 'please fill in both fields');
        if ( empty($pass_) ) return $this->invalidate_login('Editor.password', 'please fill in both fields');

        // check db
        $UserDb = $this->Ctrl->Editor->findByEmail($email_);
        if ( empty($UserDb) ) return $this->invalidate_login('Editor.email', 'user not found');
        if ( $UserDb['Editor']['password'] != $this->Ctrl->Editor->password($pass_) )
            return $this->invalidate_login('Editor.password', 'incorrect password');
        if ( $UserDb['Editor']['role'] == FT_ROLE_FIRED ) return $this->turn_away();

        // still here: login
        #pr( $UserDb );
        $User_ = explode('@', $UserDb['Editor']['email']);
        unset($UserDb['Editor']['password']);
        $UserDb['Editor']['name'] = $User_[0];
        $this->Ctrl->Session->write('user_id', $UserDb['Editor']['id']);
        $this->Ctrl->Session->write('user_role', $UserDb['Editor']['role']);
        $this->Ctrl->Session->write('user_name', $UserDb['Editor']['name']);
        $this->Ctrl->Session->write('user_data', $UserDb['Editor']);
        $this->Ctrl->Session->write('login_attempt', 0);
        return 1;
    }

    function debug()
    {
        $this->Gatekeeper->restrict_from_app_modes( array('production'),
            '/demo/',
            'this action is blocked in production mode');

        # assert tests
        assert($this->AuthwellUser instanceof AuthwellAppModel);
        assert($this->AuthwellUser->AuthwellRole instanceof AuthwellAppModel);
        assert($this->Auth instanceof AuthComponent);

        # output
        $content_t = <<<XHTML
<h3>%s Controller Debug</h3>
<pre>
%s
</pre>
XHTML;
        $this->set('content_for_layout', sprintf($content_t, __CLASS__, print_r($this,1)));
        $this->render('/layouts/blank', 'default');
    }
}

?>
