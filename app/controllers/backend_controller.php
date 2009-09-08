<?php

/*
    Cakewell Backend Controller

    Backend operations

    NOTES
        Protect this controller from general public access
*/

class BackendController extends AppController
{
    var $name = 'Backend';
    var $uses = null;   // or: array('ModelName');
    var $components = array('RequestHandler', 'Gatekeeper');

    var $CacheList = array('views', 'models', 'persistent');

    function index()
    {
        // code
        // To Do ApiHelper

        // output
        $this->set('content_for_layout', 'backend controller: to do ApiHelper');
        $this->render('/layouts/blank');
    }


    function clear_cache($all=0)
    {
        /*
            clears app cache using CakePhp's clearCache function.  If $all
            parameter is true, then it clears the views, models, and persistent
            subdirectories.  Otherwise, just the views (clearCache default
            behavior).
        */
        // restrict in production until Authwell ready
        $this->Gatekeeper->restrict_from_app_modes( array('production'),
            '/backend/',
            'this action is blocked until the Auth component is complete');


        $SubdirectoryList = $this->CacheList;
        $ClearedDirs = array();

        if ( strtolower($all) == 'false' || ! (bool) $all )
            $SubdirectoryList = array('views');

        foreach ( $SubdirectoryList as $dir )
        {
            $cleared = 'already empty';
            $path = sprintf('%s%s', CACHE, $dir);
            $count_before = count(glob(sprintf('%s/*', $path)));
            if ( ! $count_before )
            {
                $ClearedDirs[$dir] = array(
                    'path'      => $path,
                    'cleared'   => $cleared
                );
                continue;
            }
            $cleared = ( clearCache(null, $dir) ) ? 'ok' : 'fail';
            $count_after = count(glob(sprintf('%s/*', $dir)));
            $ClearedDirs[$dir] = array(
                'path'      => $path,
                'cleared'   => $cleared,
                'files'     => $count_before,
                'filed deleted' => $count_before - $count_after
            );
        }

        // output
        $this->set('header', 'Clear Cache Directories');
        $this->set('data', $ClearedDirs);
        $this->set('menu', '');
        $this->render('/demo/report');
    }

}

?>
