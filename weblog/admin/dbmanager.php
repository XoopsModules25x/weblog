<?php
/*
 * $Id: dbmanager.php 11979 2013-08-25 20:45:24Z beckmi $
 * Copyright (c) 2003 by Hiro SAKAI (http://wellwine.net/)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting
 * source code which is considered copyrighted (c) material of the
 * original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA
 */
include('../../../mainfile.php');
include(sprintf('%s/include/cp_header.php', XOOPS_ROOT_PATH));
include_once(sprintf('%s/modules/%s/header.php', XOOPS_ROOT_PATH, $xoopsModule->dirname()));
include('admin.inc.php');
include_once dirname(__FILE__) . '/admin_header.php';

$action = '';
if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        ${$k} = $v;
    }
}

function dbManagerLink() {
    global $xoopsModule , $mydirname ;

    return sprintf('<a href=\'%s/modules/%s/admin/dbmanager.php\'>%s</a>',
                   XOOPS_URL, $xoopsModule->dirname(), _AM_WEBLOG_DBMANAGER);
}

function dbManager() {
    global  $mydirname  ;
    xoops_cp_header();
//    echo sprintf('<h4>%s&nbsp;&raquo;&raquo;&nbsp;%s</h4>',
//                 indexLink(), _AM_WEBLOG_DBMANAGER);

$indexAdmin = new ModuleAdmin();
    echo $indexAdmin->addNavigation('dbmanager.php');

    echo "<table width='100%' class='outer' cellspacing='1'>\r\n";
    echo sprintf("<tr><th colspan='2'>%s</th></tr>", _AM_WEBLOG_DBMANAGER);

    // synchronize # of comments
    echo tableRow(_AM_WEBLOG_SYNCCOMMENTS, _AM_WEBLOG_SYNCCOMMENTSDSC, 'comments');

    // check table
    echo tableRow(_AM_WEBLOG_CHECKTABLE, _AM_WEBLOG_CHECKTABLEDSC, 'checktable');

    echo "</table>\r\n";
    xoops_cp_footer();
}

/**
 * param[0]=table name
 * param[1]=column name
 */
function addColumn($post) {
    global $xoopsDB , $mydirname ;

    $table = $post['param'][0];
    $column = $post['param'][1];

    if ($table==$mydirname) {
        if ($column=='cat_id') {
            $sql = sprintf('ALTER TABLE %s ADD cat_id INT( 5 ) UNSIGNED DEFAULT \'1\' NOT NULL',
                           $xoopsDB->prefix($mydirname));
        } else if ($column=='dohtml') {
            $sql = sprintf('ALTER TABLE %s ADD dohtml TINYINT( 1 ) DEFAULT \'0\' NOT NULL',
                           $xoopsDB->prefix($mydirname));
        } else if ($column=='trackbacks') {
            $sql = sprintf('ALTER TABLE %s ADD trackbacks INT(11) NOT NULL default \'0\' ',
                           $xoopsDB->prefix($mydirname));
        } else if ($column=='permission_group') {
            $sql = sprintf('ALTER TABLE %s ADD permission_group varchar(255) NOT NULL default \'all\' ',
                           $xoopsDB->prefix($mydirname));
        } else if ($column=='dobr') {
            $sql = sprintf('ALTER TABLE %s ADD dobr tinyint(1) unsigned NOT NULL default \'1\' ',
                           $xoopsDB->prefix($mydirname));
        } else {
            redirect_header('dbmanager.php', 2, _AM_WEBLOG_UNSUPPORTED);
            exit();
        }

        $result = $xoopsDB->query($sql);
        if (!$result) {
            redirect_header('dbmanager.php', 5, sprintf(_AM_WEBLOG_COLNOTADDED, $xoopsDB->error()));
            exit();
        } else {
            redirect_header('dbmanager.php', 2, _AM_WEBLOG_COLADDED);
            exit();
        }
    } else {
        redirect_header('dbmanager.php', 2, _AM_WEBLOG_UNSUPPORTED);
        exit();
    }
}

