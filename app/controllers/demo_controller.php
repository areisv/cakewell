<?php

class DemoController extends AppController
{
    var $name = 'Demo';
    var $uses = array('Mock', 'SimpleRecord');
    var $components = array('RequestHandler', 'Twitter', 'Sample', 'Gatekeeper');

    function beforeRender()
    {
        $this->set('menu', $this->Gatekeeper->get_controller_menu($this));
    }

    function index()
    {
        $summary = "A simple demonstration of CakePHP.";

        $content = "<h4>choose a menu action at right</h4>";

        $this->set('header', 'Index of DemoController');
        $this->set('content', $content);
    }

    function sandbox()
    {
        /*
          This is an open action for quick-testing new shit and things I'm
          not sure about.
        */
        $re = '%[^0-9]%';
        $in = 'a9b9c9-9d9e9-9f9g9!9';
        $out = preg_replace($re, '', $in);
        $REPORT = array(
            'in' => $in,
            'out' => $out
        );
        $this->set('header', 'Sandbox');
        $this->set('data', $REPORT);
        $this->set('menu', $this->Gatekeeper->get_controller_menu($this));
        $this->render('report');
    }

    function controller_dump()
    {
        $REPORT = array($this->name => $this);
        $this->set('header', 'Dumping the Controller Object');
        $this->set('data', $REPORT);
        $this->render('report');
    }

    function config_test()
    {
        $REPORT = array(
            'App.mode' => Configure::Read('App.mode'),
            'App.server_name' => Configure::Read('App.server_name'),
            'Configure::Read(\'App\')' => Configure::Read('App'),
            'PROJECT_ROOT' => PROJECT_ROOT
        );

        $this->set('header', 'Cakewell Domain-Specific Auto-Configuration Values');
        $this->set('data', $REPORT);
        $this->render('report');
    }

    function cake_constants()
    {
        $REPORT = array(
            'APP' => APP,
            'APP_DIR' => APP_DIR,
            'CAKE' => CAKE,
            'CAKE_CORE_INCLUDE_PATH' => CAKE_CORE_INCLUDE_PATH,
            'COMPONENTS' => COMPONENTS,
            'CONFIGS' => CONFIGS,
            'CONTROLLERS' => CONTROLLERS,
            'CORE_PATH' => CORE_PATH,
            'CSS' => CSS,
            'DEBUG' => DEBUG,
            'Configure::read("debug")' => Configure::read('debug'),
            'ConnectionManager::sourceList' => ConnectionManager::sourceList(),
            'ELEMENTS' => ELEMENTS,
            'FULL_BASE_URL' => FULL_BASE_URL,
            'FULL_BASE_URL . Router::url(\'\', false)' => FULL_BASE_URL . Router::url('', false),
            '$this->here' => $this->here,
            'JS' => JS,
            'LAYOUTS' => LAYOUTS,
            'LIBS' => LIBS,
            'LOGS' => LOGS,
            'MODELS' => MODELS,
            '$this->params[\'url\']' => $this->params['url'],
            'TMP' => TMP,
            'ROOT' => ROOT,
            'VENDORS' => VENDORS,
            'VIEWS' => VIEWS,
            'WEBROOT_DIR' => WEBROOT_DIR,
            'WWW_ROOT' => WWW_ROOT,
        );

        $this->set('header', 'Some CakePHP Constants and Globals (<a href="http://book.cakephp.org/view/122/Core-Definition-Constants">docs</a>)');
        $this->set('data', $REPORT);
        $this->render('report');
    }

    function phpinfo()
    {
        ob_start();
        phpinfo();
        $phpinfo = ob_get_clean();
        $this->set('content_for_view', $phpinfo);
        $this->render('blank', 'blank');
    }

    function test_simple_record_model()
    {
        $Records = $this->SimpleRecord->find('all', array('limit' => 3));

        $REPORT = array(
            '$this->SimpleRecord->find(\'all\', array(\'limit\' => 3))' => $Records,
            '$this->SimpleRecord' => $this->SimpleRecord,
        );

        $this->set('header', 'Simple Record Model');
        $this->set('data', $REPORT);
        $this->render('report');
    }

    function test_simple_record_save()
    {
        $Record = array(
            'SimpleRecord' => array(
                'value' => 'test: ' . md5(time()),
            )
        );

        $this->SimpleRecord->create($Record);
        $result = $this->SimpleRecord->save();

        $REPORT = array(
            '$this->SimpleRecord->save()' => $result,
            '$this->SimpleRecord->id' => $this->SimpleRecord->id,
        );

        $this->set('header', 'Simple Record Save Example');
        $this->set('data', $REPORT);
        $this->render('report');
    }

    function test_behavior()
    {
        $this->SimpleRecord->test_normalizer();
        $REPORT = array(
            '$this->SimpleRecord->test_normalizer()' => $this->SimpleRecord->test_normalizer(),
        );

        $this->set('header', 'Normalizer Behavior Test');
        $this->set('data', $REPORT);
        $this->render('report');
    }

