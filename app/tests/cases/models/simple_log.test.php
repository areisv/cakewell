<?php

/*
    Unit Test for SimpleLog Model

    NOTES:
        You must list fixture for *all* related models (even, I believe, those
        models indirectly related by related models).
*/

class SimpleLogTestCase extends CakeTestCase {

    var $SimpleLog = null;
    var $fixtures = array(
            'app.simple_log',
            'app.simple_log_type'
        );

    function start() {
        parent::start();
        $this->SimpleLog = ClassRegistry::init('SimpleLog');
    }

    function generate_random_record($keyword='test', $message='unit test') {
        # set randomizer paramaters
        $this->SimpleLog->_schema['created']['date_range'] = array(-7, 0);

        # instantiate record generator passing model object
        $RecordGenerator = new SimpleLogRecord($this->SimpleLog);

        # create random record passing preset data
        $NewRecord = $RecordGenerator->create( array(
            'keyword' => $keyword,
            'message' => $message) );

        return $NewRecord;
    }

    function testInstance() {
        $this->assertTrue(is_a($this->SimpleLog, 'SimpleLog'));
        $this->assertTrue(is_a($this->SimpleLog->SimpleLogType, 'SimpleLogType'));
    }

    function testProperties() {
        $this->assertEqual($this->SimpleLog->useTable, 'simple_logs');
        $this->assertEqual($this->SimpleLog->name, 'SimpleLog');
        $this->assertTrue(in_array('id', array_keys($this->SimpleLog->_schema)));
        #debug($this->SimpleLog->_schema);
    }

    function testTestRecord() {
        $NewRecord = $this->generate_random_record('keyword', 'message');
        $this->assertEqual($NewRecord['keyword'], 'keyword');
        debug($NewRecord);
    }

    function testGetLogTypes() {
        $LogTypes = $this->SimpleLog->get_log_types();
        $this->assertEqual($LogTypes['system'], 1);
        #debug($LogTypes);
    }

    function testLogMethod() {
        $result = $this->SimpleLog->log('system', 'test', 'unit test');
        $this->assertTrue($result);
        if ( ! $result ) {
            $this->debug_validation();
        }
    }

    function debug_validation() {
        debug( array(
            'invalid fields' => $this->SimpleLog->invalidFields(),
            'data' => $this->SimpleLog->data
        ));
    }
}
