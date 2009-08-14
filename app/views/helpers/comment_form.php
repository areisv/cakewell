<?php

class CommentFormHelper extends Helper {

    #var $helpers = array('Html');
    var $name = 'CommentForm';

    function get_javascript_functions($dom_id, $form_key, $ajax_url, $callback='null')
    {
        trigger_error('deprecated');
    }

    function get_submit_comment_js($form_key, $dom_id, $ajax_url, $callback='')
    {
        $tpl = <<<XHTML
<script type="text/javascript">
function cakewell_submit_comment_form(fuid)
{
    var form_key = '%s';
    var dom_id = '%s';
    var ajax_url = '%s';
    var callback = '%s';

    var FormData = {
        'subaction': 'preview',
        'fuid': fuid,
        'form_key': form_key,
        'dom_id': dom_id,
        'callback': callback
    };

    $('#'+fuid).find(':input').each( function(i) {
        if ( !$(this).attr('name') ) return;
        FormData[$(this).attr('name')] = $(this).val();
    });

    //console.log(FormData);
    $('#'+dom_id).load(ajax_url, FormData);
}
</script>
XHTML;
        return sprintf($tpl, $form_key, $dom_id, $ajax_url, $callback);
    }


    function get_submit_preview_js($form_key, $dom_id, $ajax_url, $callback='')
    {
        $tpl = <<<XHTML
<script type="text/javascript">
function cakewell_submit_comment_preview(fuid)
{
    var form_key = '%s';
    var dom_id = '%s';
    var ajax_url = '%s';
    var callback = '%s';

    var FormData = {
        'subaction': 'save',
        'fuid': fuid,
        'form_key': form_key,
        'dom_id': dom_id,
        'callback': callback,
        'recaptcha_challenge_field': Recaptcha.get_challenge(),
        'recaptcha_response_field': Recaptcha.get_response()
    };

    $('#'+fuid).find(':input').each( function(i) {
        if ( !$(this).attr('name') ) return;
        FormData[$(this).attr('name')] = $(this).val();
    });

    //console.log(FormData);
    $('#'+dom_id).load(ajax_url, FormData);
}
</script>
XHTML;
        return sprintf($tpl, $form_key, $dom_id, $ajax_url, $callback);
    }

    function get_edit_comment_js($form_key, $dom_id, $ajax_url, $callback='')
    {
        $tpl = <<<XHTML
<script type="text/javascript">
function cakewell_edit_comment_form(fuid)
{
    var form_key = '%s';
    var dom_id = '%s';
    var ajax_url = '%s';
    var callback = '%s';

    var FormData = {
        'subaction': 'edit',
        'fuid': fuid,
        'form_key': form_key,
        'dom_id': dom_id,
        'callback': callback
    };

    $('#'+fuid).find(':input').each( function(i) {
        if ( !$(this).attr('name') ) return;
        FormData[$(this).attr('name')] = $(this).val();
    });

    //console.log(FormData);
    $('#'+dom_id).load(ajax_url, FormData);
}
</script>
XHTML;
        return sprintf($tpl, $form_key, $dom_id, $ajax_url, $callback);
    }

    function get_reset_comment_js($form_key, $dom_id, $ajax_url, $callback='')
    {
        $tpl = <<<XHTML
<script type="text/javascript">
function cakewell_reset_comment_form(fuid)
{
    var form_key = '%s';
    var dom_id = '%s';
    var ajax_url = '%s';
    var callback = '%s';

    var FormData = {
        'subaction': 'edit',
        'fuid': fuid,
        'form_key': form_key,
        'dom_id': dom_id,
        'callback': callback
    };

    $('#'+fuid).find(':input').each( function(i) {
        if ( !$(this).attr('name') ) return;
        FormData[$(this).attr('name')] = $(this).val();
    });

    //console.log(FormData);
    $('#'+dom_id).load(ajax_url, FormData);
}
</script>
XHTML;
        return sprintf($tpl, $form_key, $dom_id, $ajax_url, $callback);
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

    // normalizer
    function n($s)
    {
        return preg_replace('%[\s\-]%', '_', trim($s));
    }

    // get unique form id
    function fuid($form_key, $dom_id)
    {
        return sprintf('%s_%s', $this->n($form_key), $this->n($dom_id));
    }
}
?>
