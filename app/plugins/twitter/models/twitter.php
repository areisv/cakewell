<?php

/*
    Twitter Model

    Summary of model here.

    REFERENCES
        http://book.cakephp.org/view/117/Plugin-Models
*/

class Twitter extends TwitterAppModel
{
    var $name = 'Twitter';
    var $useDbConfig = 'twitter';
    var $useTable = false;
    var $validate = array();

    function __construct()
    {
        App::import( array('type' => 'File', 'name' => 'Twitter.TWITTER_CONFIG',
                           'file' => 'config'.DS.'core.php') );
        App::import( array('type' => 'File', 'name' => 'Twitter.TwitterSource',
                           'file' => 'models'.DS.'datasources'.DS.'twitter_source.php') );
        $TwitterConfig =& new TWITTER_CONFIG();
        ConnectionManager::create('twitter', $TwitterConfig->login);
        $this->Api = ConnectionManager::getDataSource('twitter');
        #debug($config);
        parent::__construct();
    }

    function schema() {
        return array();
    }

    function test()
    {
        return 'test successful';
    }
}

?>
