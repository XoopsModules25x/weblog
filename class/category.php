<?php
/*
 * $Id: category.php 11979 2013-08-25 20:45:24Z beckmi $
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

global $xoopsModule;

include_once(sprintf('%s/kernel/object.php', XOOPS_ROOT_PATH));
include_once(sprintf('%s/modules/%s/class/class.weblogtree.php', XOOPS_ROOT_PATH, $xoopsModule->dirname()));

if( ! isset($mydirname) ) $mydirname = basename( dirname( dirname( __FILE__ ) ) ) ;

if( ! class_exists('WeblogCategoryBase') ){

class WeblogCategoryBase extends XoopsObject {

    var $mydirname='' ;        // abstruct

    /**
     * Constructs an instance of this class
     */
    function __construct() {
        parent::__construct();

        $this->initVar("cat_id", XOBJ_DTYPE_INT, 0, false);
        $this->initVar("cat_pid", XOBJ_DTYPE_INT, 0, true);
        $this->initVar("cat_created", XOBJ_DTYPE_INT, 0, true);
        $this->initVar("cat_title", XOBJ_DTYPE_TXTBOX, null, true, 50, true);
        $this->initVar("cat_description", XOBJ_DTYPE_TXTBOX, null, false, null, true);
        $this->initVar("cat_imgurl", XOBJ_DTYPE_URL, null, false, 150, true);
        $this->initVar("postgroup", XOBJ_DTYPE_ARRAY);
    }
}

class WeblogCategoryHandlerBase extends XoopsObjectHandler {

    var $mydirname='' ;        // abstruct
    var $mytree;

    function &create() {
        return new WeblogCategoryBase();    //abstruct
    }

    function &get($cat_id) {
        $cat_id = intval($cat_id);
        if ($cat_id > 0 ) {
            $sql = sprintf('SELECT cat_id, cat_pid, cat_title, cat_created, cat_description, cat_imgurl FROM %s WHERE cat_id=%d',
                           $this->db->prefix($this->mydirname.'_category'), $cat_id);
            if ($result = $this->db->query($sql)) {
                if ($this->db->getRowsNum($result)==1) {
                    $cat = $this->create();
                    $cat->assignVars($this->db->fetchArray($result));

                    return $cat;
                }
            }
        }

        return false;
    }

    function insert(&$category) {
        if (strtolower(get_parent_class($category)) != 'weblogcategorybase') {  // must be lowercase only
            return false;
        }
        if (!$category->isDirty()) {
            return true;
        }
        if (!$category->cleanVars()) {
            return false;
        }

        foreach ($category->cleanVars as $k => $v) {
            ${$k} = $v;
        }

        $count = $this->getCount(new Criteria('cat_id', $cat_id));
        if ($cat_id > 0 && $count > 0) {
            $sql = sprintf('UPDATE %s SET cat_pid=%d, cat_created=%d, cat_title=%s, cat_description=%s, cat_imgurl=%s WHERE cat_id=%d',
                           $this->db->prefix($this->mydirname.'_category'),
                           $cat_pid, $cat_created,
                           $this->db->quoteString($cat_title),
                           $this->db->quoteString($cat_description),
                           $this->db->quoteString($cat_imgurl),
                           $cat_id);
        } else {
            $sql = sprintf('INSERT INTO %s (cat_pid, cat_created, cat_title, cat_description, cat_imgurl) VALUES (%d, %d, %s, %s, %s)',
                           $this->db->prefix($this->mydirname.'_category'),
                           $cat_pid, $cat_created,
                           $this->db->quoteString($cat_title),
                           $this->db->quoteString($cat_description),
                           $this->db->quoteString($cat_imgurl));
        }

        if (!$result = $this->db->queryF($sql)) {  // must be queryF()
            return false;
        }

        if (empty($cat_id)) {
            $category->setVar('cat_id', $this->db->getInsertId());
        }

        return true;
    }

    function delete(&$category) {
        if (strtolower(get_parent_class($category)) != 'weblogcategorybase') {
            return false;
        }

        $sql = sprintf('DELETE FROM %s WHERE cat_id=%d LIMIT 1',
                       $this->db->prefix($this->mydirname.'_category'), $category->getVar('cat_id'));
        if (!$result = $this->db->queryF($sql)) {  // must be queryF()
            return false;
        }

        return true;
    }

    function getCount($criteria=null) {
        $sql = sprintf('SELECT count(*) as count FROM %s', $this->db->prefix($this->mydirname.'_category'));
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= sprintf(' %s', $criteria->renderWhere());
        }
        if (!$result = $this->db->query($sql)) {
            return 0;
        }
        $count = $this->db->fetchArray($result);

