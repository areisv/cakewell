<?php
/*
    Cakewell Component Test Template
    Author: Tom at klenwell@gmail.com
    Last Update: $Date$
*/
App::import('Component', 'authwell.Auth');
App::import('Controller', 'authwell.Authwell');
App::import('Component', 'Session');

class AuthwellCtrl {
    var $name = 'AuthwellCtrl';
    var $Session = null;

    function __construct() {
        $this->Session = new SessionComponent();
    }
}

class AuthComponentTest extends CakeTestCase {

    function setUp()
    {
        $this->AuthComponent = new AuthComponent();
        $Ctrl = new AuthwellCtrl();
        $this->AuthComponent->initialize($Ctrl);
    }

    function teardown()
    {
        unset($this->AuthComponent);
    }

    function testInstance() {
        $this->assertTrue(is_a($this->AuthComponent, 'AuthComponent'));
        $this->assertTrue(is_a($this->AuthComponent->Ctrl, 'AuthwellCtrl'));
    }
}

?>
