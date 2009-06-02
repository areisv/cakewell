<?php

    $form_message = ( !empty($form_message) ) ? sprintf('<h4>%s</h4>', $form_message) : '';
    
?>

<script type="text/javascript">

function submit_form_()
{
    var dom_id = '<?php print $dom_id; ?>';
    var FormData = {};

    $('#ajax_comment_form').find(':input').each( function(i) {
        if ( !$(this).attr('name') ) return;
        FormData[$(this).attr('name')] = $(this).val();
    });

    //console.log(FormData);
    $('#'+dom_id).load( '/comment/form/', FormData );
}

function reset_form_()
{
    var dom_id = '<?php print $dom_id; ?>';
    var FormData = { 'reset_comment': 'reset_',
                     'form_id': '<?php echo $form_id; ?>',
                     'dom_id': dom_id   
    };
    $('#'+dom_id).load( '/comment/form/', FormData);
}

</script>

<div class="ajax_comment_form">
<h2>Comment Form</h2>
<h4>add your comment</h4>
<h6>email required for verification purposes only</h6>
    <?php echo $form->create('Comment', array('url' => '/comment/form', 'id'=>'ajax_comment_form'));?>
        <?php echo $form->input('text', array('label' => 'comment'));?>
        <?php echo $form->input('author', array('label' => 'your name') );?>
        <?php echo $form->input('author_email', array('label' => 'your email address') );?>
        <?php echo $form->input('author_url', array('label' => 'your web address') );?>
        <?php echo $form->hidden('dom_id', array( 'id'=>'dom_id', 'value'=>$dom_id) );?>
        <?php echo $form->hidden('form_id', array( 'id'=>'form_id', 'value'=>$form_id) );?>
        <?php echo $form->hidden('submitted', array( 'id'=>'submitted', 'value'=>'form_') );?>
        
        <?php echo $form->button('preview', 
            array('type'=>'button', 'onclick'=>'javascript:submit_form_()'));?>
        <?php echo $form->button('reset', 
            array('type'=>'button', 'onclick'=>'javascript:reset_form_()'));?>
        
    <?php echo $form->end(); ?>
</div> 