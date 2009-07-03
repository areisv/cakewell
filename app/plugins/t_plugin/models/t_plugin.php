<?php

/*
    A Sample Plugin Model

    Summary of model here.

    REFERENCES
        http://book.cakephp.org/view/117/Plugin-Models
*/

class TPlugin extends TPluginAppModel
{
    var $name = 'TPlugin';
    var $useTable = false;
    var $validate = array();

    function test()
    {
        return 'test successful';
    }
}

?>
