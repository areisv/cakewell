<?php

/*
    SimplePie Component
    Author: Tom at klenwell@gmail.com
    Last Update: $Date$

    Wraps Simplepie class to interact with Atom and RSS feeds

    USAGE (in controller)
        var $components = array('Sample');
        ...
        print $this->Sample->test();

    NOTES

*/

class SimplePieComponent extends Object
{
    public $Ctrl = null;
    public $SimplePie = null;

    // called before Controller:beforeFilter()
    function initialize(&$controller)
    {
        $this->Ctrl = $controller;
    }

    // called after Controller::beforeFilter()
    function startup(&$controller)
    {
    }

    function load_simplepie()
    {
        /*
         the SimplePie class is a beast so let's load only when we need it
        */
        if ( is_null($this->SimplePie) ) {
            App::import('Vendor', 'SimplePie', array('file' => 'simplepie.inc'));
            $this->SimplePie = new SimplePie();
            $this->SimplePie->set_cache_location(SIMPLEPIE_CACHE_PATH);
            $this->SimplePie->set_cache_duration(SIMPLEPIE_CACHE_TIME);
        }
        return $this->SimplePie;
    }

    function fetch_url($feed_url)
    {
        if ( $ItemList = $this->read_cache($feed_url) ) {
            return $ItemList;
        }

        # fetch feed
        $this->load_simplepie();
        $this->SimplePie->set_feed_url($feed_url);
        $this->SimplePie->init();
        $this->SimplePie->handle_content_type();

        # repackage item data as an array
        $ItemList = $this->feed_items_to_array();
        if ( $ItemList ) {
            $this->write_cache($feed_url, $ItemList);
        }
        return $ItemList;
    }

    function feed_items_to_array()
    {
        /*
         ref: http://simplepie.org/wiki/reference/start#simplepie_item
        */
        $ItemList = array();
        if ( ! $this->SimplePie->data ) {
            return array();
        }

        $Items = $this->SimplePie->get_items();
        foreach ( $Items as $ItemObj ) {
            $ItemData = array(
                'id'         => $ItemObj->get_title(),
                'date'       => $ItemObj->get_date(),
                'permalink'  => $ItemObj->get_permalink(),
                'title'      => html_entity_decode($ItemObj->get_title(), ENT_QUOTES, 'UTF-8'),
                'content'    => $ItemObj->get_content(),
                'feed_title' => $this->SimplePie->get_title(),
                'feed_link'  => $this->SimplePie->get_permalink(),
                'feed_type'  => $this->SimplePie->get_type(),
                'encoding'   => $this->SimplePie->get_encoding(),
                'fetched_at' => date('Y-m-d H:i:s')
            );
            $ItemList[] = $ItemData;
        }

        return $ItemList;
    }

    function read_cache($feed_url) {
        $cache_key = $this->get_feed_key($feed_url);
        return Cache::read($cache_key);
    }

    function write_cache($feed_url, $content) {
        Cache::set( array(
            'duration' => sprintf('+%s seconds', $this->SimplePie->cache_duration),
            'path' => $this->SimplePie->cache_location
        ));
        $cache_key = $this->get_feed_key($feed_url);
        return Cache::write($cache_key, $content);
    }

    function get_feed_key($url) {
        return md5($url);
    }

    function configure_cache($duration, $location=null) {
        $this->SimplePie->set_cache_duration($duration);
        if ( is_null($location) ) $location = SIMPLEPIE_CACHE_PATH;
        $this->SimplePie->set_cache_location($location);
    }

    function test()
    {
        return sprintf('testing component %s for controller %s', __CLASS__, $this->Ctrl->name);
    }
}
?>
