<?php

/*
    Cakewell SourceView Component
    Author: Tom at klenwell@gmail.com
    Last Update: $Date$

    Creates links to a source code repository based on controller values.
    Conceptually, this would make more sense as a helper or an elements, but
    the controller offers more of the necessary information to infer the links
    and is simply more than convenient than building an element.

    USAGE (in controller)
        var $components = array('SourceView');
        ...
        $this->SourceView->base_url = 'http://my-repo.com/browse/my-app/app/'
        print $this->SourceView->build_icon_block();

    NOTES
        Assumes CakePhp naming conventions are followed

        Sample Introspective Data Array:

            [ModelList] => Array
                (

                    [SimpleRecord] => Array
                        (
                            [path] => models/simple_record.php
                            [behaviors] => Array
                                (
                                    [Normalizer] => models/behaviors/normalizer.php
                                )

                        )

                )

            [ControllerList] => Array
                (
                    [Demo] => controllers/demo_controller.php
                )

            [ComponentList] => Array
                (
                    [Twitter] => controllers/components/twitter.php
                    [Sample] => controllers/components/sample.php
                    [Gatekeeper] => controllers/components/gatekeeper.php
                    [SourceView] => controllers/components/source_view.php
                )

            [ViewList] => Array
                (
                    [layout] => views/layouts/default.ctp
                    [views] => views/demo/sandbox.ctp
                )

            [HelperList] => Array
                (
                    [Html] => views/helpers/html.php
                    [Form] => views/helpers/form.php
                )


*/

class SourceViewComponent extends Object
{
    var $base_url = 'http://code.google.com/p/cakewell/source/browse/app/';
    var $base_img_url = '/img/source_icons/';
    var $title_row = 'cakewell source';

    var $ModelList = array();
    var $BehaviorList = array();
    var $ControllerList = array();
    var $ComponentList = array();
    var $ViewList = array();
    var $HelperList = array();

    var $Ctrl = null;

    // called before Controller:beforeFilter()
    function initialize(&$controller)
    {
    }

    // called after Controller::beforeFilter()
    function startup(&$controller)
    {
        $this->Ctrl = $controller;
    }

    function link_icon($name, $type, $app_path)
    {
        $link_t = <<<XHTML
<a class="%s_source" href="%s%s" onclick="window.open(this.href,'_blank');return false;">
    <img class="source_view_icon" src="%s%s.png" alt="%s" title="source view for %s %s" />
</a>
XHTML;

        return sprintf($link_t, $type, $this->base_url, $app_path,
                        $this->base_img_url, $type, $type, $name, $type);
    }

    function build_icon_block()
    {
        $html_t = <<<XHTML
<div class="source_view_icon_block">
<table>
<tr><td class="title_row" colspan="3">%s</td></tr>
<tr><td class="mvc_head">model</td><td class="mvc_head">controller</td><td class="mvc_head">view</td></tr>
<tr><td class="icons model_icons">%s</td><td class="icons controller_icons">%s</td><td class="icons view_icons">%s</td></tr>
</table>
</div>
XHTML;

        $ModelHtml = array();
        $ControllerHtml = array();
        $ViewHtml = array();

        $this->introspect();

        foreach ( $this->ModelList as $name => $Data )
            $ModelHtml[] = $this->link_icon($name, 'model', $Data['path']);

        foreach ( $this->BehaviorList as $name => $path )
            $ModelHtml[] = $this->link_icon($name, 'behavior', $path);

        foreach ( $this->ControllerList as $name => $path )
            $ControllerHtml[] = $this->link_icon($name, 'controller', $path);

        foreach ( $this->ComponentList as $name => $path )
            $ControllerHtml[] = $this->link_icon($name, 'component', $path);

        foreach ( $this->ViewList as $name => $path )
            $ViewHtml[] = $this->link_icon($name, 'view', $path);

        foreach ( $this->HelperList as $name => $path )
            $ViewHtml[] = $this->link_icon($name, 'helper', $path);

        return sprintf($html_t,
                       $this->title_row,
                       implode("\n", $ModelHtml),
                       implode("\n", $ControllerHtml),
                       implode("\n", $ViewHtml));
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
                $model_app_path = sprintf('%s/%s.php', 'models', Inflector::underscore($Model->name));
                $model_full_path = sprintf('%s%s', APP, $model_app_path);
                if ( !file_exists($model_full_path) ) continue;
                $this->ModelList[$Model->name] = array( 'path' => $model_app_path, 'behaviors' => array() );

                // look for behaviors
                if ( is_array($Model->Behaviors->_attached) )
                {
                    foreach ( $Model->Behaviors->_attached as $behavior )
                    {
                        if ( isset($this->BehaviorList[$behavior]) ) continue;
                        $behavior_app_path = sprintf('%s/%s/%s.php',
                            'models', 'behaviors', Inflector::underscore($behavior));
                        $behavior_full_path = sprintf('%s%s', APP, $behavior_app_path);
                        if ( !file_exists($behavior_full_path) ) continue;
                        $this->BehaviorList[$behavior] = $behavior_app_path;
                    }
                }
            }
        }
        #debug($this->ModelList);

        # intropect controller
        $ctrl_fname = Inflector::underscore($this->Ctrl->name);
        $ctrl_app_path = sprintf('%s/%s_controller.php', 'controllers', $ctrl_fname);
        $ctrl_full_path = sprintf('%s%s', APP, $ctrl_app_path);
        if ( file_exists($ctrl_full_path) ) $this->ControllerList[$this->Ctrl->name] = $ctrl_app_path;

        # introspect components
        if ( empty($this->Ctrl->components) ) $this->Ctrl->components = array();
        foreach ( $this->Ctrl->components as $component )
        {
            $comp_app_path = sprintf('%s/%s/%s.php',
                'controllers', 'components', Inflector::underscore($component));
            $comp_full_path = sprintf('%s%s', APP, $comp_app_path);
            if ( !file_exists($comp_full_path) ) continue;
            $this->ComponentList[$component] = $comp_app_path;
        }

        # introspect layout and views
        if ( empty($this->Ctrl->layout) ) $this->Ctrl->layout = 'default';
        $layout_app_path = sprintf('%s/%s/%s.ctp', 'views', 'layouts', $this->Ctrl->layout);
        $layout_full_path = sprintf('%s%s', APP, $layout_app_path);
        if ( file_exists($layout_full_path) ) $this->ViewList['layout'] = $layout_app_path;

        $view_app_path = sprintf('%s/%s/%s.ctp', 'views', $this->Ctrl->viewPath, $this->Ctrl->action);
        $view_full_path = sprintf('%s%s', APP, $view_app_path);
        if ( file_exists($view_full_path) ) $this->ViewList[$this->Ctrl->action] = $view_app_path;

        # introspect helpers
        if ( empty($this->Ctrl->helpers) ) $this->Ctrl->helpers = array();
        foreach ( $this->Ctrl->helpers as $helper )
        {
            $helper_app_path = sprintf('%s/%s/%s.php',
                'views', 'helpers', Inflector::underscore($helper));
            $helper_full_path = sprintf('%s%s', APP, $helper_app_path);
            if ( !file_exists($helper_full_path) ) continue;
            $this->HelperList[$helper] = $helper_app_path;
        }

        #debug($this);
        return;
    }

    function test()
    {
        return sprintf('testing component %s for controller %s', __CLASS__, $this->Ctrl->name);
    }
}
?>
