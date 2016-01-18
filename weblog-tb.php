<?php
/*
 * $Id: weblog-tb.php,v 1.9 2005/07/29 23:56:01 tohokuaiki Exp $
 * Copyright (c) 2003 by ITOH Takashi <http://tohokuaiki.jp>
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
include_once(sprintf('%s/modules/%s/class/class.weblogtrackback.php', XOOPS_ROOT_PATH, $xoopsModule->dirname()));
include_once(sprintf('%s/modules/%s/class/class.weblog.php', XOOPS_ROOT_PATH, $xoopsModule->dirname()));
include_once(sprintf('%s/modules/%s/include/encode_set.inc.php', XOOPS_ROOT_PATH, $xoopsModule->dirname()));

$tb_operator =& Weblog_Trackback_Operator::getInstance() ;
$trackback =& $tb_operator->newInstance() ;

// get trackback ID
if( isset( $_SERVER['SERVER_SOFTWARE'] ) && preg_match("/Microsoft-IIS/i",$_SERVER['SERVER_SOFTWARE']) ){
    if( isset($_SERVER['QUERY_STRING']) && preg_match("/^\d+$/",$_SERVER['QUERY_STRING']) )
        $tb_id = $_SERVER['QUERY_STRING'] ;
}else{
    $tb_id = $tb_operator->getId() ;
}
// get trackback ID by another process
if( empty($tb_id) || get_class($tb_id) == "pear_error"|| ! preg_match("/^\d+$/",$tb_id) ){
    if( isset($_SERVER['REQUEST_URI']) ){    // get tb_id from REQUEST_URI
        $request_url = explode('/', $_SERVER['REQUEST_URI']);
        if( count($request_url) > 1 ){
            $tb_id_key = count( $request_url ) -1 ;
            if(preg_match("/^\d+$/",$request_url[$tb_id_key]))
                $tb_id = $request_url[$tb_id_key] ;
        }
    }
}
if( empty($tb_id) || get_class($tb_id) == "pear_error"|| ! preg_match("/^\d+$/",$tb_id) ){
    if( isset($_SERVER['QUERY_STRING']) && ! empty($_SERVER['QUERY_STRING']) ){    // get tb_id from QUERY_STRING
        if(preg_match("/^\d+$/",$_SERVER['QUERY_STRING']))
            $tb_id = $_SERVER['QUERY_STRING'] ;
    }
}

$weblog =& Weblog::getInstance();
$entry =& $weblog->handler->get($tb_id) ;

@header('Content-Type: text/xml');
// Trackback ping check
if( ! $tb_operator->isPing() ){
    $link = sprintf('%s/modules/%s/details.php?blog_id=%d' , XOOPS_URL , $xoopsModule->dirname() , $tb_id ) ;
    // rss mode
    if( isset($_GET['__mode']) && $_GET['__mode'] == "rss" ){
        $trackback_array =& $tb_operator->handler->get($tb_id) ;
        $trackback_data_array = return_trackback_data($trackback_array);
        $lang = _LANGCODE ;
        $xml = $tb_operator->toRSSXML( $trackback_data_array , $entry->getVar('title','n') , $link , $entry->getVar('contents','n') , $lang ) ;
        $xml = encoding_set( $xml , "UTF-8") ;
        exit( $xml ) ;
    }else{
        header("Location:" . $link) ;
        exit() ;
    }
}

// check trackback ID
if( ! preg_match("/^\d+$/" , $tb_id) )
    exit( $tb_operator->getPingXML(false , "Invalid trackback ID") ) ;

// save trackback
$tb_id = intval($tb_id) ;
$data = $tb_operator->analyzePing($_POST) ;
$data['description'] = $data['excerpt'] ;
$data['blog_id'] = $tb_id ;
if( function_exists('mb_detect_encoding') ){
    $data['encoding'] = mb_detect_encoding($data['description']) ;
}else{
    $data['encoding'] = "auto" ;
}
$data['link'] = false ;  // surpress PHP warnig
if( $tb_operator->Set_Trackback_Values( $trackback , $data , $data['url'] , "recieved" ) ){
    if( $tb_operator->saveTrackback($trackback) ){
        $weblog->handler->incrementTrackbacks($tb_id) ;
        exit( $tb_operator->getPingXML(true) ) ;
    }
}
exit( $tb_operator->getPingXML(false , "Server Error") ) ;

/******
* Function for __mode=rss .
*******/

function return_trackback_data($r_trackback_array){
    if( ! is_array($r_trackback_array) )
        return false ;
    
    $r_trackback_data_array = array();
    foreach( $r_trackback_array as $trackback_obj ){
        if( strtolower(get_parent_class($trackback_obj)) != "weblogtrackbackbase" )
            continue ;
        if( $trackback_obj->getVar('direction') == "recieved" ){
            $r_trackback_data_array[] = array(
                "title" => $trackback_obj->getVar('title'),
                "url" => $trackback_obj->getVar('link'),
                "excerpt" => $trackback_obj->getVar('description')
                ) ;
        }
    }
    
    return $r_trackback_data_array ;
}
