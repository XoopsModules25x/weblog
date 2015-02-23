<?php
/**
 * $Id: details.php 11979 2013-08-25 20:45:24Z beckmi $
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

include('header.php');
include_once(sprintf('%s/modules/%s/class/class.weblog.php', XOOPS_ROOT_PATH, $xoopsModule->dirname()));
include_once(sprintf('%s/modules/%s/class/class.weblogcategories.php', XOOPS_ROOT_PATH, $xoopsModule->dirname()));
include_once(sprintf('%s/modules/%s/class/class.weblogtrackback.php', XOOPS_ROOT_PATH, $xoopsModule->dirname()));

// obtain GET/POST parameters
$blog_id = !empty($_GET['blog_id']) ? intval($_GET['blog_id']) : 0;

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

// privilege check
include_once(sprintf('%s/modules/%s/include/privilege.inc.php', XOOPS_ROOT_PATH, $xoopsModule->dirname())) ;
if( ! $isAdmin && ! checkprivilege( "read_detail" , $xoopsModule->dirname() ) ){
    redirect_header(sprintf('%s/index.php', XOOPS_URL ),
                    5, _BL_CANNOT_READ_SORRY);
    exit();
}

// specify template
$xoopsOption['template_main'] = 'weblog'.$mydirnumber . '_details.html';

// obtain class instances
$myts =& MyTextSanitizer::getInstance();
$weblog =& Weblog::getInstance();
$weblogcat =& WeblogCategories::getInstance();

$entryObject =& $weblog->getEntry($currentuid, $blog_id, 0, $useroffset);
if (!$entryObject) {
  redirect_header(sprintf('%s/modules/%s/index.php', XOOPS_URL, $xoopsModule->dirname()), 5,
                  _BL_PRIVATE_NOTEXIST_SORRY);
  exit();
}
// obtain trackback
$tb_operator =& Weblog_Trackback_Operator::getInstance() ;
$trackback_array = $tb_operator->handler->get( $entryObject->getVar('blog_id') ) ;
  $trackback_transmit = array() ;
  $trackback_recieved = array() ;
if( $trackback_array ){
	foreach( $trackback_array as $trackback_obj ){
		$trackback_data = array(
		                                  "tb_url"=> $trackback_obj->getVar('tb_url'),
										  "blog_name"=> $trackback_obj->getVar('blog_name'),
										  "title"=> $trackback_obj->getVar('title'),
										  "description"=> xoops_substr( $trackback_obj->getVar('description') , 0 , 120 ),
										  "link"=> $trackback_obj->getVar('link'),
										  "trackback_created"=> formatTimestamp($trackback_obj->getVar('trackback_created'), 'Y-m-d/H:i:s' , $xoopsConfig['default_TZ'])
										  ) ;
		if( $trackback_obj->getVar('direction') == "transmit" ){
			$trackback_transmit[] = $trackback_data ;
		}elseif( $trackback_obj->getVar('direction') == "recieved" ){
			$trackback_recieved[] = $trackback_data ;
		}
	}
}
// (ex. IIS)
if( (isset( $_SERVER['SERVER_SOFTWARE'] ) && preg_match("/Microsoft-IIS/i",$_SERVER['SERVER_SOFTWARE'])) || ! isset( $_SERVER['REQUEST_URI'] ) ){
	$delimiter = "?" ;
}else{
	$delimiter = "/" ;
}
$weblog_trackback_url = XOOPS_URL.'/modules/'.$xoopsModule->dirname().'/weblog-tb.php' . $delimiter . $blog_id  ;

// wellwine for cookie begins
// Read in cookie
$weblog_read = !empty($HTTP_COOKIE_VARS[WEBLOG_COOKIE_READ]) ? unserialize($HTTP_COOKIE_VARS[WEBLOG_COOKIE_READ]) : array();
// if cookie is not set for this blog, update view count and set cookie (and auther or Admin does not count)
$curtime = time();
if ( empty($weblog_read[$blog_id]) || $weblog_read[$blog_id] + $xoopsModuleConfig['expiration'] < $curtime ) {
    if( ! (is_object($xoopsUser) && ($isAdmin || $xoopsUser->getVar('uid')==$entryObject->getVar('user_id')) ) ){
        $reads = $weblog->incrementReads($blog_id);
        $entryObject->setVar('reads', $reads);
    }
}
// Update cookie
// FIXME: doesn't check if 4kB limit of cookie is exceeded!
$weblog_read[$blog_id] = $curtime;
setcookie(WEBLOG_COOKIE_READ, serialize($weblog_read), $curtime+$xoopsModuleConfig['expiration'], $weblogCookie['path'], $weblogCookie['domain'], $weblogCookie['secure']);
// wellwine for cookie ends

// Setup the user_id in the HTTP_GET so that the Notifications module will pick it up
$HTTP_GET_VARS['user_id'] = $entryObject->getVar('user_id');

// Retrieve his/her avatar
$use_avatar = 0;
$avatar_img = '';
$avatar_width = 0;
if ($xoopsModuleConfig['showavatar']) {
    $avatar = $entryObject->getVar('user_avatar', 'E');
    if (!empty($avatar) && $avatar != 'blank.gif') {
        $use_avatar = 1;
        $avatar_img = sprintf('%s/uploads/%s', XOOPS_URL, $avatar);
        $dimension = ( ini_get('allow_url_fopen') ) ? getimagesize(sprintf('%s/uploads/%s', XOOPS_ROOT_PATH, $avatar)) : "" ;
        $avatar_width = ( is_array($dimension) ) ? $dimension[0] : "" ;
    }
}

/*
// member or not change entry text
if( $currentuid ){
    $entry_contents = str_replace( MEMBER_ONLY_READ_DELIMETER , "" , $entryObject->getVar('contents', 's') ) ;
}else{
    $entry_contents = preg_replace("/(". MEMBER_ONLY_READ_DELIMETER . ").*$/m" ,
                                   "<br /><br /><a href='" . XOOPS_URL . WEBLOG_REGISTER_LEADING_PAGE ."'>" ._BL_MEMBER_ONLY_READ_MORE . "</a><br />\n" ,
                                   $entryObject->getVar('contents', 's') ) ;
}
// strip entry division separator
$entry_contents = str_replace( _BL_ENTRY_SEPARATOR_DELIMETER , "" , $entry_contents ) ;
*/
$entry_contents = $entryObject->getVar('contents','s' , $entryObject->getVar('blog_id') ,"details" ) ;

