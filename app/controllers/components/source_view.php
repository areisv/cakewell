<?php

/*
    Cakewell SourceView Component
    Author: Tom at klenwell@gmail.com
    Last Update: $Date$

    Creates links to CakePhp files based on controller values

    USAGE (in controller)
        var $components = array('SourceView');
        ...
        print $this->SourceView->build_icon_block('http://my-repository.com/hg/project/app/');

    NOTES
        Assumes CakePhp naming conventions are followed

        Sample Introspective Structure: TBA
*/



class SourceViewComponent extends Object
{
    var $base_url = 'http://code.google.com/p/cakewell/source/browse/app/';
    var $base_img_url = '/img/source_icons/';

    var $Ctrl = null;
    var $ModelList = array();
    var $ComponentList = array();
    var $ViewList = array();
    var $HelperList = array();

    // called before Controller:beforeFilter()
    function initialize(&$controller)
    {
    }

    // called after Controller::beforeFilter()
    function startup(&$controller)
    {
        $this->Ctrl = $controller;
    }

    function build_icon_block()
    {
        $html_t = <<<XHTML
<div class="source_view_icon_block">
<table>
<tr><td colspan="3">source</td></tr>
<tr><td class="mvc_head">model</td><td class="mvc_head">controller</td><td class="mvc_head">view</td></tr>
<tr><td class="model_icons">%s</td><td class="controller_icons">%s</td><td class="view_icons">%s</td></tr>
</table>
</div>
XHTML;

        $this->introspect();
    }

    function introspect()
    {
        # introspect models
        $ModelList = array();
        if ( is_array($this->Ctrl->uses) )
        {
            foreach ( $this->Ctrl->uses as $model_name )
            {
                if ( !isset($this->Ctrl->$model_name) ) continue;
                $Model = $this->Ctrl->$model_name;
                $mpath = sprintf('%s%s.php', MODELS, low(Inflector::underscore($Model->name)));
                if ( !file_exists($mpath) ) continue;
                $this->ModelList[$Model->name] = array( 'path' => $mpath, 'behaviors' => array() );

                // look for behaviors
                if ( is_array($Model->Behaviors->_attached) )
                {
                    foreach ( $Model->Behaviors->_attached as $behavior )
                    {
                        $bpath = sprintf('%sbehaviors/%s.php', MODELS, strtolower($behavior));
                        $this->ModelList[$Model->name]['behaviors'][$behavior] = $bpath;
                    }
                }
            }
        }
        #debug($this->ModelList);

        # introspect components
        foreach ( $this->Ctrl->components as $comp_name )
        {
        }
    }

    function icon_block($repo_base_url)
    {
    }

    function test()
    {
        return sprintf('testing component %s for controller %s', __CLASS__, $this->Ctrl->name);
    }
}
?>
