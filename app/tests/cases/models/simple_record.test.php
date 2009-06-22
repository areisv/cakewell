<?php
/*
    Based on mcurry's template at
    http://github.com/mcurry/cakephp/tree/master/test_sample/tests
*/
 
class SimpleRecordTestCase extends CakeTestCase {

    var $SimpleRecord = null;
    var $fixtures = array(
            'app.simple_record',
        );
 
    function start()
    {
        parent::start();
        $this->SimpleRecord = ClassRegistry::init('SimpleRecord');
    }
    
    function testInstance() {
        $this->assertTrue(is_a($this->SimpleRecord, 'SimpleRecord'));
    }
    
    function testProperties()
    {            
        $this->assertEqual($this->SimpleRecord->useTable, 'simple_records');
        $this->assertEqual($this->SimpleRecord->name, 'SimpleRecord');
    }
}
?>