<?php

/*
    Web Test Template

    For WebTest documentation, see:
    http://simpletest.sourceforge.net/en/web_tester_documentation.html
*/

class SampleWebTest extends WebTestCase {

    var $base_url = 'http://cakewell/';
    var $demo_url = '';

    function setUp()
    {
        $this->demo_url = $this->base_url . 'demo/';
        $this->demo_index_url = $this->demo_url . 'index/';
    }

    function testGetPage()
    {
        $this->assertTrue($this->get($this->demo_index_url));
        $this->assertText('Index of DemoController');
    }

    function get_content()
    {
        return $this->_browser->getContent();
    }

    function rand_choice($ARRAY)
    {
        return $ARRAY[array_rand($ARRAY)];
    }
}

?>
