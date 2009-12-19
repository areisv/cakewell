<?php

/*
    Cakewell Mock Model
    Example of model without table.
*/
App::import('Vendor', 'klenwell/test_record');

class Mock extends AppModel
{
    var $name = 'Mock';
    var $useTable = false;

    var $_schema = array(
        'id' => array(
            'type' => 'integer',
            'null' => FALSE,
            'default' => FALSE,
            'length' => 11,
            'key' => 'primary'
        ),
        'value' => array(
            'type' => 'string',
            'null' => FALSE,
            'default' => '',
            'length' => 80,
        ),
        'created' => array(
            'type' => 'datetime',
            'null' => 1,
            'default' => null,
            'length' => null
        )
    );

    var $validate = array(
        'id' => array(
            'required' => 1,
            'rule' => 'numeric',
            'message' => 'must be an integer',
        ),
        'value' => array(
            'rule' => 'notEmpty',
            'message' => 'please fill in',
        ),
    );


    function find($type)
    {
        if ( $type == 'first' ) {
            $Record = $this->generate_record();
            $Result = array('Mock'=>$Record);
        }
        else {
            $limit = mt_rand(1,25);
            foreach( range(1,$limit) as $n ) {
                $Record = $this->generate_record();
                $Result[] = array('Mock'=>$Record);
            }
        }
        return $Result;
    }

    function generate_record() {
        $RecordGenerator = new TestRecord($this);
        $NewRecord = $RecordGenerator->create();
        return $NewRecord;
    }
}

?>
