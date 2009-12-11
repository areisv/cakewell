<?php

// this will update the head section with raw markup
#$this->addScript($html);

// stylesheet (will add to $scripts_for_layout)
$html->css('klenwell.basic', null, array(), false);
$html->css('cakewell.demo', null, array(), false);

?>
<div class="cakewell-demo" id="cakewell-demo-email">

    <div class="controller_menu">
        <h2>menu</h2>
        <ul>
            <?php echo $menu; ?>
        </ul>
    </div>

    <h2>Cakewell Email Demonstration</h2>
    <h5>using CakePhp's email component</h5>

    <?php if ( isset($status) ): ?>
    <h4 class="status"><?php echo $status; ?></h4>
    <?php endif; ?>

    <?php if ( isset($recaptcha_html) ): ?>
    <div class="recaptcha_form">
        <form action="<?php echo $this->here; ?>" method="POST">
        <h4>Enter your email address and complete the ReCaptcha to have a test
        email sent to you</h4>
            <?php
                print $form->label('email_to', 'your email address', array(
                    'class' => 'left'
                ));
                print $form->text('email_to', array(
                    'size' => '24',
                    'maxlength' => '128'
                ));
                print $recaptcha_html;
            ?>

            <h5>Email that will be sent:</h5>
            <pre>Subject: <?php echo $email_subject; ?>


<?php echo $email_body; ?>
            </pre>

            <input type="submit" name="subaction" value="send test email" />
        </form>
    </div>
    <?php endif; ?>

</div>
