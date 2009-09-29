<?php

/*
    Twitter Component
    Author: Tom at klenwell@gmail.com
    Last Update: $Date$

    A very simple example of a component.  Useful as a template.

    USAGE (in controller)
        var $components = array('Twitter');

        $xml = $this->Twitter->tweet('Cool, another cakephp twitter component!');

    NOTES
        A simplified version of Alexandru Ciobanu's Twitter Datasource.  See:
        http://bakery.cakephp.org/articles/view/twitter-datasource
*/

App::import('Core', array('Xml', 'HttpSocket'));

class TwitterComponent extends Object
{
    var $base_url = 'http://twitter.com/';
    var $description = "Twitter API";

    var $Ctrl = null;
    var $Http = null;

    // called before Controller:beforeFilter()
    function initialize(&$controller)
    {
        $this->Ctrl = $controller;
        $this->Http =& new HttpSocket();
        if ( !$this->_load_credentials() ) return 0;
    }

    // called after Controller::beforeFilter()
    function startup(&$controller)
    {
    }

    function tweet($message)
    {
        return $this->status_update($message);
    }

    function get_tweets()
    {
        return $this->status_user_timeline();
    }

    function status_update($status)
    {
        $path = 'statuses/update.xml';
        return $this->__process( $this->Http->post(
            $this->_service_url($path),
            array('status' => $status),
            $this->__getAuthHeader()
        ));
    }

    function status_user_timeline($id=false, $params = array())
    {
        /*
            For more info, see:
            http://groups.google.com/group/twitter-development-talk/web/api-documentation
        */
        $path = 'statuses/user_timeline';
        if( $id != false )
            $path .= sprintf('/%s.xml', $id);
        else
            $path .= ".xml";

        return $this->__process( $this->Http->get(
            $this->_service_url($path),
            $params,
            $this->__getAuthHeader()
        ));
    }

    function status_public_timeline($params=array()) {
        /* ref: http://apiwiki.twitter.com/Twitter-REST-API-Method%3A-statuses-public_timeline */
        $url = 'http://twitter.com/statuses/public_timeline.xml';
        return $this->__process($this->Http->get($url, $params));
    }

    function search($keyword, $lang='en', $rpp='25'){
        /* ref: http://apiwiki.twitter.com/Twitter-Search-API-Method%3A-search */
        $urlt_ = 'http://search.twitter.com/search.atom?q=%s&lang=%s&rpp=%s';
        $url = sprintf($urlt_, $keyword, $lang, $rpp);
        return $this->__process($this->Http->get($url));
    }

    function search_atom($keyword, $lang='en', $rpp='25'){
        /* ref: http://apiwiki.twitter.com/Twitter-Search-API-Method%3A-search */
        $urlt_ = 'http://search.twitter.com/search.atom?q=%s&lang=%s&rpp=%s';
        $url = sprintf($urlt_, $keyword, $lang, $rpp);
        return $this->__parse_atom($this->Http->get($url));
    }

    function search_json($keyword, $lang='en', $rpp='25') {
        /* ref: http://apiwiki.twitter.com/Twitter-Search-API-Method%3A-search */
        $urlt_ = 'http://search.twitter.com/search.json?q=%s&lang=%s&rpp=%s';
        $url = sprintf($urlt_, $keyword, $lang, $rpp);
        return json_decode($this->Http->get($url));
    }

    function help_test()
    {
        $path = 'help/test.xml';
        return $this->__process($this->Http->get($this->_service_url($path)));
    }

    function help_downtime_schedule()
    {
        $path = 'help/downtime_schedule.xml';
        return $this->__process($this->Http->get($this->_service_url($path)));
    }


    function test()
    {
        return sprintf('testing component %s for controller %s', __CLASS__, $this->Ctrl->name);
    }

    function _error($message, $type=E_USER_WARNING)
    {
        trigger_error($message, $type);
        return 0;
    }

    function _service_url($path)
    {
        if ( $path[0] == '/' ) $path = substr($path, 1);
        return $this->base_url . $path;
    }

    function _load_credentials()
    {
        if ( !defined('TWITTER_USER') )
            return $this->_error('TWITTER_USER not found: define constant in core.php');

        if ( !defined('TWITTER_PASS') )
            return $this->_error('TWITTER_PASS not found: define constant in core.php');

        return 1;
    }

    function __getAuthHeader()
    {
        return array( 'auth' => array( 'method' => 'Basic',
            'user' => TWITTER_USER,
            'pass' => TWITTER_PASS )
        );
    }

    function __parse_atom($response)
    {
        $AtomDict = array();
        $EntryList = array();

        $Xml = new SimpleXMLElement($response);

        foreach ( $Xml->children() as $Node )
        {
            $node_name = $Node->getName();
            if ( $node_name == 'entry' )
                $EntryList[] = $this->__parse_atom_entry($Node);
            elseif ( $node_name == 'link' )
                $AtomDict[(string) $Node['rel']] = (string) $Node['href'];
            else
                $AtomDict[$node_name] = (string) $Node;
        }
        $AtomDict['Entry'] = $EntryList;

        $Xml = null;
        unset($Xml);

        return $AtomDict;
    }

    function __parse_atom_entry($NodeObj)
    {
        $Dict = array();
        foreach ( $NodeObj->children() as $Node )
        {
            $node_name = $Node->getName();
            if ( $node_name == 'link' )
                $Dict[(string) $Node['rel']] = (string) $Node['href'];
            elseif ( $node_name == 'author' ) {
                $Dict['author_name'] = (string) $Node->name;
                $Dict['author_url'] = (string) $Node->uri;
            }
            else
                $Dict[$node_name] = (string) $Node;
        }
        return $Dict;
    }

    function __process($response)
    {
        /* Note: this will fail to properly distinguish among link tags -- see
           __parse_atom above for a better alternative */
        $xml = new XML($response);
        $array = $xml->toArray();

        $xml->__killParent();
        $xml->__destruct();
        $xml = null;
        unset($xml);

        return $array;
    }
}
?>
