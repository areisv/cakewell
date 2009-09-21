--
-- Database: `cakewell`
--

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

-- DROP TABLE IF EXISTS `authwell_users`;
CREATE TABLE IF NOT EXISTS `authwell_users` (
    `id` int(11) NOT NULL auto_increment,
    `name` varchar(64) NOT NULL default '',
    `email` varchar(255) NOT NULL default '',
    `password` tinyblob,
    `created` datetime default NULL,
    `updated` datetime default NULL,
    PRIMARY KEY  (`id`),
    UNIQUE KEY `u_email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- DROP TABLE IF EXISTS `authwell_users__authwell_roles`;
CREATE TABLE IF NOT EXISTS `authwell_users__authwell_roles` (
    `id` int(11) NOT NULL auto_increment,
    `authwell_users_id` int(11) NOT NULL,
    `authwell_roles_id` int(11) NOT NULL,
    created DATETIME,
    PRIMARY KEY  (`id`),
    UNIQUE KEY `u_lookup` (`authwell_users_id`, `authwell_roles_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- DROP TABLE IF EXISTS `authwell_roles`;
CREATE TABLE IF NOT EXISTS `authwell_roles` (
    `id` int(11) NOT NULL auto_increment,
    `name` varchar(140) NOT NULL default '',
    `description` varchar(255) NOT NULL default '',
    updated DATETIME,
    created DATETIME,
    PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- DROP TABLE IF EXISTS `authwell_roles__authwell_privileges`;
CREATE TABLE IF NOT EXISTS `authwell_roles__authwell_privileges` (
    `id` int(11) NOT NULL auto_increment,
    `authwell_roles_id` int(11) NOT NULL,
    `authwell_privileges_id` int(11) NOT NULL,
    created DATETIME,
    PRIMARY KEY  (`id`),
    UNIQUE KEY `u_lookup` (`authwell_roles_id`, `authwell_privileges_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- DROP TABLE IF EXISTS `authwell_privileges`;
CREATE TABLE IF NOT EXISTS `authwell_privileges` (
    `id` int(11) NOT NULL auto_increment,
    `dotpath` varchar(140) NOT NULL default '',
    `description` varchar(255) NOT NULL default '',
    updated DATETIME,
    created DATETIME,
    PRIMARY KEY  (`id`),
    UNIQUE KEY `u_notation` (`notation`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
