<?php

/*
    Helper Unit Test Template
    Author: Tom at klenwell@gmail.com
    Last Update: $Date$
*/
App::import('Helper', 'Html');


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
