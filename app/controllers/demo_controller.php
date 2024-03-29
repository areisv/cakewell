<?php

class DemoController extends AppController
{
    var $name = 'Demo';
    var $uses = array('SimpleLog', 'Mock');
    var $components = array(
            'RequestHandler',
            'Twitter',
            'SimplePie',
            'Sample',
            'Gatekeeper',
            'SourceView',
            'Authwell.Auth',
            'Email' );

    var $paginate = array(
        'SimpleLog' => array(
            'limit' => 10,
            'order' => array(
                'SimpleLog.created' => 'desc'
            )
        )
    );

    var $build_source_view = 1;

    function beforeRender()
    {
        $this->set('menu', $this->Gatekeeper->get_controller_menu($this));
        if ( $this->build_source_view )
            $this->set('source_links', $this->SourceView->build_icon_block());
    }

    function index()
    {
        $summary = "A simple demonstration of CakePHP.";

        $content = <<<XHTML
<div class="intro">
    <p>The Demo controller includes several public actions presenting basic
    CakePhp features and settings.  They are listed in the menu to the
    right.</p>

    <p>The complete source code for this site can be found at the Cakewell
    Google Code Site.  For example, the controller and view files for this
    page can be found at the following links:</p>

    <ul>
        <li><a onclick="window.open(this.href,'_blank');return false;"
            href="http://code.google.com/p/cakewell/source/browse/app/controllers/demo_controller.php">
            controllers/demo_controller.php</a></li>
        <li><a onclick="window.open(this.href,'_blank');return false;"
            href="http://code.google.com/p/cakewell/source/browse/app/views/demo/index.ctp">
            views/demo/index.ctp</a></li>
    </ul>

    <p>Additional controllers can be found at the links below.</p>
</div>

<div class="section">
    <h4><a href="/comment">Comment Controller</a></h4>
    <p>This controller presents an ajax-driven three-stage comment form that
    incorporates the reCAPTCHA widget.</p>
</div>

<div class="section">
    <h4><a href="/journal">Journal Controller</a></h4>
    <p>A very basic view-centric journal or weblog that does not involve a
       database.  This is used for the <a href="/journal/changelog">Cakewell
       changelog</a>.</p>
</div>

<div class="section">
    <h4><a href="/authwell">Authwell Plugin</a></h4>
    <p>The Authwell plugin is an authorization component.  At this point, it
    is intended mainly as a learning tool and illustration of a robust
    CakePhp plugin. It can be tested, however, with the auth_demo and
    auth_forbidden links at right.</p>
    <p>The login is 'demo@klenwell.com', password 'cakewell'.  Click the link
    above for more examples.  Source code for the plugin can be found at the
    <a href="http://code.google.com/p/cakewell/source/browse/#hg/app/plugins/authwell"
    onclick="window.open(this.href,'_blank');return false;">Cakewell Google
    Code site</a>.</p>
</div>

XHTML;

        $this->set('header', 'Welcome to the Cakewell CakePhp Demo');
        $this->set('content', $content);
    }

