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

    function logs($subaction=null, $p2=null)
    {
        $max_limit = 100;

        # sanity check limit
        if ( is_null($p2) || !is_numeric($p2) )
            $p2=25;
        if ( $p2 > $max_limit ) $p2 = $max_limit;

        # reset logs
        if ( isset($this->params['form']['reset']) )
        {
            $this->_reset_log('error');
            $this->_reset_log('debug');
            $this->set('alert', 'logs have been reset');
        }

        # subactions
        if ( $subaction == 'test' )
        {
            $this->log("test debug log ({$this->here})", LOG_DEBUG);
            $content = <<<XHTML
<h4>The message below has been added to the debug log: <tt>Log Test: %s</tt></h4>
<h5>To view the error and debug logs, see
    <a href="/backend/logs/tail/10">/backend/logs/tail/10</a>
</h5>
XHTML;
            $this->set('header', 'CakePhp Log Test');
            $this->set('content', sprintf($content, $this->here));
            $this->set('menu', '<a href="/backend/logs">Backend::Logs</a>');
            $this->render('/demo/index');
        }
        else
        {
            # log (too noisy)
            #$this->log("Tail last $p2 log entries");

            # open error and debug log file
            $ErrorLines = $this->_read_log('error');
            $DebugLines = $this->_read_log('debug');

            # collect last $p2 lines
            $ErrorLines = array_slice($ErrorLines, 0, $p2);
            $DebugLines = array_slice($DebugLines, 0, $p2);
            #debug($ErrorLines);

            # prepare view
            $this->set('limit', $p2);
            $this->set('ErrorLines', $ErrorLines);
            $this->set('DebugLines', $DebugLines);
            $this->render('log');
        }
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

    function _read_log($log_name)
    {
        $LogLines = array();
        $fpath_t = '%s/%s.log';
        $fpath = sprintf($fpath_t, LOGS, $log_name);

        if ( !$Fp = fopen($fpath, "r") )
        {
            trigger_error("unable to open log '$fpath'", E_USER_WARNING);
            return array();
        }

        while ( ! feof($Fp) )
        {
            $line = fgets($Fp);
            if ( strlen(trim($line)) )
                $LogLines[] = $line;
        }

        fclose($Fp);
        $LogLines = array_reverse($LogLines);
        return $LogLines;
    }

    function _reset_log($log_name)
    {
        $fpath_t = '%s/%s.log';
        $fpath = sprintf($fpath_t, LOGS, $log_name);

        if ( !$Fp = fopen($fpath, "w") )
        {
            trigger_error("unable to open log '$fpath'", E_USER_WARNING);
            return 0;
        }

        fwrite($Fp, '');
        fclose($Fp);

        $message = sprintf('Resetting %s log', $log_name);
        $this->log($message, $log_name);

        return 1;
    }

}

?>
