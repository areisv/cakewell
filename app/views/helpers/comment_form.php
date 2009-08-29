<?php

class CommentFormHelper extends Helper {

    #var $helpers = array('Html');
    var $name = 'CommentForm';

    function get_js_globals($form_key, $dom_id, $ajax_url, $callback='null')
    {
        $fuid = $this->fuid($form_key, $dom_id);

        $tpl = <<<XHTML
<script type="text/javascript">

    // Define global dict
    if ( typeof CakewellCommentDict == 'undefined' )
        var CakewellCommentDict = {};

    CakewellCommentDict['%s'] = {
        'form_key' : '%s',
        'dom_id'   : '%s',
        'ajax_url' : '%s',
        'callback' : '%s'
    };

</script>
XHTML;

        return sprintf($tpl, $fuid, $form_key, $dom_id, $ajax_url, $callback);
    }

    function get_submit_comment_js()
    {
        $tpl = <<<XHTML
<script type="text/javascript">
/* notice how we accommodate multiple forms in a single page making ajax
   requests for this snippet */
if ( typeof cakewell_submit_comment_form == 'undefined' )
{
    var cakewell_submit_comment_form = function(fuid)
    {
        var form_key = CakewellCommentDict[fuid]['form_key'];
        var dom_id = CakewellCommentDict[fuid]['dom_id'];
        var ajax_url = CakewellCommentDict[fuid]['ajax_url'];
        var callback = CakewellCommentDict[fuid]['callback'];

        var FormData = {
            'subaction': 'preview',
            'fuid': fuid,
            'form_key': form_key,
            'dom_id': dom_id,
            'callback': callback
        };

        /* this does not work -- for some reason, when an invalid form is
           resubmitted, some of the fields (including email field) get
           detached from the form element.  Code block below it does work.
            $('#'+fuid).find(':input').each( function(i) {
            if ( !$(this).attr('name') ) return;
            FormData[$(this).attr('name')] = $(this).val();
        }); */

        $(':input').each( function(i) {
            if ( !$(this).attr('name') ) return;
            if ( $(this).attr('name').indexOf(fuid) == -1 ) return;
            FormData[$(this).attr('name')] = $(this).val();
        });

        //console.log(FormData);
        $('#'+dom_id).load(ajax_url, FormData);
    }
}
</script>
XHTML;
        return $tpl;
    }

    function get_submit_preview_js()
    {
        $tpl = <<<XHTML
<script type="text/javascript">
if ( typeof cakewell_submit_comment_preview == 'undefined' )
{
    var cakewell_submit_comment_preview = function(fuid)
    {
        var form_key = CakewellCommentDict[fuid]['form_key'];
        var dom_id = CakewellCommentDict[fuid]['dom_id'];
        var ajax_url = CakewellCommentDict[fuid]['ajax_url'];
        var callback = CakewellCommentDict[fuid]['callback'];

        var FormData = {
            'subaction': 'save',
            'fuid': fuid,
            'form_key': form_key,
            'dom_id': dom_id,
            'callback': callback,
            'recaptcha_challenge_field': Recaptcha.get_challenge(),
            'recaptcha_response_field': Recaptcha.get_response()
        };

        $('#'+dom_id).load(ajax_url, FormData);
    }
}
</script>
XHTML;
        return $tpl;
    }

    function get_edit_comment_js()
    {
        $tpl = <<<XHTML
<script type="text/javascript">
if ( typeof cakewell_edit_comment_form == 'undefined' )
{
    var cakewell_edit_comment_form = function(fuid)
    {
        var form_key = CakewellCommentDict[fuid]['form_key'];
        var dom_id = CakewellCommentDict[fuid]['dom_id'];
        var ajax_url = CakewellCommentDict[fuid]['ajax_url'];
        var callback = CakewellCommentDict[fuid]['callback'];

        var FormData = {
            'subaction': 'edit',
            'fuid': fuid,
            'form_key': form_key,
            'dom_id': dom_id,
            'callback': callback
        };

        $('#'+dom_id).load(ajax_url, FormData);
    }
}
</script>
XHTML;
        return $tpl;
    }

    function get_reset_comment_js()
    {
        $tpl = <<<XHTML
<script type="text/javascript">
if ( typeof cakewell_reset_comment_form == 'undefined' )
{
    var cakewell_reset_comment_form = function(fuid)
    {
        var form_key = CakewellCommentDict[fuid]['form_key'];
        var dom_id = CakewellCommentDict[fuid]['dom_id'];
        var ajax_url = CakewellCommentDict[fuid]['ajax_url'];
        var callback = CakewellCommentDict[fuid]['callback'];

        var FormData = {
            'subaction': 'reset',
            'fuid': fuid,
            'form_key': form_key,
            'dom_id': dom_id,
            'callback': callback
        };

        $('#'+dom_id).load(ajax_url, FormData);
    }
}
</script>
XHTML;
        return $tpl;
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
        return preg_replace('%[\s\-#]%', '_', trim($s));
    }

    // get unique form id
    function fuid($form_key, $dom_id)
    {
        return sprintf('%s_%s', $this->n($form_key), $this->n($dom_id));
    }
}
?>
