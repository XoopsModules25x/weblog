<?php
/**
 * $Id: main.php,v 1.15 2005/08/14 06:15:44 tohokuaiki Exp $
 * Copyright (c) 2003 by Jeremy N. Cowgar <jc@cowgar.com>
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
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA
 */

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( 'WEBLOG_BL_LOADED' ) ) {

define( 'WEBLOG_BL_LOADED' , 1 ) ;

// Basic's
define('_BL_DISABLEHTML', _DISABLEHTML);
define('_BL_WRAPLINES', 'Auto wrap lines');
define('_BL_TITLE', 'Title');
define('_BL_CATEGORY', 'Category');
define('_BL_CATEGORIES', 'Categories');
define('_BL_MAIN', 'Main');
define('_BL_CONTENTS','Contents');
define('_BL_POST','Post');
define('_BL_PREVIEW',_PREVIEW);
define('_BL_PRIVATE','Private');
define('_BL_OPTIONS','Options');
define('_BL_COMMENTS','Comments');
define('_BL_READ_USERS_BLOG',"Read %s's weBLog");
define('_BL_EDIT',_EDIT);
define('_BL_READ','read');
define('_BL_SHOW','show');
define('_BL_DELETE',_DELETE);
define('_BL_BLOG','weBLog');
define('_BL_GATHERING','Gathering your weBLog entries now!');
define('_BL_GATHERING_SORRY','Sorry, My weBLog is for registered users only.');
define('_BL_PRIVATE_NOTEXIST_SORRY', 'Sorry, The entry you requested is private or does not exist.');
define('_BL_ENTRY_POSTED','weBLog entry has been posted!');
define('_BL_MOST_RECENT','Most recent entries');
define('_BL_ENTRIES_FOR','Entries for %s');
define('_BL_ENTRY_FOR','Entry for %s');
define('_BL_NUMBER_OF_READS','Reads');
define('_BL_NUMBER_OF_TRACKBACKS','Trackback');
define('_BL_CONFIRM_DELETE',"Are you sure you wish to remove weBLog entry '%s'?");
define('_BL_BLOG_DELETED','Selected weBLog entry was deleted');
define('_BL_BLOG_NOT_DELETED','Selected weBLog entry was not deleted. Insufficient rights?');
define('_BL_WHOS_BLOG',"%s's weBLog");
define('_BL_ANON_CANNOT_POST_SORRY','Sorry, Only permitted users can post weBLog entries.<br />Please contact webmasters if you would like to post entries.');
define('_BL_CANNOT_READ_SORRY','Sorry, Only permitted users can read weBLog entries.<br />Please register or contact webmasters if you would like to read entries.');
define('_BL_CANNOT_EDIT','Only Administrator or blogger who post this entry can edit.');
define('_BL_DELETE_BUTTON',_DELETE);
define('_BL_PREVIEW_BUTTON',_PREVIEW);
define('_BL_POST_BUTTON','Post');
define('_BL_POST_TOO_SMALL','Entries must be at least %d charaters, yours is %d. Please add more content!');
define('_BL_POST_TIMEOUT','Entry must be posted in <b>%d</b> minutes');
define('_BL_POST_MUST_BE','Entry must be at least <b>%d</b> charaters');
define('_BL_CONTINUE_EDITING','Continue Editing');
define('_BL_RSS_RECENT', 'Syndicate weBLogs');
define('_BL_RSS_RECENT_FOR', "Syndicate %s's entries");
define('_BL_UPDATEPING','Send update ping');
define('_BL_SPECIFY_TIME' , 'Specify entry time as below') ;
define('_BL_SPECIFY_TIME_DSC' , '&nbsp;&nbsp;&nbsp; set date') ;
define('_BL_TRACKBACK','Trackback URL');
define('_BL_TRACKBACKS','Trackbacks');
define('_BL_TRACKBACK_DSC','Please fill trackback URLs set apart by new line');
define('_BL_TRACKBACK_ADMIN','Recieved Trackback');
define('_BL_PERMISSION','Readable Groups');
define('_BL_PERMISSION_CAPTION','You can set which groups can read this entry.');
define('_BL_TRACKBACK_DELETE',_DELETE);
define('_BL_GROUP_PERMIT', "Sorry. You are not allowed to read this entry.");
define('_BL_SELECT_ALL', 'Select all');
define('_BL_CAUTION_NOHTML', '<b>Caution</b>');
define('_BL_FORBIDDEN_HTML_TAGS', '<b>You cannot use HTML Tag</b>(Please use BB tags)');

// %s is your site name
define('_BL_INTARTICLE','Interesting blog at %s');
define('_BL_INTARTFOUND','Here is an interesting blog I have found at %s');

define('_BL_PRINTERPAGE','Printer Friendly Page');
define('_BL_SENDSTORY','Send this Blog to a Friend');

define('_BL_POSTED', 'Posted');
define('_BL_AUTHOR', 'Author');
// %s is your site name
define('_BL_COMESFROM', 'This blog comes from %s');
define('_BL_PARMALINK', 'The URL of this blog');

// %s is count
define('_BL_THEREAREINTOTAL', '%s entrie(s) in archive.');
define('_BL_NOARCHIVEDESC', 'No entries in archive.');
define('_BL_ARCHIVE', 'Archives');
define('_BL_ARCHIVE_FOR', "%s's archive");
define('_BL_READS', 'Reads');
define('_BL_NEXT', 'Next entry');
define('_BL_PREV', 'Previous entry');

// division separator
define('_BL_ENTRY_SEPARATOR' , 'Entry Separator');
define('_BL_ENTRY_SEPARATOR_CAPTION' , 'This separator divides the first half and the latter half of entries. Full entry is shown at detail view');
define('_BL_ENTRY_SEPARATOR_VALUE' , 'Click this button to insert separator');
define('_BL_ENTRY_SEPARATOR_NEXT' , '...read more');

// member only delimeter
define('_BL_MEMBER_ONLY_READ' , 'Only member');
define('_BL_MEMBER_ONLY_READ_CAPTION' , 'Only member can read below this separator');
define('_BL_MEMBER_ONLY_READ_VALUE' , 'Click this button to insert separator');
define('_BL_MEMBER_ONLY_READ_MORE' , 'The member registration is necessary for you to read more.');

// image manager tray
define('_BL_WEBLOG_IMAGEMANAGER' , 'Image Manager');
define('_BL_WEBLOG_IMAGEMANAGER_CAUTION' , '"weBLog imagemanager requires PHP-GD2 extension. Your environment is not valid."');

// %s is trackback
define('_BL_TRACKBACK_ANOUNCE' , 'Trackback URL of this entry');
define('_BL_TRACKBACK_TRANSMIT' , 'This entry trackbacks to below URL');
define('_BL_TRACKBACK_RECIEVED' , 'Trackbacks to this entry');

// %s is uname
define('_BL_TRACKBACKS_FOR','to %s\'s Entries ');
define('_BL_COMMENTS_FOR','for %s\'s Entries ');

// use weBLog imagemanager :: myalbum-P
define("_BL_ALBM_PHOTOUPLOAD","Photo Upload");
define("_BL_ALBM_MAXPIXEL","Max pixel size");
define("_BL_ALBM_MAXSIZE","Max file size(byte)");
define("_BL_ALBM_PHOTOTITLE","Title");
define("_BL_ALBM_PHOTOCAT","Category");
define("_BL_ALBM_SELECTFILE","Select photo");

define("_BL_ALBM_PHOTODEL","Delete photo?");
define("_BL_ALBM_DELETINGPHOTO","Deleting photo");
define("_BL_ALBM_RECEIVED","We received your Photo. Thanks!");
define("_BL_ALBM_MUSTREGFIRST","Sorry, you don't have the permission to perform this action.<br>Please register or login first!");
define("_BL_ALBM_NOIMAGESPECIFIED","Error: No photo is uploaded");
define("_BL_ALBM_FILEERROR","Error: Photos are too big or some troubles about configuration has occurred");
define("_BL_ALBM_FILEREADERROR","Error: Photos are not readable.");

}
