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

    var $validate = array(
        'type_id' => array(
            'rule' => array('validate_type_id'),
            'required' => true,
            'allowEmpty' => false,
            'message' => 'invalid log type'
        )
    );

    function log($type, $keyword, $message)
    {
        $Data = array(
            'type_id' => $this->get_type_id($type),
            'keyword' => $keyword,
            'message' => $message
        );
        $this->create();
        return $this->save($Data);
    }

    function get_log_types()
    {
        $LogTypes = Cache::read('log_types');
        if ( $LogTypes === false ) {
            $Result = $this->SimpleLogType->find('all', array('recursive'=>-1));
            $LogTypes = Set::combine($Result, '{n}.SimpleLogType.type',
                '{n}.SimpleLogType.id');
            Cache::write('log_types', $LogTypes);
        }
        return $LogTypes;
    }

    function validate_type_id($data)
    {
        $type_id = $this->data[$this->name]['type_id'];
        $LogTypes = $this->get_log_types();
        return in_array($type_id, array_values($LogTypes));
    }

    function get_type_id($type)
    {
        $LogTypes = $this->get_log_types();
        $id = ( isset($LogTypes[$type]) ) ? $LogTypes[$type] : 'invalid';
        return $id;
    }

    function test_behavior()
    {
        return $this->test_normalizer();
    }
}