// create rdf
include_once(sprintf('%s/modules/%s/include/PEAR/Net/TrackBack.php', XOOPS_ROOT_PATH , $xoopsModule->dirname()));
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
  'identifier'=> sprintf('%s/modules/%s/details.php?blog_id=%d', XOOPS_URL , $xoopsModule->dirname() , $entryObject->getVar('blog_id') ) ,
  'description'=> strip_tags(addslashes(xoops_substr( $entry_contents , 0 , WEBLOG_RDF_DESCRIPTION_NUM ))) ,
  'creator'=> $entryObject->getVar('uname'),
  'date'=> date("Y-m-d\TH:i:s" , $entryObject->getVar('created')) . $TZ
) ;
$rdf_desc = "<!-- \n" . $net_trackback->toEmbededRDF($rdf_source_data) . "-->\n";

// Include the page header
include(XOOPS_ROOT_PATH.'/header.php');

$xoopsTpl->assign('use_avatar',$use_avatar);
$xoopsTpl->assign('avatar_img',$avatar_img);
$xoopsTpl->assign('avatar_width', $avatar_width);

$xoopsTpl->assign('title', $entryObject->getVar('title'));
$path = $weblogcat->getNicePathFromId($entryObject->getVar('cat_id'),
                                      sprintf('%s/modules/%s/index.php?',
                                              XOOPS_URL, $xoopsModule->dirname()));
//$path = $weblogcat->getCategoryPath($entryObject->getVar('cat_id'), ' > ');
$xoopsTpl->assign('category', $path);
$xoopsTpl->assign('lang_category', _BL_CATEGORY);
$xoopsTpl->assign('blog_id', $entryObject->getVar('blog_id'));
$xoopsTpl->assign('lang_author', _BL_AUTHOR);
$xoopsTpl->assign('created_date', formatTimestamp($entryObject->getVar('created'),
                                                  $xoopsModuleConfig['dateformat'],
                                                  $xoopsConfig['default_TZ']));
$xoopsTpl->assign('created_time', formatTimestamp($entryObject->getVar('created'),
                                                  $xoopsModuleConfig['timeformat'],
                                                  $xoopsConfig['default_TZ']));
$xoopsTpl->assign('uid', $entryObject->getVar('user_id'));
$xoopsTpl->assign('uname', $entryObject->getVar('uname'));
$xoopsTpl->assign('contents', $entry_contents);
$xoopsTpl->assign('private', $entryObject->getVar('private'));
$xoopsTpl->assign('profileUri', sprintf('%s/userinfo.php?uid=%d', XOOPS_URL, $entryObject->getVar('user_id')));
$xoopsTpl->assign('current_uid', $currentuid);
$xoopsTpl->assign('is_private', $entryObject->getVar('private') == 'Y' ? 1 : 0);
$xoopsTpl->assign('private', _BL_PRIVATE);
$xoopsTpl->assign('lang_comments', _BL_COMMENTS);
$xoopsTpl->assign('comments_num', $entryObject->getVar('comments'));
$xoopsTpl->assign('lang_reads', _BL_NUMBER_OF_READS);
$xoopsTpl->assign('reads', $entryObject->getVar('reads'));
$xoopsTpl->assign('lang_trackbacks', _BL_NUMBER_OF_TRACKBACKS);
$xoopsTpl->assign('trackbacks', $entryObject->getVar('trackbacks'));
$xoopsTpl->assign('lang_edit', _BL_EDIT);
$xoopsTpl->assign('read_users_blog',sprintf('<a href="index.php?user_id=%d">%s</a>',
                                            $entryObject->getVar('user_id'),
                                            sprintf(_BL_READ_USERS_BLOG, $entryObject->getVar('uname'))));
