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

    function index()
    {
        $this->redirect('authwell/login');
    }

    function login()
    {
        trigger_error('in dev', E_USER_ERROR);
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