        return $count['count'];
    }

    function &getObjects($criteria=null, $id_as_key=false) {
        $ret = array();
        $limit = $start = 0;

        $sql = sprintf('SELECT * FROM %s',
                       $this->db->prefix($this->mydirname.'_category'));
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= sprintf(' %s', $criteria->renderWhere());
            //$groupby = trim(str_replace('GROUP BY', "", $criteria->getGroupby()));
            //$sql .= ($groupby=='')?'':sprintf(' %s', $criteria->getGroupby());
            $sort = ($criteria->getSort()!='') ? $criteria->getSort() : 'cat_id';
            $sql .= sprintf(' ORDER BY %s %s', $sort, $criteria->getOrder());
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        if (!$result = $this->db->query($sql, $limit, $start)) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $category = $this->create();
            $category->assignVars($myrow);
            if ($id_as_key) {
                $ret[$myrow['cat_id']] =& $category;
            } else {
                $ret[] =& $category;
            }
            unset($category);
        }

        return $ret;
    }

    function getAllChildrenIds($cat_id) {
        $mytree =& $this->getTreeInstance();//new WeblogTree($this->db->prefix('_category'), 'cat_id', 'cat_pid');
        return $mytree->getAllChildId($cat_id);
    }

    function getFirstChildren($cat_id) {
        $mytree =& $this->getTreeInstance();//new XoopsTree($this->db->prefix('_category'), 'cat_id', 'cat_pid');
        $subarr = $mytree->getFirstChildId($cat_id, 'cat_id');
        $criteria = new criteriaCompo(new criteria('cat_id', -1));
        foreach ($subarr as $sub) {
            $criteria->add(new criteria('cat_id', $sub), 'OR');
        }
        $subcat =& $this->getObjects($criteria);

        return $subcat;
    }

    function getParents($cat_id) {
        $mytree =& $this->getTreeInstance();//new WeblogTree($this->db->prefix('_category'), 'cat_id', 'cat_pid');
        $arr = $mytree->getAllParentId($cat_id);
        $parents = array();
        foreach($arr as $p) {
            $parents[] = $this->get($p);
        }

        return $parents;
    }

    function getNicePathFromId($cat_id, $url) {
        $mytree =& $this->getTreeInstance();//new XoopsTree($this->db->prefix('_category'), 'cat_id', 'cat_pid');
        return $mytree->getNicePathFromId($cat_id, 'cat_title', $url);
    }

    function getMySelectBox($cat_id=0, $none=0, $sel_name="") {
        $mytree =& $this->getTreeInstance();
        ob_start();
        $mytree->makeMySelBox('cat_title', 'cat_title', $cat_id, $none, $sel_name);
        $selbox = ob_get_contents();
        ob_end_clean();

        return $selbox;
    }

    /**
     * @get category tree array
     * @returns array
     * created by hodaka
     */
     function getChildTreeArray($cat_id=0, $order="") {
        $mytree =& $this->getTreeInstance();

        return $mytree->getChildTreeArray($cat_id, $order);
     }

    /**
     * @access private
     */
    function &getTreeInstance() {
        static $instance;
        if (!isset($instance)) {
            $instance = new wbXoopsTree($this->db->prefix($this->mydirname.'_category'), 'cat_id', 'cat_pid');
        }

        return $instance;
    }
}

class wbXoopsTree extends WeblogTree{

    //constructor
    function __constructor($table_name, $id_name, $pid_name){
        $this->__construct($table_name, $id_name, $pid_name);
    }

    // override
    function makeMySelBox($title,$order="",$preset_id=0, $none=0, $sel_name="", $onchange=""){
        global $xoopsModule , $xoopsModuleConfig , $xoopsUser ;

        $modid = $xoopsModule->getVar('mid') ;

        if ( $sel_name == "" ) {
            $sel_name = $this->id;
        }
        $myts =& MyTextSanitizer::getInstance();
        echo "<select name='".$sel_name."'";
        if ( $onchange != "" ) {
            echo " onchange='".$onchange."'";
        }
        echo ">\n";
        // Admin or not using post permission ,show all categories
        if( ( is_object($xoopsUser) && get_class($xoopsUser) == "xoopsuser" && $xoopsUser->isAdmin($modid) ) ||
             ( ! isset($xoopsModuleConfig['category_post_permission']) || ! $xoopsModuleConfig['category_post_permission'] ) ){
            $sql = sprintf("SELECT %s, %s FROM %s WHERE %s=0 "
                       , $this->id , $title , $this->table, $this->pid );
        }else{
            $sql = sprintf("SELECT %s, %s FROM %s, %s WHERE %s=0 "
                           , $this->id , $title , $this->table, $this->db->prefix('group_permission') , $this->pid );
            $sql .= $this->weblog_cat_gpermsql();
            $sql .= " group by cat_id " ;
        }
        if ( $order != "" ) {
            $sql .= " ORDER BY $order";
        }
//		$fp = fopen("/tmp/log.sql","a");
//fputs($fp,$sql. $isadmin . "\n") ;
$result = $this->db->query($sql);
        if ( $none ) {
            echo "<option value='0'>----</option>\n";
        }
        while ( list($catid, $name) = $this->db->fetchRow($result) ) {
            $sel = "";
            if ( $catid == $preset_id ) {
                $sel = " selected='selected'";
            }
            echo "<option value='$catid'$sel>$name</option>\n";
            $sel = "";
            $arr = $this->getChildTreeArray($catid, $order);
            foreach ( $arr as $option ) {
                $option['prefix'] = str_replace(".","--",$option['prefix']);
                $catpath = $option['prefix']."&nbsp;".$myts->htmlSpecialChars($option[$title]);
                if ( $option[$this->id] == $preset_id ) {
                    $sel = " selected='selected'";
                }
                echo "<option value='".$option[$this->id]."'$sel>$catpath</option>\n";
                $sel = "";
            }
        }
        echo "</select>\n";
    }

