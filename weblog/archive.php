<?php
/**
 * $Id: archive.php 11979 2013-08-25 20:45:24Z beckmi $
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

include ('header.php');
include_once(sprintf('%s/modules/%s/class/class.weblog.php', XOOPS_ROOT_PATH, $xoopsModule->dirname()));
include_once(sprintf('%s/modules/%s/class/class.weblogcategories.php', XOOPS_ROOT_PATH, $xoopsModule->dirname()));
if (file_exists(XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/calendar.php')) {
  require_once(XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/calendar.php');
} else {
  require_once(XOOPS_ROOT_PATH.'/language/english/calendar.php');
}
// xoopsform
  include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";


$months_arr = array(1 => _CAL_JANUARY, 2 => _CAL_FEBRUARY, 3 => _CAL_MARCH, 4 => _CAL_APRIL, 5 => _CAL_MAY, 6 => _CAL_JUNE,
    7 => _CAL_JULY, 8 => _CAL_AUGUST, 9 => _CAL_SEPTEMBER, 10 => _CAL_OCTOBER, 11 => _CAL_NOVEMBER, 12 => _CAL_DECEMBER);

// obtain GET parameters
$user_id = !empty($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$date = isset($_GET['date']) ? intval($_GET['date']): 0;	// YYYYMM (index.php is unixtime)
$cat_id = isset($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;
// $fromyear = isset($_GET['year']) ? intval($_GET['year']): (($date > 0)? intval(substr($date, 0, 4)) : 0);
// $frommonth = isset($_GET['month']) ? intval($_GET['month']): (($date > 0 )? intval(substr($date, 4, 2)) : 0);
// $fromday = isset($_GET['day']) ? intval($_GET['day']): 0;
// $mode = isset($_GET['mode']) ? htmlspecialchars($_GET['mode']) : (($fromyear + $frommonth > 0)? "date" : "");
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$numperpage = isset($_GET['numperpage'])? intval($_GET['numperpage']) : 30;


// Determine the user we are retrieving the blog entries for
if (!empty($xoopsUser)) {
    $isAdmin = $xoopsUser->isAdmin($xoopsModule->mid());
    $currentuid = $xoopsUser->getVar('uid','E');
    $useroffset = $xoopsUser->timezone() - $xoopsConfig['default_TZ'];
} else {
    $isAdmin = false;
    $currentuid = 0;
    $useroffset = $xoopsConfig['default_TZ'];
}

// For specific author archive
if ($user_id > 0) {
    $blogOwner=new XoopsUser($user_id);
    $page_subtitle = sprintf(_BL_ARCHIVE_FOR, $blogOwner->getVar('uname','E'));
} else {
    $page_subtitle = _BL_ARCHIVE;
}

// specify template
$xoopsOption['template_main'] = 'weblog'.$mydirnumber . '_archive.html';

// obtain class instances
$weblog =& Weblog::getInstance();
$weblogcat =& WeblogCategories::getInstance();

// construct the page
// Include the page header
include(XOOPS_ROOT_PATH.'/header.php');
//$xoopsTpl->xoops_setDebugging(true);	// smarty debug

/**
 * make category select box
 */

$cat_array = $weblogcat->getChildTreeArray();
//$cat_array = $weblogcat->getMySelectBox(0, 0, "selectcat");

$catselbox = array();
$catbox = array();
$catselbox[0]['prefix'] = "";
$catselbox[0]['cat_id'] = 0;
$catselbox[0]['cat_title'] = _BL_SELECT_ALL;
$catselbox[0]['selected'] = ($cat_id == 0)? " selected" : "";
$i = 1;

$myts =& MyTextSanitizer::getInstance();
foreach ( $cat_array as $cat ) {
	$catselbox[$i]['prefix'] = substr(str_replace(".","--",$cat['prefix']), 2);
	$catselbox[$i]['cat_id'] = $myts->htmlSpecialChars($cat['cat_id']);
	$catselbox[$i]['cat_title'] = $myts->htmlSpecialChars($cat['cat_id']) == 0 ? "_BL_SELECT_ALLCATEGORY" : $myts->htmlSpecialChars($cat['cat_title']);
	$catselbox[$i]['selected'] = ($cat_id == $cat['cat_id'] )? " selected" : "";
	$i++;
	$catbox[$cat['cat_id']] = $myts->htmlSpecialChars($cat['cat_title']);
}

$xoopsTpl->assign("catselbox", $catselbox);






/**
 * make user_id select box
 */
$blogger_id = array('' => _BL_SELECT_ALL) ;
$sql = sprintf('SELECT user_id,uname from %s,%s where uid=user_id and private=\'N\' group by user_id ' ,
		$xoopsDB->prefix($mydirname) , $xoopsDB->prefix('users')) ;
if( $uid_result = $xoopsDB->query($sql) ){
	while( $uid_uname = $xoopsDB->fetchArray($uid_result) ){
		$blogger_id[$uid_uname['user_id']] = $uid_uname['uname'] ;
	}
}
$selbox_blogger = new XoopsFormSelect('', 'user_id', $user_id ) ;
$selbox_blogger->addOptionArray($blogger_id) ;
$xoopsTpl->assign("uidselbox", $selbox_blogger->render());


