<?php

$html->css('/authwell/css/cakewell.auth', null, array(), false);

$form_error = '';

if ( !empty($FormErrors) )
    $form_error = sprintf( '<h5 class="form_error">%s</h5>',
                      implode('<br />', $FormErrors) );

?>

<div class="authwell-form" id="authwell-form-login">

    <h2>Authwell Login</h2>

    <?php
        /* Authwell Flash Messages */
        if ( $session->check('Authwell.flash.message') ) {
            printf( '<div id="authwell-flash">%s</div>',
                    $session->read('Authwell.flash.message') );
        }
        if ( $session->check('Authwell.flash.logout') ) {
            printf( '<div id="authwell-flash-logout">%s</div>',
                    $session->read('Authwell.flash.logout') );
        }
        if ( $session->check('Authwell.flash.login') ) {
            printf( '<div id="authwell-flash-login">%s</div>',
                    $session->read('Authwell.flash.login') );
        }
        #debug($session->read('Authwell'));
        $session->del('Authwell.flash');
    ?>

    <fieldset>

        <?php echo $form_error; ?>

        <?php echo $form->create("AuthwellUser",
            array('url' => '/authwell/login'));?>

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
