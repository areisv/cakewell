<?php

$html->css('/authwell/css/cakewell.auth', null, array(), false);

$form_error = '';

if ( !empty($FormErrors) )
    $form_error = sprintf( '<h5 class="form_error">%s</h5>',
                      implode('<br />', $FormErrors) );

?>

<div class="authwell-form" id="authwell-form-login">

    <h2>Authwell Login</h2>

    <?php echo $form_error; ?>

    <?php echo $form->create("AuthwellUser",
            array('url' => '/authwell/login'));?>

    <fieldset>
        <?php echo $form->input( "AuthwellUser.email_login",
                array('label' => 'Email') );?>

        <?php echo $form->input( "AuthwellUser.password_login",
                array('type' => 'password', 'label' => 'Password') );?>

        <div class="honey">
            <?php echo $form->input("AuthwellUser.honey_login",
                array('id'=>'cakewell-honey', 'label'=>'required') );?>
        </div>

        <?php echo $form->submit( 'login' ); ?>
    </fieldset>


    <?php echo $form->end(); ?>
</div>
