<?php
 
    class CommentFixture extends CakeTestFixture
    { 
        var $name = 'Comment';
        var $import = array('table' => 'comments', 'import' => false);
        var $useDbConfig = 'test_suite';
    }
    
    
    class CommentRecord
    {
        var $ColumnList = array();
        var $Schema = array();
        
        function __construct($Fixture=null)
        {
            if ( is_null($Fixture) ) return trigger_error('must pass model instance', E_USER_WARNING);
            $this->Schema = $Fixture->_schema;
            $this->ColumnList = array_keys($this->Schema);
        }
        
        function create($Data)
        {
            $Record = array();
            foreach ( $this->ColumnList as $col )
            {
                if ( isset($Data[$col]) )
                    $Record[$col] = $Data[$col];
                else
                    $Record[$col] = $this->Schema[$col]['default'];
            }
            return $Record;
        }
        
        function random()
        {
            $Data = array(
                'meta_id' => mt_rand(1,500),
                'form_key' => 'unit_test',
                'dom_id' => 'unit-test',
                'created' => date('Y-m-d H:i:s', time()-mt_rand(3600,3600*12)),
                'approved' => date('Y-m-d H:i:s'),
                'author' => 'CommentRecord->random',
                'author_email' => 'test@cakewell.org',
                'text' => sprintf('test comment @ %s', date('g:ia')),
            );
            
            return $this->create($Data);
        }
    }
    
?> 
 