//$priv =& xoops_getmodulehandler('priv');
//$xoopsTpl->assign('provide_edit_link', ($isAdmin || ($currentuid==$entryObject->getVar('user_id') && $priv->hasPrivilege($currentUser)))?1:0);
$xoopsTpl->assign('provide_edit_link', ($isAdmin || ($currentuid==$entryObject->getVar('user_id') && checkprivilege("edit",$xoopsModule->dirname())))?1:0);
$xoopsTpl->assign('blog_id', $blog_id);
$xoopsTpl->assign('print_link', 'print.php?blog_id='.$blog_id);
$xoopsTpl->assign('lang_printerpage', _BL_PRINTERPAGE);
$xoopsTpl->assign('mail_link', 'mailto:?subject='.sprintf(_BL_INTARTICLE,$xoopsConfig['sitename']).'&amp;body='.sprintf(_BL_INTARTFOUND, $xoopsConfig['sitename']).':  '.XOOPS_URL.'/modules/'.$xoopsModule->dirname().'/details.php?blog_id='.$blog_id);
$xoopsTpl->assign('lang_sendstory', _BL_SENDSTORY);

$rss_feeder = sprintf('%s/modules/%s/backend_weblog.php', XOOPS_URL, $xoopsModule->dirname());
$rss_feeder .= sprintf('?user_id=%d', $entryObject->getVar('user_id'));
$rdf_feeder = sprintf('%s/modules/%s/weblog-rdf.php', XOOPS_URL, $xoopsModule->dirname());
$rdf_feeder .= sprintf('?user_id=%d', $entryObject->getVar('user_id'));
$xoopsTpl->assign('lang_rss', sprintf(_BL_RSS_RECENT_FOR, $entryObject->getVar('uname')));
$xoopsTpl->assign('rss_feeder', $rss_feeder);
$xoopsTpl->assign('rdf_feeder', $rdf_feeder);
$xoopsTpl->assign('rss_show', $xoopsModuleConfig['rssshow']);
$xoopsTpl->assign('rdf_desc', $rdf_desc);

$xoopsTpl->assign('page_title', $xoopsModule->name());
$xoopsTpl->assign('page_subtitle', sprintf(_BL_ENTRY_FOR, $entryObject->getVar('uname')));
$xoopsTpl->assign('xoops_weblogdir', $xoopsModule->dirname());

$prevnext_id = $weblog->getPrevNext($blog_id , $entryObject->getVar('created') , $currentuid , $isAdmin) ;
if(isset($prevnext_id['next'])){
    $xoopsTpl->assign('next_blogentry' , sprintf('%s/modules/%s/details.php?blog_id=%d', XOOPS_URL, $xoopsModule->dirname(), $prevnext_id['next'] ));
}else{
    $xoopsTpl->assign('next_blogentry' , '');
}
$xoopsTpl->assign('next', _BL_NEXT);
if(isset($prevnext_id['prev'])){
    $xoopsTpl->assign('prev_blogentry' , sprintf('%s/modules/%s/details.php?blog_id=%d', XOOPS_URL, $xoopsModule->dirname(), $prevnext_id['prev'] ));
}else{
    $xoopsTpl->assign('prev_blogentry' , '');
}
$xoopsTpl->assign('prev', _BL_PREV);
$xoopsTpl->assign('trackback_transmit' , $trackback_transmit ) ;
$xoopsTpl->assign('trackback_recieved' , $trackback_recieved ) ;
$xoopsTpl->assign('lang_trackback_anounce' , _BL_TRACKBACK_ANOUNCE ) ;
$xoopsTpl->assign('weblog_trackback_url' , $weblog_trackback_url) ;
$xoopsTpl->assign('lang_trackback_transmit' , _BL_TRACKBACK_TRANSMIT ) ;
$xoopsTpl->assign('lang_trackback_recieved' , _BL_TRACKBACK_RECIEVED ) ;
$xoopsTpl->assign('weblog_topurl' , sprintf('%s/modules/%s/index.php', XOOPS_URL, $xoopsModule->dirname()) ) ;

$weblog_head = sprintf('<link rel="stylesheet" type="text/css" media="all" href="%s/modules/%s/styles.css" />'."\n" , XOOPS_URL , $xoopsModule->dirname() );
$weblog_head .= sprintf('<link rel="alternate" type="application/rss+xml" title="RSS" href="%s" />'."\n" , $rss_feeder );
$weblog_head .= sprintf('<link rel="alternate" type="application/rdf+xml" title="RDF" href="%s" />'."\n" , $rdf_feeder );
$xoopsTpl->assign('xoops_module_header', $weblog_head , $entryObject->getVar('user_id') );

// Include the commenting module
require XOOPS_ROOT_PATH.'/include/comment_view.php';

include(XOOPS_ROOT_PATH.'/footer.php');
?>
