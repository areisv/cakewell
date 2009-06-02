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
        $this->set('form_id', 'comment-index-test');
        $this->set('dom_id', 'comment-form');
    }

    function form()
    {
        // get stage
        if ( !$this->Session->check('comment.stage') )
            $this->set_stage(1);
        $this->set_stage($this->Session->read('comment.stage'));
        
        // reset form
        if ( isset($this->params['form']['reset_comment']) )
        {
            $this->data['Comment'] = array();
            $this->_pickle();
            $this->set_stage(1);
        }
        #pr($this->params);
        #pr($this->data);
        
        // handle submissions
        if ( isset($this->data['Comment']['submitted']) )
        {
            // form (stage 1) submission
            if ( $this->data['Comment']['submitted'] == 'form_' )
            {
                $this->Comment->set($this->data);
                if ( $this->Comment->validates($this->data) )
                {
                    $this->_pickle($this->Comment->data);
                    $this->set_stage(2);
                }
            }

            // preview (stage 2) submission
            if ( $this->data['Comment']['submitted'] == 'preview_' )
            {
                $RecaptchaResponse = recaptcha_check_answer (
                                        RECAPTCHA_PRIVATE_KEY,
                                        $_SERVER["REMOTE_ADDR"],
                                        $this->params['form']['recaptcha_challenge_field'],
                                        $this->params['form']['recaptcha_response_field'] );
                
                if ( $RecaptchaResponse->is_valid )
                {
                    $this->data = $this->_unpickle();
                    $this->data['Comment']['recaptcha'] = $this->params['form']['recaptcha_response_field'];
                    $this->Comment->set($this->data);
                    $this->Comment->save();
                    $this->set_stage(3);
                }
                else
                {
                    $this->set('form_message', 'recaptcha failed: please try again');
                }
            }

            if ( $this->data['Comment']['submitted'] == 'reset_' )
                $this->set_stage(1);
        }

        // shared view settings
        $this->set('form_id', $this->_get_form_id());
        $this->set('dom_id', $this->_get_dom_id());

        // show stage 3
        if ( $this->stage == 3 )
            return $this->render('form_3');

        // show stage 2
        if ( $this->stage == 2 )
        {
            // preview data
            $Pickle = $this->_unpickle();
            $CommentData = $Pickle['Comment'];
            $this->set('author', $CommentData['author']);
            $this->set('author_email', $CommentData['author_email']);
            $this->set('author_url', $CommentData['author_url']);
            $this->set('comment_text', $CommentData['text']);
            
            // add recaptcha html
            if ( !isset($recaptcha_error) ) $recaptcha_error = '';
            return $this->render('form_2');
        }

        // show stage 1
        return $this->render('form_1');
    }


    function set_stage($num)
    {
        $this->Session->write('comment.stage', $num);
        $this->stage = $num;
    }
    
    function _pickle($data=array())
    {
        $this->Session->write('CommentData', $data);
    }
    
    function _unpickle()
    {
        if ( $this->Session->check('CommentData') )
            return $this->Session->read('CommentData');
        return array();
    }

    function _get_form_id()
    {
        if ( !empty($this->params['form']['form_id']) )
            return $this->params['form']['form_id'];
        if ( !empty($this->data['Comment']['form_id']) )
            return $this->data['Comment']['form_id'];
        return '';
    }

    function _get_dom_id()
    {
        if ( !empty($this->params['form']['dom_id']) )
            return $this->params['form']['dom_id'];
        if ( !empty($this->data['Comment']['dom_id']) )
            return $this->data['Comment']['dom_id'];
        return '';
    }
    
    
    function show()
    {
        $this->render();
    }
}

?>