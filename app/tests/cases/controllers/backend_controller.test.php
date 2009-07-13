<?php

/*
    Backend Controller Test

    For WebTest documentation, see:
    http://simpletest.sourceforge.net/en/web_tester_documentation.html
*/

App::import('Controller', 'Backend');

class Backend extends BackendController {
    var $name = 'Backend';
    var $autoRender = false;

    function redirect($url, $status = null, $exit = true) {
        $this->redirectUrl = $url;
    }

    function render($action = null, $layout = null, $file = null) {
        $this->renderedAction = $action;
    }

    function _stop($status = 0) {
        $this->stopped = $status;
    }
}

class BackendControllerTest extends CakeTestCase {

    var $base_url = 'http://cakewell/';
    var $demo_url = '';

    function setUp()
    {
        $this->Backend = new BackendController();
        $this->Backend->constructClasses();
    }

    function teardown()
    {
        $this->Backend = null;
    }

    function testInstance() {
        $this->assertTrue(is_a($this->Backend, 'BackendController'));
    }

    function testProperties() {
        $this->assertTrue(is_null($this->Backend->uses));
        $this->assertTrue(in_array('views',$this->Backend->CacheList));
    }

    function testClearCache() {
        // first, add files to each of the cache directories
        foreach ( $this->Backend->CacheList as $subdir )
        {
            $files = array('a', 'b', 'c');
            foreach ( $files as $f )
            {
                $path = sprintf('%s%s/%s', CACHE, $subdir, $f);
                $FilePtr = fopen($path, 'a');
                fwrite($FilePtr, 'a cakewell unit test');
                fclose($FilePtr);
            }
        }

        $view_cache_glob = sprintf('%s%s/*', CACHE, 'views');
        $this->assertTrue(count(glob($view_cache_glob)) >= 3, 'should be 3 files in views cache');
        $view = $this->Backend->clear_cache(1);
        $this->assertEqual(count(glob($view_cache_glob)), 0, 'views cache should now be empty');
        debug($this->Backend->viewVars['data']);
    }

    function rand_choice($ARRAY)
    {
        return $ARRAY[array_rand($ARRAY)];
    }
}

?>
