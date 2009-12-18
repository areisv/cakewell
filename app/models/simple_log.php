<?php

class SimpleLog extends AppModel
{
    var $name = 'SimpleLog';
    var $useTable = 'simple_logs';
    var $actsAs = array('Normalizer');

    var $belongsTo = array(
        'SimpleLogType' => array(
            'className'     => 'SimpleLogType',
            'foreignKey'    => 'type_id'
        )
    );

    var $validate = array();

    function test_behavior()
    {
        return $this->test_normalizer();
    }
}
