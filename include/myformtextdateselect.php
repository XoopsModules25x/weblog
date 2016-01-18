<?php
// $Id: myformtextdateselect.php 11979 2013-08-25 20:45:24Z beckmi $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
/**
 * @package     kernel
 * @subpackage  form
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */

/**
 * A text field with calendar popup
 *
 * @package     kernel
 * @subpackage  form
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
 // tohokuaiki change

class myXoopsFexormTtDateSelect extends XoopsFormText
{

    function myXoopsFormTextDateSelect($caption, $name, $size = 15, $value= 0)
    {
        $value = !is_numeric($value) ? time() : intval($value);
        $this->XoopsFormText($caption, $name, $size, 25, $value);
    }

    function render()
    {
        global $xoopsTpl ;
        $jstime = formatTimestamp('F j Y, H:i:s', $this->getValue());
        ob_start();
        include_once XOOPS_ROOT_PATH.'/include/calendarjs.php';
        $contents = ob_get_contents();
        ob_end_clean();
        $xoops_module_header = $xoopsTpl->get_template_vars('xoops_module_header') . $contents ;
        $xoopsTpl->assign('xoops_module_header',$xoops_module_header) ;

        return "<input type='text' name='".$this->getName()."' id='".$this->getName()."' size='".$this->getSize()."' maxlength='".$this->getMaxlength()."' value='".date("Y-m-d", $this->getValue())."'".$this->getExtra()." /><input type='reset' value=' ... ' onclick='return showCalendar(\"".$this->getName()."\");'>";
    }
}
