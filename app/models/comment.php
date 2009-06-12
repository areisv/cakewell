<?php

class Comment extends AppModel {
    
    var $name = 'Comment';
    var $useTable = 'comments';
    var $actsAs = array(
        'Baffler' => array(
            'TagList' => array('a', 'b', 'blockquote', 'code', 'em', 'i', 'strong', 's')
        ));
    var $honeypot_field = 'topyeno';
    
    var $PurgeList = array(
        // fields that should have any tags purged
        'author_url',
    );
    
    var $validate = array
    (
        'author_email' => array(
            'rule' => array('email'),
            'required' => true,
            'allowEmpty' => false,
            'message' => 'please enter a valid email'
        ),
        
        'author_url' => array (
            'rule' => 'url',
            'required' => false,
            'allowEmpty' => true,
            'message' => 'please enter a valid url'
        ),
        
        'text' => array(
            'is_required' => array(
                'rule' => array('is_required', 'text'),
                'message' => 'don\'t forget your comment',),
            'max_length' => array(
                'rule' => array('max_words', 'text', 250),
                'message' => 'tl:dr &mdash; make it a bit shorter',),
            'spam_check' => array(
                'rule' => array('is_not_spam'),
                'message' => 'This is kinda spammy, clean it up',),
            'validate_freetext' => array(
                'rule' => array('validate_freetext', 'text'))
        ),
    
    );

    
    function beforeValidate()
    {
        foreach ( $this->PurgeList as $field_ )
            if ( isset($this->data[$this->name][$field_]) )
                $this->data[$this->name][$field_] = $this->freetext_purge($this->data[$this->name][$field_]);
    }
    
    function validate_freetext($data, $field)
    {
        /*
            Must return true (1), else the warning message (in the invalidate
            call) will be overwritten.
        */
        if ( empty($this->data[$this->name][$field]) ) return 1;
        $this->data[$this->name][$field] = $this->freetext_sanitize($this->data[$this->name][$field]);
        if ( ! $this->freetext_validates($this->data[$this->name][$field]) )
            $this->invalidate($field, implode('<br />', $this->BaffleWarningList));
        return 1;
    }
    
    function min_words($data, $field, $min_words)
    {
        $word_count = str_word_count($data[$field]);
        return $word_count >= $min_words;
    }
    
    function max_words($data, $field, $max_words)
    {
        $word_count = str_word_count($data[$field]);
        return $word_count <= $max_words;
    }
    
    function is_not_spam($data)
    {
        if ( !empty($this->data[$this->name][$this->honeypot_field]) ) return 0;
        return 1;
    }
    
    function is_required($data, $field)
    {
        $valid = !empty($data[$field]);
        return $valid;
    }
    
    function clear_data()
    {
        $this->data = array();
    }
    
}
?>