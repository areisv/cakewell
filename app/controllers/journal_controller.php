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
    var $components = array('RequestHandler', 'Gatekeeper');
    var $layout = 'journal';

    var $view_action = '';
    var $timestamp_f = 'j M Y, g:ia';
    var $created = null;
    var $updated = null;
    var $ExcludeFromIndex = array( # exclude these methods from menu
            'test'
        );

    function beforeRender() {
        $this->set('created', $this->created);
        $this->set('updated', $this->updated);
        $this->set('timestamp_f', $this->timestamp_f);
        $this->set('index_menu', $this->_get_index_menu());
    }

    function render($action=NULL, $layout=NULL, $file=NULL) {
        if ( $action == null ) {
            $action = $this->view_action;
        }
        $output = parent::render($action, $layout, $file);
        return $output;
    }

    function index()
    {
        $this->updated = '2010-01-18 12:10';
        $this->created = '2010-01-18 08:48';

        $m = 'index';
        if ( isset($this->params['pass'][0]) ) {
            $m = $this->params['pass'][0];
        }

        #debug($this->params);
        #debug($m);
        if ( $m == 'index' ) {
            $this->set('MethodList', $this->_get_index_list());
        }

        if ( method_exists($this, $m) ) {
            $this->view_action = sprintf('/%s/%s', low($this->name), $m);
            if ( $m != 'index' ) {
                return $this->$m();
            }
        }
        else {
            $this->set('error', 'page_not_found');
        }
    }

    function changelog()
    {
        $this->updated = '2010-01-18 11:56';
        $this->created = '2010-01-18 11:56';
    }

    function test()
    {
        # these must be valid strtotime values
        $this->updated = '2010-01-18 11:39';
        $this->created = '2010-01-18 08:48';
    }

    function _get_index_list()
    {
        $MethodList = $this->Gatekeeper->get_controller_methods($this);
        return array_diff($MethodList, $this->ExcludeFromIndex);
    }

    function _get_index_menu()
    {
        $menu_t = <<<XHTML
<div class="index_menu">
    <h3>index</h3>
    <ul>
        %s
    </ul>
</div>
XHTML;
        $menu_html = '';
        $MethodList = $this->_get_index_list();
        foreach ( $MethodList as $m )
            $menu_html .= sprintf('<li><a href="/%s/%s">%s</a></li>%s',
                $this->viewPath, $m, $m, "\n");
        return sprintf($menu_t, $menu_html);
    }
}

?>