    function dump($object='options')
    {
        if ( $object == 'view' ) {
            $this->set('header', 'Dumping a View Object');
            $this->render('view_dump');
        }
        elseif ( $object == 'controller' ) {
            $this->Gatekeeper->restrict_from_app_modes( array('production'),
                    '/demo/gatekeeper/blocked',
                    'this option is blocked in production mode');
            $REPORT = array($this->name => $this);
            $this->set('header', 'Dumping the Controller Object');
            $this->set('data', $REPORT);
            $this->render('report');
        }
        elseif ( $object == 'config' ) {
            $REPORT = array(
                'App.mode' => Configure::Read('App.mode'),
                'App.domain' => Configure::Read('App.domain'),
                'debug' => Configure::read('debug'),
                'Configure::Read(\'App\')' => Configure::Read('App')
            );
            $this->set('header', 'Cakewell Context-Specific App Values');
            $this->set('data', $REPORT);
            $this->render('report');
        }
        elseif ( $object == 'phpinfo' ) {
            $this->Gatekeeper->restrict_from_app_modes( array('production'),
                '/demo/',
                'this action is blocked in production mode');
            ob_start();
            phpinfo();
            $phpinfo = ob_get_clean();
            $this->set('content_for_view', $phpinfo);
            $this->render('blank', 'default');
        }
        elseif ( $object == 'request_handler' ) {
            $Report = array(
                '$this->Session->id()' => $this->Session->id(),
                '$this->Session->id' => $this->Session->id,
                '$this->RequestHandler->getReferrer()' => $this->RequestHandler->getReferrer(),
                '$_SERVER[\'HTTP_REFERER\']' => $_SERVER['HTTP_REFERER'],
                '$_SERVER[\'HTTP_USER_AGENT\']' => $_SERVER['HTTP_USER_AGENT'],
                'Controller::referer' => Controller::referer(),
                '$this->RequestHandler->getClientIP()' => $this->RequestHandler->getClientIP(),
                'FULL_BASE_URL + Router::url(\'\', false)' => FULL_BASE_URL . Router::url('', false),
                '$this->RequestHandler' => $this->RequestHandler
            );

            $this->set('header', 'showing RequestHandler info for client at ip ' . $this->RequestHandler->getClientIP());
            $this->set('data', $Report);
            $this->render('report');
        }
        elseif ( $object == 'referer' ) {
            $Data = array(
                'referer' => $this->referer(),
                'SERVER[\'HTTP_REFERER\']' => isset($_SERVER['HTTP_REFERER'])
                    ? $_SERVER['HTTP_REFERER']
                    : 'NULL',
                'Configure::read(\'Security.level\')' => Configure::read('Security.level'),
            );
            $this->set('header', 'Checking Referrer');
            $this->set('data', $Data);
            $this->render('report');
        }
        elseif ( $object == 'constants' ) {
            $this->set('header', 'Some CakePHP Constants and Globals (<a href="http://book.cakephp.org/view/122/Core-Definition-Constants">docs</a>)');
            $this->set('data', $this->_cake_constants());
            $this->render('report');
        }
        else {
            $content = <<<EOMENU
<h3>choose an object to dump</h3>
<a href="/demo/dump/controller/">controller object</a><br />
<a href="/demo/dump/view/">view object</a><br />
<a href="/demo/dump/config/">configuration app values</a><br />
<a href="/demo/dump/request_handler/">request handler</a><br />
<a href="/demo/dump/referer/">referrer</a><br />
<a href="/demo/dump/constants/">cakephp constants</a><br />
<a href="/demo/dump/phpinfo/">phpinfo</a><br />
EOMENU;
            $this->set('header', 'Object Dumper');
            $this->set('content', $content);
            $this->render('index');
        }
    }

    function model() {
        #debug($this->params);
        App::import('Vendor', 'recaptcha/recaptchalib');
        $is_logged = 0;

        # Recaptcha form processing
        $RecaptchaResponse = null;
        $RecaptchaError = null;
        if ( isset($this->data['Recaptcha']['request']) )
        {
            $RecaptchaResponse = recaptcha_check_answer (
                RECAPTCHA_PRIVATE_KEY,
                $_SERVER['REMOTE_ADDR'],
                $this->params['form']['recaptcha_challenge_field'],
                $this->params['form']['recaptcha_response_field']
            );

            if ( $RecaptchaResponse->is_valid ) {
                if ( $this->SimpleLog->log('info', 'recaptcha',
                    sprintf('recaptcha successful: %s',
                        $this->params['form']['recaptcha_response_field'])) ) {
                    $is_logged = 1;
                }
                else {
                    trigger_error(sprintf('failed to save: %s',
                        pr($this->SimpleLog->invalidFields(),1)));
                }
            }
        }

        $recaptcha_html = recaptcha_get_html( RECAPTCHA_PUBLIC_KEY,
                                                  $RecaptchaError );
        $Logs = $this->paginate('SimpleLog');

        $this->set('recaptcha_html', $recaptcha_html);
        $this->set('RecaptchaResponse', $RecaptchaResponse);
        $this->set('is_logged', $is_logged);
        $this->set('Logs', $Logs);
        $this->render('simplelog');
    }

    function component()
    {
        $result = $this->Sample->test();
        $this->set('header', 'Component Test');
        $this->set('data', $result);
        $this->render('report');
    }

