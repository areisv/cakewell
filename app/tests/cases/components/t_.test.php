<?php
/*
    Cakewell Component Test Template
    Author: Tom at klenwell@gmail.com
    Last Update: $Date$
*/
App::import('Component', 'T_');
App::import('Controller', 'T_');
App::import('Component', 'Session');

class MockController extends AppController {
    var $name = 'SampleCtrl';
    var $Session = null;

    function __construct() {
        $this->Session = new SessionComponent();
    }
}

class SampleComponentTest extends CakeTestCase {

    function setUp()
    {
        $this->SampleComponent = new SampleComponent();
        $Ctrl = new MockController();
        $this->SampleComponent->initialize($Ctrl);
    }

    function teardown()
    {
        unset($this->SampleComponent);
    }

    function testInstance() {
        $this->assertTrue(is_a($this->SampleComponent, 'SampleComponent'));
        $this->assertTrue(is_a($this->SampleComponent->Ctrl, 'MockController'));
    }
}

?>