function addTable($post) {
    global $xoopsDB , $mydirname ;

    $table = $post['param'][0];

    if ($table==$mydirname.'_category') {
        $sql = sprintf('CREATE TABLE %s (', $xoopsDB->prefix($mydirname.'_category'));
        $sql .= 'cat_id int(5) unsigned NOT NULL auto_increment,';
        $sql .= 'cat_pid int(5) unsigned NOT NULL default \'0\',';
        $sql .= 'cat_title varchar(50) NOT NULL default \'\',';
        $sql .= 'cat_description text NOT NULL,';
        $sql .= 'cat_created int(10) NOT NULL default \'0\',';
        $sql .= 'cat_imgurl varchar(150) NOT NULL default \'\',';
        $sql .= 'PRIMARY KEY  (cat_id),';
        $sql .= 'KEY cat_pid (cat_pid)';
        $sql .= ') TYPE=MyISAM;';
    } else if ($table==$mydirname.'_priv') {
        $sql = sprintf('CREATE TABLE %s(', $xoopsDB->prefix($mydirname.'_priv'));
        $sql .= 'priv_id smallint(5) unsigned NOT NULL auto_increment,';
        $sql .= 'priv_gid smallint(5) unsigned NOT NULL default \'0\',';
        $sql .= 'PRIMARY KEY  (priv_id)';
        $sql .= ') TYPE=MyISAM;';
    } else if ($table==$mydirname.'_trackback') {
        $sql = sprintf('CREATE TABLE %s(', $xoopsDB->prefix($mydirname.'_trackback'));
        $sql .= 'blog_id mediumint(9) NOT NULL ,';
        $sql .= 'tb_url text NOT NULL,';
        $sql .= 'blog_name varchar(255) NOT NULL,';
        $sql .= 'title varchar(255) NOT NULL,';
        $sql .= 'description text NOT NULL,';
        $sql .= 'link text NOT NULL,';
        $sql .= 'direction enum(\'\',\'transmit\',\'recieved\') NOT NULL default \'\',';
        $sql .= 'trackback_created int(10) NOT NULL default \'0\',';
        $sql .= 'PRIMARY KEY  (blog_id,tb_url(100),direction)';
        $sql .= ') TYPE=MyISAM;';
    } else if ($table==$mydirname.'myalbum_photos') {
        $sql = sprintf('CREATE TABLE %s(', $xoopsDB->prefix($mydirname.'myalbum_photos'));
        $sql .= 'lid int(11) unsigned NOT NULL auto_increment, ';
        $sql .= 'cid int(5) unsigned NOT NULL default \'0\', ';
        $sql .= 'title varchar(100) NOT NULL default \'\', ';
        $sql .= 'ext varchar(10) NOT NULL default \'\', ';
        $sql .= 'res_x int(11) NOT NULL default \'\', ';
        $sql .= 'res_y int(11) NOT NULL default \'\' ,';
        $sql .= 'submitter int(11) unsigned NOT NULL default \'0\',';
        $sql .= 'status tinyint(2) NOT NULL default \'0\',';
        $sql .= 'date int(10) NOT NULL default \'0\',';
        $sql .= 'PRIMARY KEY  (lid),';
        $sql .= 'KEY cid (cid)';
        $sql .= ') TYPE=MyISAM;';
    } else {
        redirect_header('dbmanager.php', 2, _AM_WEBLOG_UNSUPPORTED);
        exit();
    }

    $result = $xoopsDB->query($sql);
    if (!$result) {
        redirect_header('dbmanager.php', 5, sprintf(_AM_WEBLOG_TABLENOTADDED, $xoopsDB->error()));
        exit();
    } else {
        if ($table==$mydirname.'_category') {
            $handler =& xoops_getmodulehandler('category');
            $cat = $handler->create();
            $cat->setVar('cat_pid', 0);
            $cat->setVar('cat_id', 1);
            $cat->setVar('cat_created', time());
            $cat->setVar('cat_title', 'Miscellaneous');
            $cat->setVar('cat_description', '');
            $cat->setVar('cat_imgurl', '');
            $ret = $handler->insert($cat);
        }
        redirect_header('dbmanager.php', 5, _AM_WEBLOG_TABLEADDED);
        exit();
    }
}