    function _cake_constants()
    {
        $Constants = array(
            'Cake Constants' => array(
                'APP' => APP,
                'APP_DIR' => APP_DIR,
                'APP_PATH' => APP_PATH,
                'CACHE' => CACHE,
                'CAKE' => CAKE,
                'CAKE_CORE_INCLUDE_PATH' => CAKE_CORE_INCLUDE_PATH,
                'COMPONENTS' => COMPONENTS,
                'CONFIGS' => CONFIGS,
                'CONTROLLER_TESTS' => CONTROLLER_TESTS,
                'CONTROLLERS' => CONTROLLERS,
                'CORE_PATH' => CORE_PATH,
                'CSS' => CSS,
                'DEBUG (use Configure::read("debug"))' => DEBUG,
                'ELEMENTS' => ELEMENTS,
                'FULL_BASE_URL' => FULL_BASE_URL,
                'FULL_BASE_URL . Router::url(\'\', false)' => FULL_BASE_URL . Router::url('', false),
                'HELPER_TESTS' => HELPER_TESTS,
                'HELPERS' => HELPERS,
                'INFLECTIONS' => INFLECTIONS,
                'JS' => JS,
                'LAYOUTS' => LAYOUTS,
                'LIB_TESTS' => LIB_TESTS,
                'LIBS' => LIBS,
                'LOGS' => LOGS,
                'MODEL_TESTS' => MODEL_TESTS,
                'MODELS' => MODELS,
                'PROJECT_ROOT (Cakewell only)' => PROJECT_ROOT,
                'TESTS' => TESTS,
                'TMP' => TMP,
                'ROOT' => ROOT,
                'VENDORS' => VENDORS,
                'VIEWS' => VIEWS,
                'WEBROOT_DIR' => WEBROOT_DIR,
                'WWW_ROOT' => WWW_ROOT,
            ),
            'Controller Properties' => array(
                '$this->action' => $this->action,
                '$this->here' => $this->here,
                '$this->name' => $this->name,
                '$this->params[\'url\']' => $this->params['url'],
            ),
            'Other' => array(
                'ConnectionManager::sourceList' => ConnectionManager::sourceList(),
                'Configure Class Settings' => 'see config_test'
            )
        );
        return $Constants;
    }

    function cache()
    {
        /*
            cakephp docs: http://book.cakephp.org/view/764/Cache
        */
        $display_t = '<span class="%s">%s</span>: %s';

        # cache config settings
        $cache_key = 'cache_demo';
        $cache_config = 'demo';
        $CacheConfig = Configure::read('Cache');

        # update settings to force cache
        if ( $CacheConfig['disable'] || ! $CacheConfig['check'] )
        {
            Configure::write('Cache.disable', 0);
            Configure::write('Cache.check', 1);
        }
        $CacheDump = Configure::read('Cache');

        # check for cache
        # note: if the content may resolve to false, assign cache to variable
        # and then use strict comparison operators
        $cache_content = Cache::read($cache_key, $cache_config);
        if ( $cache_content !== FALSE )
        {
            $display = sprintf($display_t, 'hit', 'cache found', $cache_content);
        }
        else
        {
            $cache_content = sprintf('cache saved <b>%s</b>', date('H:i:s Y-m-d'));
            $display = sprintf($display_t, 'miss', 'cache not found',
                'saving new cache (reload page to see content)');
            Cache::write($cache_key, $cache_content, $cache_config);
        }

        # restore cache settings
        Configure::write('Cache', $CacheConfig);
        #debug(Configure::read('Cache'));

        # view settings
        $content_t = <<<XHTML
<div class="cache_demo">
    <p>%s</p>
    <small>current time: %s</small>
    <h4>Configure::read('Cache')</h4>
    <pre>%s</pre>
</div>
XHTML;

        $this->set('header', 'Cakewell Cache Demo');
        $this->set('content', sprintf($content_t,
                                      $display,
                                      date('H:i:s Y-m-d'),
                                      print_r($CacheDump,1)));
        $this->render('index');
    }

