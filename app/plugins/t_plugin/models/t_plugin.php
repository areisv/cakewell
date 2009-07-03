<?php

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
