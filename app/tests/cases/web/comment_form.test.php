<?php

/*
    Comment Test Template

    For WebTest documentation, see:
    http://simpletest.sourceforge.net/en/web_tester_documentation.html
*/

class CommentFormWebTest extends WebTestCase {

    var $base_url = 'http://cakewell/';
    var $form_url = '';

    function setUp()
    {
        $this->ajax_url = $this->base_url . 'comment/form/';
    }

    function testSanity()
    {
        $this->assertTrue($this->get($this->base_url));
    }

    function testForm()
    {
        $this->stage1();
        $this->stage2();
        $this->stage3();
        $this->reset();
        $this->spambot();
    }

    function stage1()
    {
        // simulate ajax request to get form
        $cue = 'Leave a Comment';
        $AjaxPost = array(
            'dom_id' => 'comment-form',
            'form_key' => 'comment-index-test',
            'meta_id' => '1',
            'multiples_ok' => '1'
        );
        $this->assertTrue($this->post($this->ajax_url, $AjaxPost));
        $this->assertText($cue);

        // submit form
        $cue = 'Preview Comment';
        $this->submit_comment();
        $this->assertText($cue);
    }

    function stage2()
    {
        /* This stage cannot be successfully test because of the ReCaptcha
           integration.  That is a good thing.  We just have to test for
           the invalidation. */
        $cue = 'recaptcha failed: please try again';
        $AjaxPost = array(
            'subaction' => 'save',
            'form_key' => 'comment-index-test',
            'recaptcha_challenge_field' => '02gFpr6y58yu1P38PqjbSfk8uRSYFewnbzzA3TxWcyAhxLEHOxjPESpho7ehUVz-e8Rqa77tY8Fqfburii95NuEHIIinTYN5IVXEToIQZa9v6lB2kLoyeTABaGywcxMubBTWWDDIGA2KIpL-WgTqJ3yH2W8nRAmqc6YGbtDT1mz4WXQJa4fk9btJpxZdY9wyX7PqwQseJLtRroLeRZmuyd93iWpWCfRl3tV8Uncu_NE2wDQBALAAwhwm_bBKhckReQSnPP0gftZoRM5O46Uo8WEccoc4kB',
            'recaptcha_response_field' => 'abject failure'
        );
        $this->assertTrue($this->post($this->ajax_url, $AjaxPost));
        $this->assertText($cue);
    }

    function stage3()
    {
        /* Cannot actually reach this stage in test with ReCaptcha in place.
           This is included just to outline the form structure. */
        $cue = '';
    }

    function reset()
    {
        $cue = 'Leave a Comment';
        $AjaxPost = array(
            'form_key' => 'comment-index-test',
            'subaction' => 'reset'
        );
        $this->assertTrue($this->post($this->ajax_url, $AjaxPost));
        $this->assertText($cue);
    }

    function spambot()
    {
        $cue = 'This is kinda spammy, clean it up';
        $this->submit_comment($as_spam=1);
        $this->assertText($cue);
    }

    function submit_comment($as_spam=0)
    {
        $AjaxPost = array(
            'data[Comment][text]' => "Comment form test\n\nA test comment",
            'data[Comment][author_email]' => 'cakewell@code.google.com',
            'data[Comment][author]' => 'cakewell web test',
            'form_key' => 'comment-index-test',
            'subaction' => 'preview'
        );

        if ( $as_spam )
            $AjaxPost['data[Comment][topyeno]'] = 'vi@g@r@!';

        $this->post($this->ajax_url, $AjaxPost);
        #debug(htmlentities($this->get_content()));
    }

    function get_content()
    {
        return $this->_browser->getContent();
    }

    function rand_choice($ARRAY)
    {
        return $ARRAY[array_rand($ARRAY)];
    }
}

?>