    function cron()
    {
        $summary = "A simple demonstration of CakePHP.";

        $content = <<<XHTML
<div class="intro">
    <p>Cakewell introduces a couple adaptations the CakePhp framework in order to
    handle requests from the command line and accommodate cron scheduling
    a little more easily.  It does not use the native CakePhp
    <a href="http://book.cakephp.org/view/108/The-CakePHP-Console">console</a>
    feature because I found running cron jobs with it <a
    href="http://book.cakephp.org/view/846/Running-Shells-as-cronjobs">too
    complicated</a>.</p>

    <p>Rather, the Cakewell handles cron job through a special <a
    href="http://code.google.com/p/cakewell/source/browse/webroot/cron.php?r=v1s8-201001">
    cron.php</a> file that mimics the <a
    href="http://code.google.com/p/cakewell/source/browse/webroot/index.php.default?r=v1s8-201001">index.php</a>
    file that drives all CakePhp requests.  Otherwise, requests use normal
    controllers.</p>
</div>

<div class="section">
    <h4>Usage</h4>
    <p>As with the CakePhp console, php's command line interface (php-cli) must
    beinstalled on your system to use this feature.  Once installed, a request
    may be invoked like so:</p>
    <pre>$ php cakewell/webroot/cron.php /cron/test <domain></pre>
    <p>The final parameter sets the server context based on the \$ConfigDomainMap
    settings in the core.php config file.</p>
    <p>A cron job can be set like so:</p>
    <pre>*/4 9-17 * * 1-5     php cakewell/webroot/cron.php /cron/test cakewell.klenwell.com</pre>
</div>

<div class="section">
    <h4>Production Note</h4>
    <p>Because nearlyfreespeech.net does not yet support cron jobs, there are
    no cron jobs scheduled in production.  But requests can be called from
    the command line:</p>
    <pre>$ php cron.php /cron/simple_log cakewell.klenwell.com</pre>
    <p>This command updates the simple log, which can be viewed
    <a href="/demo/model">here</a>.</p>
</div>

XHTML;

        $this->set('header', 'Cakewell Cron');
        $this->set('content', $content);
        $this->render('index');
    }

    function ajax() {
        $this->render('ajax');
    }

    function email()
    {
        App::import('Vendor', 'recaptcha/recaptchalib');
        $RecaptchaResponse = null;
        $RecaptchaError = null;
        $display_recaptcha = FALSE;
        $FormErrors = array();

        $email_subject = 'Cakewell Test Email';
        $email_body = <<<HERETEXT
This message was sent from cakewell.klenwell.com/demo/email_test
as a demonstration of the email function in the CakePhp framework.
The user was required to complete a ReCaptcha challenge to
send this message.

If you did not request this message, we apologize for any
inconvenience. If you believe our service is being abused,
please feel free to contact Tom at klenwell@gmail.com.
HERETEXT;
        $email_body = sprintf($email_body, GMAIL_USER);

        # subcontroller
        $subaction = ( isset($this->params['form']['subaction']) ) ?
            $this->params['form']['subaction'] : null;

        if ( $subaction == 'send test email' ) {
            #debug($this->params);

            $RecaptchaResponse = recaptcha_check_answer(
                RECAPTCHA_PRIVATE_KEY,
                $_SERVER["REMOTE_ADDR"],
                $_POST["recaptcha_challenge_field"],
                $_POST["recaptcha_response_field"] );

            if ( ! Validation::email($this->data['email_to']) ) {
                $FormErrors[] = 'email_to';
                $this->set('status', 'invalid email address');
                $display_recaptcha = TRUE;
            }

            if ( ! $RecaptchaResponse->is_valid ) {
                $FormErrors[] = 'recaptcha';
                $this->set('status', 'ReCaptcha failed');
                $RecaptchaError = $RecaptchaResponse->error;
                $display_recaptcha = TRUE;
            }

            if ( empty($FormErrors) ) {
                list($success, $status) =
                    $this->_send_email($this->data['email_to'],
                                       $email_subject,
                                       $email_body);
                $this->set('status', $status);
                if ( ! $success ) {
                    $display_recaptcha = TRUE;
                }
            }
        }
        else {
            $display_recaptcha = TRUE;
        }

        if ( $display_recaptcha ) {
            $recaptcha_html = recaptcha_get_html( RECAPTCHA_PUBLIC_KEY,
                                                  $RecaptchaError );
            $this->set('recaptcha_html', $recaptcha_html);
            $this->set('email_subject', $email_subject);
            $this->set('email_body', $email_body);
        }

        $this->render('email');
    }

