<?php
/*
 * $Id: class.weblogcategories.php,v 1.3 2005/05/06 18:53:29 tohokuaiki Exp $
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
 *
 */

class WeblogCategories {

    var $handler;
    
    function WeblogCategories() {
        $this->handler =& xoops_getmodulehandler('category');
    }

    function &getInstance() {
        static $instance;
        if (!isset($instance)) {
            $instance = new WeblogCategories();
        }
        return $instance;
    }

    function &newInstance() {
        return $this->handler->create();
    }

    function getCategoriesByParent($cat_pid=0, $start=0, $perPage=0, $order='ASC') {
        $criteria = new criteriaCompo(new criteria('cat_pid', $cat_pid));
        $criteria->setSort('cat_title');
        $criteria->setOrder($order);
        if ($start > 0) {
            $criteria->setStart($start);
        }
        if ($perPage > 0 ) {
            $criteria->setLimit($perPage);
        }
        $result =& $this->handler->getObjects($criteria);
        return $result;
    }

    function getCategory($cat_id) {
        $criteria = new criteriaCompo(new criteria('cat_id', $cat_id));
        $result =& $this->handler->getObjects($criteria, true);
        if (isset($result[$cat_id])) {
            return $result[$cat_id];
        } else {
            return $result;
        }
    }

    function getAllChildrenIds($cat_id) {
        return $this->handler->getAllChildrenIds($cat_id);
    }

    function getFirstChildren($cat_id) {
        return $this->handler->getFirstChildren($cat_id);
    }

    function getCategoryPath($cat_id, $delim='/') {
        $self =& $this->getCategory($cat_id);
        $parents = $this->handler->getParents($cat_id);
        $path = '';
        foreach($parents as $p) {
            $path .= $delim.$p->getVar('cat_title');
        }
        $path .= $delim.$self->getVar('cat_title');
        return substr($path, strlen($delim));
    }

    function getNicePathFromId($cat_id, $url) {
        return $this->handler->getNicePathFromId($cat_id, $url);
    }

    function getMySelectBox($cat_id=0,$none=0,$sel_name="") {
        return $this->handler->getMySelectBox($cat_id,$none,$sel_name);
    }
   
    /**
	 *@return category tree array with counts
	 *@remarks added by hodaka
	 */
	function getChildTreeArray($cat_id=0, $order="",$none=0) {
	 	return $this->handler->getChildTreeArray($cat_id, $order,$none);
	}
	
}
?>