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
    }

    function form()
    {
        // sanity checks
        if ( !$form_key = $this->_get_form_key() )
            return $this->error('', 'no form key');
        if ( !$dom_id = $this->_get_dom_id($form_key) )
            return $this->error('', 'no dom_id');
        if ( !$meta_id = $this->_get_meta_id($form_key) )
            $meta_id = null;
        if ( !$multiples_ok = $this->_get_multiples_ok($form_key) )
            $multiples_ok = 1;
            
        // stage and session key
        $stage = $this->_get_stage($form_key);
                
        // get subaction
        $subaction = null;
        if ( isset($this->params['form']['subaction']) )
            $subaction = $this->params['form']['subaction'];
            
        // subaction tree
        #pr( array('subaction'=>$subaction, 'stage'=>$stage, 'dom_id'=>$dom_id, 'form_key'=>$form_key));
        // reset
        if ( $subaction == 'reset' )
            $stage = $this->_reset_form($form_key);
            
        elseif ( $subaction == 'edit' )
        {
            $CommentData = $this->_unpickle($form_key);
            #pr($CommentData);
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
                $this->data['Comment']['recaptcha'] = $this->params['form']['recaptcha_response_field'];
                $this->data['Comment']['author_ip'] = $this->RequestHandler->getClientIP();
                $this->data['Comment']['agent'] = $_SERVER['HTTP_USER_AGENT'];
                $this->data['Comment']['form_key'] = $form_key;
                $this->data['Comment']['associate_id'] = $form_key;
                $this->data['Comment']['dom_id'] = $dom_id;
                $this->data['Comment']['meta_id'] = $meta_id;
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
        {
            $this->set('multiples_ok', $multiples_ok);
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
    
    function _get_meta_id($form_key)
    {
        $session_key = "$form_key.meta_id";
        
        if ( isset($this->params['form']['meta_id']) )
            return $this->_set_dom_id($session_key, $this->params['form']['meta_id']);
        
        if ( $this->Session->check($session_key) )
            return $this->Session->read($session_key);
            
        return null;
    }
    
    function _set_meta_id($session_key, $meta_id)
    {
        $this->Session->write($session_key, $meta_id);
        return $meta_id;
    }
    
    function _get_multiples_ok($form_key)
    {
        $id = 'multiples_ok';
        $session_key = "$form_key.$id";
        
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