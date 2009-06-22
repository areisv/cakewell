<?php
/*
    Based on mcurry's template at
    http://github.com/mcurry/cakephp/tree/master/test_sample/tests
*/
 
class CommentTestCase extends CakeTestCase {

    var $Comment = null;
    var $fixtures = array(
            'app.comment',
        );
 
    function start()
    {
        parent::start();
        $this->Comment = ClassRegistry::init('Comment');
    }
    
    function testInstance() {
        $this->assertTrue(is_a($this->Comment, 'Comment'));
    }
    
    function testProperties()
    {            
        $this->assertEqual($this->Comment->useTable, 'comments');
        $this->assertEqual($this->Comment->name, 'Comment');
    }
    
    function testSchema()
    {
        $Cols = array_keys($this->Comment->_schema);
        $this->assertEqual(15, count($Cols));
        #debug($this->Comment->_schema);
    }
    
    function testSave()
    {
        $CommentRecord = new CommentRecord($this->Comment);
        $Data = $this->Comment->save($CommentRecord->random());
        $this->assertTrue($this->Comment->id);
        #debug($Data);
    }
}
?>