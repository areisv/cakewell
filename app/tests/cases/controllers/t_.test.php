<?php

/*
    Controller Test Template

    Replace Pages with your controller's name
*/
App::import('Controller', 'Pages');

class Page extends PagesController {
    var $name = 'Pages';
    var $autoRender = false;

    function redirect($url, $status = null, $exit = true) {
        $this->redirectUrl = $url;
    }

    function render($action = null, $layout = null, $file = null) {
        $this->renderedAction = $action;
    }

    function _stop($status = 0) {
        $this->stopped = $status;
    }
}

class PageControllerTest extends CakeTestCase {

    var $base_url = 'http://foo/';
    var $demo_url = '';

    function setUp()
    {
        $this->Page = new PagesController();
        $this->Page->constructClasses();
    }

    function teardown()
    {
        $this->Page = null;
    }

    function testInstance() {
        $this->assertTrue(is_a($this->Page, 'PagesController'));
    }

    function testProperties() {
        $this->assertEqual($this->Page->name, 'Pages');
    }
}

?>