    function _send_email($to_address, $subject, $body)
    {
        $email_f = '%s <%s>';
        $from_name = 'Cakewell Demo';
        $from_address = GMAIL_USER;

        $this->Email->smtpOptions = array(
            'port'      => '465',
            'timeout'   => '30',
            'host'      => 'ssl://smtp.gmail.com',
            'username'  => GMAIL_USER,
            'password'  => GMAIL_PASS,
        );

        $this->Email->delivery = 'smtp';
        $this->Email->to = sprintf($email_f, $to_address, $to_address);
        $this->Email->replyTo = $from_address;
        $this->Email->from = sprintf($email_f, $from_name, $from_address);
        $this->Email->subject = $subject;
        $this->Email->send($body);

        if ( !empty($this->Email->smtpError) ) {
            debug($this->Email->smtpError);
            $Response = array( 0,
                sprintf('<b>unable to send message</b>: %s', $this->Email->smtpError)
            );
            return $Response;
        }
        else {
            $Response = array( 1,
                sprintf('<b>message successfully sent to</b>: %s', $to_address)
            );

        }

        # notify me, too
        $body = <<<EMAILX
Requested for: %s
status: %s
EMAILX;
        $this->Email->to = sprintf($email_f, GMAIL_USER, GMAIL_USER);
        $this->Email->subject = sprintf('cakewell demo email sent at %s',
            date('g:ia'));
        $this->Email->send(sprintf($body, $to_address, $Response[1]));

        return $Response;
    }

    function gatekeeper($subaction='explain', $object=null)
    {
        $content = '';

        if ( $subaction == 'block' ) {
            if ( $object == 'production' ) {
                $content = 'this message should not be visible in production mode';
                $this->Gatekeeper->restrict_from_app_modes( array('production'),
                    '/demo/gatekeeper/blocked',
                    'this option is blocked in production mode');
            }
            elseif ( $object == 'test' ) {
                $content = 'this message should not be visible in test mode';
                $this->Gatekeeper->restrict_from_app_modes( array('test'),
                    '/demo/gatekeeper/blocked',
                    'this option is blocked in test mode');
            }
            else {
                $this->Gatekeeper->restrict_to_domains( array(),
                    '/demo/gatekeeper/blocked',
                    'this option is always blocked');
            }
        }
        elseif ( $subaction == 'blocked' ) {
            $content = '( blocked )';
        }
        elseif ( $subaction == 'api' ) {
            $MethodList = $this->Gatekeeper->get_controller_methods( $this->Gatekeeper );
            $content = sprintf('<h3>method list</h3><pre>%s</pre>',
                               print_r($MethodList,1));
        }
        else {
        }

        if ( $subaction != 'blocked' ) {
            $content .= <<<EOMENU
<h3>options</h3>
<a href="/demo/gatekeeper/block/production">block in production</a><br />
<a href="/demo/gatekeeper/block/test">block in test</a><br />
<a href="/demo/gatekeeper/block/all">block all</a><br />
<a href="/demo/gatekeeper/api">list gatekeeper methods</a>
EOMENU;
}

        $this->set('header', 'Gatekeeper Demo');
        $this->set('content', $content);
        $this->render('index');
    }

    function auth($option='default')
    {
        $header = 'Welcome to the Cakewell CakePhp Demo';

        if ( $option == 'forbidden' )
        {
            $this->Auth->flash_login('please login');
            $this->Auth->require_privilege('forbidden.null');
            $content = "<p>Oops.  You shouldn't see this.</p>";
            $this->set('header', 'Welcome to the Cakewell CakePhp Demo');
        }
        else
        {
            $this->Auth->flash_login('please login');
            $this->Auth->require_privilege('demo.demo');
            $content = sprintf('<p>You have logged in as <strong>%s</strong></p>',
                $this->Auth->get_user_name());
        }

        $content .= <<<EOMENU
<h3>options</h3>
<a href="/authwell/logout">logout</a><br />
<a href="/demo/auth/">this page</a><br />
<a href="/demo/auth/forbidden">blocked page</a><br />
EOMENU;
        $this->set('header', $header);
        $this->set('content', $content);
        $this->render('index');
    }

    function recaptcha()
    {
        return $this->redirect('/demo/model');
    }

