<?php

    $ajax_url = '/comment/form/';
    $form_message = ( !empty($form_message) ) ? sprintf('<h4>%s</h4>', $form_message) : '';
    $author = ( !empty($CommentData['author']) ) ? $CommentData['author'] : 'anonymous';
    $author_email = ( !empty($CommentData['author_email']) ) ? sprintf('| %s', $CommentData['author_email']) : '';
    $text_ = ( !empty($CommentData['text']) ) ? nl2br($CommentData['text']) : '';
    
    if ( !empty($CommentData['author_url']) )
        $author = sprintf( '<a href="%s" %s>%s</a>',
                           $CommentData['author_url'],
                           'onclick="window.open(this.href,\'_blank\');return false;"',
                           $author);
                                                    

?>

<?php
    // adds all javascript function needed for ajax submission
    echo $commentForm->get_javascript_functions($dom_id, $form_key, $ajax_url);
?>

<script type="text/javascript">

function submit_preview_()
{
    var dom_id = '<?php print $dom_id; ?>';
    var FormData = {
        'subaction': 'save',
        'form_key': '<?php echo $form_key; ?>',
        'recaptcha_challenge_field': Recaptcha.get_challenge(),
        'recaptcha_response_field': Recaptcha.get_response()
    };

    $('#'+dom_id).load( '/comment/form/', FormData );
}

function edit_form_()
{
    var dom_id = '<?php print $dom_id; ?>';
    var FormData = {
        'subaction': 'edit',
        'form_key': '<?php echo $form_key; ?>'
    };
    //console.log(FormData);
    $('#'+dom_id).load( '<?php print $ajax_url; ?>', FormData );
}

function reset_form_()
{
    var dom_id = '<?php print $dom_id; ?>';
    var FormData = {
        'subaction': 'reset',
        'form_key': '<?php echo $form_key; ?>'
    };
    $('#'+dom_id).load( '<?php print $ajax_url; ?>', FormData );
}

$(document).ready( function() {
    //Recaptcha.destroy();
    Recaptcha.create('<?php echo RECAPTCHA_PUBLIC_KEY; ?>', 'recaptcha_element', {
        //theme: themeName,
        tabindex: 0,
        callback: Recaptcha.focus_response_field
    });
});

</script>

<div class="ajax_comment_preview">
<h2>Preview Comment</h2>
<h5>if satisfied, confirm your humanity below and submit</h5>
<?php echo $form_message; ?>
    <div class="preview">
        <div class="comment_user">
            <!-- a:user | email -->
            <?php echo $author; ?>
            <?php echo $author_email; ?>
        </div>
        <div class="comment_text"><?php print $text_; ?></div>
    </div>
</div>

<div class="ajax_comment_form">  
    <div class="recaptcha">  
        <h4>to promote computer literacy, please complete the literacy test below</h4>
    
        <?php echo $form->create('Comment', array('url' => '/comment/form', 'id'=>'ajax_comment_form'));?>
            <div id="recaptcha_element">
            </div>
    
            <?php echo $form->button('publish', 
                array('type'=>'button', 'onclick'=>'javascript:submit_comment_preview_()'));?>
            <?php echo $form->button('edit', 
                array('type'=>'button', 'onclick'=>'javascript:edit_comment_form_()'));?>
            <?php echo $form->button('reset', 
                array('type'=>'button', 'onclick'=>'javascript:reset_comment_form_()'));?>
        <?php echo $form->end(); ?>
    </div>
</div>