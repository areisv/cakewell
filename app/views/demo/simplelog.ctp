<?php

// this will update the head section with raw markup
#$this->addScript($html);

// stylesheet (will add to $scripts_for_layout)
$html->css('klenwell.basic', null, array(), false);
$html->css('cakewell.demo', null, array(), false);

?>
<div class="cakewell-demo" id="cakewell-demo-simplelog">

    <div class="controller_menu">
        <h2>menu</h2>
        <ul>
            <?php echo $menu; ?>
        </ul>
    </div>

    <h2>SimpleLog Model</h2>

    <h3>Add a New Log</h3>
    <?php
        echo $form->create('Recaptcha',
                           array('action'=>'post', 'url'=>$this->here));
        echo $recaptcha_html;
        echo $form->input('request', array('type'=>'hidden'));
        echo $form->end('add log');

        if ( is_null($RecaptchaResponse) ) {
            echo '<!-- no recaptcha response -->';
        }
        elseif ( $RecaptchaResponse->is_valid ) {
            echo '<div class="ok">recaptcha successful</div>';
        }
        elseif ( $RecaptchaResponse->error ) {
            printf('<div class="fail">recaptcha error: %s</div>', $RecaptchaResponse->error);
        }

        if ( $is_logged ) {
            echo '<div class="saved">log saved</div>';
        }
    ?>

    <h3>Last 10 Log Entries</h3>
    <table id="log_results">
        <tr>
            <th><?php echo $paginator->sort('timestamp', 'SimpleLog.created'); ?></th>
            <th><?php echo $paginator->sort('keyword(s)', 'SimpleLog.keyword'); ?></th>
            <th><?php echo $paginator->sort('message', 'SimpleLog.message'); ?></th>
            <th><?php echo $paginator->sort('type', 'SimpleLogType.type'); ?></th>
        </tr>
        <?php foreach( $Logs as $Log ): ?>
        <tr>
            <td><?php echo $Log['SimpleLog']['created']; ?></td>
            <td><?php echo $Log['SimpleLog']['keyword']; ?></td>
            <td><?php echo $Log['SimpleLog']['message']; ?></td>
            <td><?php echo $Log['SimpleLogType']['type']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