    function sitemap()
    {
        /*
            Generate simple plaintext sitemap for this site.  In routes config,
            add route from /sitemap.txt to action

            NOTE: if querying a database table, too large a limit may raise
            memory issues.  Either keep limit in check or raise memory limit.
        */
        $domain_url = sprintf('http://%s/', Configure::read('App.domain'));
        $UrlList = array(
            $domain_url,
            $domain_url . 'demo/'
        );
        $ActionList = $this->Gatekeeper->get_controller_methods($this);
        foreach ( $ActionList as $action ) {
            $UrlList[] = sprintf('http://%s/%s/%s',
                                 Configure::read('App.domain'),
                                 low($this->name),
                                 $action);
        }

        if ( $this->here == '/sitemap.txt' ) {
            $this->RequestHandler->respondAs('text');
            $this->set('content_for_view', trim(implode("\n", $UrlList)));
            return $this->render('blank', 'blank');
        }
        else {
            $content_t = <<<XHTML
<h5>For spider view, try <a href="/sitemap.txt">sitemap.txt</a></h5>
<h6>%s urls</h6>
<pre>
%s
</pre>
XHTML;
            $this->set('content', sprintf( $content_t,
                                           count($UrlList),
                                           implode("\n", $UrlList)) );
            $this->set('header', 'Sitemap Link List');
            $this->render('index');
        }
    }

    function atom_builder() {
        /*
         This just repackages the Google updates feed as
        */
        $feed_url = 'http://code.google.com/feeds/p/cakewell/updates/basic';
        $ItemList = $this->SimplePie->fetch_url($feed_url);

        # Tidy output
        foreach ( $ItemList as $Item ) {
            foreach ( $Item as $k => $v ) {
                $v = preg_replace('%\s+%', ' ', $v);
                $Item_[$k] = preg_replace('%\n+%', "\n", $v);
            }
            $ItemList_[] = $Item_;
        }

        $this->set('ItemList', $ItemList_);
        $this->RequestHandler->respondAs('xml');
        $this->render('atom_builder', 'blank');
    }

    function atom_consumer() {
        $feed_url = 'http://code.google.com/feeds/p/cakewell/hgchanges/basic';
        $ItemList = $this->SimplePie->fetch_url($feed_url);

        # Tidy output
        $maxlen = 90;
        foreach ( $ItemList as $Item ) {
            foreach ( $Item as $k => $v ) {
                $v = preg_replace('%\s+%', ' ', $v);
                $Item_[$k] = preg_replace('%\n+%', "\n", $v);
                if ( strlen($v) > $maxlen+3 ) {
                    $Item_[$k] = sprintf('%s... (strlen: %s)',
                                         substr($v,0,$maxlen),
                                         strlen($v) );
                }
            }
            $ItemList_[] = $Item_;
        }


        $this->set('header',
                   'Google Code Atom Feed for Mercurial commits to Cakewell');
        $this->set('data', $ItemList_);
        $this->render('report');
    }

    function twitter_component()
    {
        $TweetData = $this->Twitter->get_tweets();

        // output
        $this->set('header', 'Twitter Component: $this->Twitter->get_tweets()');
        $this->set('data', $TweetData);
        $this->render('report');
    }

    function custom_error()
    {
        $this->cakeError('cakewellTestError', array('message'=>'a test error'));
        $REPORT = array(
            'you should be redirected to the error page'
        );

        $this->set('header', 'Test Error');
        $this->set('data', $REPORT);
        $this->render('report');
    }

    function cakeError_404()
    {
        $message = sprintf('%s [testing]', $this->here);
        $this->set('link', '/demo');
        $this->set('label', 'return to demo index');
        $this->cakeError('error404', array('url' => $message));
    }

    function redirection($type='index')
    {
        if ( $type == 'redirect' ) {
            $this->redirect("/{$this->viewPath}/index");
            die('unexpected error');
        }
        elseif ( $type == 'session_flash') {
            $this->Session->setFlash('sessionFlash set');
            $this->redirect("/{$this->viewPath}/index");
            die('unexpected error');
        }
        elseif ( $type == 'flash' ) {
            $this->flash('you are being redirected to the index',
                         '/'.$this->viewPath);
        }
        else {
            $content = <<<EOMENU
<h3>redirect options</h3>
<a href="/demo/redirection/redirect/">redirect</a><br />
<a href="/demo/redirection/session_flash/">redirect with Session->setFlash</a><br />
<a href="/demo/redirection/flash/">flash</a><br />
EOMENU;
            $this->set('header', 'Redirection Examples');
            $this->set('content', $content);
            $this->render('index');
        }
    }

