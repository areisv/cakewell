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
