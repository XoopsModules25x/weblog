<?php
	if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

	if( ! isset( $module ) || ! is_object( $module ) ) $module = $xoopsModule ;
	else if( ! is_object( $xoopsModule ) ) die( '$xoopsModule is not set' )  ;

	if( file_exists("../language/".$xoopsConfig['language']."/modinfo.php") ) {
		include_once("../language/".$xoopsConfig['language']."/modinfo.php");
	} else {
		include_once("../language/english/modinfo.php");
	}

	include( './menu.php' ) ;

	array_push( $adminmenu , array( 'title' => _PREFERENCES , 'link' => '../system/admin.php?fct=preferences&op=showmod&mod=' . $module->getvar('mid') ) ) ;
	$menuitem_dirname = $module->getvar('dirname') ;
//	array_push( $adminmenu , array( 'title' => _PREFERENCES , 'link' => 'admin/admin.php?fct=preferences&op=showmod&mod=' . $module->getvar('mid') ) ) ;

	echo "<div width='95%' align='center'>\n" ;
	$menuitem_count = 0 ;
	$mymenu_uri = empty( $mymenu_fake_uri ) ? $_SERVER['REQUEST_URI'] : $mymenu_fake_uri ;
	foreach( $adminmenu as $menuitem ) {
		if( stristr( $mymenu_uri , $menuitem['link'] ) ) {
			$menuitem_bgcolor = '#FFCCCC' ;
		} else {
			$menuitem_bgcolor = '#DDDDDD' ;
		}
		echo "<a href='".XOOPS_URL."/modules/$menuitem_dirname/{$menuitem['link']}' style='background-color:$menuitem_bgcolor;font:normal normal bold 9pt/12pt;'>{$menuitem['title']}</a> &nbsp; \n" ;

		if( ++ $menuitem_count >= 4 ) {
			echo "</div>\n<div width='95%' align='center'>\n" ;
			$menuitem_count = 0 ;
		}
	}
	echo "</div>\n" ;
?>