 <?php
/*
 * $Id: blocks.php,v 1.12 2005/08/14 06:15:44 tohokuaiki Exp $
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


if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( 'WEBLOG_MB_LOADED' ) ) {

define( 'WEBLOG_MB_LOADED' , 1 ) ;

define('_MB_WEBLOG_EDIT_NUMBER_OF_ENTRIES','Number of entries to show');
define('_MB_WEBLOG_EDIT_LINK_TO_LIST', 'Link authors to their entries? (0=No,1=Yes)');
define('_MB_WEBLOG_EDIT_MAX_TITLE_LENGTH','Maximum title length');
define('_MB_WEBLOG_EDIT_MAX_CONTENTS_LENGTH','Maximum contents length("0" means show nothing. "-1" means show first part of entry. )');
define('_MB_WEBLOG_EDIT_DATE_FORMAT','Date format');
define('_MB_WEBLOG_EDIT_USE_AVATARS','Use avatars? (0=No,1=Yes)');
define('_MB_WEBLOG_EDIT_TYPE','Type (1=small,2=medium,3=large)');

define('_MB_WEBLOG_EDIT_NUMBER_OF_USERS', 'How many users to show ?');
define('_MB_WEBLOG_EDIT_NUMBER_OF_TRACKBACKS', 'How many trackbacks to show ?');
define('_MB_WEBLOG_EDIT_NUMBER_OF_COMMENTS', 'How many weBLog comments to show ?');
define('_MB_WEBLOG_EDIT_ORDER_BY', 'Order user by (0=last entry time,1=Total Reads)');
define('_MB_WEBLOG_EDIT_LINKS_MODULE', 'select integration link module<br />(now mylinks and weblinks is available)');
define('_MB_WEBLOG_EDIT_LINKS_NUMBER', 'Number of show links');
define('_MB_WEBLOG_EDIT_LINKS_ONLYPOST', 'Show when Blogger POST (0=No , 1=Yes)');
define('_MB_WEBLOG_EDIT_LINKS_SHOWDSC', 'Show descriptions of links (0=No , 1=Yes)');
define('_MB_WEBLOG_EDIT_CAT_ORDERBY', 'Order category');	// added for category list by hodaka
define('_MB_WEBLOG_EDIT_CAT_TITLE', 'TITLE');
define('_MB_WEBLOG_EDIT_CAT_ID', 'ID');
define('_MB_WEBLOG_EDIT_SHOW_FORBIDDEN_PICTURE', 'Show picture when the entry is not allowed to user. (0=not show , 1=show )');
define('_MB_WEBLOG_EDIT_SHOW_CONTENTS', 'Show blog contents. (0=not show , 1=show )');
define('_MB_WEBLOG_EDIT_TBCENTER_ENTRIES', 'trackback center block shows this number entries');
define('_MB_WEBLOG_EDIT_TBCENTER_CONTENTS_NUM', 'contents length of each entry');
define('_MB_WEBLOG_EDIT_TBCENTER_CATEGORY', 'Category name which you want to treat as trackback center');
// added for archive list by hodaka
define('_MB_WEBLOG_EDIT_ARCHIVE_NUMBER_PER_PAGE', 'How many months to show ?');
define('_MB_WEBLOG_LANG_SORT_ARCHIVE', 'Sort archives');
// added for calendar by hodaka
define('_MB_WEBLOG_LANG_PREVMONTH', '&laquo;');
define('_MB_WEBLOG_LANG_NEXTMONTH', '&raquo;');
define('_MB_WEBLOG_LANG_PREVYEAR', '&laquo;');
define('_MB_WEBLOG_LANG_NEXTYEAR', '&raquo;');
define('_MB_WEBLOG_LANG_PREVMONTH_TITLE', 'show previous month calendar');
define('_MB_WEBLOG_LANG_NEXTMONTH_TITLE', 'show next month calendar');
define('_MB_WEBLOG_LANG_PREVYEAR_TITLE', 'show previous year calendar');
define('_MB_WEBLOG_LANG_NEXTYEAR_TITLE', 'show next year calendar');
define('_MB_WEBLOG_LANG_THIS_MONTH_TITLE', 'display this month archive');
define('_MB_WEBLOG_LANG_SUNDAY', 'Son');
define('_MB_WEBLOG_LANG_MONDAY', 'Mon');
define('_MB_WEBLOG_LANG_TUESDAY', 'Die');
define('_MB_WEBLOG_LANG_WEDNESDAY', 'Mit');
define('_MB_WEBLOG_LANG_THURSDAY', 'Don');
define('_MB_WEBLOG_LANG_FRIDAY', 'Fre');
define('_MB_WEBLOG_LANG_SATURDAY', 'Sam');

define('_MB_WEBLOG_LANG_TITLE', 'Title');
define('_MB_WEBLOG_LANG_ENTRIES', 'Recent Entries');
define('_MB_WEBLOG_LANG_AUTHOR', 'Author');
define('_MB_WEBLOG_LANG_COMMENTS', 'Comments');
define('_MB_WEBLOG_LANG_POSTED', 'Posted');
define('_MB_WEBLOG_LANG_READS', 'Reads');
define('_MB_WEBLOG_LANG_MOREWEBLOGS', 'More weBLogs');
define('_MB_WEBLOG_LANG_TRACKBACKS', 'Trackbacks');
define('_MB_WEBLOG_LANG_TB_TITLE', 'Title of Trackback');
define('_MB_WEBLOG_LANG_TB_WEBLOGTITLE', 'Title of this entry');
define('_MB_WEBLOG_LANG_TB_BLOGNAME', 'Blog name');
define('_MB_WEBLOG_LANG_TB_POSTED', 'Trackback Date');
define('_MB_WEBLOG_LANG_COM_TITLE', 'Comment Title');
define('_MB_WEBLOG_LANG_COM_UNAME', 'User');
define('_MB_WEBLOG_LANG_COM_WEBLOGTITLE', 'Title of this entry');
define('_MB_WEBLOG_LANG_COM_POSTED', 'Comment Date');
define('_MB_WEBLOG_LANG_LINKS_FOR','Links for %s');
define('_MB_WEBLOG_LANG_LINKS_FOR_EVERYONE','Links');
define('_MB_WEBLOG_LANG_DENOTE_PERMIT','<span style=\'color:#FF0000;font-size:10px\'>*</span> denotes forbidden entry.');

define('_MB_WEBLOG_USERS_SORT_READS', 'Total Reads');
define('_MB_WEBLOG_USERS_SORT_UPDATE', 'Last Entry');

}
?>