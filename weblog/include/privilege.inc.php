<?php
/**
 * $Id: privilege.inc.php 11979 2013-08-25 20:45:24Z beckmi $
 * Copyright (c) 2005 by ITOH Takashi (http://tohokuaiki.jp/)
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

function checkprivilege( $type , $mydirname , $cat_id=0 ){
    global $xoopsModuleConfig ;

    $priv_class = "Privilege" . ucfirst($xoopsModuleConfig['privilege_system']) ;
    $priv = new $priv_class() ;

    if($type == "read_index"){
        return $priv->can_read_index( $mydirname , $cat_id ) ;
    }elseif($type == "read_detail"){
        return $priv->can_read_detail( $mydirname ) ;
    }elseif( $type == "edit" ){
        return $priv->can_edit( $mydirname , $cat_id ) ;
    }else{
        return false ;
    }
}

class PrivilegeXOOPS{

    function get_globalperm( $mydirname ){
        global $xoopsDB , $xoopsUser ;

        $global_perms = 0 ;
        if( is_object( $xoopsDB ) ) {
            // get module's ID
            $rs = $xoopsDB->query( "SELECT mid FROM ".$xoopsDB->prefix('modules')." WHERE dirname='$mydirname'" ) ;
            list( $weblog_mid ) = $xoopsDB->fetchRow( $rs ) ;
            // get group global_perms
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
            $rs = $xoopsDB->query( "SELECT gperm_itemid FROM ".$xoopsDB->prefix("group_permission")." WHERE gperm_modid='$weblog_mid' AND gperm_name='weblog_global' AND ($whr_groupid)" ) ;
            while( list( $itemid ) = $xoopsDB->fetchRow( $rs ) ) {
                $global_perms |= $itemid ;
            }
        }

        return $global_perms ;
    }
    // NOW nothing
    function get_categoryperm( $mydirname ){
        return 1 ;
    }

    /* ------------  READABLE PERMISSION  ----------- */
    // still nothing
    function weblog_read_catperm( $mydirname , $cat_id=0 ){
        return true ;
    }

    function can_read_index( $mydirname , $cat_id=0  ){
        $global_perms = $this->get_globalperm($mydirname) ;
        if( ( $global_perms & WEBLOG_PERMIT_READINDEX ) && $this->weblog_read_catperm( $mydirname , $cat_id ) ){
            return true ;
        }else{
            return false ;
        }
    }

    function can_read_detail( $mydirname ){
        $global_perms = $this->get_globalperm($mydirname) ;
        if( ( $global_perms & WEBLOG_PERMIT_READDETAIL ) && $this->weblog_read_catperm( $mydirname ) ){
            return true ;
        }else{
            return false ;
        }
    }

    /* ------------  EDITABLE PERMISSION  ----------- */
    function weblog_edit_gperm( $mydirname ){
        global $xoopsModuleConfig , $xoopsUser , $xoopsModule ;

        $global_perms = $this->get_globalperm($mydirname) ;
        if( ! ( $global_perms & WEBLOG_PERMIT_EDIT ) ){
            return false ;
        }else{
            return true ;    // success
        }
    }

    // still nothing
    function weblog_edit_catperm( $mydirname , $cat_id=0 ){
        return true ;
    }

    function can_edit( $mydirname , $cat_id=0 ){
        if( $this->weblog_edit_gperm( $mydirname ) && $this->weblog_edit_catperm( $mydirname , $cat_id ) ){
            return true ;
        }else{
            return false ;
        }
    }

}

class PrivilegeWeblog{

    function can_read_index( $mydirname , $cat_id=0 ){
        // anyone can read
        return true ;
    }
    function can_read_detail( $mydirname ){
        // anyone can read
        return true ;
    }

    function can_edit( $mydirname , $cat_id=0 ){
        global $xoopsModuleConfig , $xoopsUser , $xoopsModule ;
        if (is_object($xoopsUser)) {
            $currentUser = $xoopsUser;
        } else {
            $currentUser = new XoopsUser();
            $currentUser->setVar('uid', 0);
        }
        $isAdmin = $currentUser->isAdmin($xoopsModule->mid());
        $currentuid = $currentUser->getVar('uid');
        // Check to ensure this user can post.
        $priv =& xoops_getmodulehandler('priv');
        if ($currentuid==0 || (!$isAdmin && ($xoopsModuleConfig['adminonly'] || !$priv->hasPrivilege($currentUser)))) {
            return false ;
        }else{
            return true ;
        }
    }
}
