<?php
/*
    Based on mcurry's template at
    http://github.com/mcurry/cakephp/tree/master/test_sample/tests
*/
 
class ExtagCommentTestCase extends CakeTestCase {

    var $ExtagComment = null;
    var $fixtures = array(
            'app.extag_comment',
            'app.extag',
            'app.extag_tagger',
            'app.extag_tagged',
            'app.extag_tagkey',
            'app.extag_mod',
            'app.editor',
            );
 
    function start()
    {
        parent::start();
        $this->ExtagComment = ClassRegistry::init('ExtagComment');
    }
    
    function testInstance() {
        $this->assertTrue(is_a($this->ExtagComment, 'ExtagComment'));
    }
    
    function testProperties()
    {            
        $this->assertEqual($this->ExtagComment->useTable, 'extag_comments');
        $this->assertEqual($this->ExtagComment->name, 'ExtagComment');
    }
    
    function testValidate()
    {
        $CommentData = array(
            'ExtagComment' => array(
                'text' => 'A unit test for the model -- that\'s all'
            )
        );
        $this->ExtagComment->create($CommentData);
        $this->assertTrue($this->ExtagComment->validates());
    }
    
    function testInvalid()
    {
        $CommentData = array(
            'ExtagComment' => array(
                'text' => 'Too short'
            )
        );
        $this->ExtagComment->create($CommentData);
        $this->assertTrue(!$this->ExtagComment->validates());
        #debug($this->ExtagComment->invalidFields());
    }
    
    function testSanitize()
    {
        $CommentData = array(
            'ExtagComment' => array(
                'text' => '<script>A unit test </script>for the model -- <i>that\'s</i> <b>all<b>'
            )
        );
        $expect = 'A unit test for the model -- <i>that\'s</i> <b>all<b>';
        $this->ExtagComment->create($CommentData);
        $this->assertFalse($this->ExtagComment->validates());
        $this->assertEqual($expect, $this->ExtagComment->data['ExtagComment']['text']);

        $InvalidFields = $this->ExtagComment->invalidFields();
        $this->assertEqual('mismatched html tag: remove or correct', $InvalidFields['text']);
    }
    
    
    function testSave()
    {
        $CommentData = array(
            'ExtagComment' => array(
                'text' => 'A unit test for the model -- that\'s all'
            )
        );
        $this->ExtagComment->create($CommentData);
        $this->assertTrue($Result = $this->ExtagComment->save());
        #debug($Result);
    }
}
?>