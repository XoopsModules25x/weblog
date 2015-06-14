<?php
/*
 * $Id: modinfo.php 11979 2013-08-25 20:45:24Z beckmi $
 * Copyright (c) 2003 by Jeremy N. Cowgar <jc@cowgar.com>
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

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( 'WEBLOG_MI_LOADED' ) ) {

define('WEBLOG_MI_LOADED' , 1 ) ;

define('_MI_WEBLOG_NAME','weBLog');
define('_MI_WEBLOG_MYGROUPSADMIN','Blöcke und Gruppen');
define('_MI_WEBLOG_DESC','weBLogging/Journal-System');
define('_MI_WEBLOG_SMNAME1','Mein weBLog');
define('_MI_WEBLOG_SMNAME2','Eintrag senden');
define('_MI_WEBLOG_SMNAME3','Archive');

// submenu name
define('_MI_WEBLOG_DBMANAGER', 'Datenbank'); //eng
define('_MI_WEBLOG_CATMANAGER', 'Kategorien'); //eng
define('_MI_WEBLOG_PRIVMANAGER', 'weBLog Berechtigungen'); //eng
define('_MI_WEBLOG_MYBLOCKSADMIN', 'Blöcke und Gruppen'); //eng
define('_MI_WEBLOG_TEMPLATE_MANEGER', 'Templates');
define('_AM_WEBLOG_PRIVMANAGER_WEBLOG_CAUTION', 'Achtung: Das sind keine Xoops-Berechtigungen, sondern spezielle Berechtigungen für das Modul. Man kann zwischen dieser Berechtigung oder der Xoops-Berechtigung wählen. Die dazu nötige Einstellung befindet sich in der allgemeinen Modulvoreinstellung.');

define('_MI_WEBLOG_NOTIFY','Dieses weBLog');
define('_MI_WEBLOG_NOTIFYDSC','Wenn etwas bei dem weBLog passiert');
define('_MI_WEBLOG_ENTRY_NOTIFY','Dieser weBLog-Eintrag');
define('_MI_WEBLOG_ENTRY_NOTIFYDSC','Wenn etwas bei diesem weBLog-Eintrag passiert');

define('_MI_WEBLOG_ADD_NOTIFY','Neuer Eintrag');
define('_MI_WEBLOG_ADD_NOTIFYCAP','Benachrichtigen wenn ein Eintrag veröffentlicht worden ist');
define('_MI_WEBLOG_ADD_NOTIFYDSC','Wenn ein neuer Eintrag veröffentlicht worden ist');
define('_MI_WEBLOG_ADD_NOTIFYSBJ','Neuer weBLog-Eintrag');

define('_MI_WEBLOG_ENTRY_COMMENT','Kommentar hinzugefügt');
define('_MI_WEBLOG_ENTRY_COMMENTDSC','Benachrichtigen wenn ein neuer Kommentar für diesen Eintrag veröffentlicht worden ist.');

define('_MI_WEBLOG_RECENT_BNAME1','Die neuesten weBLogs');
define('_MI_WEBLOG_RECENT_BNAME1_DESC','Die neuesten weBLog-Einträge');
define('_MI_WEBLOG_TOP_WEBLOGS','Top weBLogs');
define('_MI_WEBLOG_TOP_WEBLOGS_DESC','Top weBLogs');
define('_MI_WEBLOG_USERS_WEBLOGS','Users\' weBLogs');
define('_MI_WEBLOG_USERS_WEBLOGS_DESC','Recent weBLogs group by user');
define('_MI_WEBLOG_RECENT_TRACKBACKS','Recent Trackbacks');
define('_MI_WEBLOG_RECENT_TRACKBACKS_DESC','Recent Recieved Trackbacks ');
define('_MI_WEBLOG_RECENT_COMMENTS','Recent Comments');
define('_MI_WEBLOG_RECENT_COMMENTS_DESC','Recent Comments to weBLog entries');
define('_MI_WEBLOG_LINKS','weBLog owner\'s Links');
define('_MI_WEBLOG_LINKS_DESC','Links integrated with a links module');
define('_MI_WEBLOG_RECENT_IMAGES','Recent Images');
define('_MI_WEBLOG_RECENT_IMAGES_DESC','Recent Images used by weBLog');
define('_MI_WEBLOG_CATEGORY_LIST', 'Kategorieliste');
define('_MI_WEBLOG_CATEGORY_LIST_DESC', 'Category list with counts');
define('_MI_WEBLOG_TB_CENTER', 'Trackback Center');
define('_MI_WEBLOG_TB_CENTERDSC', 'Show entries you want trackbacks especially');
// hodaka added archive list block
define('_MI_WEBLOG_ARCHIVE_LIST', 'Archivliste');
define('_MI_WEBLOG_ARCHIVE_LIST_DESC', 'Archives list enables to sort by');
// hodaka added calendar block
define('_MI_WEBLOG_CALENDAR', 'weBLog Kalender');
define('_MI_WEBLOG_CALENDAR_DESC', 'weBLog monatl. Kalender');

// Config Settings
define('_MI_WEBLOG_NUMPERPAGE','Anzahl der Einträge pro Seite');
define('_MI_WEBLOG_NUMPERPAGEDSC','');
define('_MI_WEBLOG_DATEFORMAT','Datumsformat');
define('_MI_WEBLOG_DATEFORMATDSC','');
define('_MI_WEBLOG_TIMEFORMAT','Zeitformat');
define('_MI_WEBLOG_TIMEFORMATDSC','');
define('_MI_WEBLOG_RECENT_DATEFORMAT','Datumsformat in neueste weBLog\'s');
define('_MI_WEBLOG_RECENT_DATEFORMATDSC','');
define('_MI_WEBLOG_SHOWAVATAR','Mitglieder-Avatare bei jedem Eintrag anzeigen');
define('_MI_WEBLOG_SHOWAVATARDSC','');
define('_MI_WEBLOG_ALIGNAVATAR','Avatar ausrichten');
define('_MI_WEBLOG_ALIGNAVATARDSC','');
define('_MI_WEBLOG_MINENTRYSIZE','Minimale Länge des Eintrags (0 = Längen-Check deaktiviert)');
define('_MI_WEBLOG_MINENTRYSIZEDSC','');
define('_MI_WEBLOG_IMGURL', 'Image URL'); //eng
define('_MI_WEBLOG_IMGURLDSC', 'URL of image that is shown or indicated in printer-friendly page and RSS');
define('_MI_WEBLOG_OPDOHTML', 'Option/Disable HTML') ;
define('_MI_WEBLOG_OPDOHTMLDSC', 'If you want to be checked disable HTML Option as default , set "Yes".') ;
define('_MI_WEBLOG_OPDOBR', 'Option/Auto wrap lines') ;
define('_MI_WEBLOG_OPDOBRDSC', 'If you want to be checked "Auto wrap lines" Option as default , set "Yes".') ;
define('_MI_WEBLOG_OPPRIVATE', 'Option/Private') ;
define('_MI_WEBLOG_OPPRIVATEDSC', 'If you want to be checked Private Option as default , set "Yes".') ;
define('_MI_WEBLOG_OPUPDATEPING', 'Option/Update ping') ;
define('_MI_WEBLOG_OPUPDATEPINGDSC', 'If you want to be checked Update ping Option as default , set "Yes".') ;

define('_MI_WEBLOG_UPDATE_READS_WHEN','Zugriffs-Zähler aktualisieren');
define('_MI_WEBLOG_UPDATE_READS_WHENDSC','Zugriffs-Zähler aktualisieren');
define('_MI_WEBLOG_UPDATE_READS_WHEN1','Wenn Details gelesen werden');
define('_MI_WEBLOG_UPDATE_READS_WHEN2','Wenn Mitglieder-weBLog gelesen wird');
define('_MI_WEBLOG_UPDATE_READS_WHEN3','Wenn Einträge in jeglichen Listen gelesen werden');
define('_MI_WEBLOG_GTICKETTIMEOUT','Timeout Einstellung zum Erstellen eines neuen Eintrages.');
define('_MI_WEBLOG_GTICKETTIMEOUTDSC','Der Eintrag muß innerhalb dieser Minuten erstellt werden. (Spamschutz)');

define('_MI_WEBLOG_TEMPLATE_ENTRIESDSC','Einträge für den gegebenen weBLog anzeigen');
define('_MI_WEBLOG_TEMPLATE_POSTDSC','Neuen weBLog-Eintrag veröffentlichen');
define('_MI_WEBLOG_TEMPLATE_DETAILSDSC','Details eines weBLog-Eintrags anzeigen');
define('_MI_WEBLOG_TEMPLATE_RSSFEEDDSC','RSS-Feed der weBLog-Einträge');
define('_MI_WEBLOG_TEMPLATE_PRINTDSC','Druckoptimierte Seite');
define('_MI_WEBLOG_TEMPLATE_ARCHIVEDSC','Monatliche Archive');
define('_MI_WEBLOG_TEMPLATE_IMAGEMANAGERDSC','weBLog Image Manager');
define('_MI_WEBLOG_CALBLOCKCSS_DSC','CSS for calendar block');
// myarchive.php by hodaka
define('_MI_WEBLOG_TEMPLATE_MYARCHIVEDSC', 'Sort archives');

define('_MI_WEBLOG_EDITORHEIGHT','Höhe der Editor-Box (in Zeilen)');
define('_MI_WEBLOG_EDITORHEIGHTDSC','Höhe der Editor-Box');
define('_MI_WEBLOG_EDITORWIDTH','Breite der Editor-Box (in Zeichen)');
define('_MI_WEBLOG_EDITORWIDTHDSC','Breite der Editor-Box');
define('_MI_WEBLOG_ONLYADMIN',"Nur Modul-Admins Veröffentlichung erlauben?");
define('_MI_WEBLOG_ONLYADMINDSC','Nein erlaubt allen registrierten Mitglieder die Veröffentlichung. Ja erlaubt nur Modul-Administratoren die Veröffentlichung.');
define('_MI_WEBLOG_POST_COUNTUP',"Count up XOOPS USERS POSTS number");
define('_MI_WEBLOG_POST_COUNTUPDSC','Setting count up users posts when user post Blog entry .');
define('_MI_WEBLOG_DISABLE_HTML',"Forbidden HTML tags to all users");
define('_MI_WEBLOG_DISABLE_HTMLDSC','From a viewpoint of Security, Please set this "YES" if you cannot trust all your registrants.');
define('_MI_WEBLOG_TB_BLOGNAME',"Set strings when your user send trackbacks to another Blog, you use as BLOG TITLE.");
define('_MI_WEBLOG_TB_BLOGNAMEDSC','You can use {MODULE_NAME},{USER_NAME},{SITE_NAME}');

// wellwine for read cookie
define('_MI_WEBLOG_EXPIRATION','Ablauf des Lesez&auml;hlers (in Sekunden)');
define('_MI_WEBLOG_EXPIRATIONDSC','Ablauf des Lesez&auml;hlers jedes weBLog definieren. Der Z&auml;hler wird erh&ouml;ht wenn der festgelegte Zeitraum seit dem letzten Aufruf verstrichen ist.');
define('_MI_WEBLOG_RSSSHOW','Icon anzeigen das mit RSS Feed verlinkt werden soll');
define('_MI_WEBLOG_RSSSHOWDSC','RSS-Icon zeigen');
define('_MI_WEBLOG_RSSMAX','Anzahl der Eintr&auml;ge f&uuml;r RSS');
define('_MI_WEBLOG_RSSMAXDSC','Anzahl der RSS Einträge');

define('_MI_WEBLOG_USESEPARATOR','use entry division function');
define('_MI_WEBLOG_USESEPARATORDSC','inserting separator , you can divide entry as first half and latter half. First half is shown at index.php and Full entry is shown at details.php');
define('_MI_WEBLOG_USESMEMBERONLY','use menber only readable function');
define('_MI_WEBLOG_USESMEMBERONLYDSC','inserting separator , you can make part of entry which only registered member can read.');
define('_MI_WEBLOG_USEIMAGEMANAGER','use weBLog Image manager');
define('_MI_WEBLOG_USEIMAGEMANAGERDSC','You can use weBLog image manager different with XOOPS core image manager. It can deal thumbnail view.');
define('_MI_WEBLOG_USEPERMITSYSTEM','add permission to each entry');
define('_MI_WEBLOG_USEPERMITSYSTEMDSC','You can add permission attribute which groups can read or not');
define('_MI_WEBLOG_DEFAULT_PERMIT','Default permission when using permission system');
define('_MI_WEBLOG_DEFAULT_PERMITDSC','Default permission when user post new entry');
define('_MI_WEBLOG_PERMIT_SHOWTITLE','Show only title when user is not allowed to read') ;
define('_MI_WEBLOG_PERMIT_SHOWTITLEDSC','If you choose "No", user should not notice whether what kind of entry exists.<br/>Notice: if you choose "Yes",user can read trackbacks and comments of forbidden entries.') ;
define('_MI_WEBLOG_PRIVILEGE_SYSTEM','Privilege System');
define('_MI_WEBLOG_PRIVILEGE_SYSTEMDSC','You can choose "weBLog" or "XOOPS". "weBLog" is used till ver.1.41 as in the past and is simple. "XOOPS" is using XOOPS core privilege function and is detailed.');
define('_MI_WEBLOG_SHOWCATEGORY','SHOW CATEGORY LIST OR NOT');
define('_MI_WEBLOG_SHOWCATEGORY_DSC','SHOW CATEGORY LIST IN index.php');
define('_MI_WEBLOG_CAT_POSTPERM','Category permission system when posting');
define('_MI_WEBLOG_CAT_POSTPERM_DSC','Selecting "YES", you can controll posting category permission by every group.');
define('_MI_WEBLOG_CAT_LISTCOL','NUMBER OF COLUMNS OF CATEGORY LIST');
define('_MI_WEBLOG_CAT_LISTCOL_DSC','');

// myalbum-P imagemanager
// Config Items
define( "_MI_ALBM_CFG_PHOTOSPATH" , "Path to photos" ) ;
define( "_MI_ALBM_CFG_DESCPHOTOSPATH" , "Path from the directory installed XOOPS.<br />(The first character must be '/'. The last character should not be '/'.)<br />This directory's permission is 777 or 707 in unix." ) ;
define( "_MI_ALBM_CFG_THUMBSPATH" , "Path to thumbnails" ) ;
define( "_MI_ALBM_CFG_DESCTHUMBSPATH" , "Same as 'Path to photos'." ) ;
//define( "_MI_ALBM_CFG_THUMBWIDTH" , "Thumb Image Width" ) ;
//define( "_MI_ALBM_CFG_DESCTHUMBWIDTH" , "The height of thumbs will be decided from the width automatically." ) ;
define( "_MI_ALBM_CFG_THUMBSIZE" , "Size of thumbnails (pixel)" ) ;
define( "_MI_ALBM_CFG_THUMBRULE" , "Calc rule for building thumbnails" ) ;
define( "_MI_ALBM_CFG_WIDTH" , "Max photo width" ) ;
define( "_MI_ALBM_CFG_DESCWIDTH" , "This means the photo's width to be resized.<br />If you use GD without truecolor, this means the limitation of width." ) ;
define( "_MI_ALBM_CFG_HEIGHT" , "Max photo height" ) ;
define( "_MI_ALBM_CFG_DESCHEIGHT" , "Same as 'Max photo width'." ) ;
define( "_MI_ALBM_CFG_FSIZE" , "Max file size" ) ;
define( "_MI_ALBM_CFG_DESCFSIZE" , "The limitation of the size of uploading file.(byte)" ) ;
define( "_MI_ALBM_CFG_MIDDLEPIXEL" , "Max image size in single view" ) ;
//define( "_MI_ALBM_CFG_DESCMIDDLEPIXEL" , "Specify (width)x(height)<br />eg) 480x480" ) ;

define( "_MI_ALBUM_OPT_CALCFROMWIDTH" , "width:specified  height:auto" ) ;
define( "_MI_ALBUM_OPT_CALCFROMHEIGHT" , "width:auto  width:specified" ) ;
define( "_MI_ALBUM_OPT_CALCWHINSIDEBOX" , "put in specified size squre" ) ;

// hodaka added against for trackback spams
define('_MI_WEBLOG_CHECK_TRACKBACK','Reject trackbacks with no Japanese words in a blog name or a blog title<br />(Select required number of letters,Hiragana,Katakana or Kanji)');
define('_MI_WEBLOG_CHECK_TRACKBACK_DESC','No need in no multi-bytes countries');
define('_MI_WEBLOG_NO_CHECK_TRACKBACK','No need to check');
define('_MI_WEBLOG_TRACKBACK_NEEDLETTER1','1 letter or more');
define('_MI_WEBLOG_TRACKBACK_NEEDLETTER2','2 letters or more');
define('_MI_WEBLOG_TRACKBACK_NEEDLETTER3','3 letters or more');

define('_MI_WEBLOG_SPAM_WORDS','Banned words in trackbacks');
define('_MI_WEBLOG_SPAM_WORDS_DESC','Each word must be seperated by "|"');

define('_MI_WEBLOG_CHECK_RBL','Check RBL');
define('_MI_WEBLOG_CHECK_RBL_DSC','');
define('_MI_WEBLOG_RBL_LIST','url of RBL<br />Each must be seperated by "|"');
define('_MI_WEBLOG_RBL_LIST_DESC','');
define('_MI_WEBLOG_SEND_TO_ADMIN','Send notify mail to administrator');
define('_MI_WEBLOG_SEND_TO_ADMIN_DSC','');
define('_MI_WEBLOG_SEND_ADDRESS','Mail address must be seperated by "|" if plural<br />send to admin if none');
define('_MI_WEBLOG_SEND_ADDRESS_DSC','');
}
