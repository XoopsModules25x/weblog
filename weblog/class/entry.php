<?php
/*
 * $Id: entry.php 11979 2013-08-25 20:45:24Z beckmi $
 * Copyright (c) 2003 by Hiro SAKAI (http://wellwine.zive.net/)
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
 *
 */

if( ! defined( 'XOOPS_ROOT_PATH' ) ) exit ;

include_once(XOOPS_ROOT_PATH.'/kernel/object.php');

if( ! isset($mydirname) ) $mydirname = basename( dirname( dirname( __FILE__ ) ) ) ;

if( ! class_exists('WeblogEntryBase') ){

class WeblogEntryBase extends XoopsObject {

	var $mydirname='' ;		// abstruct
    /**
     * Constructs an instance of this class
     */
    function WeblogEntryBase() {
        global $xoopsModuleConfig ;
        $default_dohtml = ( $xoopsModuleConfig['default_dohtml'] ) ? 0 : 1 ;
        $default_dobr = ( $xoopsModuleConfig['default_dobr'] ) ? 1 : 0 ;
        $default_private = ( $xoopsModuleConfig['default_private'] ) ? "Y" : "N" ;
        $default_updateping = ( $xoopsModuleConfig['default_updateping'] ) ? 1 : 0 ;

        $this->XoopsObject();

        $this->initVar("blog_id", XOBJ_DTYPE_INT, 0, false);
        $this->initVar("user_id", XOBJ_DTYPE_INT, 0, true);
        $this->initVar("cat_id", XOBJ_DTYPE_INT, 0, true);
        $this->initVar("created", XOBJ_DTYPE_INT, 0, true);
        $this->initVar("title", XOBJ_DTYPE_TXTBOX, null, true, 128, true);
        $this->initVar("contents", XOBJ_DTYPE_TXTAREA, null, true, null, true);
        $this->initVar("comments", XOBJ_DTYPE_INT, 0, false);
        $this->initVar("reads", XOBJ_DTYPE_INT, 0, false);
        $this->initVar("trackbacks", XOBJ_DTYPE_INT, 0, false);
        $this->initVar("dohtml", XOBJ_DTYPE_INT, $default_dohtml, false);
        $this->initVar("dobr", XOBJ_DTYPE_INT, $default_dobr, false);
        $this->initVar("private", XOBJ_DTYPE_TXTBOX, $default_private, true, 1, true);
        $this->initVar("updateping", XOBJ_DTYPE_INT, $default_updateping, false);
        $this->initVar("specify_created", XOBJ_DTYPE_INT, 0, false);
        $this->initVar("ent_trackbackurl", XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("permission_group", XOBJ_DTYPE_TXTBOX, null, false);

        $this->initVar("uname", XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("user_avatar", XOBJ_DTYPE_TXTBOX, null, false);
    }

    function isPrivate() {
        return ($this->getVar('private', 'n')=='Y') ? true : false;
    }

    function doHtml() {
        return ($this->getVar('dohtml')==1) ? true : false;
    }

    function doBr() {
        return ($this->getVar('dobr')==1) ? true : false;
    }

    function isUpdateping() {
        return ($this->getVar('updateping')==1) ? true : false;
    }

    function isSpecifycreated() {
        return ($this->getVar('specify_created')==1) ? true : false;
    }

    function &getVar($key, $format='s', $blog_id=0 , $contents_mode=false){
		if( ! ($key == 'contents' && $contents_mode) ) {
			$ret = parent::getVar($key , $format) ;
            return $ret;
        }

		$contents = parent::getVar($key, $format) ;
		$ret = $this->parse_viewmode( $contents , $blog_id ,$contents_mode , $this->mydirname) ;
        return $ret;

	}

	// parse entry->content in case of using separator functions
	function parse_viewmode( $contents , $blog_id ,$contents_mode="details" , $mydirname=''){
		global $xoopsUser , $xoopsModuleConfig , $xoopsModule , $xoopsConfig ;

		$mydirname = ( empty($mydirname) ) ? $this->mydirname : $mydirname ;
		include_once(sprintf('%s/modules/%s/config.php', XOOPS_ROOT_PATH, $mydirname )) ;

		// if not allowed
	    if( preg_match("/^GROUP_PERMIT$/",$contents) ){
	        return _BL_GROUP_PERMIT ;
	    }

		// get weblog Module Config if not.
		if( ! isset($xoopsModuleConfig['minentrysize']) ){	// this config is sapmle.
			include_once(sprintf('%s/modules/%s/language/%s/main.php', XOOPS_ROOT_PATH, $mydirname, $xoopsConfig['language'] )) ;
			$module_h =& xoops_gethandler('module') ;
			$module =& $module_h->getByDirname($mydirname) ;
			$config_h =& xoops_gethandler('config') ;
			$tmp_xoopsModuleConfig = $xoopsModuleConfig ;
			$xoopsModuleConfig = $config_h->getConfigsByCat(0, $module->getVar('mid'));
		}

		if (empty($xoopsModule) || ( get_class($xoopsModule)=="xoopsmodule" && $xoopsModule->dirname()!=$mydirname )) {	// for using block
			$mod_handler =& xoops_gethandler('module');
			$wbModule =& $mod_handler->getByDirname( $mydirname );
		}else{
			$wbModule =& $xoopsModule ;
		}
		// check user
		if( isset($xoopsUser) && get_class($xoopsUser) == "xoopsuser" ){
			$currentuid = $xoopsUser->getVar('uid');
//			$currentusergroup = $xoopsUser->getGroups();
			$isAdmin = $xoopsUser->isAdmin($wbModule->mid());
		}else{
			$currentuid = 0 ;
//			$currentusergroup = array(XOOPS_GROUP_ANONYMOUS) ;
			$isAdmin = false ;
		}

		switch( $contents_mode ){
	        case "rss" :
	        case "trackback" :
	            $contents = preg_replace("/(". MEMBER_ONLY_READ_DELIMETER . ").*$/sm" , "" , $contents ) ;
	            $contents = str_replace( _BL_ENTRY_SEPARATOR_DELIMETER , "" , $contents ) ;
	            break ;

	        case "details" :	// parse member limit and erase first-last half separator
	            // member or not change entry text
	            if( $currentuid ){
	                $contents = str_replace( MEMBER_ONLY_READ_DELIMETER , "" , $contents ) ;
	            }else{
	                $contents = preg_replace("/(". MEMBER_ONLY_READ_DELIMETER . ").*$/sm" ,
	                                               "<br /><br /><a href='" . XOOPS_URL . WEBLOG_REGISTER_LEADING_PAGE ."'>" ._BL_MEMBER_ONLY_READ_MORE . "</a><br />\n" ,
	                                               $contents ) ;
	            }
	            // strip entry division separator
	            $contents = str_replace( _BL_ENTRY_SEPARATOR_DELIMETER , "" , $contents ) ;
	            break ;

	        case "index" :	// parse member limit and first-last half
	            // member or not change entry text
	            if( isset($xoopsModuleConfig['use_memberonly']) && $xoopsModuleConfig['use_memberonly'] && ! $currentuid ){
	                $contents = preg_replace("/(". MEMBER_ONLY_READ_DELIMETER . ").*$/sm" ,
	                                               "<br /><br /><a href='" . XOOPS_URL . WEBLOG_REGISTER_LEADING_PAGE . "'>" ._BL_MEMBER_ONLY_READ_MORE . "</a><br />\n" ,
	                                               $contents ) ;
	            }else{
	                $contents = str_replace( MEMBER_ONLY_READ_DELIMETER , "" , $contents ) ;
	            }



	            // entry division separator
	            if( isset($xoopsModuleConfig['use_separator']) && $xoopsModuleConfig['use_separator'] ){
	                $weblog_division_next_string = sprintf( "<br /><br /><a href=\"%s/modules/%s/details.php?blog_id=%d\">%s</a><br />\n" ,
	                                                         XOOPS_URL , $mydirname , $blog_id , _BL_ENTRY_SEPARATOR_NEXT );
	                $contents = preg_replace("/(". _BL_ENTRY_SEPARATOR_DELIMETER . ").*$/sm" ,
	                                                   $weblog_division_next_string , $contents ) ;
	            }else{
	                $contents = str_replace( _BL_ENTRY_SEPARATOR_DELIMETER , "" , $contents ) ;
	            }
	            break ;
	        case "post":	// nothing
	            break ;
			default :
		}
		// turn back $xoopsModuleConfig
		if( isset($tmp_xoopsModuleConfig) ){
			$xoopsModuleConfig = $tmp_xoopsModuleConfig ;
		}
		return $contents ;
	}
}	// end of class

class WeblogEntryHandlerBase extends XoopsObjectHandler {

	var $mydirname='' ;	// abstruct

    function &create() {
        return new WeblogEntryBase();	// abstruct
    }

    function &get($blog_id) {
        $blog_id = intval($blog_id);
		// use permission system or show title ?
		list( $bl_contents_field , $permission_group_sql ) = weblog_create_permissionsql() ;
        if ($blog_id > 0 ) {
            $sql = sprintf('SELECT bl.blog_id, bl.user_id, bl.cat_id, bl.created, bl.title, %s as contents, bl.private, bl.comments, bl.`reads`, bl.dohtml, bl.dobr, bl.trackbacks, bl.permission_group, u.uname, u.user_avatar FROM %s as bl, %s as u WHERE blog_id=%d AND bl.user_id=u.uid %s',
                           $bl_contents_field ,
                           $this->db->prefix($this->mydirname),
                           $this->db->prefix('users'), $blog_id , $permission_group_sql );
            if ($result = $this->db->query($sql)) {
                if ($this->db->getRowsNum($result)==1) {
                    $result =
                    $entry = $this->create();
                    $entry->assignVars($this->db->fetchArray($result));
                    return $entry;
                }
            }
        }
        return false;
    }

    function insert(&$entry) {
        global $xoopsModuleConfig ;

        if (strtolower(get_parent_class($entry)) != 'weblogentrybase') {  // must be lowercase only
            return false;
        }
        if (!$entry->isDirty()) {
            return true;
        }
        if (!$entry->cleanVars()) {
            return false;
        }

        foreach ($entry->cleanVars as $k => $v) {
            ${$k} = $v;
        }

        $count = $this->getCount(new Criteria('blog_id', $blog_id));

        if( $xoopsModuleConfig['use_permissionsystem'] && isset($permission_group) ){
            // check all or not
            $permission_group_array = explode('|' , $permission_group) ;

            $member_handler =& xoops_gethandler('group');
            $groups =& $member_handler->getObjects();
            $group_array = array() ;
            foreach( $groups as $group ){
                if( $group->getVar('groupid') == 1 ) continue ;
                $group_array[] = $group->getVar('groupid') ;
            }
            $group_diff = array_diff($group_array,$permission_group_array) ;
            if( empty($group_diff) ){
                $permission_group_value = "all";
            }else{
                $permission_group_value = $permission_group ;
            }

            if ($blog_id > 0 && $count > 0) {
                $permission_group_field = ", permission_group=" ;	// update
                $permission_group_value = $this->db->quoteString($permission_group_value) ;
            }else{
                $permission_group_field = ", permission_group" ;	// insert
                $permission_group_value = ", " . $this->db->quoteString($permission_group_value) ;
            }
        }else{
                $permission_group_field = "" ;
                $permission_group_value = "" ;
        }
		// when not specify created time
		if( empty($specify_created) ){
            if ($blog_id > 0 && $count > 0) {
				$created = "created" ;	// update
			}else{
				$created = time() ;	// insert
			}
		}
		// create sql
        if ($blog_id > 0 && $count > 0) {
            $sql = sprintf('UPDATE %s SET user_id=%d, cat_id=%d, created=%s, title=%s, contents=%s, private=%s, dohtml=%d, dobr=%d %s WHERE blog_id=%d',
                           $this->db->prefix($this->mydirname),
                           $user_id, $cat_id, $created,
                           $this->db->quoteString($title),
                           $this->db->quoteString($contents),
                           $this->db->quoteString($private),
                           $dohtml, $dobr,
                           $permission_group_field . $permission_group_value,
                           $blog_id );
        } else {
            $sql = sprintf('INSERT INTO %s (user_id, cat_id, created, title, contents, private, dohtml, dobr %s) VALUES (%d, %d, %d, %s, %s, %s, %d, %d %s)',
                           $this->db->prefix($this->mydirname),
                           $permission_group_field ,
                           $user_id, $cat_id, $created,
                           $this->db->quoteString($title),
                           $this->db->quoteString($contents),
                           $this->db->quoteString($private),
                           $dohtml, $dobr,
                           $permission_group_value );
        }
//		echo $sql ;
        if (!$result = $this->db->query($sql)) {  // must be query()
            return false;
        }

        if (empty($blog_id)) {
            $entry->setVar('blog_id', $this->db->getInsertId());
			// count up user post
			if( $xoopsModuleConfig['userpost_countup'] ){
				$sql = sprintf('update %s set posts=posts+1 where uid=%d', $this->db->prefix('users'), $user_id) ;
				$this->db->query($sql) ;
			}
        }

        return true;
    }

    function delete(&$entry) {
        if (strtolower(get_parent_class($entry)) != 'weblogentrybase') {
            return false;
        }

        $sql = sprintf('DELETE FROM %s WHERE blog_id=%d LIMIT 1',
                       $this->db->prefix($this->mydirname), $entry->getVar('blog_id'));
        if (!$result = $this->db->query($sql)) {  // must be query()
            return false;
        }
        return true;
    }

    function getCount($criteria=null) {
        $sql = sprintf('SELECT count(*) as count FROM %s', $this->db->prefix($this->mydirname));
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= sprintf(' %s', $criteria->renderWhere());
        }
        if (!$result = $this->db->query($sql)) {
            return 0;
        }
        $count = $this->db->fetchArray($result);
        return $count['count'];
    }

    function &getObjects($criteria=null, $id_as_key=false, $contents_mode="detail", $useroffset=0) {
        $ret = array();
        $limit = $start = 0;
		// use permission system or show title ?
		list( $bl_contents_field , $permission_group_sql ) = weblog_create_permissionsql() ;
		// sql main
        $sql = sprintf('SELECT bl.blog_id, bl.user_id, bl.cat_id, bl.created+%d as created, bl.title, %s as contents, bl.private, bl.comments, bl.`reads`, bl.trackbacks, bl.permission_group, bl.dohtml, bl.dobr, bl.trackbacks, u.uname, u.user_avatar FROM %s as bl, %s as u ',
                       $useroffset*3600 ,
                       $bl_contents_field ,
                       $this->db->prefix($this->mydirname) ,
                       $this->db->prefix('users') );
		// criteria
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= sprintf(' %s %s %s', $criteria->renderWhere(), 'AND bl.user_id=u.uid ' , $permission_group_sql );
            //$groupby = trim(str_replace('GROUP BY', "", $criteria->getGroupby()));
            //$sql .= ($groupby=='')?'':sprintf(' %s', $criteria->getGroupby());
            $sort = ($criteria->getSort()!='') ? $criteria->getSort() : 'blog_id';
            $sql .= sprintf(' ORDER BY %s %s', $sort, $criteria->getOrder());
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        } else {
            $sql .= 'WHERE bl.user_id=u.uid ' . $permission_group_sql ;
        }

//echo $sql ;
		// DB connect
        if (!$result = $this->db->query($sql, $limit, $start)) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $entry = $this->create();
            $entry->assignVars($myrow);
            if ($id_as_key) {
                $ret[$myrow['blog_id']] =& $entry;
            } else {
                $ret[] =& $entry;
            }
            unset($entry);
        }
        return $ret;
    }

    function incrementReads($blog_id) {
        $blog_id = intval($blog_id);
        $sql = sprintf('UPDATE %s SET `reads` = `reads` + 1 WHERE blog_id=%d',
                       $this->db->prefix($this->mydirname), $blog_id);
        if (!$result = $this->db->queryF($sql)) {  // must be queryF()
            return -1;
        }
        $sql = sprintf('SELECT `reads` FROM %s WHERE blog_id=%d',
                       $this->db->prefix($this->mydirname), $blog_id);
        if (!$result = $this->db->query($sql)) {
            return -1;
        }
        $reads = $this->db->fetchArray($result);
        return $reads['reads'];
    }

    function incrementTrackbacks($blog_id , $increment_num=1) {
        $blog_id = intval($blog_id);
		if( $increment_num > 0 ){
			$increment_num = "+" . $increment_num ;
		}elseif( $increment_num < 0 ){
			$increment_num = "-" . abs($increment_num) ;
		}else{
			return true ;
		}
        $sql = sprintf('UPDATE %s SET trackbacks = trackbacks %s WHERE blog_id=%d',
                       $this->db->prefix($this->mydirname), $increment_num , $blog_id);
        if (!$result = $this->db->queryF($sql)) {  // must be queryF()
            return -1;
        }
		return true ;
    }

    function updateComments($blog_id, $total_num) {
        $blog_id = intval($blog_id);
        $total_num = intval($total_num);
        $sql = sprintf('UPDATE %s SET comments=%d WHERE blog_id=%d',
                       $this->db->prefix($this->mydirname),
                       $total_num, $blog_id);
        if (!$result = $this->db->queryF($sql)) {  // must be queryF()
            return -1;
        }

        $sql = sprintf('SELECT comments FROM %s WHERE blog_id=%d',
                       $this->db->prefix($this->mydirname), $blog_id);
        if (!$result = $this->db->query($sql)) {
            return -1;
        }
        $comments = $this->db->fetchArray($result);
        return $comments['comments'];
    }

    function getPrevNextBlog_id($blog_id , $created , $criteria) {
        $return_id = array() ;
        $blog_id = intval($blog_id);
		$created = intval($created) ;
		if( $criteria->render() ){
			$extra = ' and ' . $criteria->render() ;
		}else{
			$extra = '' ;
		}
		if( $created <= 0 && $blog_id < 0 ){
			return $return_id ;
		}elseif( $created < 0 ){
			$rs = $this->db->query( sprintf('select created from %s where blog_id=%d' ,  $this->db->prefix($this->mydirname) , $blog_id) ) ;
			$result = $this->db->fetchArray($rs) ;
			$created = $result['created'] ;
		}

        $sql_prev = sprintf('SELECT blog_id FROM %s where created<%d %s order by created desc limit 1', $this->db->prefix($this->mydirname) ,$created , $extra );
        $sql_next = sprintf('SELECT blog_id FROM %s where created>%d %s order by created limit 1', $this->db->prefix($this->mydirname), $created , $extra  );
        if ($result = $this->db->query($sql_prev)) {
            if($prev = $this->db->fetchArray($result)){
				$return_id['prev'] = $prev['blog_id'] ;
			}
        }
        if ($result = $this->db->query($sql_next)) {
            if( $next = $this->db->fetchArray($result) ){
				$return_id['next'] = $next['blog_id'] ;
			}
        }
        return $return_id;
    }
}
}


// for module duplicate
$entry_class =  strval(ucfirst($mydirname) . 'Entry') ;
if( ! defined($entry_class) && isset($GLOBALS['mydirname']) ){
	define($entry_class , 'DEFINED CLASS') ;

eval('
	class '. ucfirst($GLOBALS['mydirname']) .'Entry extends WeblogEntryBase{
		var $mydirname="'. $GLOBALS['mydirname'] .'" ;
	    function ' . ucfirst($GLOBALS['mydirname']) . 'Entry() {
				$this->WeblogEntryBase() ;
		}
	}
') ;

eval('
	class '. ucfirst($GLOBALS['mydirname']) .'EntryHandler extends WeblogEntryHandlerBase{
		var $mydirname="'. $GLOBALS['mydirname'] .'" ;
	    function &create() {
	        $ret = new ' . ucfirst($GLOBALS['mydirname']) . 'Entry();
	        return  $ret;
	    }
	}
') ;

}
?>
