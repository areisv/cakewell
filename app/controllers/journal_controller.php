<?php

/*
    Journal Controller

    This controller is intended for static views.  It functions as a quick-and-
    dirty journal or blog.

    NOTES
    Requires routing be defined for /journal/* -> /journal/index

    Another possible application would be as a docs_controller.
*/

class JournalController extends AppController
{
    var $name = 'Journal';
    var $uses = null;   // or: null;
    var $components = array('RequestHandler');
    var $view_action = '';

    function render($action=NULL, $layout=NULL, $file=NULL) {
        if ( $action == null ) {
            $action = $this->view_action;
        }
        $output = parent::render($action, $layout, $file);
        return $output;
    }

    function index()
    {
        $m = 'null';
        if ( isset($this->params['pass'][0]) ) {
            $m = $this->params['pass'][0];
        }
        #debug($this->params);
        #debug($m);

        if ( method_exists($this, $m) ) {
            $this->view_action = sprintf('/%s/%s', low($this->name), $m);
            return $this->$m();
        }
        else {
            $this->set('error', 'page_not_found');
        }
    }

    function test()
    {
    }
}

?>
