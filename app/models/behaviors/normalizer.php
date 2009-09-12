<?php

/** Documentation

Normalizer CakePhp Behavior Class
Last Update: $Date$

A very simple example of a behavior.  Useful as a template.

Usage (in model):
    var $actsAs = array('Normalizer');

    print $this->test_normalizer();

______________________________________________________________________________*/

class NormalizerBehavior extends ModelBehavior {

    public $name = 'Normalizer';

    /*
        Initiate behaviour for the model using specified settings.
     */
    function setup(&$Model, $settings = array())
    {
    }

    /*
        Run before a model is saved.
     */
    function beforeSave(&$Model)
    {
    }

    function ymd_date(&$Model, $date_str)
    {
        return date('Y-m-d', strtotime(trim($date_str)));
    }

    function test_normalizer(&$Model)
    {
        return sprintf('testing behavior %s for model %s', __CLASS__, $Model->name);
    }
}
?>
