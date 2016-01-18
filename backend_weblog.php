<?php
/*
 * $Id: backend_weblog.php 11979 2013-08-25 20:45:24Z beckmi $
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

require('header.php');
require_once(XOOPS_ROOT_PATH.'/class/template.php');
include_once(sprintf('%s/modules/%s/class/class.weblog.php', XOOPS_ROOT_PATH, $xoopsModule->dirname()));
include_once(sprintf('%s/modules/%s/include/encode_set.inc.php', XOOPS_ROOT_PATH, $xoopsModule->dirname()));

// privilege check
include_once(sprintf('%s/modules/%s/include/privilege.inc.php', XOOPS_ROOT_PATH, $xoopsModule->dirname())) ;
$isAdmin = ( isset($xoopsUser) && is_object($xoopsUser) ) ? $xoopsUser->isAdmin($xoopsModule->mid()) : false ;
if( ! $isAdmin && ! checkprivilege( "read_index" , $xoopsModule->dirname() ) ){
    exit();
}

// Include our module's language file
if ( file_exists(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname().'/language/'.$xoopsConfig['language'].'/main.php') ) {
  require_once(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname().'/language/'.$xoopsConfig['language'].'/main.php');
  require_once(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname().'/language/'.$xoopsConfig['language'].'/modinfo.php');
} else {
  require_once(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname().'/language/english/main.php');
  require_once(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname().'/language/english/modinfo.php');
}

// obtain GET/POST parameters
$user_id = !empty($_GET['user_id']) ? intval($_GET['user_id']) : 0;

// obtain class instances
$myts =& MyTextSanitizer::getInstance();
$weblog =& Weblog::getInstance();

// obtain configuration parameters
$max = $xoopsModuleConfig['rssmax'];

header ('Content-Type:text/xml; charset=utf-8');
$tpl = new XoopsTpl();
$tpl->xoops_setTemplateDir(XOOPS_ROOT_PATH.'/themes');
$tpl->xoops_setCaching(2);
$tpl->xoops_setCacheTime(10);

// Limit by user?
if ($user_id > 0) {
  $owner = new XoopsUser($user_id);
  $whosBLog = sprintf(_BL_WHOS_BLOG, $owner->getVar('uname','E'));
  $description = sprintf(_BL_ENTRIES_FOR, $owner->getVar('uname','E'));
  $link = sprintf('%s/modules/%s/index.php?user_id=%d', XOOPS_URL, $xoopsModule->dirname(), $user_id);
} else {
  // No, we must just be getting the last $max entries
  $whosBLog = $xoopsConfig['sitename'];
  $description = $xoopsConfig['slogan'];
  $link = XOOPS_URL.'/';
}

$format_timestamp = "D, d M Y H:i:s";
$sign = ( $xoopsConfig['default_TZ'] >= 0 ) ? "+" : "-" ;
$timezone = $sign . sprintf( "%02d00",abs($xoopsConfig['default_TZ']) );
$tpl->assign('channel_title', encoding_set(htmlspecialchars($whosBLog, ENT_QUOTES) , 'UTF-8' ));
$tpl->assign('channel_link', XOOPS_URL.'/');
$tpl->assign('channel_desc', encoding_set(htmlspecialchars($description, ENT_QUOTES) , 'UTF-8'));
$tpl->assign('channel_lastbuild', date($format_timestamp ,time()) . " " . $timezone );
//$tpl->assign('channel_webmaster', $xoopsConfig['adminmail']);
//$tpl->assign('channel_editor', $xoopsConfig['adminmail']);
$tpl->assign('channel_category', encoding_set(htmlspecialchars(_BL_BLOG, ENT_QUOTES) , 'UTF-8'));
$tpl->assign('channel_generator', encoding_set(htmlspecialchars($xoopsModule->name(), ENT_QUOTES) , 'UTF-8'));
$tpl->assign('channel_language', _LANGCODE);
$tpl->assign('image_url', $xoopsModuleConfig['imgurl']);
$dimention = ( ini_get('allow_url_fopen') ) ? getimagesize($xoopsModuleConfig['imgurl']) : "" ;
if (empty($dimention[0])) {
  $width = 88;
} else {
  $width = ($dimention[0] > 144) ? 144 : $dimention[0];
}
if (empty($dimention[1])) {
  $height = 31;
} else {
    $height = ($dimention[1] > 400) ? 400 : $dimention[1];
}
$tpl->assign('image_width', $width);
$tpl->assign('image_height', $height);

$results = $weblog->getEntries(0, $user_id, 0, $max);

foreach($results as $entryObject) {
  $item['title'] = $myts->htmlSpecialChars(encoding_set($entryObject->getVar('title', 'e') , 'UTF-8'));
  $item['link'] = sprintf('%s/modules/%s/details.php?blog_id=%d', XOOPS_URL, $xoopsModule->dirname(), $entryObject->getVar('blog_id'));
  $item['guid'] = sprintf('%s/modules/%s/details.php?blog_id=%d', XOOPS_URL, $xoopsModule->dirname(), $entryObject->getVar('blog_id'));
  $item['pubdate'] = date($format_timestamp , $entryObject->getVar('created')) . " " . $timezone ;
  $description = $entryObject->getVar('contents','s', $entryObject->getVar('blog_id') ,"rss" ) ;
  $item['description'] = encoding_set(htmlspecialchars(strip_tags($description), ENT_QUOTES) , 'UTF-8');
  $tpl->append('items', $item);
}

header('Content-type: application/xml; charset=utf-8');
$tpl->display('db:weblog_rss.html');
