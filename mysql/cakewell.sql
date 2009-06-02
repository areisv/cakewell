-- 
-- Database: `cakewell`
--

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

-- DROP TABLE IF EXISTS `simple_records`;
CREATE TABLE IF NOT EXISTS `simple_records` (
    id int(11) NOT NULL auto_increment,
    value varchar(80) NOT NULL,
    updated DATETIME,
    created DATETIME,
    
    PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `comments` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `form_id` varchar(32) NOT NULL default '',
  `dom_id` varchar(32) NOT NULL default '',
  `parent_id` bigint(20) NOT NULL default '0',
  `type` varchar(20) NOT NULL default '',
  `created` datetime default NULL,
  `approved` tinyint(4) NOT NULL default '1',
  `recaptcha` varchar(32) NOT NULL default '',
  `author` varchar(255) NOT NULL default '',
  `author_email` varchar(100) NOT NULL default '',
  `author_url` varchar(200) NOT NULL default '',
  `author_ip` varchar(100) default NULL,
  `text` text NOT NULL,
  `agent` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `approved` (`approved`),
  KEY `form_id` (`form_id`),
  KEY `dom_id` (`dom_id`),
  KEY `parent_id` (`parent_id`),
  KEY `created` (`created`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;