    // override
    function getChildTreeArray($sel_id=0,$order="",$parray = array(),$r_prefix=""){
        global $xoopsModule , $xoopsModuleConfig , $xoopsUser ;

        $modid = $xoopsModule->getVar('mid') ;

        // Admin or not using post permission ,show all categories
        if( ( is_object($xoopsUser) && get_class($xoopsUser) == "xoopsuser" && $xoopsUser->isAdmin($modid) ) ||
            ( ! isset($xoopsModuleConfig['category_post_permission']) || ! $xoopsModuleConfig['category_post_permission'] ) ){
            $sql = sprintf("SELECT * FROM %s WHERE %s=%s "
                            , $this->table, $this->pid , $sel_id ) ;
        }else{
            $sql = sprintf("SELECT * FROM %s, %s WHERE %s=%d " ,
                            $this->table , $this->db->prefix('group_permission') , $this->pid , $sel_id, $modid );
            $sql .= $this->weblog_cat_gpermsql();
            $sql .= " group by cat_id " ;
        }
        if ( $order != "" ) {
            $sql .= " ORDER BY $order";
        }
//echo $sql ;
        $result = $this->db->query($sql);
        $count = $this->db->getRowsNum($result);
        if ( $count == 0 ) {
            return $parray;
        }
        while ( $row = $this->db->fetchArray($result) ) {
            $row['prefix'] = $r_prefix.".";
            array_push($parray, $row);
            $parray = $this->getChildTreeArray($row[$this->id],$order,$parray,$row['prefix']);
        }

        return $parray;
    }

    // create where phrase (in case of using category post permission system)
    function weblog_cat_gpermsql(){
        global $xoopsUser , $xoopsModule ;

        $modid = $xoopsModule->getVar('mid') ;

        $whr_phrase = "" ;
        // only post.php
        if( basename($_SERVER['SCRIPT_NAME']) != "post.php" )
            return "" ;
        // get user groups
        if( isset($xoopsUser) && get_class($xoopsUser) == "xoopsuser" ){
            $currentuid = $xoopsUser->getVar('uid');
            $currentusergroup = $xoopsUser->getGroups();
            $isAdmin = $xoopsUser->isAdmin($modid);
            if( $isAdmin )
                return "" ;
        }else{
            $currentuid = 0 ;
            $currentusergroup = array(XOOPS_GROUP_ANONYMOUS) ;
            $isAdmin = false ;
        }
        foreach( $currentusergroup as $groupid ){
            $whr_phrase .= sprintf(" gperm_groupid=%d or " , $groupid) ;
        }
        $whr_phrase = rtrim($whr_phrase , "or ") ;

        return sprintf(" and gperm_modid=%d and cat_id=gperm_itemid and gperm_name='weblog_cat_post' " , $modid ) . " and (" . $whr_phrase . ") " ;
    }
}
}

// for module duplicate
$entry_class =  strval(ucfirst($mydirname) . 'Category') ;
if( ! defined($entry_class) ){
    define($entry_class , 'DEFINED CLASS') ;

eval('
	class '. ucfirst($GLOBALS['mydirname']) .'Category extends WeblogCategoryBase{
		var $mydirname="'. $GLOBALS['mydirname'] .'" ;
	    function ' . ucfirst($GLOBALS['mydirname']) . 'Category() {
				$this->WeblogCategoryBase() ;
		}
	}
') ;
eval('
	class '. ucfirst($GLOBALS['mydirname']) .'CategoryHandler extends WeblogCategoryHandlerBase{
		var $mydirname="'. $GLOBALS['mydirname'] .'" ;
	    function &create() {
	        $ret = new ' . ucfirst($GLOBALS['mydirname']) . 'Category();
	        return $ret;
	    }
	}
') ;
}
