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
    public $layout = 'ajax';
    public $stage = 0;
    
    function afterFilter()
    {
    }
    
    function index()
    {
        $this->layout = 'jquery';
        $this->set('form_key', 'comment-index-test');
        $this->set('dom_id', 'comment-form');
    }

    function form()
    {
        // sanity checks
        if ( !$form_key = $this->_get_form_key() )
            return $this->error('', 'no form key');
        if ( !$dom_id = $this->_get_dom_id($form_key) )
            return $this->error('', 'no dom_id');
            
        // stage and session key
        $stage = $this->_get_stage($form_key);
                
        // get subaction
        $subaction = null;
        if ( isset($this->params['form']['subaction']) )
            $subaction = $this->params['form']['subaction'];
            
        // subaction tree
        pr( array('subaction'=>$subaction, 'stage'=>$stage, 'dom_id'=>$dom_id, 'form_key'=>$form_key));
        // reset
        if ( $subaction == 'reset' )
            $stage = $this->_reset_form($form_key);
            
        elseif ( $subaction == 'edit' )
        {
            $CommentData = $this->_unpickle($form_key);
            pr($CommentData);
            $this->Comment->set($CommentData);
            $this->Comment->validates($CommentData);
            $stage = $this->_set_stage($form_key, 1);
        }
        
        // preview (submit comment form)
        elseif ( $subaction == 'preview' )
        {
            $this->Comment->set($this->data);
            if ( $this->Comment->validates($this->data) )
            {
                $this->_pickle($form_key, $this->Comment->data);
                $stage = $this->_set_stage($form_key, 2);
            }
        }
        
        // save (submit preview)
        elseif ( $subaction == 'save' )
        {
             $RecaptchaResponse = recaptcha_check_answer (
                                    RECAPTCHA_PRIVATE_KEY,
                                    $_SERVER["REMOTE_ADDR"],
                                    $this->params['form']['recaptcha_challenge_field'],
                                    $this->params['form']['recaptcha_response_field'] );
            
            if ( $RecaptchaResponse->is_valid )
            {
                $this->data['Comment'] = $this->_unpickle($form_key);
                #pr($this->data);
                $this->data['Comment']['recaptcha'] = $this->params['form']['recaptcha_response_field'];
                $this->Comment->set($this->data);
                if ( $this->Comment->save() )
                    $stage = $this->_set_stage($form_key, 3);
                else
                    $this->set('form_message', 'unable to save comment');
            }
            else
            {
                $this->set('form_message', 'recaptcha failed: please try again');
            }
        }

        // shared view settings
        $this->set('form_key', $form_key);
        $this->set('dom_id', $dom_id);
        $this->set('CommentData', $this->_unpickle($form_key));

        // show stage 3
        if ( $stage == 3 )
            return $this->render('form_3');

        // show stage 2
        if ( $stage == 2 )
        {           
            // add recaptcha html
            if ( !isset($recaptcha_error) ) $recaptcha_error = '';
            return $this->render('form_2');
        }

        // show stage 1
        return $this->render('form_1');
    }
    
    function error($message='', $error='unspecified')
    {
        $this->set('message', $message);
        $this->set('error', $error);
        return $this->render('error');
    }
    
    
    function _get_stage($form_key)
    {
        $session_key = "$form_key.stage";
        if ( !$this->Session->check($session_key) )
            return $this->_set_stage($form_key, 1);
        return $this->Session->read($session_key);
    }

    function _set_stage($form_key, $num)
    {
        $session_key = "$form_key.stage";
        $this->Session->write($session_key, $num);
        return $num;
    }
    
    function _reset_form($form_key)
    {
        $this->_pickle($form_key);
        return $this->_set_stage($form_key, 1);
    }
    
    
    function _get_form_key()
    {
        if ( isset($this->params['form']['form_key']) )
            return $this->params['form']['form_key'];
        return null;
    }
    
    function _get_dom_id($form_key)
    {
        $session_key = "$form_key.dom_id";
        
        if ( isset($this->params['form']['dom_id']) )
            return $this->_set_dom_id($session_key, $this->params['form']['dom_id']);
        
        if ( $this->Session->check($session_key) )
            return $this->Session->read($session_key);
            
        return null;
    }
    
    function _set_dom_id($session_key, $dom_id)
    {
        $this->Session->write($session_key, $dom_id);
        return $dom_id;
    }
    
    function _pickle($form_key, $CommentData=null)
    {
        $session_key = sprintf('%s.CommentData', $form_key);
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
    
    function _unpickle($form_key)
    {
        $session_key = sprintf('%s.CommentData', $form_key);
        
        if ( $this->Session->check($session_key) )
            return $this->Session->read($session_key);
        return array();
    }

    
    
    function show()
    {
        $this->render();
    }
}

?>