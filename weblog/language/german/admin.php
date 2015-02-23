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
// now these all are English not French . Please transrate and tell me .
// http://xoopsdevelopment.tohokuaiki.jp

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( 'WEBLOG_AM_LOADED' ) ) {

define( 'WEBLOG_AM_LOADED' , 1 ) ;

define('_AM_WEBLOG_CONFIG',  ' Modul-Konfiguration');
define('_AM_WEBLOG_PREFERENCES', 'Einstellungen');
define('_AM_WEBLOG_PREFERENCESDSC', 'allgem. Konfiguration.');
define('_AM_WEBLOG_GO', 'Los');
define('_AM_WEBLOG_CANCEL', 'Abbrechen');
define('_AM_WEBLOG_DELETE', 'Löschen');
define('_AM_WEBLOG_TITLE', 'Titel');

define('_AM_WEBLOG_DBMANAGER', 'Datenbank');
define('_AM_WEBLOG_DBMANAGERDSC', 'Datenbanktools zum updaten des Modules.');
define('_AM_WEBLOG_TABLE', 'Tabelle');
define('_AM_WEBLOG_SYNCCOMMENTS', 'Synchronize comments count');
define('_AM_WEBLOG_SYNCCOMMENTSDSC', 'Correct count if you see # of comments of each entry wrong.<br />This might be because v1.02 or earlier version did not handle count correctly.');
define('_AM_WEBLOG_CHECKTABLE', 'Check tables structure');
define('_AM_WEBLOG_CHECKTABLEDSC', 'Check tables in database. You could checkNew version might require new tables or columns.');
define('_AM_WEBLOG_CREATETABLE', 'Tabelle erstellen \'%s\'');
define('_AM_WEBLOG_CREATETABLEDSC', 'Erstellen einer Tabelle mit dem Namen \'%s\'');

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

define('_AM_WEBLOG_CATMANAGER', 'Kategorien');
define('_AM_WEBLOG_CATMANAGERDSC', 'hinzufügen/ändern/löschen von Kategorien.');
define('_AM_WEBLOG_ADDCAT', 'Kategorie hinzufügen');
define('_AM_WEBLOG_ADDMAINCAT', 'Eine Hauptkategorie hinzufügen');
define('_AM_WEBLOG_ADDSUBCAT', 'Eine Unterkategorie hinzufügen');
define('_AM_WEBLOG_CAT', 'Kategorie');
define('_AM_WEBLOG_IMGURL', 'Bild URL');
define('_AM_WEBLOG_ERRORTITLE', 'FEHLER: Bitte einen TITEL eingeben!');
define('_AM_WEBLOG_NEWCATADDED', 'Eine neue Kategorie wurde hinzugefügt!');
define('_AM_WEBLOG_CATNOTADDED', 'Kategorie kann nicht hinzugefügt werden!');
define('_AM_WEBLOG_CATMODED', 'Kategorie wurde erfolgreich geändert!');
define('_AM_WEBLOG_CATNOTMODED', 'Kategorie konnte nicht geändert werden!');
define('_AM_WEBLOG_MODCAT', 'Kategorie ändern');
define('_AM_WEBLOG_PCAT', 'Hauptkategorie');
define('_AM_WEBLOG_CHOSECAT', 'gewählte Kategorie');
define('_AM_WEBLOG_DELCONFIRM', 'Soll diese Kategorie \'%s\' mit samt den Unterkategorien wirklich gelöscht werden?<br />ALLE Einträge werden unwiederruflichgelöscht.');
define('_AM_WEBLOG_CATDELETED', 'Kategorie wurde erfolgreich gelöscht!');

define('_AM_WEBLOG_MYBLOCKSADMIN', 'Blöcke und Gruppen');
define('_AM_WEBLOG_MYBLOCKSADMINDSC', 'Einstellungen der Blöcke und Gruppen');
define('_AM_WEBLOG_PRIVMANAGER_WEBLOG_DSC', 'Berechtigungsmanager nach weblog-art');
define('_AM_WEBLOG_PRIVMANAGER_WEBLOG', 'Berechtigungsmgr. nach weblog');
define('_AM_WEBLOG_PRIVMANAGER_XOOPS_DSC', 'Berechtigungsmanager nach Xoops-art');
define('_AM_WEBLOG_PRIVMANAGER_XOOPS', 'Berechtigungsmgr. nach Xoops-art');
define('_AM_WEBLOG_TEMPLATE_MANEGERDSC', 'Templatemanager');
define('_AM_WEBLOG_TEMPLATE_MANEGER', 'Templatemanager');
define('_AM_WEBLOG_PRIVMANAGER', 'Berechtigungen');
define('_AM_WEBLOG_PRIVMANAGERDSC', 'Control posting privilege.');
define('_AM_WEBLOG_ADDPRIV', 'hinzufügen');
define('_AM_WEBLOG_DELETEPRIV', 'Löschen');
define('_AM_WEBLOG_NONPRIV', 'keinen Zugriff');
define('_AM_WEBLOG_PRIV', 'Zugriff');
define('_AM_WEBLOG_GROUPPERM_GLOBAL', 'Xoops Gruppenberechtigung');
define('_AM_WEBLOG_PRIV_EDIT', 'darf editieren');
define('_AM_WEBLOG_PRIV_READINDEX', 'darf den weBLog-Index lesen');
define('_AM_WEBLOG_PRIV_READDETAIL', 'darf die Details lesen');
define('_MI_WEBLOG_MYGROUPSADMIN', 'Xoops Berechtigungen');

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
?>
