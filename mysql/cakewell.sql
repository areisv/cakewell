--
-- Database: `cakewell`
--

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

-- DROP TABLE IF EXISTS `simple_logs`;
CREATE TABLE IF NOT EXISTS `simple_logs` (
    id int(11) NOT NULL auto_increment,
    type_id int(11) default NULL,
    keyword varchar(16) default NULL,
    message varchar(255) NOT NULL,
    created DATETIME,
    PRIMARY KEY  (`id`),
    KEY `k_type_id` (`type_id`),
    KEY `k_keyword` (`keyword`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
INSERT INTO `simple_logs` (`id`, `type_id`, `keyword`, `message`, `created`) VALUES
(1, 1, 'database', 'creating simple_records and simple_log_types databases', NOW());

-- DROP TABLE IF EXISTS `simple_log_types`;
CREATE TABLE IF NOT EXISTS `simple_log_types` (
    id int(11) NOT NULL auto_increment,
    type varchar(16) NOT NULL,
    updated DATETIME,
    created DATETIME,
    PRIMARY KEY  (`id`),
    KEY `k_type` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
INSERT INTO `simple_log_types` (`id`, `type`, `created`) VALUES
(1, 'system', NOW()),
(2, 'error', NOW()),
(3, 'warning', NOW()),
(4, 'info', NOW()),
(5, 'debug', NOW());

-- DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `meta_id` bigint(20) NOT NULL default '0',
  `parent_id` bigint(20) NOT NULL default '0',
  `form_key` varchar(40) NOT NULL default '',
  `dom_id` varchar(40) NOT NULL default '',
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
  KEY `meta_id` (`meta_id`),
  KEY `form_key` (`form_key`),
  KEY `dom_id` (`dom_id`),
  KEY `parent_id` (`parent_id`),
  KEY `created` (`created`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;



-- --------------------------------------------------------
--
-- MIGRATIONS (a very crude implementation)
-- post-install updates
--

-- SPRINT v1s7
-- removing deprecated tables
-- development: 2009.12.19
-- production: TBA
DROP TABLE IF EXISTS `simple_records`;
DROP TABLE IF EXISTS `simple_users`;
