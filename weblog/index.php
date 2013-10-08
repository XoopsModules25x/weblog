<?php
/*
 * $Id: index.php 11979 2013-08-25 20:45:24Z beckmi $
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

include_once('header.php');
include_once(sprintf('%s/modules/%s/class/class.weblog.php', XOOPS_ROOT_PATH, $xoopsModule->dirname()));
include_once(sprintf('%s/modules/%s/class/class.weblogcategories.php', XOOPS_ROOT_PATH, $xoopsModule->dirname()));

function getAllChildrenCount($currentuid, $cat_id, $user_id=0) {
    $weblog =& Weblog::getInstance();
    $weblogcat =& WeblogCategories::getInstance();

    $count = $weblog->getCountByCategory($currentuid, $cat_id, $user_id);
    $arr = $weblogcat->getAllChildrenIds($cat_id);
    $size = count($arr);
    for ($i=0; $i<$size; $i++){
        $count += $weblog->getCountByCategory($currentuid, $arr[$i], $user_id);
    }
    return $count;
}

// obtain GET/POST parameters
$user_id = !empty($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$start = !empty($_GET['start']) ? intval($_GET['start']) : 0;
$cat_id = !empty($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;
$date = !empty($_GET['date']) ? intval($_GET['date']) : 0;	// unixtime (archive.php is YYYYMM)

// privilege check
include_once(sprintf('%s/modules/%s/include/privilege.inc.php', XOOPS_ROOT_PATH, $xoopsModule->dirname())) ;
$isAdmin = ( isset($xoopsUser) && is_object($xoopsUser) ) ? $xoopsUser->isAdmin($xoopsModule->mid()) : false ;
if( ! $isAdmin && ! checkprivilege( "read_index" , $xoopsModule->dirname() , $cat_id ) ){
    redirect_header(sprintf('%s/index.php', XOOPS_URL ),
                    5, _BL_CANNOT_READ_SORRY);
    exit();
}

// obtain configuration parameters
$perPage = $xoopsModuleConfig['numperpage'];

// Determine the user we are retrieving the blog entries for
if (is_object($xoopsUser)) {
    $currentUser = $xoopsUser;
	$useroffset = $xoopsUser->timezone() - $xoopsConfig['server_TZ'] ;
} else {
    $currentUser = new XoopsUser();
    $currentUser->setVar('uid', 0);
	$useroffset = $xoopsConfig['default_TZ'] - $xoopsConfig['server_TZ'] ; ;
}
$isAdmin = $currentUser->isAdmin($xoopsModule->mid());
$currentuid = $currentUser->getVar('uid');

// specify template
$xoopsOption['template_main'] = 'weblog'.$mydirnumber.'_entries.html';

// obtain class instances
$myts =& MyTextSanitizer::getInstance();
$weblog =& Weblog::getInstance();
$weblogcat =& WeblogCategories::getInstance();

if ($user_id > 0) {
  $blogOwner = new XoopsUser($user_id);
  $page_subtitle = sprintf(_BL_ENTRIES_FOR, $blogOwner->getVar('uname','E'));
  $page_rss = sprintf(_BL_RSS_RECENT_FOR, $blogOwner->getVar('uname', 'E'));
} else {
  $page_subtitle = _BL_MOST_RECENT;
  $page_rss = _BL_RSS_RECENT;
}

if ($xoopsModuleConfig['update_reads_when'] == 2 && $user_id > 0 ||
    $xoopsModuleConfig['update_reads_when'] == 3) {
  $updateReads = true;
} else {
  $updateReads = false;
}

// obtain row count
//$count = $weblog->getCountByUser($currentuid, $user_id);
//$count = $weblog->getCountByCategory($currentuid, $cat_id, $user_id);
$count = $weblog->getCountByDate($currentuid, $cat_id, $user_id , $date, $useroffset);

// obtain entries
//$result =& $weblog->getEntries($currentuid, $user_id, $start, $perPage);
if( empty($date) ){
	$weblog_result =& $weblog->getEntriesByCategory($currentuid, $cat_id, $user_id, $start, $perPage, 'DESC', $useroffset);
}else{
	$weblog_result =& $weblog->getEntriesForArchives($currentuid , $user_id , $date , $cat_id , $start , $perPage , 'DESC' , $useroffset ) ;
}
$catresult =& $weblogcat->getCategoriesByParent($cat_id);

// Include the page header
include_once(XOOPS_ROOT_PATH.'/header.php');

// Category navigation
if( $xoopsModuleConfig['show_category_list'] ){
	$category_navi = array() ;
	$cat_array = $weblogcat->getChildTreeArray(0, 'cat_title');
	$cat_root_num = 0 ;	// flag for </tr>
	foreach ( $cat_array as $cat ) {
		$category = array();
		$category['cat_id'] = $cat['cat_id'];
		$category['cat_title'] = $myts->htmlSpecialChars($cat['cat_title']);
		$category['count'] = $weblog->getCountByCategory($currentuid, $cat['cat_id'], $user_id);
		$prefix_num = intval(strlen($cat['prefix']));
		$category['prefix_num'] = $prefix_num ;	// flag for </td>
		if( $prefix_num == 1 ){
			$category['cat_root_num'] = $cat_root_num ;
			$cat_root_num++ ;
			$category['margin'] = "8px 4px 0px 0px" ;
		}elseif( $prefix_num == 2 ){
			$category['margin'] = "4px 4px 0px 0px" ;
		}else{
			$category['margin'] = "0px 0px 0px 4px" ;
		}
		$prefix_space = "" ;
		$now = ($cat_id == $cat['cat_id'])? "_now" : "" ;
		if( $prefix_num <= 3 ){
			$category['prefix'] = sprintf("<img src='%s/modules/%s/images/cat%d%s.gif'>" , XOOPS_URL , $mydirname , $prefix_num , $now) ;
		}else{
			for( $i=3; $i<$prefix_num; $i++ ){
				$prefix_space .= sprintf("<img src='%s/modules/%s/images/cat_space.gif'>" , XOOPS_URL , $mydirname )  ;
			}
				$category['prefix'] = $prefix_space . sprintf("<img src='%s/modules/%s/images/cat%d%s.gif'>" , XOOPS_URL , $mydirname , '3' , $now ) ;
		}
		$category_navi[] = $category;
	}
	$xoopsTpl->assign('category_col', $xoopsModuleConfig['category_col']);
	$xoopsTpl->assign('category_navi', $category_navi);
}
$xoopsTpl->assign('show_category_list', $xoopsModuleConfig['show_category_list']);


// add page navigator if # of entries bigger than # per page
$totalPages = ceil($count / $perPage);
if ( $count > $perPage ) {
  $uri='';
  if ($user_id>0) {
    $uri .= 'user_id='.$user_id;
  }
  if ($date>0) {
    if (strlen($uri)>0) {
      $uri .= '&';
    }
    $uri .= 'date='.$date;
  }
  if ($cat_id>0) {
    if (strlen($uri)>0) {
      $uri .= '&';
    }
    $uri .= 'cat_id='.$cat_id;
  }
  include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
  $nav = new XoopsPageNav($count, $perPage, $start, "start", $uri);
  $xoopsTpl->assign('pagination', $nav->renderNav());
} else {
  $xoopsTpl->assign('pagination', '');
}

foreach ($weblog_result as $entryObject) {
  $entry = array();
  if ($updateReads==true) {
    $curtime = time();
    $blog_id = $entryObject->getVar('blog_id');
    // wellwine for cookie begins
    // Read in cookie
    $weblog_read = !empty($HTTP_COOKIE_VARS[WEBLOG_COOKIE_READ]) ? unserialize($HTTP_COOKIE_VARS[WEBLOG_COOKIE_READ]) : array();
    // if cookie is not set for this blog, update view count and set cookie
    if ( empty($weblog_read[$blog_id]) || $weblog_read[$blog_id] + $xoopsModuleConfig['expiration'] < $curtime ) {
        $reads = $weblog->incrementReads($blog_id);
        $entryObject->setVar('reads', $reads);
    }
    // Update cookie
    // FIXME: doesn't check if 4kB limit of cookie is exceeded!
    $weblog_read[$blog_id] = $curtime;
    setcookie(WEBLOG_COOKIE_READ, serialize($weblog_read), $curtime+$xoopsModuleConfig['expiration'], $weblogCookie['path'], $weblogCookie['domain'], $weblogCookie['secure']);
    // wellwine for cookie ends
  }

  // create rdf
  include_once(sprintf('%s/modules/%s/include/PEAR/Net/TrackBack.php', XOOPS_ROOT_PATH , $xoopsModule->dirname()));
  $weblog_trackback_url = XOOPS_URL.'/modules/'.$xoopsModule->dirname().'/weblog-tb.php/'. $entryObject->getVar('blog_id')  ;
  $net_trackback = new Net_TrackBack() ;
  if( $xoopsConfig['default_TZ'] < 0 ){
    $TZ = "-" . sprintf('%02d' , abs($xoopsConfig['default_TZ']) ) . ':00';
  }else{
    $TZ = "+" . sprintf('%02d' , $xoopsConfig['default_TZ'] ) . ':00';
  }
  $rdf_source_data = array(
    'about'=> sprintf('%s/modules/%s/details.php?blog_id=%d', XOOPS_URL , $xoopsModule->dirname() , $entryObject->getVar('blog_id') ) ,
    'ping'=> $weblog_trackback_url ,
    'title'=> addslashes($entryObject->getVar('title')) ,
    'identifier'=> sprintf('%s/modules/%s/details.php?blog_id=%d', XOOPS_URL , $xoopsModule->dirname() , $entryObject->getVar('blog_id') ),
    'description'=> strip_tags(addslashes(xoops_substr( $entryObject->getvar('contents','s') , 0 , WEBLOG_RDF_DESCRIPTION_NUM ) ) ),
    'creator'=> $entryObject->getVar('uname'),
    'date'=> date("Y-m-d\TH:i:s" , $entryObject->getVar('created')) . $TZ
  ) ;
  $rdf_desc = "<!-- \n" . $net_trackback->toEmbededRDF($rdf_source_data) . "-->\n";

  // Retrieve his/her avatar
  $entry['use_avatar'] = 0;
  $entry['avatar_img'] = '';
  $entry['avatar_width'] = 0;
  if ($xoopsModuleConfig['showavatar']) {
    $avatar = $entryObject->getVar('user_avatar', 'E');
    if (!empty($avatar) && $avatar != 'blank.gif') {
      $entry['use_avatar'] = 1;
      $entry['avatar_img'] = sprintf('%s/uploads/%s', XOOPS_URL, $avatar);
    }
  }

  $entry['created_date'] = formatTimestamp($entryObject->getVar('created'),
                                           $xoopsModuleConfig['dateformat'],
                                           $xoopsConfig['default_TZ']);
  $entry['created_time'] = formatTimestamp($entryObject->getVar('created'),
                                           $xoopsModuleConfig['timeformat'],
                                           $xoopsConfig['default_TZ']);
  $entry['uid'] = $entryObject->getVar('user_id');
  $entry['uname'] = $entryObject->getVar('uname');
  $entry['title'] = $entryObject->getVar('title');
  $entry['contents'] = $entryObject->getVar('contents','s' , $entryObject->getVar('blog_id') , "index");
  $path = $weblogcat->getNicePathFromId($entryObject->getVar('cat_id'),
                                        sprintf('%s/modules/%s/index.php?user_id=%d',
                                                XOOPS_URL, $xoopsModule->dirname(),
                                                $user_id));
  $entry['category'] = $path;
  $entry['lang_category'] = _BL_CATEGORY;
  $entry['lang_author'] = _BL_AUTHOR;
  $entry['profileUri'] = sprintf('%s/userinfo.php?uid=%d', XOOPS_URL, $entryObject->getVar('user_id'));
  $entry['blog_id'] = $entryObject->getVar('blog_id');
  $entry['comments'] = $entryObject->getVar('comments');
  $entry['comlink'] = sprintf('%s/modules/%s/details.php?blog_id=%d#comment',
                              XOOPS_URL, $xoopsModule->dirname(), $entryObject->getVar('blog_id'));
  $entry['lang_comments'] = _BL_COMMENTS;

  $entry['is_private'] = ($entryObject->getVar('private')=='Y')?1:0;
  $entry['private'] = _BL_PRIVATE;
  $entry['read_users_blog'] = sprintf('<a href="index.php?user_id=%d">%s</a>',
                                      $entryObject->getVar('user_id'), sprintf(_BL_READ_USERS_BLOG, $entryObject->getVar('uname')));
//  $priv =& xoops_getmodulehandler('priv');
//  $entry['provide_edit_link'] = ($isAdmin || ($currentuid==$entryObject->getVar('user_id') && $priv->hasPrivilege($currentUser)))?1:0;
  $entry['provide_edit_link'] = ($isAdmin || ($currentuid==$entryObject->getVar('user_id') && checkprivilege("edit",$xoopsModule->dirname())))?1:0;
  $entry['lang_reads'] = _BL_NUMBER_OF_READS;
  $entry['reads'] = $entryObject->getVar('reads');
  $entry['trackbacks'] = $entryObject->getVar('trackbacks');
  $entry['tracklink'] = sprintf('%s/modules/%s/details.php?blog_id=%d#trackback',
                              XOOPS_URL, $xoopsModule->dirname(), $entryObject->getVar('blog_id'));
  $entry['lang_trackbacks'] = _BL_NUMBER_OF_TRACKBACKS;
  $entry['rdf_desc'] = $rdf_desc ;
  $xoopsTpl->append('entries', $entry);
}

// wellwine
$rss = sprintf('%s/modules/%s/backend_weblog.php', XOOPS_URL, $xoopsModule->dirname());
$rdf = sprintf('%s/modules/%s/weblog-rdf.php', XOOPS_URL, $xoopsModule->dirname());
if ($user_id>0) {
    $rss .= sprintf('?user_id=%d', $user_id);
    $rdf .= sprintf('?user_id=%d', $user_id);
}
$xoopsTpl->assign('lang_rss', $page_rss);
$xoopsTpl->assign('rss_feeder', $rss);
$xoopsTpl->assign('rdf_feeder', $rdf);
$xoopsTpl->assign('rss_show', $xoopsModuleConfig['rssshow']);

$xoopsTpl->assign('page_title', $xoopsModule->name());
$xoopsTpl->assign('page_subtitle', $page_subtitle);
$xoopsTpl->assign('xoops_weblogdir', $xoopsModule->dirname());
//$xoopsTpl->assign('uri', $_SERVER['QUERY_STRING']);

// Language assigns
$xoopsTpl->assign('lang_categories', _BL_CATEGORIES);
$xoopsTpl->assign('lang_edit', _BL_EDIT);
$xoopsTpl->assign('lang_recententries', _BL_MOST_RECENT);
$cat_url = sprintf('<a href=\'index.php?user_id=%d\'>%s</a>&nbsp;:&nbsp;',
                   $user_id, _BL_MAIN);
$cat_url .= $weblogcat->getNicePathFromId($cat_id, sprintf('%s/modules/%s/index.php?user_id=%d',
                                                           XOOPS_URL, $xoopsModule->dirname(),
                                                           $user_id));
$xoopsTpl->assign('cat_url', $cat_url);
//$xoopsTpl->assign('lang_blog', _BL_BLOG);
//$xoopsTpl->assign('lang_delete', _BL_DELETE);
$weblog_head = sprintf('<link rel="stylesheet" type="text/css" media="all" href="%s/modules/%s/styles.css" />'."\n" , XOOPS_URL , $xoopsModule->dirname() );
$weblog_head .= sprintf('<link rel="alternate" type="application/rss+xml" title="RSS" href="%s" />'."\n" , $rss );
$weblog_head .= sprintf('<link rel="alternate" type="application/rdf+xml" title="RDF" href="%s" />'."\n" , $rdf );
$xoopsTpl->assign('xoops_module_header', $weblog_head , $user_id );

// Include the page footer
include_once(XOOPS_ROOT_PATH.'/footer.php');
?>
