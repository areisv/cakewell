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
    `active` tinyint(4) NOT NULL default '1',
    `created` datetime default NULL,
    `updated` datetime default NULL,
    PRIMARY KEY  (`id`),
    UNIQUE KEY `u_email` (`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

INSERT INTO `authwell_users`
    (`id`, `name`, `email`, `active`, `created`, `password`)
VALUES
    (1, 'system', 'system@klenwell.com', 1, '2009-10-01 00:00:00', 0x3061616231323138636634656132333734626635353232396134373936383231),
    (2, 'null', 'null@klenwell.com', 0, '2009-10-01 00:00:00', 0x3061616231323138636634656132333734626635353232396134373936383231),
    (3, 'demo', 'demo@klenwell.com', 1, '2009-10-01 00:00:00', 0x3061616231323138636634656132333734626635353232396134373936383231);


-- DROP TABLE IF EXISTS `authwell_users__authwell_roles`;
CREATE TABLE IF NOT EXISTS `authwell_users__authwell_roles` (
    `id` int(11) NOT NULL auto_increment,
    `authwell_user_id` int(11) NOT NULL,
    `authwell_role_id` int(11) NOT NULL,
    created DATETIME,
    PRIMARY KEY  (`id`),
    UNIQUE KEY `u_lookup` (`authwell_user_id`, `authwell_role_id`)
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
    `authwell_role_id` int(11) NOT NULL,
    `authwell_privilege_id` int(11) NOT NULL,
    created DATETIME,
    PRIMARY KEY  (`id`),
    UNIQUE KEY `u_lookup` (`authwell_role_id`, `authwell_privilege_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- DROP TABLE IF EXISTS `authwell_privileges`;
CREATE TABLE IF NOT EXISTS `authwell_privileges` (
    `id` int(11) NOT NULL auto_increment,
    `dotpath` varchar(140) NOT NULL default '',
    `description` varchar(255) NOT NULL default '',
    updated DATETIME,
    created DATETIME,
    PRIMARY KEY  (`id`),
    UNIQUE KEY `u_dotpath` (`dotpath`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
