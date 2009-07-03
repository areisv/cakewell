<?php

/*
    Twitter Plugin Controller



    REFERENCES
        http://bakery.cakephp.org/articles/view/twitter-datasource
        http://book.cakephp.org/view/116/Plugin-Controllers
*/


class TwitterController extends AppController
{
    var $name = 'Twitter';
    var $uses = null;
    #var $components = array('RequestHandler');

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
        #debug($this->Twitter);
        $this->redirect('/twitter/debug');
    }

    function debug()
    {
        $Report = array();
        $Report['response'] = $this->Twitter->account_verify_credentials();
        $Report['search'] = $this->Twitter->search('cakephp');
        $this->set('Report', $Report);
        $this->render('report');
    }
}

?>
