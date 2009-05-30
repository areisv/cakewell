<?php

class Mock extends AppModel
{
    var $name = 'Mock';
    var $useTable = false;
    
    var $_schema = array(
        'id' => array(
            'type' => 'integer'
        ),
        'value' => array(
            'type' => 'string',
            'length' => 80
        ),
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

    
    function find()
    {
        $RESULT = array(
            array( 'Mock' => array( 'id' => 1, 'value' => 'insult' ) ),
            array( 'Mock' => array( 'id' => 2, 'value' => 'humiliate' ) ),
            array( 'Mock' => array( 'id' => 3, 'value' => 'ridicule' ) ),
            array( 'Mock' => array( 'id' => 4, 'value' => 'satirize' ) ),
        );
        return $RESULT;
    }
}

?>