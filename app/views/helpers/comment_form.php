<?php

class CommentFormHelper extends Helper {

    #var $helpers = array('Html');
    var $name = 'CommentForm';

    function get_javascript_functions($dom_id, $form_key, $ajax_url, $callback='null')
    {
        $tpl = <<<XHTML
<script type="text/javascript">

var CommentFormGlobals = {
    'dom_id'   : '%s',
    'form_key' : '%s',
    'ajax_url' : '%s',
    'callback' : '%s'
};

function submit_comment_form_()
{
    var dom_id = CommentFormGlobals['dom_id'];
    var ajax_url = CommentFormGlobals['ajax_url'];
    var FormData = {
        'subaction': 'preview',
        'form_key': CommentFormGlobals['form_key'],
        'callback': CommentFormGlobals['callback']
    };

    $(':input').each( function(i) {
        if ( !$(this).attr('name') ) return;
        FormData[$(this).attr('name')] = $(this).val();
    });

    //console.log(FormData);
    $('#'+dom_id).load( ajax_url, FormData);
}

function submit_comment_preview_()
{
    var dom_id = CommentFormGlobals['dom_id'];
    var ajax_url = CommentFormGlobals['ajax_url'];
    var FormData = {
        'subaction': 'save',
        'form_key': CommentFormGlobals['form_key'],
        'callback': CommentFormGlobals['callback'],
        'recaptcha_challenge_field': Recaptcha.get_challenge(),
        'recaptcha_response_field': Recaptcha.get_response()
    };

    $('#'+dom_id).load( ajax_url, FormData );
}

function edit_comment_form_()
{
    var dom_id = CommentFormGlobals['dom_id'];
    var ajax_url = CommentFormGlobals['ajax_url'];
    var FormData = {
        'subaction': 'edit',
        'form_key': CommentFormGlobals['form_key'],
        'callback': CommentFormGlobals['callback']
    };

    //console.log(FormData);
    $('#'+dom_id).load( ajax_url, FormData );
}

function reset_comment_form_()
{
    var dom_id = CommentFormGlobals['dom_id'];
    var ajax_url = CommentFormGlobals['ajax_url'];
    var FormData = {
        'subaction': 'reset',
        'form_key': CommentFormGlobals['form_key'],
        'callback': CommentFormGlobals['callback']
    };

    //console.log(FormData);
    $('#'+dom_id).load( ajax_url, FormData);
}

</script>
XHTML;

        return sprintf($tpl, $dom_id, $form_key, $ajax_url, $callback);
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
