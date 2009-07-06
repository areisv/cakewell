<?php

/*
    Twitter Plugin Controller

    Uses iscander twitter datasource.

    REFERENCES
        http://bakery.cakephp.org/articles/view/twitter-datasource
        http://book.cakephp.org/view/116/Plugin-Controllers
*/


class TwitterController extends TwitterAppController
{
    var $name = 'Twitter';
    var $uses = null;
    var $helpers = array( 'Javascript' );
    var $components = array('RequestHandler');

    function beforeFilter()
    {
        App::Import('ConnectionManager');
        App::Import('DataSource');
        App::import( array('type' => 'File', 'name' => 'Twitter.TWITTER_CONFIG',
                           'file' => 'config'.DS.'core.php') );
        App::import( array('type' => 'File', 'name' => 'Twitter.TwitterSource',
                           'file' => 'models'.DS.'datasources'.DS.'twitter_source.php') );
        $TwitterConfig =& new TWITTER_CONFIG();
        ConnectionManager::create('twitter', $TwitterConfig->login);
        $this->Twitter = ConnectionManager::getDataSource('twitter');
    }

    function index()
    {
        // get methods to provide api
        $ApiMethods = $this->_get_controller_methods();
        $this->set('header', 'Twitter Plugin API');
        $this->set('Data', $ApiMethods);
        $this->render('report');
    }

    function twitter($format='ajax')
    {
        /*
           This should be called in a POST request with a 'tweet' parameter for
           the status message, as in an AJAX call for instance.
        */
        #pr($this->params);
        if ( empty($this->params['form']['tweet']) )
            return $this->_render_as_json(array('error' => 'POST::tweet not found'));

        // Get twitter response
        $Response = $this->Twitter->status_update($this->params['form']['tweet']);

        // Ajax format
        if ( $this->params['isAjax'] || $format == 'ajax' )
            return $this->_render_as_json($Response);

        // Full HTML format
        $this->set('header', 'Twitter::twitter Response');
        $this->set('Data', $Response);
        $this->render('report');
    }

    function credentials($format)
    {
        // Get twitter response
        $Response = $this->Twitter->account_verify_credentials();

        // Ajax format
        if ( $this->params['isAjax'] || $format == 'ajax' )
            return $this->_render_as_json($Response);

        // Full HTML format
        $this->set('Data', array( 'account_verify_credentials' => $Response ));
        $this->render('report');
    }

    function help_test($format)
    {
        // Get twitter response
        $Response = $this->Twitter->help_test();

        // Ajax format
        if ( $this->params['isAjax'] || $format == 'ajax' )
            return $this->_render_as_json($Response);

        // Full HTML format
        $this->set('Data', array( 'help_test' => $Response ));
        $this->render('report');
    }

    function test_tweet($format)
    {
        $tweet = sprintf('cakewell twitter api test - more info at %s',
            'http://code.google.com/p/cakewell/');
        $view = $this->requestAction(
            array( 'controller' => 'twitter',
                   'action' => 'twitter',
                   'form' => array( 'tweet' => $tweet ) ),
            array('return', 'pass'=>array('html'))
        );
        $this->set('content_for_layout', $view);
        $this->render('/layouts/blank');
    }


    function _render_as_json($Data)
    {
        $this->set('Data', $Data);
        $this->render('ajax', 'ajax');
    }


    function _get_controller_methods($filter_=1)
    {
        $MethodList = array_values(
            array_diff( get_class_methods(__CLASS__), get_class_methods('AppController') )
        );
        if ( $filter_ )
            foreach ( range(0,count($MethodList)-1) as $i )
                if ( substr($MethodList[$i],0,1) == '_' )
                    unset($MethodList[$i]);
        return $MethodList;
    }
}

?>
