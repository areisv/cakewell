<?php

$author_email = ( !empty($author_email) ) ? sprintf('(%s)', $author_email) : '';
$form_message = ( !empty($form_message) ) ? sprintf('<h4>%s</h4>', $form_message) : '';

?>

<script type="text/javascript">

function submit_preview_()
{
    var dom_id = '<?php print $dom_id; ?>';
    var FormData = {
        'recaptcha_challenge_field': Recaptcha.get_challenge(),
        'recaptcha_response_field': Recaptcha.get_response()
    };

    $('#ajax_comment_form').find(':input').each( function(i) {
        if ( !$(this).attr('name') ) return;
        FormData[$(this).attr('name')] = $(this).val();
    });
    $('#'+dom_id).load( '/comment/form/', FormData );
}

function reset_form_()
{
    var dom_id = '<?php print $dom_id; ?>';
    $('#'+dom_id).load( '/comment/form/', { 'reset_comment': 'reset_' } );
}

$(document).ready( function() {
    //Recaptcha.destroy();
    Recaptcha.create('<?php echo RECAPTCHA_PUBLIC_KEY; ?>', 'recaptcha_element', {
        //theme: themeName,
        //tabindex: 0,
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
    <div id="recaptcha_element">
    </div>
    <?php #echo $recaptcha_widget; ?>
    

    
    <?php echo $form->create('Comment', array('url' => '/comment/form', 'id'=>'ajax_comment_form'));?>
        <?php echo $form->hidden('submitted', array( 'id'=>'submitted', 'value'=>'preview_') );?>
        <?php echo $form->button('publish', 
            array('type'=>'button', 'onclick'=>'javascript:submit_preview_()'));?>
        <?php echo $form->button('reset', 
            array('type'=>'button', 'onclick'=>'javascript:reset_form_()'));?>
    <?php echo $form->end(); ?>