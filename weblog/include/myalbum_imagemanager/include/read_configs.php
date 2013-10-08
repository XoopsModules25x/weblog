<?php
// Original script is GIJOE's myalbum-P (http://www.peak.ne.jp/xoops/).
	// These config value can be defined in use of myalbum-P. 
	// But using weblog imagemanager , these config values are fixed.
	$myalbum_imagingpipe = 0 ;	// GD mode only. 
	$myalbum_forcegd2 = 1 ;  // Force GD2
	$myalbum_imagickpath = "" ;
	$myalbum_netpbmpath = "" ;	
	$myalbum_allownoimage = 0 ;	// always image required
	$myalbum_makethumb = 1 ;	// always make thumb
	$myalbum_defaultorder = "dateD" ;	// order by POST DATE
	$myalbum_addposts = 0 ;	// add image is not post count .
	$myalbum_catonsubmenu = 0 ; 
	$myalbum_colsoftableview = 0 ;
	$myalbum_allowedexts = 'jpg|jpeg|gif|png' ;	// these file extensions are permitted
	$myalbum_allowedmime = 'image/gif|image/pjpeg|image/jpeg|image/x-png|image/png' ;	// these MIME encoding can be posted.
	
	
	if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

	if( ! $mydirname )
		$mydirname = basename( dirname(dirname(dirname( dirname( __FILE__ ) ) ) ) ) ;
	if( preg_match( '/^\w+(\d*)$/' , $mydirname , $regs ) ) {
		$myalbum_number = $regs[1] ;
	} else {
		die( "invalid dirname of myalbum: " . htmlspecialchars( $mydirname ) ) ;
	}

	global $xoopsConfig , $xoopsDB , $xoopsUser ;

	// module information
	$mod_url = XOOPS_URL . "/modules/$mydirname" ;
	$mod_path = XOOPS_ROOT_PATH . "/modules/$mydirname" ;
	$mod_copyright = "<a href='http://www.peak.ne.jp/xoops/'><b>myAlbum-P 2.8</b></a> &nbsp; <small>(<a href='http://bluetopia.homeip.net/'>original</a>)</small></div>" ;

	// global langauge file
	$language = $xoopsConfig['language'] ;
	if ( file_exists( "$mod_path/language/$language/myalbum_constants.php" ) ) {
		include_once "$mod_path/language/$language/myalbum_constants.php" ;
	} else {
		include_once "$mod_path/language/english/myalbum_constants.php" ;
		$language = "english" ;
	}

	// read from xoops_config
	// get my mid
	$myalbum_mid = $xoopsModule->mid() ;
/*	$rs = $xoopsDB->query( "SELECT mid FROM ".$xoopsDB->prefix('modules')." WHERE dirname='$mydirname'" ) ;
	list( $myalbum_mid ) = $xoopsDB->fetchRow( $rs ) ;	*/

	// read configs from xoops_config directly
	$myalbum_configs = $xoopsModuleConfig ;
/*	$rs = $xoopsDB->query( "SELECT conf_name,conf_value FROM ".$xoopsDB->prefix('config')." WHERE conf_modid=$myalbum_mid" ) ;
	while( list( $key , $val ) = $xoopsDB->fetchRow( $rs ) ) {
		$myalbum_configs[ $key ] = $val ;
	}	*/

	foreach( $myalbum_configs as $key => $val ) {
		if( strncmp( $key , "weblog_myalbum_" , 15 ) == 0 ){
			$key = substr($key , 7) ;
			$$key = $val ;
		}
	}
	// User Informations
	if( empty( $xoopsUser ) ) {
		$my_uid = 0 ;
		$isadmin = false ;
	} else {
		$my_uid = $xoopsUser->uid() ;
		$isadmin = $xoopsUser->isAdmin( $myalbum_mid ) ;
	}

	// Value Check
	$myalbum_addposts = intval( $myalbum_addposts ) ;
	if( $myalbum_addposts < 0 ) $myalbum_addposts = 0 ;

	// Path to Main Photo & Thumbnail ;
	if( ord( $myalbum_photospath ) != 0x2f ) $myalbum_photospath = "/$myalbum_photospath" ;
	if( ord( $myalbum_thumbspath ) != 0x2f ) $myalbum_thumbspath = "/$myalbum_thumbspath" ;
	$photos_dir = XOOPS_ROOT_PATH . $myalbum_photospath ;
	$photos_url = XOOPS_URL . $myalbum_photospath ;
	if( $myalbum_makethumb ) {
		$thumbs_dir = XOOPS_ROOT_PATH . $myalbum_thumbspath ;
		$thumbs_url = XOOPS_URL . $myalbum_thumbspath ;
	} else {
		$thumbs_dir = $photos_dir ;
		$thumbs_url = $photos_url ;
	}

	// DB table name
	$table_photos = $xoopsDB->prefix( "{$mydirname}myalbum_photos" ) ;
	$table_cat = $xoopsDB->prefix( "{$mydirname}_category" ) ;
//	$table_text = $xoopsDB->prefix( "{$mydirname}_text" ) ;
//	$table_votedata = $xoopsDB->prefix( "{$mydirname}_votedata" ) ;
//	$table_comments = $xoopsDB->prefix( "xoopscomments" ) ;

	// Pipe environment check
	if( $myalbum_imagingpipe || function_exists( 'imagerotate' ) ) $myalbum_canrotate = true ;
	else $myalbum_canrotate = false ;
	if( $myalbum_imagingpipe || $myalbum_forcegd2 ) $myalbum_canresize = true ;
	else $myalbum_canresize = false ;

	// Normal Extensions of Image
	$myalbum_normal_exts = array( 'jpg' , 'jpeg' , 'gif' , 'png' ) ;

	// Allowed extensions & MIME types
	if( empty( $myalbum_allowedexts ) ) {
		$array_allowed_exts = $myalbum_normal_exts ;
	} else {
		$array_allowed_exts = explode( '|' , $myalbum_allowedexts ) ;
	}
	if( empty( $myalbum_allowedmime ) ) {
		$array_allowed_mimetypes = array() ;
	} else {
		$array_allowed_mimetypes = explode( '|' , $myalbum_allowedmime ) ;
	}
?>