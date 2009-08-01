<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 * Override this controller by placing a copy in controllers directory of an application
 *
 */

class PagesController extends AppController {

    var $name = 'Pages';
    var $helpers = array('Html');
    var $uses = array();

    function home() {
        $this->render('home', 'home');
    }

    function display() {
        $path = func_get_args();

        if (!count($path))
            $this->redirect('/');

        $count = count($path);
        $page = $subpage = $title = null;

        if (!empty($path[0]))
            $page = $path[0];

        if (!empty($path[1]))
            $subpage = $path[1];

        if (!empty($path[$count - 1]))
            $title = Inflector::humanize($path[$count - 1]);

        $this->set(compact('page', 'subpage', 'title'));
        $this->render(join('/', $path));
    }
}

?>
