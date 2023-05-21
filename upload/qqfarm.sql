--
-- for qqfarm 4.0 Final
-- dump by seaif@zealv.com
--

DROP TABLE IF EXISTS `uchome_qqfarm_config`;
CREATE TABLE `uchome_qqfarm_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `username` varchar(20) NOT NULL,
  `money` int(11) DEFAULT '0',
  `YB` int(11) DEFAULT '0',
  `pf` int(11) DEFAULT '0',
  `vip` text NOT NULL,
  `tianqi` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `uchome_qqfarm_nc`;
CREATE TABLE `uchome_qqfarm_nc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `Status` text NOT NULL,
  `reclaim` int(11) NOT NULL DEFAULT '6',
  `exp` int(11) NOT NULL DEFAULT '0',
  `taskid` int(11) NOT NULL DEFAULT '0',
  `package` text,
  `flower` text,
  `fruit` text,
  `tools` text,
  `decorative` text,
  `dog` text,
  `Weed` text,
  `pest` text,
  `badnum` int(11) NOT NULL DEFAULT '50',
  `activeItem` int(11) NOT NULL DEFAULT '90001',
  `repertory` text NOT NULL,
  `tips` text NOT NULL,
  `healthmode` text NOT NULL,
  `chris` int(11) NOT NULL DEFAULT '0',
  `zong` mediumint(9) NOT NULL DEFAULT '0',
  `nc_e` int(11) NOT NULL DEFAULT '0',
  `levelup` int(11) NOT NULL DEFAULT '200',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `uchome_qqfarm_nclogs`;
CREATE TABLE `uchome_qqfarm_nclogs` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `uid` int(11) NOT NULL,
 `type` tinyint(4) NOT NULL,
 `count` int(11) NOT NULL,
 `fromid` int(11) NOT NULL,
 `time` int(11) NOT NULL,
 `cropid` int(11) NOT NULL,
 `isread` int(11) NOT NULL,
 `counts` text NOT NULL,
 `money` text NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `uchome_qqfarm_mc`;
CREATE TABLE `uchome_qqfarm_mc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `Status` text NOT NULL,
  `exp` int(11) DEFAULT '0',
  `taskid` int(11) NOT NULL,
  `package` text NOT NULL,
  `feed` text NOT NULL,
  `decorative` text NOT NULL,
  `bad` text NOT NULL,
  `badnum` int(11) NOT NULL,
  `badtime` int(11) NOT NULL DEFAULT '0',
  `parade` text NOT NULL,
  `repertory` text NOT NULL,
  `dabian` tinyint(4) NOT NULL,
  `sfeedleft` int(11) NOT NULL DEFAULT '30',
  `zong` mediumint(9) NOT NULL DEFAULT '0',
  `mclock` text NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `uchome_qqfarm_mclogs`;
CREATE TABLE `uchome_qqfarm_mclogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `fromid` int(11) NOT NULL,
  `count` text NOT NULL,
  `iid` text NOT NULL,
  `money` text NOT NULL,
  `isread` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `uchome_qqfarm_message`;
CREATE TABLE `uchome_qqfarm_message` (
 `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `toID` int(11) NOT NULL,
 `toName` varchar(50) NOT NULL default '',
 `fromID` int(11) NOT NULL,
 `fromName` varchar(50) NOT NULL default '',
 `msg` text character set gbk NOT NULL,
 `time` int(11) NOT NULL,
 `isReply` int(2) default '0',
 `isread` int(2) default '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
