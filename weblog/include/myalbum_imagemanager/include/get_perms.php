<?php
if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;
// weblog use individual privilege system not global group permittion.

//$global_perms = 0 ;
$post_privilege = false ;
if( is_object( $xoopsDB ) ) {
    if( ! is_object( $xoopsUser ) ) {
//		$whr_groupid = "gperm_groupid=".XOOPS_GROUP_ANONYMOUS ;
        $whr_groupid = "priv_gid=".XOOPS_GROUP_ANONYMOUS ;
    } else {
        $groups =& $xoopsUser->getGroups() ;
//		$whr_groupid = "gperm_groupid IN (" ;
        $whr_groupid = "priv_gid IN (" ;
        foreach( $groups as $groupid ) {
            $whr_groupid .= "$groupid," ;
        }
        $whr_groupid = substr( $whr_groupid , 0 , -1 ) . ")" ;
    }
/*	$rs = $xoopsDB->query( "SELECT gperm_itemid FROM ".$xoopsDB->prefix("group_permission")." WHERE gperm_modid='$myalbum_mid' AND gperm_name='myalbum_global' AND ($whr_groupid)" ) ;
    while( list( $itemid ) = $xoopsDB->fetchRow( $rs ) ) {
        $global_perms |= $itemid ;
    } */
    $rs = $xoopsDB->query( "SELECT priv_gid FROM ".$xoopsDB->prefix("weblog_priv")." WHERE $whr_groupid" );
    if( $xoopsDB->getRowsNum($rs) > 0 ){
        $post_privilege = true ;
    }
}
