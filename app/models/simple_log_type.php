<?php

class SimpleLogType extends AppModel
{
    var $name = 'SimpleLogType';
    var $useTable = 'simple_log_types';

    var $hasMany = array(
        'SimpleLog' => array(
            'className'     => 'SimpleLog',
            'foreignKey'    => 'type_id',
            #'conditions'    => array('SimpleLog.keyword' => 'system'),
            'order'         => 'SimpleLog.created DESC',
            'limit'         => '30',
            'dependent'     => true
        )
    );

    var $validate = array();

    function test_behavior()
    {
        return $this->test_normalizer();
    }
}
