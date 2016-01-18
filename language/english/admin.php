<?php
/**
 * $Id: admin.php 11979 2013-08-25 20:45:24Z beckmi $
 * Copyright (c) 2003 by Hiro SAKAI (http://wellwine.zive.net/)
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
 * Foundation, Inc., 59 Temple Place
 */
if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( 'WEBLOG_AM_LOADED' ) ) {

global $xoopsModule ;

define( 'WEBLOG_AM_LOADED' , 1 ) ;

define('_AM_WEBLOG_CONFIG', $xoopsModule->name().' Configuration');
define('_AM_WEBLOG_PREFERENCES', _PREFERENCES);
define('_AM_WEBLOG_PREFERENCESDSC', 'General Configuration.');
define('_AM_WEBLOG_GO', _GO);
define('_AM_WEBLOG_CANCEL', _CANCEL);
define('_AM_WEBLOG_DELETE', _DELETE);
define('_AM_WEBLOG_MODIFY', 'Change');
define('_AM_WEBLOG_TITLE', 'Title');

define('_AM_WEBLOG_DBMANAGER', 'Database');
define('_AM_WEBLOG_DBMANAGERDSC', 'Database utility that is basically for updating module.');
define('_AM_WEBLOG_TABLE', 'Table');
define('_AM_WEBLOG_SYNCCOMMENTS', 'Synchronize comments count');
define('_AM_WEBLOG_SYNCCOMMENTSDSC', 'Correct count if you see # of comments of each entry wrong.<br />This might be because v1.02 or earlier version did not handle count correctly.');
define('_AM_WEBLOG_CHECKTABLE', 'Check tables structure');
define('_AM_WEBLOG_CHECKTABLEDSC', 'Check tables in database. You could checkNew version might require new tables or columns.');
define('_AM_WEBLOG_CREATETABLE', 'Create table \'%s\'');
define('_AM_WEBLOG_CREATETABLEDSC', 'Create a table named \'%s\'');

define('_AM_WEBLOG_ADD', 'Column \'%s\' not found');
define('_AM_WEBLOG_ADDDSC', 'Column \'<b>%s</b>\' is not found in the database table. This column is required for current version.<br />Press button to add this column to existing table.<br />Backing up your database is strongly recommended.');
define('_AM_WEBLOG_NOADD', 'Table \'%s\' is ready!');
define('_AM_WEBLOG_NOADDDSC', 'Table \'%s\' is ready to be used for current version. You do not have to warry anything about the table.');
define('_AM_WEBLOG_DBUPDATED', 'Database updated successfully!');
define('_AM_WEBLOG_UNSUPPORTED', 'Error: Not supported request');
define('_AM_WEBLOG_TABLEADDED', 'New table created successfully!');
define('_AM_WEBLOG_TABLENOTADDED', 'Error: Table could not created: %s');
define('_AM_WEBLOG_COLADDED', 'New column added successfully!');
define('_AM_WEBLOG_COLNOTADDED', 'Error: Column could not added: %s');

define('_AM_WEBLOG_CATMANAGER', 'Categories');
define('_AM_WEBLOG_CATMANAGERDSC', 'Add/Modify/Delete Categories.');
define('_AM_WEBLOG_ADDCAT', 'Add Category');
define('_AM_WEBLOG_ADDMAINCAT', 'Add Main Category');
define('_AM_WEBLOG_ADDSUBCAT', 'Add Sub Category');
define('_AM_WEBLOG_CAT', 'Category');
define('_AM_WEBLOG_IMGURL', 'Image URL');
define('_AM_WEBLOG_ERRORTITLE', 'ERROR: You need to enter a TITLE!');
define('_AM_WEBLOG_NEWCATADDED', 'New category added successfully!');
define('_AM_WEBLOG_CATNOTADDED', 'Category could not added!');
define('_AM_WEBLOG_CATMODED', 'Category modified successfully!');
define('_AM_WEBLOG_CATNOTMODED', 'Category could not modified!');
define('_AM_WEBLOG_MODCAT', 'Modify Category');
define('_AM_WEBLOG_PCAT', 'Parent Category');
define('_AM_WEBLOG_CHOSECAT', 'Chosen Category');
define('_AM_WEBLOG_DELCONFIRM', 'Are you sure to delete category \'%s\' and its sub categories?<br />All entries that belong to those categories will be deleted.');
define('_AM_WEBLOG_CATDELETED', 'Category deleted successfully!');
define('_AM_WEBLOG_CAT_GPERM', 'Groups allowed to post this category');
define('_AM_WEBLOG_CAT_OPERATE', 'Operation');
define('_AM_WEBLOG_CAT_SETALL', 'Set group post permissions of all categories ');

define('_AM_WEBLOG_MYBLOCKSADMIN', 'Blocks and Groups');
define('_AM_WEBLOG_MYBLOCKSADMINDSC', 'Setting of Blocks and Groups');
define('_AM_WEBLOG_TEMPLATE_MANEGER', 'Templates');
define('_AM_WEBLOG_TEMPLATE_MANEGERDSC', 'Shortcut to Template manager');

define('_AM_WEBLOG_PRIVMANAGER_WEBLOG', 'Privileges (weBLog simple system)');
define('_AM_WEBLOG_PRIVMANAGER_WEBLOG_DSC', 'Control posting privilege.');
define('_AM_WEBLOG_PRIVMANAGER_WEBLOG_CAUTION', 'These setting parameters are valid when you choose "weBLog simple system" as Privilege manager with weBLog Preferences.');
define('_AM_WEBLOG_PRIVMANAGER_XOOPS', 'Privileges (XOOPS standerd system)');
define('_AM_WEBLOG_PRIVMANAGER_XOOPS_DSC', 'Control posting and reading privilege.');
define('_AM_WEBLOG_PRIVMANAGER_XOOPS_CAUTION', 'These setting parameters are valid when you choose "XOOPS standard system" as Privilege manager with weBLog Preferences.');
define('_AM_WEBLOG_ADDPRIV', _ADD);
define('_AM_WEBLOG_DELETEPRIV', _DELETE);
define('_AM_WEBLOG_NONPRIV', 'Non Privileged');
define('_AM_WEBLOG_PRIV', 'Privileged');

define('_AM_WEBLOG_GROUPPERM_GLOBAL' , 'Privilege manager for each groups' );
define('_AM_WEBLOG_GROUPPERM_GLOBALDESC' , 'Privilege Settings to every group. EDIT Privilege to Anonymous user is invalid.' );
define('_AM_WEBLOG_GPERMUPDATED' , 'Update group perameters' );
define('_AM_WEBLOG_PRIV_EDIT' , 'POST/EDIT/DELETE') ;
define('_AM_WEBLOG_PRIV_READINDEX' , 'READ ENTRY INDEX/RSS/RDF') ;
define('_AM_WEBLOG_PRIV_READDETAIL' , 'READ DETAIL ENTRY(details.php)') ;

//1.47

//directories
define('_AM_WEBLOG_AVAILABLE2',"<span style='color : green;'>Available. </span>");
define('_AM_WEBLOG_NOTAVAILABLE2',"<span style='color : red;'>is not available. </span>");
define('_AM_WEBLOG_NOTWRITABLE2',"<span style='color : red;'>".' should have permission ( %1$d ), but it has ( %2$d )'. '</span>');
define('_AM_WEBLOG_CREATETHEDIR2','Create it');
define('_AM_WEBLOG_SETMPERM2','Set the permission');

define('_AM_WEBLOG_DIRCREATED2','The directory has been created');
define('_AM_WEBLOG_DIRNOTCREATED2','The directory can not be created');
define('_AM_WEBLOG_PERMSET2','The permission has been set');
define('_AM_WEBLOG_PERMNOTSET2','The permission can not be set');

}
