<?php
/*
    Cakewell Component Test Template
    Author: Tom at klenwell@gmail.com
    Last Update: $Date$
*/
App::import('Component', 'authwell.Auth');
App::import('Controller', 'authwell.Authwell');
App::import('Component', 'Session');

class AuthComponentTest extends CakeTestCase {

    var $fixtures = array(
        'plugin.authwell.authwell_user',
        'plugin.authwell.authwell_role',
        'plugin.authwell.authwell_privilege',
        'plugin.authwell.authwell_user_authwell_role',
        'plugin.authwell.authwell_role_authwell_privilege',
    );

    function setUp()
    {
        $this->AuthComponent = new AuthComponent();
        $Ctrl = new AuthwellController();
        # This does not work as it seems the fixture db has not yet been created
        #$Ctrl->constructClasses();
        # But we need a Session component to initialize the AuthComponent
        $Ctrl->Session = new SessionComponent();
        $this->AuthComponent->initialize($Ctrl);

        $this->UserDb = array(
            'AuthwellUser' => array(
                'id'        => 1,
                'email'     => 'cakewell@klenwell.com',
                'password'  => 'secret',
            ),
            'AuthwellRole' => array(
            ),
        );
    }

    function _setUpCtrl()
    {
        $this->AuthComponent->Ctrl->constructClasses();
    }

    function teardown()
    {
        unset($this->AuthComponent);
    }

    function testInstance() {
        $this->assertTrue(is_a($this->AuthComponent, 'AuthComponent'));
        $this->assertTrue(is_a($this->AuthComponent->Ctrl, 'AuthwellController'));
    }

    function testDiffDotpaths() {
        $keypath = 'a.b.c';
        $LockList = array(
            'a' => array(1=>'b',2=>'c'),    # 0
            'a.b' => array(2=>'c'),         # 0
            'a.b.c' => array(),             # 1
            'a.b.c.x' => array(),           # 1
            'a.b.x' => array(2=>'c'),       # 0
            'a.x' => array(1=>'b',2=>'c'),  # 0
            'x' => array('a','b','c'),      # 0
            'b' => array('a','b','c'),      # 0
            'b.c' =>array('a','b','c'),     # 0
            '*' => array(),                 # 1
            '*.b.c' => array(),             # 1
            '*.x' => array(1=>'b',2=>'c'),  # 0
        );

        foreach ( $LockList as $lockpath => $ExpectSet )
        {
            $DiffSet = $this->AuthComponent->_diff_dotpaths($keypath, $lockpath);
            #debug($DiffSet);
            $this->assertEqual($DiffSet, $ExpectSet);
        }
    }

    function testPrivilegeHasAccess() {
        $TestArray = array(
            #array(keypath, lockpath, expect)
            array('a.b.c', 'a.b.c', 1),
            array('a', 'a.b.c', 1),
            array('a.b.c', 'a', 0),
            array('', '*', 1),
            array('a.b', 'b.*', 0)
        );

        foreach ( $TestArray as $T_ )
            $this->assertEqual(
                $this->AuthComponent->_privilege_has_access($T_[0], $T_[1]),
                $T_[2] );
    }

    function testLoginRequest() {

        // first save a user
        $email = 'test@cakewell.com';
        $pw = 'password';
        $Model = ClassRegistry::init('Authwell.AuthwellUser');
        $RecordObj = new AuthwellUserRecord($Model);
        $Record = $RecordObj->create(array('email'=>$email,
            'password'=>$Model->password($pw)));
        $this->assertTrue($Model->save($Record));

        // then call
        $FormData = array(
            'AuthwellUser' => array(
                'email' => $email,
                'password' => $pw
            )
        );

        /* To use the controller Session component, we must construct its
           helper classes.  However, we can't do it in the setup (see note
           above, so we do it here */
        $this->_setUpCtrl();
        $is_logged_in = $this->AuthComponent->login_request($FormData);
        $SessionUser = $this->AuthComponent->Ctrl->Session->read('Authwell.User');

        $this->assertTrue($is_logged_in);
        $this->assertEqual($SessionUser['email'],$email);
    }

    function testGetUserData()
    {
        $this->AuthComponent->_login_user_to_session($this->UserDb);
        $UserData = $this->AuthComponent->get_user_data();
        $this->assertEqual( $UserData['id'],
                            $this->UserDb['AuthwellUser']['id'] );
        $this->assertEqual( $UserData['User'],
                            $this->UserDb['AuthwellUser'] );
    }

    function testLoginUserToSession()
    {
        $this->AuthComponent->_login_user_to_session($this->UserDb);

        $this->assertEqual( $this->AuthComponent->Ctrl->Session->read('Authwell.user_id'),
                            $this->UserDb['AuthwellUser']['id'] );
        $this->assertEqual( $this->AuthComponent->Ctrl->Session->read('Authwell.User'),
                            $this->UserDb['AuthwellUser'] );
        $this->assertEqual( $this->AuthComponent->Ctrl->Session->read('Authwell.login_attempt'),
                            0 );
    }

    function testClearUserSession()
    {
        $this->AuthComponent->_login_user_to_session($this->UserDb);
        $this->AuthComponent->_clear_user_session();

        $this->assertEqual( $this->AuthComponent->Ctrl->Session->read('Authwell.user_id'),
                            0 );
        $this->assertEqual( $this->AuthComponent->Ctrl->Session->read('Authwell.User'),
                            array() );
        $this->assertEqual( $this->AuthComponent->Ctrl->Session->read('Authwell.login_attempt'),
                            0 );
    }
}

?>
