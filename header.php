<?php
/*
 * $Id: header.php 11979 2013-08-25 20:45:24Z beckmi $
 *
 * Copyright (c) 2003 by Jeremy N. Cowgar <jc@cowgar.com>
 *
 */

if( ! defined('XOOPS_ROOT_PATH') )
    include_once "../../mainfile.php";

// $mydirname / $mydirnumber are critical GLOBALS.
if( ! isset($mydirname) ){
    $mydirname = basename(  dirname( __FILE__ ) ) ;
}
if( ! isset($mydirnumber) ){
    if(preg_match("/^(\D+)(\d+)$/", $mydirname , $match)){
        $mydirnumber = strval($match[2]) ;
    }else{
        $mydirnumber = '' ;
    }
}
include_once XOOPS_ROOT_PATH.'/modules/'.$mydirname.'/config.php';

// load language_main file
xoops_loadLanguage('main', $mydirname);
//if( ! defined("WEBLOG_BL_LOADED") ){
//	if( file_exists( sprintf('%s/modules/%s/language/%s/main.php', XOOPS_ROOT_PATH ,$mydirname , $xoopsConfig->language) ) ){
//		include_once sprintf('%s/modules/%s/language/%s/main.php', XOOPS_ROOT_PATH , $mydirname , $xoopsConfig->language) ;
//	}else{
//		include_once sprintf('%s/modules/%s/language/english/main.php', XOOPS_ROOT_PATH , $mydirname) ;
//	}
//}
