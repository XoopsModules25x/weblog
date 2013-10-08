<?php
/**
 * $Id: print.php,v 1.3 2005/11/06 15:56:30 tohokuaiki Exp $
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

// Include our module's language file
if ( file_exists(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname().'/language/'.$xoopsConfig['language'].'/main.php') ) {
  require_once(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname().'/language/'.$xoopsConfig['language'].'/main.php');
  require_once(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname().'/language/'.$xoopsConfig['language'].'/modinfo.php');
} else {
  require_once(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname().'/language/english/main.php');
  require_once(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname().'/language/english/modinfo.php');
}

// privilege check
include_once(sprintf('%s/modules/%s/include/privilege.inc.php', XOOPS_ROOT_PATH, $xoopsModule->dirname())) ;
if(  ! checkprivilege( "read_detail" , $xoopsModule->dirname() ) ){
    redirect_header(sprintf('%s/index.php', XOOPS_URL ),
                    5, _BL_CANNOT_READ_SORRY);
    exit();
}

// obtain GET/POST parameters
$blog_id = isset($HTTP_GET_VARS['blog_id']) ? intval($HTTP_GET_VARS['blog_id']) : 0;
if ( empty($blog_id) ) {
    redirect_header("index.php");
}

// obtain class instances
$myts =& MyTextSanitizer::getInstance();
$weblog =& Weblog::getInstance();

// Determine the user we are retrieving the blog entries for
$currentuid = !empty($xoopsUser) ? $xoopsUser->getVar('uid','E') : 0;

header ('Content-Type:text/html; charset='._CHARSET);
$tpl = new XoopsTpl();
$tpl->xoops_setTemplateDir(XOOPS_ROOT_PATH.'/themes');
$tpl->xoops_setCaching(2);
$tpl->xoops_setCacheTime(10);

$entryObject = $weblog->getEntry($currentuid, $blog_id);

$tpl->assign('charset', _CHARSET);
$tpl->assign('author', $entryObject->getVar('uname'));
$tpl->assign('sitename', $xoopsConfig['sitename']);
$tpl->assign('description', "");
$tpl->assign('generator', _MI_WEBLOG_NAME);
$tpl->assign('image_url', $xoopsModuleConfig['imgurl']);
$tpl->assign('datetime', formatTimestamp($entryObject->getVar('created'), "l"));
$tpl->assign('blog_title', $entryObject->getVar('title'));
$tpl->assign('lang_date', _BL_POSTED);
$tpl->assign('lang_author', _BL_AUTHOR);
$tpl->assign('lang_comesfrom', sprintf(_BL_COMESFROM, $xoopsConfig['sitename']));
$tpl->assign('contents', $myts->displayTarea($entryObject->getVar('contents'),1,1,1,1,1,1,1,1,1));
$tpl->assign('site_url', XOOPS_URL);
$tpl->assign('lang_parmalink', _BL_PARMALINK);
$tpl->assign('parmalink', sprintf('%s/modules/%s/details.php?blog_id=%d', XOOPS_URL, $xoopsModule->dirname(), $blog_id));

$tpl->display('db:weblog_print.html');
?>