<?php

/*
    A CakePhp Controller Template

    Summary of controller here.
*/

App::import('Vendor', 'recaptcha/recaptchalib');

class CommentController extends AppController
{
    public $name = 'Comment';
    public $uses = array('Comment');
    public $components = array('RequestHandler');
    public $helpers = array('CommentForm');
    public $layout = 'ajax';
    public $stage = 0;

    function afterFilter()
    {
    }

    function index()
    {
        /* An example usage of the comment element through a controller
            form_key is a unique identifier for the form (this allows
                multiple forms to be used in a single page).
            dom_id is the DOM element on the page that should be replaced
                by the form
            meta_id would be the foreign_key id for the comment table. For
                example this could the id for a post from a posts table
                that was displayed on the page and that these comments
                were to be associated with.
        */
        $this->layout = 'jquery';
        $this->set('form_key', 'comment-index-test');
        $this->set('dom_id', 'comment-form');
        $this->set('meta_id', 1);
        $this->set('list_dom', 'comment-form-list');
        $this->set('callback', 'load_comment_list');
        $this->render('index', 'default');
    }

    function multiform()
    {
        $this->layout = 'jquery';
        $this->set('form_key1', 'comment-multiform-1');
        $this->set('dom_id1', 'comment-multiform-1');
        $this->set('list_dom1', 'multiform-comment-list-1');
        $this->set('meta_id1', 0);
        $this->set('form_key2', 'comment-multiform-2');
        $this->set('dom_id2', 'comment-multiform-2');
        $this->set('list_dom2', 'multiform-comment-list-2');
        $this->set('meta_id2', 0);
        $this->set('callback', 'load_comment_list');
    }

    function show()
    {
        /* Ajax call that returns a list of comments for the given form_key
          (and dom_id, if supplied) */
        $RequestDict = $this->_parse_show_request();

        $CommentList = $this->Comment->find_by_form_key(
            $RequestDict['form_key'],
            $RequestDict['dom_id'],
            $RequestDict['meta_id'],
            $RequestDict['limit'] );

        $this->set('CommentList', $CommentList);
        return $this->render('show');
    }

    function form()
    {
        // get Request dict
        $RequestDict = $this->_parse_form_request();

        // sanity checks
        if ( is_null($RequestDict['fuid']) )
            return $this->error('', 'no fuid');
        if ( is_null($RequestDict['form_key']) )
            return $this->error('', 'no form key');
        if ( is_null($RequestDict['dom_id']) )
            return $this->error('', 'no dom_id');

        // cake autoform is keyed to fuid to allow for multiple forms on a page
        // since only one form can be submitted at a time, we can factor the
        // fuid out of the data array
        if ( isset($this->data['Comment'][$RequestDict['fuid']]) )
            $this->data['Comment'] = $this->data['Comment'][$RequestDict['fuid']];
        ##pr($this->data);
        ##pr($RequestDict);

        // stage and session key
        $stage = $this->_get_stage($RequestDict['fuid']);

        // subaction tree
        // reset
        if ( $RequestDict['subaction'] == 'reset' )
            $stage = $this->_reset_form($RequestDict['fuid']);

        elseif ( $RequestDict['subaction'] == 'edit' )
        {
            $CommentData = $this->_unpickle($RequestDict['fuid']);
            $this->Comment->set($CommentData);
            $this->Comment->validates($CommentData);
            $stage = $this->_set_stage($RequestDict['fuid'], 1);
        }

        // preview (submit comment form)
        elseif ( $RequestDict['subaction'] == 'preview' )
        {
            $this->Comment->set($this->data);
            if ( $this->Comment->validates($this->data) )
            {
                $this->_pickle($RequestDict['fuid'], $this->Comment->data);
                $stage = $this->_set_stage($RequestDict['fuid'], 2);
            }
        }

        // save (submit preview)
        elseif ( $RequestDict['subaction'] == 'save' )
        {
             $RecaptchaResponse = recaptcha_check_answer (
                                    RECAPTCHA_PRIVATE_KEY,
                                    $_SERVER["REMOTE_ADDR"],
                                    $this->params['form']['recaptcha_challenge_field'],
                                    $this->params['form']['recaptcha_response_field'] );

            pr($this->_unpickle($RequestDict['fuid']));

            if ( $RecaptchaResponse->is_valid )
            {
                $this->data['Comment'] = $this->_unpickle($RequestDict['fuid']);
                debug($RequestDict);
                $this->data['Comment']['recaptcha'] = $this->params['form']['recaptcha_response_field'];
                $this->data['Comment']['author_ip'] = $this->RequestHandler->getClientIP();
                $this->data['Comment']['agent'] = $_SERVER['HTTP_USER_AGENT'];
                $this->data['Comment']['form_key'] = $RequestDict['form_key'];
                $this->data['Comment']['associate_id'] = $RequestDict['form_key'];
                $this->data['Comment']['dom_id'] = $RequestDict['dom_id'];
                $this->data['Comment']['meta_id'] = $RequestDict['meta_id'];
                $this->Comment->set($this->data);

                if ( $this->Comment->save() )
                    $stage = $this->_set_stage($RequestDict['fuid'], 3);
                else
                    $this->set('form_message', 'unable to save comment');
            }
            else
            {
                $this->set('form_message', 'recaptcha failed: please try again');
            }
        }

        // shared view settings
        $this->set('form_key', $RequestDict['form_key']);
        $this->set('dom_id', $RequestDict['dom_id']);
        $this->set('callback', $RequestDict['callback']);
        $this->set('CommentData', $this->_get_form_data($RequestDict['fuid']));

        // show stage 3
        if ( $stage == 3 )
        {
            $this->set('multiples_ok', $RequestDict['multiples_ok']);
            return $this->render('form_3');
        }

        // show stage 2
        if ( $stage == 2 )
        {
            // add recaptcha html
            if ( !isset($recaptcha_error) ) $recaptcha_error = '';
            return $this->render('form_2');
        }

        // show stage 1
        $this->set('TagList', $this->Comment->actsAs['Baffler']['TagList']);
        $this->set('honeypot_field', $this->Comment->honeypot_field);
        return $this->render('form_1');
    }

