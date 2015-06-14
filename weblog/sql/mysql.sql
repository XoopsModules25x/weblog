#
# Table structure for table weblog
#
# phpMyAdmin MySQL-Dump
# version 2.5.0
# http://www.phpmyadmin.net/ (download page)
#
# --------------------------------------------------------

#
# Table Structure weblog
#

CREATE TABLE weblog (
  blog_id mediumint(9) NOT NULL auto_increment,
  user_id mediumint(9) NOT NULL default '0',
  cat_id int(5) unsigned NOT NULL default '0',
  created int(10) NOT NULL default '0',
  title varchar(128) NOT NULL default '',
  contents text NOT NULL,
  private char(1) NOT NULL default '',
  comments int(11) NOT NULL default '0',
  `reads` int(11) NOT NULL default '0',
  trackbacks int(11) NOT NULL default '0',
  description text NOT NULL,
  dohtml tinyint(1) UNSIGNED NOT NULL default '0',
  dobr tinyint(1) UNSIGNED NOT NULL default '1',
  permission_group varchar(255) NOT NULL default 'all',
  PRIMARY KEY  (blog_id),
  KEY user_id (user_id,created,title,private)
) ENGINE=MyISAM;
# --------------------------------------------------------

#
# Table Structure weblog_category
#

CREATE TABLE weblog_category (
  cat_id int(5) unsigned NOT NULL auto_increment,
  cat_pid int(5) unsigned NOT NULL default '0',
  cat_title varchar(50) NOT NULL default '',
  cat_description text NOT NULL,
  cat_created int(10) NOT NULL default '0',
  cat_imgurl varchar(150) NOT NULL default '',
  PRIMARY KEY  (cat_id),
  KEY cat_pid (cat_pid)
) ENGINE=MyISAM;

INSERT INTO weblog_category (
  cat_id, cat_pid, cat_title, cat_description, cat_created, cat_imgurl)
  VALUES (
    '1', '0', 'Miscellaneous', '', '1051983686', ''
);
# --------------------------------------------------------

CREATE TABLE weblog_priv (
  priv_id smallint(5) unsigned NOT NULL auto_increment,
  priv_gid smallint(5) unsigned NOT NULL default '0',
  PRIMARY KEY  (priv_id)
) ENGINE=MyISAM;

# --------------------------------------------------------
CREATE TABLE weblog_trackback (
  blog_id mediumint(9) NOT NULL ,
  tb_url text NOT NULL,
  blog_name varchar(255) NOT NULL,
  title varchar(255) NOT NULL,
  description text NOT NULL,
  link text NOT NULL,
  direction enum('','transmit','recieved') NOT NULL default '',
  trackback_created int(10) NOT NULL default '0',
  PRIMARY KEY  (blog_id,tb_url(100),direction)
) ENGINE=MyISAM;

# --------------------------------------------------------
#
# Table structure for table `myalbum_photos`
#

CREATE TABLE weblogmyalbum_photos (
  lid int(11) unsigned NOT NULL auto_increment,
  cid int(5) unsigned NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  ext varchar(10) NOT NULL default '',
  res_x int(11) NOT NULL default '0',
  res_y int(11) NOT NULL default '0',
  submitter int(11) unsigned NOT NULL default '0',
  status tinyint(2) NOT NULL default '0',
  date int(10) NOT NULL default '0',
  PRIMARY KEY  (lid),
  KEY cid (cid)
) ENGINE=MyISAM;
