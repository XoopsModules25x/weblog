<?php
/*
 * $Id: admin.inc.php,v 1.1 2003/08/29 14:10:29 wellwine Exp $
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

function indexLink() {
    global $xoopsModule;

    return sprintf('<a href=\'%s/modules/%s/admin/index.php\'>%s</a>',
                   XOOPS_URL, $xoopsModule->dirname(), _AM_WEBLOG_CONFIG);
}

function tableRow($title, $description, $action='', $hidden=array()) {
    $html = sprintf("<tr valign='top' align='left'><td class='head'>%s<br /><br /><span style='font-weight:normal;'>%s</span></td>",
                    $title, $description);
    if ($action!='') {
        $html .= "<td class='even'><form method='post' action='dbmanager.php'>\r\n";
        $html .= sprintf("<input type='hidden' name='action' value='%s' />", $action);
        if (count($hidden)>0) {
            foreach ($hidden as $p) {
                $html .= sprintf("<input type='hidden' name='param[]' value='%s'/>", $p);
            }
        }
        $html .= sprintf("<input type='submit' class='formButton' name='button'  value='%s'/>", _AM_WEBLOG_GO);
        $html .= "</form>";
    }
    $html .= "</td></tr>\r\n";

    return $html;
}
?>