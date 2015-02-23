<?php
/**
 * $Id: config.php,v 1.10 2005/06/18 17:56:01 tohokuaiki Exp $
 * Copyright (c) 2003 by Hiro SAKAI (http://wellwine.net/)
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

// You shouldn't have to change any of these
$weblogUrl['root'] = XOOPS_URL."/modules/". $mydirname;
$weblogUrl['admin'] = $weblogUrl['root']."admin";
$weblogUrl['images'] = $weblogUrl['root']."images";

/* -- Cookie settings (lastvisit) -- */
// Most likely you can leave this be, however if you have problems
// logging into the forum set this to your domain name, without
// the http://
// For example, if your forum is at http://www.mysite.com/phpBB then
// set this value to
// $weblogCookie['domain'] = "www.mysite.com";
$weblogCookie['domain'] = "";

// It should be safe to leave these alone as well.
$weblogCookie['path'] = str_replace(basename($_SERVER['PHP_SELF']),"",$_SERVER['PHP_SELF']);
$weblogCookie['secure'] = false;


if( ! defined('_WEBLOG_COMMON_FUNCTIONS') ){
	define('_WEBLOG_COMMON_FUNCTIONS' , 1 );

// You shold not have to change this
//define("WEBLOG_COOKIE_READ","weblog_blog_read" . $mydirnumber );
define("WEBLOG_COOKIE_READ","weblog_blog_read" );
// permission values
define('WEBLOG_PERMIT_READINDEX','1');
define('WEBLOG_PERMIT_READDETAIL','2');
define('WEBLOG_PERMIT_EDIT','4') ;
define('WEBLOG_PERMIT_CATPOST','8') ;
// separator
define('_BL_ENTRY_SEPARATOR_DELIMETER' , '---UnderThisSeparatorIsLatterHalf---');
define('MEMBER_ONLY_READ_DELIMETER' , '---AnonymousUserCantReadUnderHere---');
// rss
define('WEBLOG_TB_EXCERPT_NUM', '480');
define('WEBLOG_RDF_DESCRIPTION_NUM', '210');
// you can change this values as register page.
define('WEBLOG_REGISTER_LEADING_PAGE' , '/register.php') ;



// encoding functions
function encoding_set( $data , $to_enc , $from_enc=false ){

            if( is_array($data) ){
                $enc_data = array() ;
                foreach( $data as $key=>$value ){
                    $enc_data[$key] = weblog_convert_encoding( $value , $to_enc , $from_enc ) ;
                }
            }else{
                $enc_data = weblog_convert_encoding( $data , $to_enc , $from_enc ) ;
            }
        return $enc_data ;
}

function weblog_convert_encoding( $string , $to_enc , $from_enc=false ){

        if( $from_enc == $to_enc )
            return $string ;
            
        if( empty($from_enc) )
            $from_enc = weblog_return_enc( $string ) ;

        if( function_exists('mb_convert_encoding') )
            $string = mb_convert_encoding( $string , $to_enc , $from_enc ) ;

        return $string ;
}


function weblog_return_enc( $data_string=false , $suppose_enc=false ){
        if( empty($suppose_enc) ){
                if( defined("_CHARSET") )
                        return _CHARSET ;
                if( function_exists('mb_detect_encoding') &&  $data_string && $suppose_enc = mb_detect_encoding($data_string) )
                        return $suppose_enc ;
                if( function_exists('mb_internal_encoding') && $suppose_enc = mb_internal_encoding() )
                        return $suppose_enc ;

                return "ISO-8859-15" ;
        }

        return $suppose_enc ;
}


// entry convert functions
// function which these WeblogEntry class use

function weblog_create_permissionsql($blockModuleConfig=array() , $mydirname='' ){
	global $xoopsUser , $xoopsModule , $xoopsModuleConfig ;

		if( empty($mydirname) )
			$mydirname = $GLOBALS['mydirname'] ;

		if( empty($blockModuleConfig) ){
			$config = $xoopsModuleConfig ;
		}else{
			$config = $blockModuleConfig ;
		}
		if (empty($xoopsModule) || ( get_class($xoopsModule)=="xoopsmodule" && $xoopsModule->dirname()!=$mydirname )) {
			$mod_handler =& xoops_gethandler('module');
			$wbModule =& $mod_handler->getByDirname( $mydirname );
		}else{
			$wbModule =& $xoopsModule ;
		}

		// check user's group privilege 
		if( isset($xoopsUser) && get_class($xoopsUser) == "xoopsuser" ){
			$currentuid = $xoopsUser->getVar('uid');
			$currentusergroup = $xoopsUser->getGroups();
			$isAdmin = $xoopsUser->isAdmin($wbModule->mid());
		}else{
			$currentuid = 0 ;
			$currentusergroup = array(XOOPS_GROUP_ANONYMOUS) ;
			$isAdmin = false ;
		}
		// permission sql prepare
		$permission_group_sql = "" ;
		$bl_contents_field = "" ;
		if( $config['use_permissionsystem'] && ! $isAdmin ){
			if( $config['permission_showtitle'] ){
				foreach( $currentusergroup as $groupid ){
					$bl_contents_field .= '\\\\|' . $groupid . '\\\\||' ;
				}
				$bl_contents_field = rtrim( $bl_contents_field , '|' ) . '|' ;
				$bl_contents_field = sprintf( "if(user_id=%d,contents,if(permission_group='all',contents,if(permission_group regexp '(%s)' , bl.contents , 'GROUP_PERMIT' )))" , $currentuid , $bl_contents_field ) ;
			}else{
				$permission_group_sql = " and (bl.permission_group='all' or " ;
				foreach( $currentusergroup as $groupid ){
					$permission_group_sql .= " bl.permission_group like '%|" . $groupid . "|%' or" ;
				}
				$permission_group_sql = $permission_group_sql . " user_id=" . $currentuid . ")" ;
				$bl_contents_field = "bl.contents" ;
			}
		}else{
			$bl_contents_field = "bl.contents" ;
		}
	return array( $bl_contents_field , $permission_group_sql ) ;
}

/*
// get $contents_mode
function get_contents_mode(){
	
	$file_name = basename($_SERVER['SCRIPT_NAME'],".php") ;
	switch( $file_name ){
		case "index" :
		case "details" :
		case "post" :
			return $file_name ;
		case "weblog-tb" :
			return "trackback" ;
		case "weblog-rdf" :
		case "backend_weblog" :
			return "rss" ;
	}
	return "details";	// default
}
*/
}
?>
