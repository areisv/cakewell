<?php

/*
    Authwell Plugin Controller

    Summary of controller here.

    REFERENCES
        http://book.cakephp.org/view/116/Plugin-Controllers
*/

class AuthwellController extends AppController
{
    var $name = 'Authwell';
    var $uses = array('Authwell.AuthwellUser',
                      'Authwell.AuthwellRole',
                      'Authwell.AuthwellPrivilege');   // or: null;
    var $components = array('RequestHandler');

    function index()
    {
    }

    function debug()
    {
        assert($this->AuthwellUser instanceof AuthwellAppModel);
        assert($this->AuthwellUser->AuthwellRole instanceof AuthwellAppModel);
        $this->set('content_for_layout', sprintf('%s debug', __CLASS__));
        $this->render('/layouts/blank', 'default');
    }
}

?>
