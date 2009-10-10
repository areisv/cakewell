<?php
/*
    Authwell User Model Unit Test

    NOTES

    REFERENCES
    http://book.cakephp.org/view/485/Testing-plugins
*/

class AuthwellPrivilegeTestCase extends CakeTestCase {

    var $AuthwellRole = null;
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
        $this->AuthwellPrivilege = ClassRegistry::init('AuthwellPrivilege');
        $this->RecordObj = new AuthwellPrivilegeRecord($this->AuthwellPrivilege);
    }

    function testInstance() {
        $this->assertTrue($this->AuthwellPrivilege instanceof AppModel);
        #debug($this->AuthwellRole);
    }

    function testProperties()
    {
        $this->assertEqual($this->AuthwellPrivilege->useTable, 'authwell_privileges');
        $this->assertEqual($this->AuthwellPrivilege->name, 'AuthwellPrivilege');
    }

    function testSchema()
    {
        $Cols = array_keys($this->AuthwellPrivilege->_schema);
        $this->assertEqual(5, count($Cols));
        #debug($this->AuthwellUser->_schema);
    }

    function testRecord()
    {
        $path = 'admin.all';
        $Record = $this->RecordObj->create(array('dotpath'=>$path));
        $this->assertEqual($Record['dotpath'], $path);
    }
}
?>
