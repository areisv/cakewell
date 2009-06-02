<?php

/*
    A CakePhp Controller Template
    
    Summary of controller here.
*/

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
        
        // handle submissions
        if ( isset($this->data['Comment']['submitted']) )
        {
            // form (stage 1) submission
            if ( $this->data['Comment']['submitted'] == 'form_' )
            {
                $this->Comment->set($this->data);
                if ( $this->Comment->validates($this->data) )
                {
                    $this->_pickle();
                    $this->set_stage(2);
                }
            }

            // preview (stage 2) submission
            if ( $this->data['Comment']['submitted'] == 'preview_' )
            {
                $RecaptchaResponse = recaptcha_check_answer (
                                        RECAPTCHA_PRIVATE_KEY,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"] );
                
                if ( $RecaptchaResponse->is_valid )
                {
                    $this->Comment->save();
                    $this->set_stage(3);
                }
                else
                {
                    $RecaptchaError = $RecaptchaResponse->error;
                    $header = sprintf('<div class="fail">recaptcha error: %s</div>',
                                        $RecaptchaError );
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
            return $this->render('form_2');

        // show stage 1
        return $this->render('form_1');
    }


    function set_stage($num)
    {
        $this->Session->write('comment.stage', $num);
        $this->stage = $num;
    }
    
    function _pickle()
    {
        $this->Session->write('CommentData', $this->data);
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