/**
 * make month select box
 */
$sql = sprintf('SELECT created from %s group by left(from_unixtime(created+%d)+0,6) order by created DESC' ,
				$xoopsDB->prefix($mydirname) , $useroffset*3600 , '%Y%') ;
$month_result = $xoopsDB->query($sql) ;
$last_year = 100000 ;
$months = array('' => _BL_SELECT_ALL) ;
while( $created_example = $xoopsDB->fetchArray($month_result) ){
	list( $this_year , $this_month ) = explode(':' , date('Y:m',$created_example['created'])) ;
	if( $last_year > $this_year ){
		$last_year = $this_year ;
		$months[$this_year.'00'] = $this_year . '&nbsp;' . _BL_SELECT_ALL ;
	}
	$months[$this_year.$this_month] = $this_year . '&nbsp;' . $months_arr[intval($this_month)] ;
}
$selbox_month = new XoopsFormSelect('', 'date', $date ) ;
$selbox_month->addOptionArray($months) ;
$xoopsTpl->assign("dateselbox", $selbox_month->render());


/*****************************
 * Get Entries
 *****************************/
$results = $weblog->getEntriesForArchives($currentuid, $user_id, $date, $cat_id, $start , $numperpage, 'DESC' , $useroffset);
$count = $weblog->getCountByDate($currentuid, $cat_id, $user_id, $date, $useroffset) ;
if (count($results) > 0) {
    $xoopsTpl->assign('show_archives', true);
    $xoopsTpl->assign(makeTplVars());

} else {
	$xoopsTpl->assign('show_archives', false);
	$xoopsTpl->assign('lang_noarchives', _BL_NOARCHIVEDESC);
}

$xoopsTpl->assign('page_title', $xoopsModule->name());
$xoopsTpl->assign('page_subtitle', $page_subtitle);
$xoopsTpl->assign('xoops_weblogdir', $xoopsModule->dirname());

include XOOPS_ROOT_PATH."/footer.php";

function makeTplVars() {
	global $results, $count, $numperpage, $start;
	global $user_id, $cat_id, $mode, $catbox, $date, $useroffset;
	global $xoopsModuleConfig;

	$blog['show_blogs'] = true;
	$blog['lang_title'] = _BL_TITLE;
	$blog['lang_date'] = _BL_POSTED;
	$blog['lang_author'] = _BL_AUTHOR;
	$blog['lang_reads'] = _BL_READS;
	$blog['lang_comments'] = _BL_COMMENTS;
	$blog['lang_trackbacks'] = _BL_TRACKBACKS;
	$blog['lang_contents'] = _BL_CONTENTS;
	$blog['lang_cat_title'] = _BL_CATEGORY;
	$blog['lang_show'] = _BL_SHOW;

	$blog['entries'] = array();
    foreach($results as $entryObject) {
		$entry = array();
		$entry['title'] = sprintf('<a href=\'detail.php?blog_id=%d\'>%s</a>',
		$entryObject->getVar('blog_id'),
		$entryObject->getVar('title','s'));
		$entry['reads'] = $entryObject->getVar('reads');
		$entry['date'] = formatTimestamp($entryObject->getVar('created'), $xoopsModuleConfig['dateformat'], $useroffset);
		$entry['time'] = formatTimestamp($entryObject->getVar('created'), $xoopsModuleConfig['timeformat'], $useroffset);
		$entry['uid'] = $entryObject->getVar('user_id');
		$entry['uname'] = $entryObject->getVar('uname');
		$entry['title'] = $entryObject->getVar('title');
		$entry['profileUrl'] = sprintf('%s/userinfo.php?uid=%d', XOOPS_URL, $entryObject->getVar('user_id'));
		$entry['blog_id'] = $entryObject->getVar('blog_id');
		$entry['cat_title'] = (isset($catbox[$entryObject->getVar('cat_id')])) ? $catbox[$entryObject->getVar('cat_id')] : '' ;
		$entry['contents'] = xoops_substr(strip_tags($entryObject->getVar('contents','s',$entryObject->getVar('blog_id'),'index')), 0, 40);
		$entry['comments'] = $entryObject->getVar('comments');
		$entry['trackbacks'] = $entryObject->getVar('trackbacks');
		$blog['entries'][] = $entry;
	}
	$blog['lang_blogtotal'] = sprintf(_BL_THEREAREINTOTAL, $count);

	// add page navigator if entries > per page
	if ( $count > $numperpage ) {
  		$uri='';
  		if ($user_id>0) {
    		$uri .= 'user_id='.$user_id;
  		}
		if( intval($date) > 0 ){
			$uri .= '&date='. $date ;
		}
		if( intval($cat_id) > 0 ){
			$uri .= '&cat_id='. $cat_id ;
		}
		include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
		$nav = new XoopsPageNav($count, $numperpage, $start, "start", $uri);
		$blog['pagenavi'] = $nav->renderNav();
	} else {
		$blog['pagenavi'] = '';
	}

	return $blog;
}


?>
