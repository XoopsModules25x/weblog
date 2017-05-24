<?php
/*
 * $Id: search.inc.php 40 2006-06-14 07:50:12Z hodaka $
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

$mydirname = basename( dirname( dirname( __FILE__ ) ) ) ;
if( ! preg_match( '/^(\D+)(\d*)$/' , $mydirname , $regs ) ) die ( "invalid dirname: " . htmlspecialchars( $mydirname ) ) ;
$mydirnumber = $regs[2] === '' ? '' : intval( $regs[2] ) ;

eval( '

function weblog'.$mydirnumber.'_search( $keywords , $andor , $limit , $offset , $uid )
{
	return weblog_search_base( "'.$mydirname.'" , $keywords , $andor , $limit , $offset , $uid ) ;
}

' ) ;

if( ! function_exists( 'weblog_search_base' ) ) {

function weblog_search_base($mydirname, $queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB, $xoopsUser, $xoopsConfig;
    $myts = MyTextSanitizer::getInstance();

    $currentuid = !empty($xoopsUser) ? $xoopsUser->getVar('uid','E') : 0;
    if (is_object($xoopsUser)) {
        $useroffset = $xoopsUser->timezone() - $xoopsConfig['server_TZ'] ;
    } else {
        $useroffset = $xoopsConfig['default_TZ'] - $xoopsConfig['server_TZ'] ; ;
    }

    $showcontext = isset( $_GET['showcontext'] ) ? $_GET['showcontext'] : 0 ;
    if( $showcontext == 1 && function_exists('search_make_context')){
        $sql = sprintf("SELECT blog_id,title,created,user_id,contents,dohtml,dobr FROM %s WHERE (private='N' OR user_id=%d) AND created+%d<=%d ", $xoopsDB->prefix($mydirname), $currentuid, $useroffset*3600, time());
    }else{
        $sql = sprintf("SELECT blog_id,title,created,user_id FROM %s WHERE (private='N' OR user_id=%d) AND created+%d<=%d ", $xoopsDB->prefix($mydirname), $currentuid, $useroffset*3600, time());
    }
    if ($userid != 0) $sql .= "AND user_id=".$userid." ";

    // because count() returns 1 even if a supplied variable
    // is not an array, we must check if $querryarray is really an array
    $count = count($queryarray);
    if ( $count > 0 && is_array($queryarray) ) {
        $sql .= "AND ((title LIKE '%$queryarray[0]%' OR contents LIKE '%$queryarray[0]%')";
        for ( $i = 1; $i < $count; $i++ ) {
            $sql .= " $andor ";
            $sql .= "(title LIKE '%$queryarray[$i]%' OR contents LIKE '%$queryarray[$i]%')";
        }
        $sql .= ") ";
    }
    $sql .= "ORDER BY created DESC";
    $result = $xoopsDB->query($sql,$limit,$offset);

    $ret = array();
    $i = 0;
    while ( $myrow = $xoopsDB->fetchArray($result) ) {
        $ret[$i]['image'] = "images/".$mydirname.".png";
        $ret[$i]['link'] = "details.php?blog_id=".$myrow['blog_id'];
        $ret[$i]['title'] = $myrow['title'];
        $ret[$i]['time'] = $myrow['created'];
        $ret[$i]['uid'] = $myrow['user_id'];
        if( !empty( $myrow['contents'] ) ){
            $context = preg_replace("/-{3}(\w+)-{3}/","",strip_tags($myrow['contents']));
            $context = strip_tags($myts->displayTarea($context,$myrow['dohtml'],0,1,0,$myrow['dobr']));
            $ret[$i]['context'] = search_make_context($context,$queryarray);
        }
        $i++;
    }

    return $ret;
}

}
