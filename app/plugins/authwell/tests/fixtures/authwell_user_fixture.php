<?php

    class AuthwellUserFixture extends CakeTestFixture
    {
        var $name = 'AuthwellUser';
        var $import = array('model' => 'AuthwellUser', 'table' => 'authwell_users');
        var $useDbConfig = 'test_suite';
    }

    class AuthwellUserRecord
    {
        var $ColumnList     = array();
        var $Schema         = array();
        var $RandomColumn    = array();

        function __construct($Fixture=null)
        {
            if ( is_null($Fixture) ) return trigger_error('must pass model instance', E_USER_WARNING);
            $this->Schema = $Fixture->_schema;
            $this->ColumnList = array_keys($this->Schema);
            $this->RandomColumn = $this->set_random_columns();
        }

        function create_random_records($num)
        {
            $RecordList = array();
            foreach ( range(1,$n) as $n )
                $RecordList[] = $this->create();
        }

        function create($Data=array())
        {
            $Record = array();
            foreach ( $this->ColumnList as $col )
            {
                if ( isset($Data[$col]) )
                    $Record[$col] = $Data[$col];
                elseif ( isset($this->RandomColumn[$col]) )
                    $Record[$col] = $this->RandomColumn[$col];
                else
                    $Record[$col] = null;
            }
            return $Record;
        }

        function set_random_columns()
        {
            $name = $this->random_string(8);
            $Data = array(
                'name' => $name,
                'email' => sprintf('%s@cakewell.com', $name),
                'password' => $this->random_string(8)
            );
            return $Data;
        }

        function random_datetime($offset1=-604800, $offset2=0) {
            return date( 'Y-m-d H:i:s', time() + mt_rand($offset1, $offset2) );
        }

        function random_string($len=9) {
            return substr(md5(mt_rand(1,1000000)), -9);
        }
    }

?>
