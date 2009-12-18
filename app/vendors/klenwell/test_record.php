<?php

    class TestRecord extends Object
    {
        var $ColumnList     = array();
        var $Schema         = array();

        function __construct($FixtureInstance=null)
        {
            if ( is_null($FixtureInstance) )
                return trigger_error('must pass model instance', E_USER_WARNING);
            $this->Schema = $FixtureInstance->_schema;
            $this->ColumnList = array_keys($this->Schema);
        }

        function create($Data=array())
        {
            $Record = array();
            foreach ( $this->ColumnList as $col )
            {
                $type = $this->Schema[$col]['type'];
                $col_method = sprintf('%s_%s', 'random', $col);
                $type_method = sprintf('%s_%s', 'random', $type);
                $is_primary = isset($this->Schema[$col]['key']) &&
                    $this->Schema[$col]['key'] == 'primary';

                if ( $is_primary ) {
                    continue;
                }

                if ( isset($Data[$col]) ) {
                    $Record[$col] = $Data[$col];
                }
                elseif ( method_exists($this, $col_method) ) {
                    $Record[$col] = $this->$col_method($col);
                }
                elseif ( method_exists($this, $type_method) ) {
                    $Record[$col] = $this->$type_method($col);
                }
                elseif ( ! empty($this->Schema[$col]['null']) ) {
                    $Record[$col] = null;
                }
                else {
                    $Record[$col] = $this->Schema[$col]['default'];
                }
            }
            return $Record;
        }

        function random_string($col) {
            $string = '';

            if ( isset($this->Schema[$col]['random_string_length']) ) {
                if ( is_array($this->Schema[$col]['random_string_length']) ) {
                    $MinMax = $this->Schema[$col]['random_string_length'];
                    $length = mt_rand($MinMax[0], $MinMax[1]);
                }
                elseif ( is_numeric($this->Schema[$col]['random_string_length']) ) {
                    $length = $this->Schema[$col]['random_string_length'];
                }
            }
            elseif ( !empty($this->Schema[$col]['length']) ) {
                $length = mt_rand(1, $this->Schema[$col]['length']);
            }

            while ( strlen($string) < $length ) {
                $string .= md5(mt_rand(1,10000));
            }

            return substr($string, -$length);
        }

        function random_integer($col) {
            $min = 1;
            $max = 999999;
            if ( isset($this->Schema[$col]['random_integer_min']) ) {
                $min = $this->Schema[$col]['random_integer_min'];
            }
            if ( isset($this->Schema[$col]['random_integer_max']) ) {
                $max = $this->Schema[$col]['random_integer_max'];
            }
            return mt_rand($min, $max);
        }

        function random_datetime($col) {
            if ( isset($this->Schema[$col]['date_range']) &&
                is_array($this->Schema[$col]['date_range']) ) {
                $MinMax = $this->Schema[$col]['date_range'];
                $day_offset = mt_rand($MinMax[0], $MinMax[1]);
            }
            else {
                $day_offset = mt_rand(-30,1);
            }
            return date( 'Y-m-d H:i:s', time() + 60*60*24*$day_offset );
        }

        function random_date($col) {
            return date('Y-m-d', strtotime($this->random_datetime()));
        }
    }

?>
