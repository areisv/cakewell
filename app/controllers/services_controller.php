<?php

/*
    A CakePhp Controller Template

    Summary of controller here.

    NOTES
        Controllers should be name _controller (e.g. posts_controller)
*/

class ServicesController extends AppController
{
    var $name = 'Services';
    var $uses = null;
    var $helpers = array('Javascript');
    var $components = array('RequestHandler');

    function beforeFilter() {
        /*
        $is_ajax = ( isset($this->params['isAjax']) ) ? $this->params['isAjax'] : 0;
        if ( ! $is_ajax  ) {
            #$this->_bad_request('ajax request only');
            #$this->redirect('/');
            #return 0;
        }
        */
    }

    function index()
    {
        $this->redirect('/');
    }

    function test() {
        print 'test';
        die();
    }

    function random($min=1, $max=6, $format='json') {
        if ( $min < 0 ) {
            $min = 0;
        }
        if ( $max > 1000000 ) {
            $max = 1000000;
        }

        $Data = array( 'number' => mt_rand($min, $max) );

        $this->RequestHandler->respondAs('json');
        $this->set('Data', $Data);
        $this->render('json', 'ajax');
    }

    function _bad_request($reason='no explanation')
    {
        header('HTTP/1.1 400 Bad Request');
        $Data = array( 'error' => $reason );
        $this->_render_as_json($Data);
        die();
    }

    function _render_as_json($Data)
    {
        header("Pragma: no-cache");
        header("Cache-Control: no-store, no-cache, max-age=0, must-revalidate");
        header('Content-Type: application/json');
        header("X-JSON: ".$javascript->object($Data));
        print $javascript->object($Data);
        die();
    }
}

?>
