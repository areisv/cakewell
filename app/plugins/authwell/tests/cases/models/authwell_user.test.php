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
        $this->assertEqual(6, count($Cols));
        #debug($this->AuthwellUser->_schema);
    }

    function testHabtmAssociation()
    {
        $this->assertTrue(isset($this->AuthwellUser->AuthwellRole));
        $this->assertTrue($this->AuthwellUser->AuthwellRole instanceof AuthwellRole);
        #debug($this->AuthwellUser);
    }
}
?>