    function set_utility($method='options') {
        if ( $method == 'merge' ) {
            $A1 = array(
                'Model' => array(
                    'f1' => '1',
                    'f2' => '1',
                    'f3' => '1',
                )
            );
            $A2 = array(
                'Model' => array(
                    'f3' => '2',
                    'f4' => '1',
                    'f5' => '1',
                )
            );

            $merged_arrays = array_merge($A1, $A2);
            $added_arrays = $A1 + $A2;
            $merged_recursive = array_merge_recursive($A1, $A2);

            function array_update($arr,$ins)
            {
                if(is_array($arr) && is_array($ins))
                {
                    foreach($ins as $k=>$v)
                    {
                        if(isset($arr[$k])&&is_array($v)&&is_array($arr[$k]))
                            $arr[$k] = array_update($arr[$k],$v);
                        else
                            $arr[$k] = $v;
                    }
                }
                elseif(!is_array($arr)&&(strlen($arr)==0||$arr==0))
                    $arr=$ins;

                return($arr);
            }
            $array_update = array_update($A1, $A2);
            $set_merge = Set::merge($A1, $A2);

            $REPORT = array(
                'A1' => $A1,
                'A2' => $A2,
                'merged' => $merged_arrays,
                'added' => $added_arrays,
                'merged_recursive' => $merged_recursive,
                'array_update' => $array_update,
                'Set::merge' => $set_merge,
                'Set::isEqual($array_update, $set_merge)' => Set::isEqual($array_update, $set_merge),
            );
        }
        elseif ( $method == 'diff' ) {
            $set = 'a.b.c';
            $DiffList = array(
                'a' => 0,
                'a.b' => 0,
                'a.b.c' => 1,
                'a.b.c.x' => 1,
                'a.b.x' => 0,
                'a.x' => 0,
                'x' => 0,
                'b' => 0,
                'b.c' => 0,
                '*' => 1,
                '*.b.c' => 1,
                '*.x' => 0
            );
            $REPORT['results'] = '';

            foreach ( $DiffList as $diff_set => $expect )
            {
                $SetArray = explode('.', $set);
                $DiffArray = explode('.', $diff_set);
                $depth_delta = count($SetArray) - count($DiffArray);

                if ( $depth_delta > 0 )
                    foreach( range(1,$depth_delta) as $n )
                        $DiffArray[] = ( $DiffArray[count($DiffArray)-1] == '*' ) ? '*' : '!';

                $SetDiff = Set::diff($SetArray, $DiffArray);
                $mismatch = 0;
                if ( $SetDiff )
                    foreach ( $SetArray as $n => $x )
                        if ( isset($SetDiff[$n]) && $DiffArray[$n] != '*' )
                            $mismatch = "$n => $x";

                $has_privilege = (int)!(bool)$mismatch;

                $REPORT[$diff_set] = array(
                    'pass' => (int)($has_privilege == $expect),
                    'expect' => $expect,
                    'result' => $has_privilege,
                    'mismatch' => $mismatch,
                    "$set diff $diff_set" => $SetDiff,
                );
            }
            $REPORT['results'] = Set::extract('{s}.pass', $REPORT);
            unset($REPORT['results'][0]);
        }
        else {
            $content = <<<EOMENU
<h3>methods</h3>
<a href="/demo/set_utility/merge">Set::merge</a><br />
<a href="/demo/set_utility/diff">Set::diff</a><br />
EOMENU;
            $this->set('header', 'Gatekeeper Test');
            $this->set('content', $content);
            return $this->render('index');
        }

        $this->set('header', 'Set Examples');
        $this->set('data', $REPORT);
        $this->render('report');
    }

    function sandbox()
    {
        /*
          This is an open action for quick-testing new stuff and things I'm
          not sure about.
        */
        $this->SourceView->introspect();

        $a = 'a';
        $b = 'b';
        $c = 'c';
        $Arr = array( 'a' => 'not a', 'c' => 'not c' );
        extract($Arr, EXTR_OVERWRITE);
        $REPORT = array($a,$b,$c);

        $this->set('header', 'Sandbox');
        $this->set('data', $REPORT);

        $this->set('menu', $this->Gatekeeper->get_controller_menu($this));
        $this->render('report');
    }
}

?>
