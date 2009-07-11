<?php

    $date_format = 'j M Y \a\t g:ia';

    function comment_author_($author='', $url=null)
    {
        $http = 'http://';
        $url_tpl = '<a href="%s" %s>%s</a>';
        $onclick = 'onclick="window.open(this.href,\'_blank\');return false;"';
        if ( empty($author) ) $author = 'anonymous';
        if ( empty($url) ) return $author;
        if ( strpos($url, $http) === false ) $url = $http . $url;
        return sprintf($url_tpl, $url, $onclick, $author);
    }

?>


<div class="comment-list">

    <?php if ( empty($CommentList) ): ?>

        <p>no comments</p>

    <?php else: ?>

        <?php foreach ( $CommentList as $Comment ): ?>

            <div class="comment-list-comment"><div class="comment-list-comment-child">
                <div class="comment-header">
                    <div class="comment-datestamp">
                        <?php print date($date_format, strtotime($Comment['Comment']['created'])); ?>
                    </div>
                    <div style="clear:both;"></div>
                </div>

                <div class="comment-text">
                    <?php print nl2br($Comment['Comment']['text']); ?>
                </div>

                <div class="comment-footer">
                    <div class="comment-author">
                        <?php print comment_author_($Comment['Comment']['author'], $Comment['Comment']['author_url']); ?>
                    </div>
                    <div style="clear:both;"></div>
                </div>
            </div></div>

        <?php endforeach ?>

    <?php endif; ?>

</div>
