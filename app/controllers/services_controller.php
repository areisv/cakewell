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

    function random($min=1, $max=100, $format='json') {
        if ( $min < 0 ) {
            $min = 0;
        }
        if ( $max > 1000000 ) {
            $max = 1000000;
        }

        $Data = array(
            'min'    => $min,
            'max'    => $max,
            'number' => mt_rand($min, $max)
        );

        if ( $format == 'plain' ) {
            $this->_render_plain($Data['number']);
        }
        else {
            $this->_render_json($Data);
        }
    }

    function dice($sides=6) {
        $DiceChoice = array(4, 6, 10, 20);
        $base_die_code = 9855;
        $die_html = '';

        if ( ! in_array($sides, $DiceChoice) ) {
            $sides = 6;
        }

        $rolled = mt_rand(1, $sides);
        $callback = ( isset($this->params['url']['callback']) )
            ? $this->params['url']['callback'] : NULL;
        if ( $sides == 6 ) {
            $die_html = sprintf('&#%s;', $base_die_code + $rolled);
        }

        $JsonData = array( 'rolled' => $rolled,
                           'die'    => $die_html );

        $this->_render_jsonp($JsonData, $callback);
    }

    function _bad_request($reason='no explanation')
    {
        header('HTTP/1.1 400 Bad Request');
        $Data = array( 'error' => $reason );
        $this->_render_as_json($Data);
        die();
    }

    function _render_json($JsonData)
    {
        App::import('Helper', 'Javascript');
        $Javascript = new JavascriptHelper();
        $content = $Javascript->object($JsonData);

        header("Pragma: no-cache");
        header("Cache-Control: no-store, no-cache, max-age=0, must-revalidate");
        header('Content-Type: application/x-javascript; charset=utf-8');
        header("X-JSON: ".$content);
        print $content;
        die();
    }

    function _render_jsonp($JsonData, $wrapper='?') {
        if ( $wrapper == '?' ) {
            $wrapper = substr(md5(microtime(1)), -9);
        }

        App::import('Helper', 'Javascript');
        $Javascript = new JavascriptHelper();
        $content = sprintf('%s(%s)', $wrapper, $Javascript->object($JsonData));

        header('Content-Type: application/x-javascript; charset=utf-8');
        print $content;
        die();
    }

    function _render_plain($content) {
        header('Content-type: text/plain; charset=UTF-8');
        print $content;
        die();
    }
}

?>
