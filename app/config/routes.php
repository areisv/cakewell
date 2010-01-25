<?php
/**
 * Short description for file.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * Example (maps / to /pages/display with view file home) :
 * Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
 */

    Router::connect('/', array('controller' => 'pages', 'action' => 'home'));
    Router::connect('/journal/*', array('controller' => 'journal', 'action' => 'index'));
    Router::connect('/cake', array('controller' => 'pages', 'action' => 'display', 'cake'));
    Router::connect('/gatekeeper/block',
        array('controller' => 'demo', 'action' => 'test_gatekeeper_component',
              'restrict', 'redirect', 'message'));

    // Pages Controller (a default)
    #Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

    // Test Controller
    Router::connect('/test', array('controller' => 'tests', 'action' => 'index'));

    // Sitemap
    Router::connect('/sitemap.txt', array('controller' => 'demo', 'action' => 'sitemap'));
?>