function checkTables() {
    global $mydirname ;
     xoops_cp_header();
//    echo sprintf('<h4>%s&nbsp;&raquo;&raquo;&nbsp;%s&nbsp;&raquo;&raquo;&nbsp;%s</h4>',
//                 indexLink(), dbManagerLink(), _AM_WEBLOG_CHECKTABLE);

    $indexAdmin = new ModuleAdmin();
        echo $indexAdmin->addNavigation('dbmanager.php');

    // checking table 'weblog'
    $columns = array('blog_id', 'user_id', 'cat_id', 'created', 'title',
                     'contents', 'private', 'comments', 'reads', 'trackbacks', 'permission_group', 'dohtml' , 'dobr');
    checkTable($mydirname, $columns);

    echo "<br />";
    // checking table 'weblog_category'
    $columns = array('cat_id', 'cat_pid', 'cat_title', 'cat_description', 'cat_created', 'cat_imgurl');
    checkTable($mydirname.'_category', $columns);

    echo "<br />";
    // checking table 'weblog_priv'
    $columns = array('priv_id', 'priv_gid');
    checkTable($mydirname.'_priv', $columns);

    echo "<br />";
    // checking table 'weblog_trackback'
    $columns = array('blog_id', 'tb_url', 'blog_name', 'title', 'description', 'link', 'direction', 'trackback_created');
    checkTable($mydirname.'_trackback', $columns);

    echo "<br />";
    // checking table 'weblogmyalbum_photos'
    $columns = array('lid', 'cid', 'title', 'ext', 'res_x', 'res_y', 'submitter', 'status' , 'date');
    checkTable($mydirname.'myalbum_photos', $columns);

    xoops_cp_footer();
}

function checkTable($table, $columns=array()) {
    global $xoopsDB , $mydirname ;

    $sql = sprintf('SELECT count(*) as count FROM %s WHERE 1',
                   $xoopsDB->prefix($table));
    $result = $xoopsDB->query($sql);
    $table_exist = ($result) ? true : false;
    if ($table_exist) {
        list($count) = $xoopsDB->fetchRow($result);
        $row_exist = (isset($count['count']) && $count['count'] > 0) ? true : false;
    }

    echo "<table width='100%' class='outer' cellspacing='1'>\r\n";
    echo sprintf('<tr><th colspan=\'2\'>%s: \'%s\'</th></tr>', _AM_WEBLOG_TABLE, $table);

    // if table does not exist or table does not have rows
    //if (!$table_exist || !$row_exist) {
    if (!$table_exist) {
        $hidden = array(0=>$table);
        echo tableRow(sprintf(_AM_WEBLOG_CREATETABLE, $table),
                      sprintf(_AM_WEBLOG_CREATETABLEDSC, $table),
                      'addtable', $hidden);
    // table does exist and columns are missing
    } else {
        $sql = sprintf('SHOW COLUMNS FROM %s', $xoopsDB->prefix($table));
        $result = $xoopsDB->query($sql);
        $fields = array();
        while (list($field) = $xoopsDB->fetchRow($result)) {
            $fields[] = $field;
        }
        $alter = false;
        foreach ($columns as $column) {
            foreach ($fields as $field) {
                if ($column===$field) {
                    continue 2;
                }
            }
            $hidden = array(0=>$table, 1=>$column);
            echo tableRow(sprintf(_AM_WEBLOG_ADD, $column), sprintf(_AM_WEBLOG_ADDDSC, $column), 'addcolumn', $hidden);
            $alter = true;
        }
        if ($alter==false) {
            echo tableRow(sprintf(_AM_WEBLOG_NOADD, $table), sprintf(_AM_WEBLOG_NOADDDSC, $table));
        }
    }

    echo "</table>\r\n";
}

function synchronizeComments() {
    global $xoopsDB, $xoopsModule , $mydirname ;
    $sql = sprintf('SELECT bl.blog_id, COUNT(cm.com_id) FROM %s AS bl LEFT JOIN %s AS cm ON bl.blog_id=cm.com_itemid AND cm.com_modid=%d GROUP BY bl.blog_id',
                   $xoopsDB->prefix($mydirname),
                   $xoopsDB->prefix('xoopscomments'),
                   $xoopsModule->getVar('mid'));
    $result = $xoopsDB->query($sql) or exit($xoopsDB->error());
    $handler =& xoops_getmodulehandler('entry');
    while (list($blog_id, $comments) = $xoopsDB->fetchRow($result)) {
        $handler->updateComments($blog_id, intval($comments));
    }
    redirect_header('dbmanager.php', 2, _AM_WEBLOG_DBUPDATED);
    exit();
}

switch ($action) {
    case "comments":
        synchronizeComments();
        break;
    case "checktable":
        checkTables();
        break;
    case "addcolumn":
        addColumn($_POST);
        break;
    case "addtable":
        addtable($_POST);
        break;
    default:
        dbManager();
        break;
}
