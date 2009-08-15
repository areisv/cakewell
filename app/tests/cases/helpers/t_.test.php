<?php

/*
    Helper Test Template

    Replace Foo with your controller's name
*/
App::import('Helper', 'Html');
#uses('view'.DS.'helpers'.DS.'app_helper', 'controller'.DS.'controller', 'model'.DS.'model', 'view'.DS.'helper', 'view'.DS.'helpers'.DS.'js');


class SampleHelperTest extends CakeTestCase {

    var $base_url = 'http://foo/';
    var $demo_url = '';

    function setUp()
    {
        $this->HtmlHelper = new HtmlHelper();
    }

    function teardown()
    {
        unset($this->HtmlHelper);
    }

    function testInstance() {
        $this->assertTrue(is_a($this->HtmlHelper, 'HtmlHelper'));
    }

}

?>
