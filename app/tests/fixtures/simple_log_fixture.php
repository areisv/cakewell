<?php

App::import('Vendor', 'klenwell/test_record');
App::import('Model', 'SimpleLogTest');

class SimpleLogFixture extends CakeTestFixture {
    var $name = 'SimpleLog';
    var $import = array('table' => 'simple_logs', 'import' => false);
    var $useDbConfig = 'test_suite';
}

class SimpleLogRecord extends TestRecord {

    function random_keyword($col) {
        $KeywordList = array('system', 'database', 'demo', 'jackpot!');
        return $KeywordList[array_rand($KeywordList)];
    }

    function random_type_id($col) {
        $SimpleLogType = ClassRegistry::init('SimpleLogType');
        $test_suite = $SimpleLogType->setDataSource('test');
        $Result = $SimpleLogType->find('all', array(
            'recursive' => -1,
            'fields'    => array('id') )
        );
        $TypeIds = Set::extract($Result, '{n}.SimpleLogType.id');

        $SimpleLogType->setDataSource($test_suite);
        return $TypeIds[array_rand($TypeIds)];
    }
}
