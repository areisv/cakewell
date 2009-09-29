<?php
/*
    Cakewell Component Test Template
    Author: Tom at klenwell@gmail.com
    Last Update: $Date$
*/
App::import('Component', 'Twitter');
App::import('Component', 'Session');

class MockController {
    var $name = 'MockController';
    var $Session = null;

    function __construct() {
        $this->Session = new SessionComponent();
    }
}

class TwitterComponentTest extends CakeTestCase {

    var $search_atom = '';

    function setUp()
    {
        $this->TwitterComponent = new TwitterComponent();
        $Ctrl = new MockController();
        $this->TwitterComponent->initialize($Ctrl);

        $this->search_atom = <<<XATOM
<?xml version="1.0" encoding="UTF-8"?>
<feed xmlns:google="http://base.google.com/ns/1.0" xml:lang="en-US" xmlns:openSearch="http://a9.com/-/spec/opensearch/1.1/" xmlns="http://www.w3.org/2005/Atom" xmlns:twitter="http://api.twitter.com/">
  <id>tag:search.twitter.com,2005:search/twitter</id>
  <link type="text/html" href="http://search.twitter.com/search?q=twitter" rel="alternate"/>
  <link type="application/atom+xml" href="http://search.twitter.com/search.atom?q=twitter" rel="self"/>
  <title>twitter - Twitter Search</title>
  <link type="application/opensearchdescription+xml" href="http://search.twitter.com/opensearch.xml" rel="search"/>
  <link type="application/atom+xml" href="http://search.twitter.com/search.atom?q=twitter&amp;since_id=4458216700" rel="refresh"/>
  <twitter:warning>since_id removed for pagination.</twitter:warning>
  <updated>2009-09-29T01:09:52Z</updated>
  <openSearch:itemsPerPage>15</openSearch:itemsPerPage>
  <link type="application/atom+xml" href="http://search.twitter.com/search.atom?max_id=4458216700&amp;page=2&amp;q=twitter" rel="next"/>
  <entry>
    <id>tag:search.twitter.com,2005:4458216700</id>
    <published>2009-09-29T01:09:52Z</published>
    <link type="text/html" href="http://twitter.com/nanashino/statuses/4458216700" rel="alternate"/>
    <title>@moyasshi &#65312;&#12480;&#12524;&#12480;&#12524; &#12364;&#12388;&#12356;&#12390;&#12427;&#30330;&#35328;&#12399;&#12289;&#12300;&#30330;&#35328;&#12375;&#12383;&#20154;&#12301;&#12300;&#65312;&#12480;&#12524;&#12480;&#12524;&#12373;&#12435;&#12301;&#12300;&#30330;&#35328;&#12375;&#12383;&#20154;+&#65312;&#12480;&#12524;&#12480;&#12524;&#12373;&#12435;  &#12398;&#20001;&#26041;&#12434;&#12501;&#12457;&#12525;&#12540;&#12375;&#12390;&#12427;&#20154;&#12301;&#12398;&#12479;&#12452;&#12512;&#12521;&#12452;&#12531;&#12395;&#12375;&#12363;&#34920;&#31034;&#12373;&#12428;&#12414;&#12379;&#12435;&#12290;  &#65288;twitter.com/&#30330;&#35328;&#12375;&#12383;&#20154;  &#12398;URL&#12395;&#34892;&#12369;&#12400;&#30330;&#35328;&#20869;&#23481;&#12399;&#35211;&#12428;&#12427;&#65289;</title>
    <content type="html">&lt;a href=&quot;http://twitter.com/moyasshi&quot;&gt;@moyasshi&lt;/a&gt; &#65312;&#12480;&#12524;&#12480;&#12524; &#12364;&#12388;&#12356;&#12390;&#12427;&#30330;&#35328;&#12399;&#12289;&#12300;&#30330;&#35328;&#12375;&#12383;&#20154;&#12301;&#12300;&#65312;&#12480;&#12524;&#12480;&#12524;&#12373;&#12435;&#12301;&#12300;&#30330;&#35328;&#12375;&#12383;&#20154;+&#65312;&#12480;&#12524;&#12480;&#12524;&#12373;&#12435;  &#12398;&#20001;&#26041;&#12434;&#12501;&#12457;&#12525;&#12540;&#12375;&#12390;&#12427;&#20154;&#12301;&#12398;&#12479;&#12452;&#12512;&#12521;&#12452;&#12531;&#12395;&#12375;&#12363;&#34920;&#31034;&#12373;&#12428;&#12414;&#12379;&#12435;&#12290;  &#65288;twitter.com/&#30330;&#35328;&#12375;&#12383;&#20154;  &#12398;URL&#12395;&#34892;&#12369;&#12400;&#30330;&#35328;&#20869;&#23481;&#12399;&#35211;&#12428;&#12427;&#65289;</content>
    <updated>2009-09-29T01:09:52Z</updated>
    <link type="image/png" href="http://a1.twimg.com/profile_images/389431702/autumn_normal.jpg" rel="image"/>
    <twitter:source>&lt;a href=&quot;http://d.hatena.ne.jp/Kiri_Feather/20071121&quot; rel=&quot;nofollow&quot;&gt;Tween&lt;/a&gt;</twitter:source>
    <twitter:lang>ja</twitter:lang>
    <author>
      <name>nanashino (&#12394;&#12394;&#12375;&#12398;)</name>
      <uri>http://twitter.com/nanashino</uri>
    </author>
  </entry>
  <entry>
    <id>tag:search.twitter.com,2005:4458216489</id>
    <published>2009-09-29T01:09:52Z</published>
    <link type="text/html" href="http://twitter.com/AlexKennedy23/statuses/4458216489" rel="alternate"/>
    <title>My article for Yahoo's Ball Don't Lie blog. NBA Player roundtable about how Twitter is changing sports... http://bit.ly/3veWbl</title>
    <content type="html">My article for Yahoo's Ball Don't Lie blog. NBA Player roundtable about how &lt;b&gt;Twitter&lt;/b&gt; is changing sports... &lt;a href=&quot;http://bit.ly/3veWbl&quot;&gt;http://bit.ly/3veWbl&lt;/a&gt;</content>
    <updated>2009-09-29T01:09:52Z</updated>
    <link type="image/png" href="http://a3.twimg.com/profile_images/439888095/me_normal.jpg" rel="image"/>
    <twitter:source>&lt;a href=&quot;http://twitter.com/&quot;&gt;web&lt;/a&gt;</twitter:source>
    <twitter:lang>en</twitter:lang>
    <author>
      <name>AlexKennedy23 (Alex Kennedy)</name>
      <uri>http://twitter.com/AlexKennedy23</uri>
    </author>
  </entry>
</feed>
XATOM;
    }

    function teardown()
    {
        unset($this->TwitterComponent);
    }

    function testInstance() {
        $this->assertTrue(is_a($this->TwitterComponent, 'TwitterComponent'));
        $this->assertTrue(is_a($this->TwitterComponent->Ctrl, 'MockController'));
    }

    function testPublicTimeline() {
        $RecentTweets = $this->TwitterComponent->status_public_timeline();
        $TweetList = $RecentTweets['Statuses']['Status'];
        $this->assertEqual($RecentTweets['Statuses']['type'], 'array');
        $this->assertEqual(count($TweetList), 20);
        $this->assertTrue($TweetList[0]['User']);
        #debug($TweetList[0]);
    }

    function testSearch() {
        $TweetFeed = $this->TwitterComponent->search('twitter', 'en', 5);
        $TweetList = $TweetFeed['Feed']['Entry'];
        $this->assertEqual($TweetFeed['Feed']['id'],
                           'tag:search.twitter.com,2005:search/twitter');
        $this->assertEqual(count($TweetList), 5);
        $this->assertTrue($TweetList[0]['Link']['rel'] == 'image');
        #debug($TweetList[0]);
    }

    function testSearchJson() {
        $TweetFeed = $this->TwitterComponent->search_json('twitter', 'en', 5);
        $this->assertEqual(count($TweetFeed->results), 5);
        #debug($TweetFeed);
    }

    function testPregReplaceTags() {
        /* Note: this introduces more problems than it solves and hence has
           been abandoned */
        $s = '<twitter:lang type="hello">en</twitter:lang>';

        $re_s = '%(</?)([^>]+)(>)%U';
        $result = preg_replace_callback($re_s, 'fix_twitter_tag', $s);
        $this->assertEqual($result, str_replace(':','_',$s));
        #debug(h(preg_replace($re_s, $re_r, $s)));
    }

    function testAtomParse() {
        $FeedDict = $this->TwitterComponent->__parse_atom($this->search_atom);
        $this->assertEqual($FeedDict['Entry'][0]['image'],
                           'http://a1.twimg.com/profile_images/389431702/autumn_normal.jpg');
        #debug($FeedDict);
    }
}

function fix_twitter_tag($MatchList) {
    $MatchList[2] = str_replace(':', '_', $MatchList[2]);
    return implode('', array_slice($MatchList,1));
}

?>
