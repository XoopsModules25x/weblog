<?php
/*
 * $Id: main.php 11979 2013-08-25 20:45:24Z beckmi $
 * Copyright (c) 2003 by Jeremy N. Cowgar <jc@cowgar.com>
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

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( 'WEBLOG_BL_LOADED' ) ) {

define( 'WEBLOG_BL_LOADED' , 1 ) ;

// Basic's
define('_BL_DISABLEHTML', _DISABLEHTML);
define('_BL_WRAPLINES', 'Auto wrap lines');
define('_BL_TITLE','Titel');
define('_BL_CATEGORY', 'Kategorie'); //eng
define('_BL_CATEGORIES', 'Kategorien'); //eng
define('_BL_MAIN', 'Main'); //eng
define('_BL_CONTENTS','Inhalte');
define('_BL_POST','Eintrag');
define('_BL_PREVIEW','Vorschau');
define('_BL_PRIVATE','Privat');
define('_BL_OPTIONS','Optionen');
define('_BL_COMMENTS','Kommentare');
define('_BL_READ_USERS_BLOG',"Lese %s's weBLog");
define('_BL_EDIT','Bearbeiten');
define('_BL_READ','Lesen');
define('_BL_SHOW','show');
define('_BL_DELETE','Löschen');
define('_BL_BLOG','weBLog');
define('_BL_GATHERING','Ihre weBlog-Einträge werden jetzt zusammengefasst!');
define('_BL_GATHERING_SORRY','Verzeihung, My weBLog steht nur registrierten Usern zur Verfügung.');
define('_BL_PRIVATE_NOTEXIST_SORRY', 'Verzeihung! Der angeforderte Eintrag ist privat oder existiert nicht.');
define('_BL_ENTRY_POSTED','weBLog-Eintrag wurde veröffentlicht!');
define('_BL_MOST_RECENT','Die neuesten Einträge');
define('_BL_ENTRIES_FOR','Einträge für %s');
define('_BL_ENTRY_FOR','Eintrag f&uuml;r %s');
define('_BL_NUMBER_OF_READS','gelesen');
define('_BL_NUMBER_OF_TRACKBACKS','Trackback');//eng
define('_BL_CONFIRM_DELETE',"Sind Sie sicher Sie wollen den weBLog-Eintrag '%s' löschen?");
define('_BL_BLOG_DELETED','Der ausgewäèlte weBLog-Eintrag wurde gelöscht');
define('_BL_BLOG_NOT_DELETED','Der ausgewäèlte weBLog-Eintrag wurde NICHT gelöócht. Unzureichende Rechte?');
define('_BL_WHOS_BLOG',"%s's weBLog");
define('_BL_ANON_CANNOT_POST_SORRY','Verzeihung, nur registrierte User köînen weBLog-Einträge veröffentlichen.');
define('_BL_CANNOT_READ_SORRY','Sorry, Only permitted users can read weBLog entries.<br />Please register or contact webmasters if you would like to read entries.');
define('_BL_CANNOT_EDIT','Only Administrator or blogger who post this entry can edit.');
define('_BL_DELETE_BUTTON','Löschen');
define('_BL_PREVIEW_BUTTON','Vorschau');
define('_BL_POST_BUTTON','Veröffentlichen');
define('_BL_POST_TOO_SMALL','Einträge müssen wenigstens %d Zeichen enthalten, Ihrer enthält %d. Bitte mehr Inhalt hinzufügen!');
define('_BL_POST_TIMEOUT','Entry must be posted in <b>%d</b> minutes');
define('_BL_POST_MUST_BE','Einträge m&uuml;ssen wenigstens <b>%d</b> Zeichen enthalten');
define('_BL_CONTINUE_EDITING','Bearbeitung fortsetzen');
define('_BL_RSS_RECENT', 'weBLogs verteilen');
define('_BL_RSS_RECENT_FOR', "%s's Eintr&auml;ge verteilen");
define('_BL_UPDATEPING','Send update ping'); //eng
define('_BL_SPECIFY_TIME' , 'Specify entry time as below') ;
define('_BL_SPECIFY_TIME_DSC' , '&nbsp;&nbsp;&nbsp; set date') ;
define('_BL_TRACKBACK','Trackback URL'); //eng
define('_BL_TRACKBACKS','Trackbacks');
define('_BL_TRACKBACK_DSC','Please fill trackback URLs set apart by new line'); //eng
define('_BL_TRACKBACK_ADMIN','Recieved Trackback'); //eng
define('_BL_PERMISSION','Readable Groups');
define('_BL_PERMISSION_CAPTION','You can set which groups can read this entry.');
define('_BL_TRACKBACK_DELETE','Löschen'); //eng
define('_BL_GROUP_PERMIT', "Sorry. You are not allowed to read this entry.");
define('_BL_SELECT_ALL', 'Alle auswählen');
define('_BL_CAUTION_NOHTML', '<b>Achtung</b>');
define('_BL_FORBIDDEN_HTML_TAGS', '<b>Es sind kein HTML Tag erlaubt</b>(Bitte BB Tags verwenden)');

// %s is your site name
define('_BL_INTARTICLE','Interessanter weBLog auf %s');
define('_BL_INTARTFOUND','Hier ist ein interessanter weBLog den ich auf %s gefunden habe');

define('_BL_PRINTERPAGE','Druckoptimierte Seite');
define('_BL_SENDSTORY','Diesen weBLog einem Freund schicken');

define('_BL_POSTED', 'Ver&ouml;ffentlicht');
define('_BL_AUTHOR', 'Autor');
define('_BL_T_COMMENTS', 'Kommentare');
// %s is your site name
define('_BL_COMESFROM', 'Dieser weBLog kommt von %s');
define('_BL_PARMALINK', 'Die URL dieses weBLogs');

// %s is count
define('_BL_THEREAREINTOTAL', '%s Eintr&auml;ge in den Archiven.');
define('_BL_NOARCHIVEDESC', 'Keine Einträge im Archiv.'); //eng
define('_BL_ARCHIVE', 'Archiv');
define('_BL_ARCHIVE_FOR', "%s's Archiv");
define('_BL_READS', 'x gelesen');
define('_BL_NEXT', 'nächster Eintrag'); //eng
define('_BL_PREV', 'vorherig Eintrag'); //eng

// division separator
define('_BL_ENTRY_SEPARATOR' , 'Entry Separator');
define('_BL_ENTRY_SEPARATOR_CAPTION' , 'This separator divides the first half and the latter half of entries. Full entry is shown at detail view');
define('_BL_ENTRY_SEPARATOR_VALUE' , 'Click this button to insert separator');
define('_BL_ENTRY_SEPARATOR_NEXT' , '...mehr lesen');

// member only delimeter
define('_BL_MEMBER_ONLY_READ' , 'Only member');
define('_BL_MEMBER_ONLY_READ_CAPTION' , 'Only member can read below this separator');
define('_BL_MEMBER_ONLY_READ_VALUE' , 'Click this button to insert separator');
define('_BL_MEMBER_ONLY_READ_MORE' , 'The member registration is necessary for you to read more.');

// image manager tray
define('_BL_WEBLOG_IMAGEMANAGER' , 'Bild-Manager');
define('_BL_WEBLOG_IMAGEMANAGER_CAUTION' , '"weBLog imagemanager requires PHP-GD2 extension. Your environment is not valid."');

// %s is trackback
define('_BL_TRACKBACK_ANOUNCE' , 'Trackback URL von diesem Eintrag'); //eng
define('_BL_TRACKBACK_TRANSMIT' , 'This entry trackbacks to below URL'); //eng
define('_BL_TRACKBACK_RECIEVED' , 'Trackbacks zu diesem Eintrag'); //eng


// %s is uname
define('_BL_TRACKBACKS_FOR','to %s\'s Einträge ');
define('_BL_COMMENTS_FOR','for %s\'s Einträge ');

// use weBLog imagemanager :: myalbum-P
define("_BL_ALBM_PHOTOUPLOAD","Photo Upload");
define("_BL_ALBM_MAXPIXEL","Max. Pixelgröße");
define("_BL_ALBM_MAXSIZE","Max. Dateigröße (byte)");
define("_BL_ALBM_PHOTOTITLE","Titel");
define("_BL_ALBM_PHOTOCAT","Kategorie");
define("_BL_ALBM_SELECTFILE","Foto wählen");

define("_BL_ALBM_PHOTODEL","Foto löschen?");
define("_BL_ALBM_DELETINGPHOTO","Foto gelöscht");
define("_BL_ALBM_RECEIVED","We received your Photo. Thanks!");
define("_BL_ALBM_MUSTREGFIRST","Sorry, you don't have the permission to perform this action.<br>Please register or login first!");
define("_BL_ALBM_NOIMAGESPECIFIED","Error: No photo is uploaded");
define("_BL_ALBM_FILEERROR","Error: Photos are too big or some troubles about configuration has occurred");
define("_BL_ALBM_FILEREADERROR","Error: Photos are not readable.");

}
