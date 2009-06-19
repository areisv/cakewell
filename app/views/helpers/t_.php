<?php

class SampleHelper extends Helper {
    
    #var $helpers = array('Html');
    var $name = 'Sample';
    
    function access_element_via_view()
    {
        /*
          Example showing how to access another element in the helper via
          the View object        
        */
        $View =& ClassRegistry::getObject('view');
        $View->element('element_name', array('var' => 'hello_world'));
    }
        
    function l_($url, $label=null, $class_=null, $external=1)
    {
        /*
          Helper function to build links the way I like them
        */
        if ( is_null($label) ) $label = $url;
        $c_ = ( !is_null($class_) ) ? " class=\"{$class_}\"" : '';
        $oc_ = ( $external ) ? ' onclick="window.open(this.href,\'_blank\');return false;"' : '';
        return "<a{$c_} href=\"{$url}\" alt=\"{$label}\"{$oc_}>{$label}</a>";
    }
    
    function test() 
    {
        return sprintf('testing helper %s', __CLASS__);
    }
}
?>