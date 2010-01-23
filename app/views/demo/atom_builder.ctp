<?php

    /* Output Atom XML Feed for Feedburner Account */
    App::import('Vendor', 'atombuilder', array('file' => 'class.AtomBuilder.inc.php'));

    // automatic values
    $date = date('Y-m-d');
    $year = date('Y');

    // settings
    $atom_version   = '1.0.0';
    $feed_title     = 'Cakewell Atom Builder Demo';
    $feed_subtitle  = 'A demonstration of Atom feedbuilding using the Flaimo Library';
    $site_url       = sprintf('http://%s/', $_SERVER['SERVER_NAME']);
    $feed_url       = sprintf('%sdemo/atombulder', $site_url);
    $contact        = 'klenwell';
    $email          = 'klenwell@gmail.com';
    $contact_url    = 'http://cakewell.klenwell.com';
    $feed_tag       = sprintf('tag:%s,%s:%s', $email, $date, $feed_url);

    // last time the feed or one of it's entries was last modified/updated; in php5 you can use date('c', $timestamp) to generate a valid date
    $last_update = date('c', strtotime($ItemList[0]['fetched_at']));

    // Atom feed object
    $Atom = new AtomBuilder($feed_title, $feed_url, $feed_tag);

    // Feed Level Settings
    // required settings
    $Atom->setEncoding('UTF-8');                    // only needed if NOT utf-8
    $Atom->setLanguage('en');                       // recommended, but not required
    $Atom->addContributor($contact, $email, $contact_url);   // optional (not!)
    $Atom->setUpdated($last_update);
    $Atom->setAuthor($contact, $email, $contact_url);  // name required, email and url are optional

    // optional settings
    $Atom->setSubtitle($feed_subtitle);
    $Atom->setRights('$year <cc> $feed_url');
    #$Atom->setIcon(sprintf('%s%s', $site_url, '16x16_icon.png'));
    #$Atom->setLogo(sprintf('%s%s', $site_url, '100x50_logo.png'));

    // links: see http://www.xml.com/pub/a/2004/06/16/dive.html
    $Atom->addLink($site_url, sprintf('%s : %s', $feed_title, $feed_subtitle),
        'alternate', 'text/html', 'en');
    $Atom->addLink($contact_url, 'Cakewell Home', 'related', 'text/html', 'en');

    // categories: see http://edward.oconnor.cx/2007/02/representing-tags-in-atom
    $Atom->addCategory('cakewell', 'http://code.google.com/p/cakewell/', 'Cakewell Code Site');
    $Atom->addCategory('flaimo', 'http://code.google.com/p/flaimo-php/wiki/AtomBuilder',
                       'Flaimo Php AtomBuilder Library');
    $Atom->addCategory('cakephp', 'http://book.cakephp.org/', 'CakePhp Book');
    $Atom->addCategory('php', 'http://www.php.net/manual/en/langref.php', 'Php Manual');

    // the entries
    foreach ( $ItemList as $Record )
    {
        // entry values
        $title_ = sprintf('Cakewell Atom Re-feed: %s', $Record['title']);
        $permalink_ = $Record['permalink'];
        $permalink_path_ = str_replace($site_url, '', $permalink_);
        $tag_ = sprintf('tag:%s;%s:%s', $contact, $date, $permalink_path_);
        $date_ = date('c', strtotime($Record['date']));

        // Entry object
        $Entry = $Atom->newEntry($title_, $permalink_, $tag_);

        // meta
        $Entry->setUpdated($date_);
        $Entry->setPublished($date_);
        $Entry->setAuthor('klenwell', $email, $site_url);
        $Entry->addContributor('flaimo');
        $Entry->setRights(sprintf('some rights reserved, &copy;%s', $year));

        // links
        #$Entry->addLink($link_url, $link_label, 'related', 'text/html', 'en');

        // content (both summary and content can contain plain text, html, and xhtml)
        $sum_ = 'Demonstration of CakePhp Atom Builder.  Repackages post %s from feed %s';
        $Entry->setSummary(
            sprintf($sum_, $Record['permalink'], $Record['feed_link']),
            'html' );
        $Entry->setContent($Record['content'], 'html');

        // add to Atom object
        $Atom->addEntry($Entry);
        $EntryList[] = $Entry;
    }

    // Output
    echo $Atom->outputAtom($atom_version);

?>
