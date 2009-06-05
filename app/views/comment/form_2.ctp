<?php

    $ajax_url = '/comment/form/';
    $form_message = ( !empty($form_message) ) ? sprintf('<h4>%s</h4>', $form_message) : '';
    $author = ( !empty($CommentData['author']) ) ? $CommentData['author'] : '';
    $author_email = ( !empty($CommentData['author_email']) ) ? sprintf('(%s)', $CommentData['author_email']) : '';
    $author_url = ( !empty($CommentData['author_url']) ) ? $CommentData['author_url'] : '';
    $comment_text = ( !empty($CommentData['text']) ) ? sprintf('<h4>%s</h4>', $CommentData['text']) : '';

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

<h1>Comment Form</h1>
<h2>preview your comment</h2>
<?php echo $form_message; ?>
    <div class="comment_preview">
    <div class="comment_text"><?php print $comment_text; ?></div>
    <div class="comment_user">user: <?php print sprintf('%s %s', $author, $author_email); ?></div>
    <div class="comment_url">url: <?php print $author_url; ?></div>
    </div>
    
    <h5>to promote computer literacy, please complete the literacy test below</h5>
    
    <?php echo $form->create('Comment', array('url' => '/comment/form', 'id'=>'ajax_comment_form'));?>
        <div id="recaptcha_element">
        </div>
        <?php #echo $recaptcha_widget; ?>
    
        <?php echo $form->button('publish', 
            array('type'=>'button', 'onclick'=>'javascript:submit_preview_()'));?>
        <?php echo $form->button('edit', 
            array('type'=>'button', 'onclick'=>'javascript:edit_form_()'));?>
        <?php echo $form->button('reset', 
            array('type'=>'button', 'onclick'=>'javascript:reset_form_()'));?>
    <?php echo $form->end(); ?>