<?php
/*
    Authwell User Model Unit Test

    NOTES

    REFERENCES
    http://book.cakephp.org/view/485/Testing-plugins
*/

class AuthwellRoleTestCase extends CakeTestCase {

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
        $this->AuthwellRole = ClassRegistry::init('AuthwellRole');
        $this->RecordObj = new AuthwellRoleRecord($this->AuthwellRole);
    }

    function testInstance() {
        $this->assertTrue($this->AuthwellRole instanceof AppModel);
        #debug($this->AuthwellRole);
    }

    function testProperties()
    {
        $this->assertEqual($this->AuthwellRole->useTable, 'authwell_roles');
        $this->assertEqual($this->AuthwellRole->name, 'AuthwellRole');
    }

    function testSchema()
    {
        $Cols = array_keys($this->AuthwellRole->_schema);
        $this->assertEqual(5, count($Cols));
        #debug($this->AuthwellUser->_schema);
    }

    function testRecord()
    {
        $name = 'test_role';
        $Record = $this->RecordObj->create(array('name'=>$name));
        $this->assertEqual($Record['name'], $name);
    }
}
?>
