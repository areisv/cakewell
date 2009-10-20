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
    (`id`, `name`, `email`, `active`, `password`)
VALUES
    (1, 'system', 'system@klenwell.com', 1, 0x6339643135316363643362636238626139356334613663336635386234626335),
    (2, 'inactive', 'inactive@klenwell.com', 0, 0x3061616231323138636634656132333734626635353232396134373936383231),
    (3, 'null', 'null@klenwell.com', 1, 0x3061616231323138636634656132333734626635353232396134373936383231),
    (4, 'demo', 'demo@klenwell.com', 1, 0x3061616231323138636634656132333734626635353232396134373936383231);

-- DROP TABLE IF EXISTS `authwell_users__authwell_roles`;
CREATE TABLE IF NOT EXISTS `authwell_users__authwell_roles` (
    `id` int(11) NOT NULL auto_increment,
    `authwell_user_id` int(11) NOT NULL,
    `authwell_role_id` int(11) NOT NULL,
    created DATETIME,
    PRIMARY KEY  (`id`),
    UNIQUE KEY `u_lookup` (`authwell_user_id`, `authwell_role_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
INSERT INTO `authwell_users__authwell_roles`
    (`id`, `authwell_user_id`, `authwell_role_id`)
VALUES
    (1, 1, 1),
    (2, 2, 2),
    (3, 4, 2);

-- DROP TABLE IF EXISTS `authwell_roles`;
CREATE TABLE IF NOT EXISTS `authwell_roles` (
    `id` int(11) NOT NULL auto_increment,
    `name` varchar(140) NOT NULL default '',
    `description` varchar(255) NOT NULL default '',
    created DATETIME,
    updated DATETIME,
    PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
INSERT INTO `authwell_roles`
    (`id`, `name`, `description`)
VALUES
    (1, 'system', 'system-related operations'),
    (2, 'demo', 'a role specifically for the demo site');

-- DROP TABLE IF EXISTS `authwell_roles__authwell_privileges`;
CREATE TABLE IF NOT EXISTS `authwell_roles__authwell_privileges` (
    `id` int(11) NOT NULL auto_increment,
    `authwell_role_id` int(11) NOT NULL,
    `authwell_privilege_id` int(11) NOT NULL,
    created DATETIME,
    PRIMARY KEY  (`id`),
    UNIQUE KEY `u_lookup` (`authwell_role_id`, `authwell_privilege_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
INSERT INTO `authwell_roles__authwell_privileges`
    (`id`, `authwell_role_id`, `authwell_privilege_id`)
VALUES
    (1, 1, 1),
    (2, 1, 2),
    (3, 1, 3),
    (4, 2, 4),
    (5, 2, 5);

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
INSERT INTO `authwell_privileges`
    (`id`, `dotpath`, `description`)
VALUES
    (1, 'system.cron', 'system cron jobs'),
    (2, 'system.email', 'send email'),
    (3, 'system.easter', '???'),
    (4, 'demo.demo', 'demo actions'),
    (5, 'demo.read', 'demo read actions'),
    (6, 'demo.write', 'demo write actions');
