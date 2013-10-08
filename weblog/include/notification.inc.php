<?php
/*
 * $Id: notification.inc.php,v 1.4 2005/05/06 18:53:29 tohokuaiki Exp $
 * Copyright (c) 2003 by Jeremy N. Cowgar <jc@cowgar.com>
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


if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

$mydirname = basename( dirname( dirname( __FILE__ ) ) );
if( ! preg_match( '/^(\D+)(\d*)$/' , $mydirname , $regs ) ) die ( "invalid dirname: " . htmlspecialchars( $mydirname ) ) ;
$mydirnumber = $regs[2] === '' ? '' : intval( $regs[2] ) ;


eval('
function blog'.$mydirnumber.'_info($category, $item_id)
{
	return blog_info_base( "'.$mydirname.'", $category, $item_id ) ;
}
') ;


//function blog'.$mydirnumber.'_info_base( $mydirname, $category, $item_id )
function blog_info_base( $mydirname, $category, $item_id )
{
  global $xoopsModule, $xoopsModuleConfig, $xoopsConfig;
  
  if (empty($xoopsModule) || $xoopsModule->getVar("dirname") != $mydirname) {	
    $module_handler =& xoops_gethandler("module");
    $module =& $module_handler->getByDirname($mydirname);
    $config_handler =& xoops_gethandler("config");
    $config =& $config_handler->getConfigsByCat(0,$module->getVar("mid"));
  } else {
    $module =& $xoopsModule;
    $config =& $xoopsModuleConfig;
  }
  
  include_once XOOPS_ROOT_PATH . "/modules/" . $mydirname . "/language/" . $xoopsConfig["language"] . "/main.php";
  
  if ($category=="global") {
    $item["name"] = "";
    $item["url"] = "";
    return $item;
  }
  
  global $xoopsDB;
  if ($category=="blog") {
    // Assume we have a valid forum id
    $sql = "SELECT uname FROM " . $xoopsDB->prefix("users") . " WHERE uid = ".$item_id;
    $result = $xoopsDB->query($sql); // TODO: error check
    $result_array = $xoopsDB->fetchArray($result);
    $item["name"] = sprintf(_BL_WHOS_BLOG, $result_array["uname"]);
    $item["url"] = XOOPS_URL . "/modules/" . $module->getVar("dirname") . "/index.php?user_id=" . $item_id;
    return $item;
  } else if ($category=="blog_entry") {
    // Assume we have a valid forum id
    $sql = "SELECT title FROM " . $xoopsDB->prefix($mydirname) . " WHERE blog_id = ".$item_id;
    $result = $xoopsDB->query($sql); // TODO: error check
    $result_array = $xoopsDB->fetchArray($result);
    $item["name"] = $result_array["title"];
    $item["url"] = XOOPS_URL . "/modules/" . $mydirname . "/details.php?blog_id=" . $item_id;
    return $item;
  }
}

?>
