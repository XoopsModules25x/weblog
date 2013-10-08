<?php
/**
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

define( 'WEBLOG_MI_LOADED' , 1 ) ;

define('_MI_WEBLOG_NAME','weBLog');
define('_MI_WEBLOG_DESC','weBLogging/Journal system');
define('_MI_WEBLOG_SMNAME1','My weBLog');
define('_MI_WEBLOG_SMNAME2','Post');
define('_MI_WEBLOG_SMNAME3','Archives');

// submenu name
define('_MI_WEBLOG_DBMANAGER', 'Database');
define('_MI_WEBLOG_CATMANAGER', 'Categories');
define('_MI_WEBLOG_PRIVMANAGER', 'Privileges (simple)');
define('_MI_WEBLOG_MYGROUPSADMIN', 'Privileges (XOOPS)');
define('_MI_WEBLOG_MYBLOCKSADMIN', 'Blocks and Groups');
define('_MI_WEBLOG_TEMPLATE_MANEGER', 'Templates');

define('_MI_WEBLOG_NOTIFY','This weBLog');
define('_MI_WEBLOG_NOTIFYDSC','When something happens to this weBLog');
define('_MI_WEBLOG_ENTRY_NOTIFY','This weBLog entry');
define('_MI_WEBLOG_ENTRY_NOTIFYDSC','When something happens to this weBLog entry');

define('_MI_WEBLOG_ADD_NOTIFY','New Post');
define('_MI_WEBLOG_ADD_NOTIFYCAP','Notify me when a new post occurs');
define('_MI_WEBLOG_ADD_NOTIFYDSC','When a new post is made');
define('_MI_WEBLOG_ADD_NOTIFYSBJ','New weBLog Post');

define('_MI_WEBLOG_ENTRY_COMMENT','Comment Added');
define('_MI_WEBLOG_ENTRY_COMMENTDSC','Notify me when a new comment is posted for this item.');

define('_MI_WEBLOG_RECENT_BNAME1','Recent weBLogs');
define('_MI_WEBLOG_RECENT_BNAME1_DESC','Recent weBLog Entries');
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
define('_MI_WEBLOG_CATEGORY_LIST', 'Category list');
define('_MI_WEBLOG_CATEGORY_LIST_DESC', 'Category list with counts');
define('_MI_WEBLOG_TB_CENTER', 'Trackback Center');
define('_MI_WEBLOG_TB_CENTERDSC', 'Show entries you want trackbacks especially');
// hodaka added archive list block
define('_MI_WEBLOG_ARCHIVE_LIST', 'Archives list');
define('_MI_WEBLOG_ARCHIVE_LIST_DESC', 'Archives list enables to sort by');
// hodaka added calendar block
define('_MI_WEBLOG_CALENDAR', 'Weblog Calendar');
define('_MI_WEBLOG_CALENDAR_DESC', 'Weblog monthly calendar');

// Config Settings
define('_MI_WEBLOG_NUMPERPAGE','Number of entries per page');
define('_MI_WEBLOG_NUMPERPAGEDSC','');
define('_MI_WEBLOG_DATEFORMAT','Date format');
define('_MI_WEBLOG_DATEFORMATDSC','');
define('_MI_WEBLOG_TIMEFORMAT','Time format');
define('_MI_WEBLOG_TIMEFORMATDSC','');
define('_MI_WEBLOG_RECENT_DATEFORMAT','Date format in Recent weBLog\'s');
define('_MI_WEBLOG_RECENT_DATEFORMATDSC','');
define('_MI_WEBLOG_SHOWAVATAR','Show users avatar on each entry');
define('_MI_WEBLOG_SHOWAVATARDSC','');
define('_MI_WEBLOG_ALIGNAVATAR','Align avatar');
define('_MI_WEBLOG_ALIGNAVATARDSC','');
define('_MI_WEBLOG_MINENTRYSIZE','Minimum size of entry (0=size checking disabled)');
define('_MI_WEBLOG_MINENTRYSIZEDSC','');
define('_MI_WEBLOG_IMGURL', 'Image URL');
define('_MI_WEBLOG_IMGURLDSC', 'URL of image that is shown or indicated in printer-friendly page and RSS');
define('_MI_WEBLOG_OPDOHTML', 'Option/Disable HTML') ;
define('_MI_WEBLOG_OPDOHTMLDSC', 'If you want to be checked disable HTML Option as default , set "Yes".') ;
define('_MI_WEBLOG_OPDOBR', 'Option/Auto wrap lines') ;
define('_MI_WEBLOG_OPDOBRDSC', 'If you want to be checked "Auto wrap lines" Option as default , set "Yes".') ;
define('_MI_WEBLOG_OPPRIVATE', 'Option/Private') ;
define('_MI_WEBLOG_OPPRIVATEDSC', 'If you want to be checked Private Option as default , set "Yes".') ;
define('_MI_WEBLOG_OPUPDATEPING', 'Option/Update ping') ;
define('_MI_WEBLOG_OPUPDATEPINGDSC', 'If you want to be checked Update ping Option as default , set "Yes".') ;

define('_MI_WEBLOG_UPDATE_READS_WHEN','Update read counter when');
define('_MI_WEBLOG_UPDATE_READS_WHENDSC','');
define('_MI_WEBLOG_UPDATE_READS_WHEN1','When viewing details');
define('_MI_WEBLOG_UPDATE_READS_WHEN2','When viewing users weBLog');
define('_MI_WEBLOG_UPDATE_READS_WHEN3','When viewing entry in any list');
define('_MI_WEBLOG_GTICKETTIMEOUT','Time out setting when you post an entry.');
define('_MI_WEBLOG_GTICKETTIMEOUTDSC','Entry must be posted for this time. (unit is minute)');

define('_MI_WEBLOG_TEMPLATE_ENTRIESDSC','Display entries for the given weBLog');
define('_MI_WEBLOG_TEMPLATE_POSTDSC','Post a new weBLog entry');
define('_MI_WEBLOG_TEMPLATE_DETAILSDSC','Display details about a weBLog entry');
define('_MI_WEBLOG_TEMPLATE_RSSFEEDDSC','RSS feed of weBLog entries');
define('_MI_WEBLOG_TEMPLATE_PRINTDSC','Printer friendly page');
define('_MI_WEBLOG_TEMPLATE_ARCHIVEDSC','Monthly archives');
define('_MI_WEBLOG_TEMPLATE_IMAGEMANAGERDSC','weBLog Image Manager');
define('_MI_WEBLOG_CALBLOCKCSS_DSC','CSS for calendar block');
// myarchive.php by hodaka
define('_MI_WEBLOG_TEMPLATE_MYARCHIVEDSC', 'Sort archives');

define('_MI_WEBLOG_EDITORHEIGHT','Height of editor box (lines)');
define('_MI_WEBLOG_EDITORHEIGHTDSC','');
define('_MI_WEBLOG_EDITORWIDTH','Width of editor box (characters)');
define('_MI_WEBLOG_EDITORWIDTHDSC','');
define('_MI_WEBLOG_ONLYADMIN',"Allow only module admin's to post?");
define('_MI_WEBLOG_ONLYADMINDSC','Setting to no will allow all registered users to post, while yes would mean only module administrators can post.(This setting is valid when you choose "weBLog" as privilege system.)');
define('_MI_WEBLOG_POST_COUNTUP',"Count up XOOPS USERS POSTS number");
define('_MI_WEBLOG_POST_COUNTUPDSC','Setting count up users posts when user post Blog entry .');
define('_MI_WEBLOG_DISABLE_HTML',"Forbidden HTML tags to all users");
define('_MI_WEBLOG_DISABLE_HTMLDSC','From a viewpoint of Security, Please set this "YES" if you cannot trust all your registrants.');
define('_MI_WEBLOG_TB_BLOGNAME',"Set strings when your user send trackbacks to another Blog, you use as BLOG TITLE.");
define('_MI_WEBLOG_TB_BLOGNAMEDSC','You can use {MODULE_NAME},{USER_NAME},{SITE_NAME}');

// wellwine for read cookie
define('_MI_WEBLOG_EXPIRATION','Expiration of read count (second)');
define('_MI_WEBLOG_EXPIRATIONDSC','Define the time expiration of each blog read count. The count will be incremented if it has passed this period since last viewing.');
define('_MI_WEBLOG_RSSSHOW','Show an icon linked to RSS feed');
define('_MI_WEBLOG_RSSSHOWDSC','');
define('_MI_WEBLOG_RSSMAX','The number of entries to be fed in RSS');
define('_MI_WEBLOG_RSSMAXDSC','');

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
//define('_MI_WEBLOG_PERMIT_INGROUP' , 'Define what is "SAME GROUP"');
//define('_MI_WEBLOG_PERMIT_INGROUPDSC' , '');
//define('_MI_WEBLOG_PERMIT_OUTGROUP' , 'Define what is "DIFFERENT GROUP"');
//define('_MI_WEBLOG_PERMIT_OUTGROUPDSC' , '');
//define('_MI_WEBLOG_PERMIT_G_COMPLETE_S','All belong groups are completely the same');
//define('_MI_WEBLOG_PERMIT_G_PARTIAL_S','At least one belong group is same');
//define('_MI_WEBLOG_PERMIT_G_COMPLETE_D','No belong group is same');
//define('_MI_WEBLOG_PERMIT_G_PARTIAL_D','At least one belong group is different');
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
?>
