<?php
/*
 * $Id: comment_functions.php,v 1.6 2005/07/01 15:03:18 tohokuaiki Exp $
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

// configure values and common functions
if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

// configure values and common functions
if( ! isset($mydirname) ){
    $mydirname = basename(  dirname( dirname( __FILE__ ) ) );
}

function weblog_com_update($link_id, $total_num){
  global $mydirname ;
  $db =& Database::getInstance();
  $sql = 'UPDATE '.$db->prefix($mydirname).' SET comments = '.$total_num.' WHERE blog_id = '.$link_id;
  $db->query($sql);
}

function weblog_com_approve(&$comment){
  // send notification mail
}
?>