    function error($message='', $error='unspecified')
    {
        $this->set('message', $message);
        $this->set('error', $error);
        return $this->render('error');
    }


    function _get_stage($fuid)
    {
        $session_key = "$fuid.stage";
        if ( !$this->Session->check($session_key) )
            return $this->_set_stage($fuid, 1);
        return $this->Session->read($session_key);
    }

    function _set_stage($fuid, $num)
    {
        $session_key = "$fuid.stage";
        $this->Session->write($session_key, $num);
        return $num;
    }

    function _reset_form($fuid)
    {
        $this->_pickle($fuid);
        return $this->_set_stage($fuid, 1);
    }

    function _get_dom_id($fuid)
    {
        $session_key = "$fuid.dom_id";

        if ( isset($this->params['form']['dom_id']) )
            return $this->_set_dom_id($session_key, urldecode($this->params['form']['dom_id']));

        if ( $this->Session->check($session_key) )
            return $this->Session->read($session_key);

        return null;
    }

    function _set_dom_id($session_key, $dom_id)
    {
        $this->Session->write($session_key, $dom_id);
        return $dom_id;
    }

    function _get_meta_id($fuid)
    {
        $session_key = "$fuid.meta_id";

        if ( isset($this->params['form']['meta_id']) )
            return $this->_set_dom_id($session_key, urldecode($this->params['form']['meta_id']));

        if ( $this->Session->check($session_key) )
            return $this->Session->read($session_key);

        return null;
    }

    function _set_meta_id($session_key, $meta_id)
    {
        $this->Session->write($session_key, $meta_id);
        return $meta_id;
    }

    function _get_multiples_ok($fuid)
    {
        $id = 'multiples_ok';
        $session_key = "$fuid.$id";

        if ( isset($this->params['form'][$id]) )
            return $this->_set_multiples_ok($session_key, $this->params['form'][$id]);

        if ( $this->Session->check($session_key) )
            return $this->Session->read($session_key);

        return null;
    }

    function _set_multiples_ok($session_key, $ok)
    {
        $this->Session->write($session_key, $ok);
        return $ok;
    }

    function _pickle($fuid, $CommentData=null)
    {
        $session_key = sprintf('%s.CommentData', $fuid);
        if ( isset($CommentData['Comment']) )
            $CommentData = $CommentData['Comment'];

        if ( is_null($CommentData) )
        {
            if ( $this->Session->check($session_key) )
                $this->Session->del($session_key);

            return array();
        }

        $this->Session->write($session_key, $CommentData);
        return $CommentData;
    }

    function _unpickle($fuid)
    {
        $session_key = sprintf('%s.CommentData', $fuid);

        if ( $this->Session->check($session_key) )
            return $this->Session->read($session_key);
        return array();
    }

    function _get_form_data($fuid)
    {
        #pr($this->_unpickle($form_key)); pr($this->data);
        return Set::merge($this->_unpickle($fuid), $this->data['Comment']);
    }

    function _parse_form_request()
    {
        $RequestDict = array(
            'subaction' => null,
            'fuid' => null,
            'form_key' => null,
            'dom_id' => null,
            'meta_id' => null,
            'multiples_ok' => 1,
            'callback' => 'null'
        );

        if ( isset($this->params['form']['subaction']) )
            $RequestDict['subaction'] = $this->params['form']['subaction'];

        if ( isset($this->params['form']['fuid']) )
            $RequestDict['fuid'] = urldecode($this->params['form']['fuid']);

        if ( isset($this->params['form']['form_key']) )
            $RequestDict['form_key'] = urldecode($this->params['form']['form_key']);

        if ( isset($this->params['form']['callback']) )
            $RequestDict['callback'] = urldecode($this->params['form']['callback']);

        $RequestDict['dom_id'] = $this->_get_dom_id($RequestDict['fuid']);
        $RequestDict['meta_id'] = $this->_get_meta_id($RequestDict['fuid']);
        $RequestDict['multiples_ok'] = $this->_get_multiples_ok($RequestDict['fuid']);

        return $RequestDict;
    }

    function _parse_show_request()
    {
        $RequestDict = array(
            'list_dom' => $this->params['form']['list_dom'],
            'form_key' => $this->params['form']['form_key'],
            'dom_id' => null,
            'meta_id' => null,
            'limit' => null
        );

        if ( isset($this->params['form']['dom_id']) )
            $RequestDict['dom_id'] = $this->params['form']['dom_id'];

        if ( isset($this->params['form']['meta_id']) )
            $RequestDict['meta_id'] = $this->params['form']['meta_id'];

        if ( isset($this->params['form']['limit']) )
            $RequestDict['limit'] = $this->params['form']['limit'];

        return $RequestDict;
    }

}

?>
