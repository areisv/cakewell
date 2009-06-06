<?php

    $ajax_url = '/comment/form/';
    $form_message = ( !empty($form_message) ) ? sprintf('<h4>%s</h4>', $form_message) : '';
    $author = ( !empty($CommentData['author']) ) ? $CommentData['author'] : '';
    $author_email = ( !empty($CommentData['author_email']) ) ? $CommentData['author_email'] : '';
    $author_url = ( !empty($CommentData['author_url']) ) ? $CommentData['author_url'] : '';
    $comment_text = ( !empty($CommentData['text']) ) ? $CommentData['text'] : '';
    
    // testing
    if ( Configure::read('debug') > 1 )
    {
        if ( empty($comment_text) ) $comment_text = sprintf('test prefill: %s', time());
        if ( empty($author_email) ) $author_email = 'test@klenwell.com';
    }
    
?>

<script type="text/javascript">

function submit_form_()
{
    var dom_id = '<?php print $dom_id; ?>';
    var FormData = { 'subaction': 'preview',
                     'form_key': '<?php echo $form_key; ?>' };

    $('#ajax_comment_form').find(':input').each( function(i) {
        if ( !$(this).attr('name') ) return;
        FormData[$(this).attr('name')] = $(this).val();
    });

    //console.log(FormData);
    $('#'+dom_id).load( '<?php print $ajax_url; ?>', FormData );
}

function reset_form_()
{
    var dom_id = '<?php print $dom_id; ?>';
    var FormData = { 'subaction': 'reset',
                     'form_key': '<?php echo $form_key; ?>' };
    $('#'+dom_id).load( '<?php print $ajax_url; ?>', FormData);
}

</script>

<div class="ajax_comment_form">
<h2>Leave a Comment</h2>
<h5>e-mail address required, but will not be published</h5>
    <?php echo $form->create('Comment', array('url' => '/comment/form', 'id'=>'ajax_comment_form'));?>
    
        <fieldset>
        <?php echo $form->input('text', array('label' => 'comment',
                                              'value' => $comment_text,
                                              'rows'  => '10', 'cols' => '30' ));?>
        </fieldset>
        
        <fieldset>
        <?php echo $form->input('author', array('label' => 'your name', 'value'=>$author) );?>
        <?php echo $form->input('author_email', array('label' => 'your email address*', 'value'=>$author_email) );?>
        <?php echo $form->input('author_url', array('label' => 'your web address', 'value'=>$author_url) );?>
        <div class="honey">
            <?php echo $form->input($honeypot_field, array('id'=>$honeypot_field, 'label'=>'required') );?>
        </div>
        </fieldset>
        
        <fieldset>
        <?php echo $form->button('preview', 
            array('type'=>'button', 'onclick'=>'javascript:submit_form_()'));?>
        <?php echo $form->button('reset', 
            array('type'=>'button', 'onclick'=>'javascript:reset_form_()'));?>
        </fieldset>
        
    <?php echo $form->end(); ?>
</div> 