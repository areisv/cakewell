<?php

class CommentFormHelper extends Helper {

    #var $helpers = array('Html');
    var $name = 'CommentForm';

    function dedash($s)
    {
        return str_replace('-', '_', $s);
    }

    function get_javascript_functions($dom_id, $form_key, $ajax_url, $callback='null')
    {
        $dom_id_ = $this->dedash($dom_id);
        $form_key_ = $this->dedash($form_key);

        // unique form id
        $ufid = sprintf('%s_%s', $dom_id_, $form_key_);

        $tpl = <<<XHTML
<script type="text/javascript">

var CommentFormGlobals_{$ufid} = {
    'dom_id'   : '%s',
    'form_key' : '%s',
    'ajax_url' : '%s',
    'callback' : '%s'
};

function submit_comment_form_{$ufid}()
{
    var dom_id = CommentFormGlobals_{$ufid}['dom_id'];
    var ajax_url = CommentFormGlobals_{$ufid}['ajax_url'];
    var FormData = {
        'subaction': 'preview',
        'form_key': CommentFormGlobals_{$ufid}['form_key'],
        'callback': CommentFormGlobals_{$ufid}['callback']
    };

    $(':input').each( function(i) {
        if ( !$(this).attr('name') ) return;
        FormData[$(this).attr('name')] = $(this).val();
    });

    //console.log(FormData);
    $('#'+dom_id).load( ajax_url, FormData);
}

function submit_comment_preview_{$ufid}()
{
    var dom_id = CommentFormGlobals_{$ufid}['dom_id'];
    var ajax_url = CommentFormGlobals_{$ufid}['ajax_url'];
    var FormData = {
        'subaction': 'save',
        'form_key': CommentFormGlobals_{$ufid}['form_key'],
        'callback': CommentFormGlobals_{$ufid}['callback'],
        'recaptcha_challenge_field': Recaptcha.get_challenge(),
        'recaptcha_response_field': Recaptcha.get_response()
    };

    $('#'+dom_id).load( ajax_url, FormData );
}

function edit_comment_form_{$ufid}()
{
    var dom_id = CommentFormGlobals_{$ufid}['dom_id'];
    var ajax_url = CommentFormGlobals_{$ufid}['ajax_url'];
    var FormData = {
        'subaction': 'edit',
        'form_key': CommentFormGlobals_{$ufid}['form_key'],
        'callback': CommentFormGlobals_{$ufid}['callback']
    };

    //console.log(FormData);
    $('#'+dom_id).load( ajax_url, FormData );
}

function reset_comment_form_{$ufid}()
{
    var dom_id = CommentFormGlobals_{$ufid}['dom_id'];
    var ajax_url = CommentFormGlobals_{$ufid}['ajax_url'];
    var FormData = {
        'subaction': 'reset',
        'form_key': CommentFormGlobals_{$ufid}['form_key'],
        'callback': CommentFormGlobals_{$ufid}['callback']
    };

    //console.log(FormData);
    $('#'+dom_id).load(ajax_url, FormData);
}

</script>
XHTML;
        #debug($tpl);
        return sprintf($tpl,$dom_id, $form_key, $ajax_url, $callback);
    }

    function get_recaptcha_js($recaptcha_key)
    {
        $tpl = <<<XHTML
<script type="text/javascript">

$(document).ready( function() {
    //Recaptcha.destroy();
    Recaptcha.create('%s', 'recaptcha_element', {
        //theme: themeName,
        tabindex: 0,
        callback: Recaptcha.focus_response_field
    });
});

</script>
XHTML;
        return sprintf($tpl, $recaptcha_key);
    }

    function get_callback($callback='null')
    {
        if ( $callback == 'null' )
            return "\n<!-- no callback for comment form -->\n";

        $tpl = <<<XHTML
<script type="text/javascript">
// callback: %s
%s();
</script>
XHTML;

        return sprintf($tpl, $callback, $callback);
    }
}
?>
