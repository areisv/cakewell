<?php

class SimpleRecord extends AppModel
{
    var $name = 'SimpleRecord';
    var $useTable = 'simple_records';
    var $actsAs = array('Normalizer');
    var $validate = array();
    
    function test_behavior()
    {
        return $this->test_normalizer();
    }
}

?>