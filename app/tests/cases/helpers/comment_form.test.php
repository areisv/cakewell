<?php

/*
    Comment Form Test Template
*/
App::import('Helper', 'CommentForm');


class CommentFormHelperTest extends CakeTestCase {

    function setUp()
    {
        $this->CommentFormHelper = new CommentFormHelper();
    }

    function teardown()
    {
        unset($this->CommentFormHelper);
    }

    function testInstance() {
        $this->assertTrue(is_a($this->CommentFormHelper, 'CommentFormHelper'));
    }

    function testFuidNormalizer()
    {
        $s = ' # fuid is  form-unique id ';
        $expect = '__fuid_is__form_unique_id';
        $this->assertEqual($this->CommentFormHelper->n($s), $expect);
    }

    function testFuid()
    {
        $form_key = ' hello ';
        $dom_id = ' world ';
        $expect = 'hello_world';
        $this->assertEqual($this->CommentFormHelper->fuid($form_key, $dom_id), $expect);
    }

}

?>
