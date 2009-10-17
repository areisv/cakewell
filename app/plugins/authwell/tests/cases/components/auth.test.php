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
            'User' => array(
                'id'        => 1,
                'email'     => 'cakewell@klenwell.com',
                'password'  => 'secret',
            ),
            'Roles' => array(
            ),
            'Privileges' => array(
            ),
        );
    }

    function _setUpCtrl()
    {
        $this->AuthComponent->Ctrl->constructClasses();
    }

    function _setUpDatabase()
    {
        $this->_setUpCtrl();
        $Ctrl = $this->AuthComponent->Ctrl;
        $UserModel = $Ctrl->AuthwellUser;
        $RoleModel = $UserModel->AuthwellRole;
        $PrivModel = $RoleModel->AuthwellPrivilege;
        $UserGen = new AuthwellUserRecord($UserModel);
        $RoleGen = new AuthwellRoleRecord($RoleModel);
        $PrivGen = new AuthwellPrivilegeRecord($PrivModel);

        $User1 = $UserGen->create(array('name'=>'user1', 'email'=>'user1@klenwell.com', 'password'=>'user1'));
        $User2 = $UserGen->create(array('name'=>'user2', 'email'=>'user2@klenwell.com', 'password'=>'user2'));
        $Role1 = $RoleGen->create(array('name'=>'role1'));
        $Role2 = $RoleGen->create(array('name'=>'role2'));
        $Priv1 = $PrivGen->create(array('dotpath'=>'priv.one'));
        $Priv2 = $PrivGen->create(array('dotpath'=>'priv.two'));

        $PrivModel->save($Priv1);
        $PrivIds[] = $PrivModel->id;
        $PrivModel->save($Priv2);
        $PrivIds[] = $PrivModel->id;

        $RoleModel->save( array('AuthwellRole'=>$Role1,
                            'AuthwellPrivilege'=>array(1=>$PrivIds[0],2=>$PrivIds[1])) );
        $RoleIds[] = $RoleModel->id;
        $RoleModel->save( array('AuthwellRole'=>$Role2,
                            'AuthwellPrivilege'=>array(1=>$PrivIds[0])) );
        $RoleIds[] = $RoleModel->id;

        $UserModel->save( array('AuthwellUser'=>$User1,
                            'AuthwellRole'=>array(1=>$RoleIds[0],2=>$RoleIds[1])) );
        $UserModel->save( array('AuthwellUser'=>$User2,
                            'AuthwellRole'=>array(1=>$RoleIds[0])) );

        return;
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

    function testGetUserData()
    {
        $this->AuthComponent->login_user_to_session($this->UserDb);
        $UserData = $this->AuthComponent->get_user_data();
        $this->assertEqual( $UserData['id'],
                            $this->UserDb['User']['id'] );
        $this->assertEqual( $UserData['User'],
                            $this->UserDb['User'] );
    }

    function testLoginUserToSession()
    {
        $this->AuthComponent->login_user_to_session($this->UserDb);

        $this->assertEqual( $this->AuthComponent->Ctrl->Session->read('Authwell.user_id'),
                            $this->UserDb['User']['id'] );
        $this->assertEqual( $this->AuthComponent->Ctrl->Session->read('Authwell.User'),
                            $this->UserDb['User'] );
        $this->assertEqual( $this->AuthComponent->Ctrl->Session->read('Authwell.login_attempt'),
                            0 );
    }

    function testClearUserSession()
    {
        $this->AuthComponent->login_user_to_session($this->UserDb);
        $this->AuthComponent->_clear_user_session();

        $this->assertEqual( $this->AuthComponent->Ctrl->Session->read('Authwell.user_id'),
                            0 );
        $this->assertEqual( $this->AuthComponent->Ctrl->Session->read('Authwell.User'),
                            array() );
        $this->assertEqual( $this->AuthComponent->Ctrl->Session->read('Authwell.login_attempt'),
                            0 );
    }


    function testAuthComplete()
    {
        $FormData = array(
            'AuthwellUser' => array(
                'email_login' => 'user1@klenwell.com',
                'password_login' => 'user1'
            )
        );

        $this->_setUpDatabase();
        $is_logged_in = $this->AuthComponent->Ctrl->AuthwellUser->is_valid_login_request($FormData);
        $this->AuthComponent->login_user_to_session(
            $this->AuthComponent->Ctrl->AuthwellUser->UserDataCache );
        $UserData = $this->AuthComponent->get_user_data();

        $this->assertTrue($this->AuthComponent->user_has_role('role1'));
        $this->assertFalse($this->AuthComponent->user_has_role('null'));
        $this->assertTrue($this->AuthComponent->user_has_privilege('priv.one'));
        $this->assertFalse($this->AuthComponent->user_has_privilege('priv'));
        $this->assertFalse($this->AuthComponent->user_has_privilege('priv.null'));
        $this->assertTrue($this->AuthComponent->user_has_privilege('priv.one.null'));
    }

}

?>
