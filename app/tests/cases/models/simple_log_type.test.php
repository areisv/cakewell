<?php

/*
    Unit Test for SimpleLogType Model
*/

class SimpleLogTypeTestCase extends CakeTestCase {

    var $SimpleLogType = null;
    var $fixtures = array(
            'app.simple_log',
            'app.simple_log_type'
        );

    function start()
    {
        parent::start();
        $this->SimpleLogType = ClassRegistry::init('SimpleLogType');
    }

    function testInstance() {
        $this->assertTrue(is_a($this->SimpleLogType, 'SimpleLogType'));
    }

    function testProperties()
    {
        $this->assertEqual($this->SimpleLogType->useTable, 'simple_log_types');
        $this->assertEqual($this->SimpleLogType->name, 'SimpleLogType');
        $this->assertTrue(in_array('id', array_keys($this->SimpleLogType->_schema)));
        #debug($this->SimpleLogType->_schema);
    }
}
