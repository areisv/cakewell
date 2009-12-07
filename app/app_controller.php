<?php

/*
    The Cakewell App Controller

    "Controller attributes and methods created in your AppController will be
    available to all of your application's controllers. It is the ideal place
    to create code that is common to all of your controllers."
        -- http://book.cakephp.org/view/829/The-App-Controller

    NOTES
        Error layout handling based on:
        http://planetcakephp.org/aggregator/items/2177-custom-404-error-page-with-cakephp
*/

class AppController extends Controller
{
    var $layout = 'default';

    function beforeRender()
    {
        $this->_setErrorLayout();
    }

    function _setErrorLayout()
    {
        if ($this->name == 'CakeError')
            $this->layout = 'error';
    }
}

?>
