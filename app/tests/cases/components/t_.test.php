<?php
/*
    Cakewell Component Test Template
    Author: Tom at klenwell@gmail.com
    Last Update: $Date$
*/
App::import('Component', 'T_');

class MockController {}

class SampleComponentTest extends CakeTestCase {

    function setUp()
    {
        $this->SampleComponent = new SampleComponent();
        $controller = new MockController();
        $this->SampleComponent->initialize($controller);
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
