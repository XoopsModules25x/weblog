<?php
/*
 * $Id: menu.php 11979 2013-08-25 20:45:24Z beckmi $
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

defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

$path = dirname(dirname(dirname(dirname(__FILE__))));
include_once $path . '/mainfile.php';

$dirname         = basename(dirname(dirname(__FILE__)));
$module_handler  = xoops_gethandler('module');
$module          = $module_handler->getByDirname($dirname);
$pathIcon32      = $module->getInfo('icons32');
$pathModuleAdmin = $module->getInfo('dirmoduleadmin');
$pathLanguage    = $path . $pathModuleAdmin;


if (!file_exists($fileinc = $pathLanguage . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $pathLanguage . '/language/english/main.php';
}

include_once $fileinc;

$adminmenu = array();
$i=0;
$adminmenu[$i]["title"] = _AM_MODULEADMIN_HOME;
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/home.png';
$i++;
$adminmenu[$i]['title'] = _MI_WEBLOG_CATMANAGER;
$adminmenu[$i]['link'] = "admin/catmanager.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/category.png';
$i++;
$adminmenu[$i]['title'] = _MI_WEBLOG_PRIVMANAGER;
$adminmenu[$i]['link'] = "admin/privmanager.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/manage.png';
$i++;
$adminmenu[$i]['title'] = _MI_WEBLOG_MYGROUPSADMIN ;
$adminmenu[$i]['link'] = "admin/groupperm_global.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/permissions.png';
$i++;
$adminmenu[$i]['title'] = _MI_WEBLOG_DBMANAGER;
$adminmenu[$i]['link'] = "admin/dbmanager.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/list.png';
//$i++;
//$adminmenu[$i]['title'] = _MI_WEBLOG_MYBLOCKSADMIN ;
//$adminmenu[$i]['link'] = "admin/myblocksadmin.php" ;
//$adminmenu[$i]["icon"]  = $pathIcon32 . '/block.png';
//$i++;
//$adminmenu[$i]['title'] = _MI_WEBLOG_TEMPLATE_MANEGER ;
//$adminmenu[$i]['link'] = "admin/index.php?op=templates" ;
//$adminmenu[$i]["icon"]  = $pathIcon32 . '/watermark.png';
$i++;
$adminmenu[$i]['title'] = _AM_MODULEADMIN_ABOUT;
$adminmenu[$i]["link"]  = "admin/about.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/about.png';
