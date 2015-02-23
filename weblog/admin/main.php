<?php
/*
 * $Id: main.php 11979 2013-08-25 20:45:24Z beckmi $
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
include('../../../mainfile.php');
include(sprintf('%s/include/cp_header.php', XOOPS_ROOT_PATH));

$op = '';

if (isset($HTTP_POST_VARS)) {
    foreach ($HTTP_POST_VARS as $k => $v) {
        ${$k} = $v;
    }
}

if (isset($HTTP_GET_VARS['op'])) {
    $op = $HTTP_GET_VARS['op'];
    if (isset($HTTP_GET_VARS['storyid'])) {
        $storyid = intval($HTTP_GET_VARS['storyid']);
    }
}

function adminItem($url, $title, $desc='') {
    $item = "<tr align='left'>\r\n";
    $item .= "<td class='odd' align='left'>\r\n";
    $item .= sprintf("<a href='%s'>%s</a>\r\n", $url, $title);
    $item .= "</td>\r\n";
    $item .= sprintf("<td class='odd' align='left'>%s</td>\r\n", $desc);
    $item .= "</tr>\r\n";

    return $item;
}

switch($op){

	case 'templates' :
	header('Location: '.XOOPS_URL.'/modules/system/admin.php?fct=tplsets&op=listtpl&tplset='.$xoopsConfig['template_set'].'&moddir='.$xoopsModule->dirname().'');
		exit();
		break ;
    default:
    xoops_cp_header();
    echo sprintf('<h4>%s</h4>', _AM_WEBLOG_CONFIG);

    echo "<table width='100%' border='0' cellspacing='1' class='outer'>";
    echo "<tr><td class='odd'><table width='100%' border='0' cellspacing='0'>";

    echo adminItem(sprintf("%s/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=%d",
                           XOOPS_URL, $xoopsModule->getVar('mid')),
                   _AM_WEBLOG_PREFERENCES, _AM_WEBLOG_PREFERENCESDSC);

    echo adminItem(sprintf("%s/modules/%s/admin/catmanager.php",
                           XOOPS_URL, $xoopsModule->dirname()),
                   _AM_WEBLOG_CATMANAGER, _AM_WEBLOG_CATMANAGERDSC);

    echo adminItem(sprintf("%s/modules/%s/admin/privmanager.php",
                           XOOPS_URL, $xoopsModule->dirname()),
                   _AM_WEBLOG_PRIVMANAGER_WEBLOG, _AM_WEBLOG_PRIVMANAGER_WEBLOG_DSC);

    echo adminItem(sprintf("%s/modules/%s/admin/groupperm_global.php",
                           XOOPS_URL, $xoopsModule->dirname()),
                   _AM_WEBLOG_PRIVMANAGER_XOOPS, _AM_WEBLOG_PRIVMANAGER_XOOPS_DSC);

    echo adminItem(sprintf("%s/modules/%s/admin/dbmanager.php",
                           XOOPS_URL, $xoopsModule->dirname()),
                   _AM_WEBLOG_DBMANAGER, _AM_WEBLOG_DBMANAGERDSC);

    echo adminItem(sprintf("%s/modules/%s/admin/myblocksadmin.php",
                           XOOPS_URL, $xoopsModule->dirname()),
                   _AM_WEBLOG_MYBLOCKSADMIN, _AM_WEBLOG_MYBLOCKSADMINDSC);

    echo adminItem(sprintf("%s/modules/system/admin.php?fct=tplsets&op=listtpl&tplset=%s&moddir=%s",
                           XOOPS_URL, $xoopsConfig['template_set'] , $xoopsModule->dirname()),
                   _AM_WEBLOG_TEMPLATE_MANEGER, _AM_WEBLOG_TEMPLATE_MANEGERDSC);

    echo "</table></td></tr>";
    echo "</table>";

    xoops_cp_footer();
    break;
}
?>