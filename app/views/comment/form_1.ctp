<?php

    $form_message = ( !empty($form_message) ) ? "<h4>$form_message</h4>" : '';

?>

<script type="text/javascript">


function submit_form_()
{
    var dom_id = '<?php print $dom_id; ?>';
    var FormData = {};

    $('#ajax_comment_form').find(':input').each( function(i) {
        console.log($(this).attr('name'), $(this).val());
        if ( !$(this).attr('name') ) return;
        FormData[$(this).attr('name')] = $(this).val();
    });

    console.log(FormData);
    $('#'+dom_id).load( '/comment/form/', FormData );
}


</script>

<div class="ajax_comment_form">
<h2>Comment Form</h2>
<h4>add your comment</h4> 
    <?php echo $form->create('Comment', array('url' => '/comment/form', 'id'=>'ajax_comment_form'));?>
        <?php echo $form->input('text', array('label' => 'comment'));?>
        <?php echo $form->input('author', array('label' => 'your name') );?>
        <?php echo $form->input('author_url', array('label' => 'your web address') );?>
        <?php echo $form->hidden('dom_id', array( 'id'=>'dom_id', 'value'=>$dom_id) );?>
        <?php echo $form->hidden('form_id', array( 'id'=>'form_id', 'value'=>$form_id) );?>
        <?php echo $form->hidden('submitted', array( 'id'=>'submitted', 'value'=>'form_') );?>
        <?php #echo $form->submit('preview');?>
        <?php echo $form->button('preview', 
            array('type'=>'button', 'onclick'=>'javascript:submit_form_()'));?>
    <?php echo $form->end(); ?>
</div> 