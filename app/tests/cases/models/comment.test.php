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
    
    function testValidation()
    {
        // create record
        $CommentRecord = new CommentRecord($this->Comment);
        $Data = $CommentRecord->random();
        
        // add some invalid data
        $Data['text'] = '<a href="/">some mixed up tags</i>';
        
        // tests
        $this->Comment->create($Data);
        $Invalid = $this->Comment->invalidFields();
        $this->assertFalse($this->Comment->validates());
        $this->assertEqual($Invalid['text'], 'mismatched html tag: please correct');
        #debug($Invalid);               
    }
    
    function testSpam()
    {
        // create record
        $CommentRecord = new CommentRecord($this->Comment);
        $Data = $CommentRecord->random();
        
        // add some invalid data
        $Data[$this->Comment->honeypot_field] = 'Oops.  A bot filled this in.  Vi@gara!';
        
        // tests
        $this->Comment->create($Data);
        $Invalid = $this->Comment->invalidFields();
        $this->assertFalse($this->Comment->validates());
        $this->assertEqual($Invalid['text'], $this->Comment->validate['text']['spam_check']['message']);
        #debug($Invalid);
    }
    
    function testBafflerPurge()
    {
        // create record
        $CommentRecord = new CommentRecord($this->Comment);
        $Data = $CommentRecord->random();
        
        // add some invalid data
        $Data['text'] = <<<XHTML
A comment with some sneaky
<script>
alert('javascript!');
</script>
XHTML;
        
        // tests
        $Data['text'] = $this->Comment->freetext_purge($Data['text']);
        $this->Comment->create($Data);
        $Invalid = $this->Comment->invalidFields();
        $this->assertTrue(strpos($Data['text'], '<script>') === false);
        $this->assertTrue($this->Comment->validates());
    }
    
    function testBafflerFreetextValidates()
    {
        // create record
        $CommentRecord = new CommentRecord($this->Comment);
        $Data = $CommentRecord->random();
        
        // add some invalid data
        $Data['text'] = 'A comment with an <i>unclosed tag';
        
        // tests
        $this->assertFalse($this->Comment->freetext_validates($Data['text']));
        $this->assertEqual($this->Comment->BaffleWarningList[0], 'mismatched html tag: please correct');
    }
}
?>