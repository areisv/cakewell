<?php

App::import('Vendor', 'input_baffle/input_baffle');
App::import('Sanitize');

class BafflerBehavior extends ModelBehavior {  
    
    public $name = 'Baffler';
    
    /*
        Initiate behaviour for the model using specified settings.
     */
    function setup(&$Model, $settings = array())
    {
        $BafflerConfig = array( 'debug'=>0,
             'TagList' => array('a', 'i', 'b', 'em', 'strong', 's')
        );
        $this->Baffler = new InputBaffle($BafflerConfig);
    }
    
    /*
        Run before a model is saved.
     */
    function beforeSave(&$Model)
    {
    }
    
    function freetext_purge(&$Model, $value)
    {
        /* this purges the value of all tags */
        return Sanitize::html($value, true);
    }
    
    function freetext_sanitize(&$Model, $value)
    {
        return $this->Baffler->sanitize($value);
    }
    
    function freetext_validates(&$Model, $value)
    {
        $Model->BaffleWarningList = array();
        if ( !$this->Baffler->validates($value) )
        {
            $Model->BaffleWarningList = $this->Baffler->getWarnings();
            return 0;
        }
        return 1;
    } 

    function test(&$Model) 
    {
        return sprintf('testing behavior %s for model %s', __CLASS__, $Model->name);
    } 
}
?>