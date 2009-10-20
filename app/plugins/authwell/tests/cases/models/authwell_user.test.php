<?php
/*
    Authwell User Model Unit Test

    NOTES

    REFERENCES
    http://book.cakephp.org/view/485/Testing-plugins
*/

class AuthwellUserTestCase extends CakeTestCase {

    var $AuthwellUser = null;
    var $fixtures = array(
            'plugin.authwell.authwell_user',
            'plugin.authwell.authwell_role',
            'plugin.authwell.authwell_privilege',
            'plugin.authwell.authwell_user_authwell_role',
            'plugin.authwell.authwell_role_authwell_privilege',
        );

    function start()
    {
        parent::start();
        $this->AuthwellUser = ClassRegistry::init('Authwell.AuthwellUser');
        $this->RecordObj = new AuthwellUserRecord($this->AuthwellUser);
        $this->RoleRecordObj = new AuthwellRoleRecord($this->AuthwellUser->AuthwellRole);
        $this->PrivRecordObj = new AuthwellPrivilegeRecord(
            $this->AuthwellUser->AuthwellRole->AuthwellPrivilege);
    }

    function testInstance() {
        $this->assertTrue($this->AuthwellUser instanceof AppModel);
        #debug(get_class($this->AuthwellUser));
    }

    function testProperties()
    {
        $this->assertEqual($this->AuthwellUser->useTable, 'authwell_users');
        $this->assertEqual($this->AuthwellUser->name, 'AuthwellUser');
    }

    function testSchema()
    {
        $Cols = array_keys($this->AuthwellUser->_schema);
        $this->assertEqual(7, count($Cols));
        #debug($this->AuthwellUser->_schema);
        #debug(implode(' ', $Cols));
    }

    function testHabtmAssociation()
    {
        $this->assertTrue(isset($this->AuthwellUser->AuthwellRole));
        $this->assertTrue($this->AuthwellUser->AuthwellRole instanceof AuthwellRole);
        #debug($this->AuthwellUser);
    }

    function testRecord()
    {
        $name = 'ichibod';
        $Record = $this->RecordObj->create(array('name'=>$name));
        $this->assertEqual($Record['name'], $name);
    }

    function testInsertDeleteRecords()
    {
        $Data = $this->AuthwellUser->save($this->RecordObj->create());
        $this->assertTrue($this->AuthwellUser->id);
        $this->AuthwellUser->delete($this->AuthwellUser->id, 0);
    }

    function testFindByEmail()
    {
        $email = 'test@cakewell.com';
        $Record = $this->RecordObj->create(array('email'=>$email));
        $this->AuthwellUser->save($Record);
        $SavedRecord = $this->AuthwellUser->findByEmail($email);
        $NotFound = $this->AuthwellUser->findByEmail('not@cakewell.com');

        $this->assertEqual($SavedRecord['AuthwellUser']['email'], $email);
        $this->assertEqual($NotFound, false);
    }

    function testFindUserByEmail()
    {
        $Record = $this->RecordObj->create();
        $this->AuthwellUser->save($Record);
        $SavedRecord = $this->AuthwellUser->find_user_by_email($Record['email'], 1);
        $NotFound = $this->AuthwellUser->find_user_by_email('not@cakewell.com', 1);

        $this->assertEqual($SavedRecord['User']['email'], $Record['email']);
        $this->assertEqual($NotFound, false);
    }

    function testInvalidateLogin()
    {
        $message = 'unit test';
        $is_valid = $this->AuthwellUser->invalidate_login($message);
        $this->assertFalse($is_valid);
        $this->assertTrue(in_array($message, $this->AuthwellUser->loginFormErrors));
    }

    function testHoneypot()
    {
        $field = 'honeypot_unit_test';
        $tval = null;
        $fval = 'honey';
        $data = null;

        $this->AuthwellUser->data[$this->AuthwellUser->name][$field] = $tval;
        $true = $this->AuthwellUser->is_honeypot($data, $field);
        $this->assertTrue($true);

        $this->AuthwellUser->data[$this->AuthwellUser->name][$field] = $fval;
        $false = $this->AuthwellUser->is_honeypot($data, $field);
        $this->assertFalse($false);
    }

    function testPassword()
    {
        $plain = 'cakewell';
        $_0x_password = $this->AuthwellUser->_0x_password($plain);

        $this->assertEqual( $_0x_password,
            $this->AuthwellUser->as_binary($this->AuthwellUser->password($plain)) );

        #debug($_0x_password);
    }

    function testSimpleLoginRequest() {

        // first save a random user
        $Record = $this->RecordObj->create(array('active'=>1));
        $this->AuthwellUser->create();
        $this->assertTrue($this->AuthwellUser->save($Record));
        #debug($this->AuthwellUser->invalidFields());

        // then call
        $FormData = array(
            'AuthwellUser' => array(
                'email_login' => $Record['email'],
                'password_login' => $Record['password']
            )
        );

        $is_logged_in = $this->AuthwellUser->is_valid_login_request($FormData);
        $Cache = $this->AuthwellUser->UserDataCache;

        $this->assertTrue($is_logged_in);
        $this->assertEqual($Cache['User']['email'], $Record['email']);
    }

    function testFindUserByEmailWithRolesAndPrivileges()
    {
        # create user record
        $UserRecord = $this->RecordObj->create();
        $user_name = $UserRecord['name'];

        # create role and privilege records
        $RoleRecord = $this->RoleRecordObj->create(
            array( 'name'=>sprintf('priv1_for_%s',$user_name) ) );
        $PrivRecord1 = $this->PrivRecordObj->create(
            array( 'dotpath'=>sprintf('%s.priv1',$user_name) ) );
        $PrivRecord2 = $this->PrivRecordObj->create(
            array( 'dotpath'=>sprintf('%s.priv2',$user_name) ) );
        #debug(array($UserRecord, $RoleRecord, $PrivRecord1, $PrivRecord2));

        # save records
        $this->AuthwellUser->AuthwellRole->AuthwellPrivilege->save($PrivRecord1);
        $PrivIds[] = $this->AuthwellUser->AuthwellRole->AuthwellPrivilege->id;
        $this->AuthwellUser->AuthwellRole->AuthwellPrivilege->save($PrivRecord2);
        $PrivIds[] = $this->AuthwellUser->AuthwellRole->AuthwellPrivilege->id;

        $this->AuthwellUser->AuthwellRole->save( array(
            'AuthwellRole'=>$RoleRecord,
            'AuthwellPrivilege'=>$PrivIds ) );
        $role_id = $this->AuthwellUser->AuthwellRole->id;

        $this->AuthwellUser->save( array(
            'AuthwellUser'=>$UserRecord,
            'AuthwellRole'=>array($role_id) ) );

        $UserDbRecord = $this->AuthwellUser->find_user_by_email($UserRecord['email'], 1);

        $this->assertEqual($UserDbRecord['User']['email'], $UserRecord['email']);
        $this->assertEqual($UserDbRecord['Roles'][0]['description'],
                           $RoleRecord['description']);
        $this->assertTrue( in_array( $PrivRecord1['dotpath'],
                                     $UserDbRecord['User']['dotpaths'] ));

        #debug($UserDbRecord);
    }
}
?>
