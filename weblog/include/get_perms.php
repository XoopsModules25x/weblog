<?php
if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

$global_perms = 0 ;
if( is_object( $xoopsDB ) ) {
	if( ! is_object( $xoopsUser ) ) {
		$whr_groupid = "gperm_groupid=".XOOPS_GROUP_ANONYMOUS ;
	} else {
		$groups =& $xoopsUser->getGroups() ;
		$whr_groupid = "gperm_groupid IN (" ;
		foreach( $groups as $groupid ) {
			$whr_groupid .= "$groupid," ;
		}
		$whr_groupid = substr( $whr_groupid , 0 , -1 ) . ")" ;
	}
	$rs = $xoopsDB->query( "SELECT gperm_itemid FROM ".$xoopsDB->prefix("group_permission")." WHERE gperm_modid='$myalbum_mid' AND gperm_name='myalbum_global' AND ($whr_groupid)" ) ;
//	echo "SELECT gperm_itemid FROM ".$xoopsDB->prefix("group_permission")." WHERE gperm_modid='$myalbum_mid' AND gperm_name='myalbum_global' AND ($whr_groupid)";
	while( list( $itemid ) = $xoopsDB->fetchRow( $rs ) ) {
		var_dump($itemid);
		$global_perms |= $itemid ;
	}
}
?>