    function test_mock_model()
    {
        // import does not seem to work when $uses set to null
        #App::import('Mock');
        $Mock = new Mock();

        $REPORT = array(
            '$Mock->find()' => $Mock->find(),
            'Mock Model Object' => $Mock,
        );

        $this->set('header', 'A Mock Model');
        $this->set('data', $REPORT);
        $this->render('report');
    }

    function test_component()
    {
        $result = $this->Sample->test();
        $this->set('header', 'Component Test');
        $this->set('data', $result);
        $this->render('report');
    }

    function test_request_handler()
    {
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

    function test_recaptcha()
    {
        App::import('Vendor', 'recaptcha/recaptchalib');

        $RecaptchaResponse = null;
        $RecaptchaError = null;
        $header = 'recaptcha demo';

        if ( isset($_POST["submit"]) )
        {
            $RecaptchaResponse = recaptcha_check_answer (
                                    RECAPTCHA_PRIVATE_KEY,
                                    $_SERVER["REMOTE_ADDR"],
                                    $_POST["recaptcha_challenge_field"],
                                    $_POST["recaptcha_response_field"] );

            if ( $RecaptchaResponse->is_valid )
            {
                $header = '<div class="ok">recaptcha successful</div>';
            }
            else
            {
                $RecaptchaError = $RecaptchaResponse->error;
                $header = sprintf('<div class="fail">recaptcha error: %s</div>',
                                    $RecaptchaError );
            }
        }
        $recaptcha_html = recaptcha_get_html( RECAPTCHA_PUBLIC_KEY,
                                                  $RecaptchaError );

        $form_html = <<<XHTML
<div class="recaptcha_form">
<form action="{$this->here}" method="post">
    {$recaptcha_html}
    <input type="submit" name="submit" value="submit" />
</form>
</div>
XHTML;

        // output
        $this->set('header', $header . $form_html);
        $this->set('data', ( isset($RecaptchaResponse) ) ? print_r($RecaptchaResponse,1) : '');
        $this->render('report');
    }

    function test_twitter_component()
    {
        $TweetData = $this->Twitter->get_tweets();

        // output
        $this->set('header', 'Twitter Component: $this->Twitter->get_tweets()');
        $this->set('data', $TweetData);
        $this->render('report');
    }

    function test_gatekeeper_component($restrict=null, $redirect=null, $message=null)
    {
        $restrict = ( $restrict == 'restrict' ) ? 1 : null;
        $redirect = ( $redirect == 'redirect' ) ? 1 : null;
        $message = ( $message == 'message' ) ? 1 : null;
        #debug(sprintf('%s %s', $redirect, $message));
        #debug((int) ($redirect || $message));

        if ( $restrict )
        {
            if ( $redirect ) $redirect = '/demo/index';
            if ( $message ) $message = 'the gatekeeper is blocking you';
            $this->Gatekeeper->restrict_to_domains(array(), $redirect, $message);
        }

        $Menu = array(
            'click one of the links below to test',
            '<a href="/demo/test_gatekeeper_component/restrict/redirect/message">block: redirect with message</a>',
            '<a href="/demo/test_gatekeeper_component/restrict/redirect/nomessage">block: redirect with no message</a>',
            '<a href="/demo/test_gatekeeper_component/restrict/noredirect/message">block: redirect to home with message</a>',
            '<a href="/demo/test_gatekeeper_component/restrict/noredirect/nomessage">block: redirect to home</a>',
            '<a href="/demo/test_gatekeeper_component/norestrict/">no block: reload this page</a>',
        );

        $this->set('header', 'Gatekeeper Component');
        $this->set('content', sprintf('<pre>%s</pre>', print_r($Menu,1)));
        $this->render('index');
    }

    function test_gatekeeper_block_production()
    {
        $this->Gatekeeper->restrict_from_app_modes( array('production'),
            '/demo/',
            'this action is blocked in production mode');

        $this->set('header', 'Gatekeeper Test');
        $this->set('content', 'this message should not be visible in production mode');
        $this->render('index');
    }

    function test_gatekeeper_block_test()
    {
        $this->Gatekeeper->restrict_from_app_modes( array('test'),
            '/demo/',
            'this action is blocked in test mode');

        $this->set('header', 'Gatekeeper Test');
        $this->set('content', 'this message should not be visible in test mode');
        $this->render('index');
    }

    function test_gatekeeper_method_list()
    {
        $MethodList = $this->Gatekeeper->get_controller_methods( $this );

        $this->set('header', 'Gatekeeper Method List');
        $this->set('data', $MethodList);
        $this->render('report');
    }

    function test_flash()
    {
        $this->flash('you are being redirected to the index', '/'.$this->viewPath);
    }

    function test_redirect()
    {
        $this->redirect("/{$this->viewPath}/index");
        die();
    }

    function test_set_merge()
    {
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

        $this->set('header', 'Sandbox');
        $this->set('data', $REPORT);
        $this->render('report');
    }
}

?>
