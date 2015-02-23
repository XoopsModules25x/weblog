<?php
/*
 * $Id: class.weblog.php 11979 2013-08-25 20:45:24Z beckmi $
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

include_once(sprintf('%s/modules/%s/class/class.weblogtree.php', XOOPS_ROOT_PATH, $xoopsModule->dirname()));

class Weblog {

    var $handler;

    function Weblog() {
        $this->handler =& xoops_getmodulehandler('entry');
    }

    function &getInstance() {
        static $instance;
        if (!isset($instance)) {
            $instance = new Weblog();
        }
        return $instance;
    }

    function &newInstance() {
        return $this->handler->create();
    }

    function saveEntry(&$entry) {
        return $this->handler->insert($entry);
    }

    function removeEntry($blog_id) {
        $entry =& $this->handler->create();
        $entry->setVar('blog_id', intval($blog_id));
        return $this->handler->delete($entry);
    }

    function isOwner($blog_id, $user_id) {
        $criteria = new criteriaCompo(new criteria('user_id', $user_id));
        $criteria->add(new criteria('blog_id', $blog_id));
        $count = $this->handler->getCount($criteria);
        if ($count == 1) {
            return true;
        }
        return false;
    }

    function getCountByUser($currentuid, $user_id=0) {
        $criteria = new criteriaCompo(new criteria('user_id', $currentuid));
        $criteria->add(new criteria('private', 'N'), 'OR');
        if ($user_id > 0) {
            $criteria = new criteriaCompo($criteria);
            $criteria->add(new criteria('user_id', $user_id));
        }
        return $this->handler->getCount($criteria);
    }

    function getCountByCategory($currentuid, $cat_id, $user_id=0) {
        $criteria = new criteriaCompo(new criteria('user_id', $currentuid));
        $criteria->add(new criteria('private', 'N'), 'OR');
        $criteria = new criteriaCompo($criteria);
        if ($cat_id>0) {
            $criteria->add(new criteria('cat_id', $cat_id));
        }
        if ($user_id > 0) {
            $criteria->add(new criteria('user_id', $user_id));
        }
        return $this->handler->getCount($criteria);
    }

    function getCountByDate($currentuid, $cat_id, $user_id=0, $date, $useroffset) {
        $criteria = new criteriaCompo(new criteria('user_id', $currentuid));
        $criteria->add(new criteria('private', 'N'), 'OR');
        $criteria = new criteriaCompo($criteria);
        if ($date>0) {
			$date_num = strlen(strval($date)) ;
			$criteria->add( new criteria(sprintf('left(from_unixtime(created+%d)+0,%d)',$useroffset*3600 , $date_num) , $date) ) ;
        }
        if ($cat_id>0) {
            $criteria->add(new criteria('cat_id', $cat_id));
        }
        if ($user_id > 0) {
            $criteria->add(new criteria('user_id', $user_id));
        }
        return $this->handler->getCount($criteria);
    }

    function getEntries($currentuid, $user_id=0, $start=0, $perPage=0, $order='DESC', $useroffset=0) {
        $criteria = new criteriaCompo(new criteria('user_id', $currentuid));
        $criteria->add(new criteria('private', 'N'), 'OR');
        if ($user_id > 0) {
            $criteria = new criteriaCompo($criteria);
            $criteria->add(new criteria('user_id', $user_id));
        }
        $criteria->setSort('created');
        $criteria->setOrder($order);
        if ($start > 0) {
            $criteria->setStart($start);
        }
        if ($perPage > 0 ) {
            $criteria->setLimit($perPage);
        }
        $result =& $this->handler->getObjects($criteria, false, 'details', $useroffset);
        return $result;
    }

    function getEntriesByCreated($currentuid, $from, $to, $user_id=0, $start=0, $perPage=0, $order='DESC') {
        $criteria = new criteriaCompo(new criteria('user_id', $currentuid));
        $criteria->add(new criteria('private', 'N'), 'OR');
        $criteria = new criteriaCompo($criteria);
        $criteria->add(new criteria('created', $from, '>'));
        $criteria->add(new criteria('created', $to, '<'));
        if ($user_id > 0) {
            $criteria->add(new criteria('user_id', $user_id));
        }
        $criteria->setSort('created');
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

    function getEntriesByCategory($currentuid, $cat_id=0, $user_id=0, $start=0, $perPage=0, $order='DESC', $useroffset=0) {
        $criteria = new criteriaCompo(new criteria('user_id', $currentuid));
        $criteria->add(new criteria('private', 'N'), 'OR');
        $criteria = new criteriaCompo($criteria);
        if ($cat_id>0) {
            $criteria->add(new criteria('cat_id', $cat_id));
        }
        if ($user_id > 0) {
            $criteria->add(new criteria('user_id', $user_id));
        }
        $criteria->setSort('created');
        $criteria->setOrder($order);
        if ($start > 0) {
            $criteria->setStart($start);
        }
        if ($perPage > 0 ) {
            $criteria->setLimit($perPage);
        }
        $result =& $this->handler->getObjects($criteria, false , 'details', $useroffset);
        return $result;
    }


    function getEntry($currentuid, $blog_id=0, $user_id=0, $useroffset=0) {
        $criteria = new criteriaCompo(new criteria('user_id', $currentuid));
        $criteria->add(new criteria('private', 'N'), 'OR');
        $criteria = new criteriaCompo($criteria);
        $criteria->add(new criteria('blog_id', $blog_id));
        if ($user_id > 0) {
            $criteria->add(new criteria('user_id', $user_id));
        }
        $result =& $this->handler->getObjects($criteria, true, 'details', $useroffset);
        if (isset($result[$blog_id])) {
            return $result[$blog_id];
        } else {
            return $result;
        }
    }

    function incrementReads($blog_id) {
        return $this->handler->incrementReads($blog_id);
    }

    function updateComments($blog_id, $total_num) {
        return $this->handler->updateComments($blog_id, $total_num);
    }

    function getPrevNext($blog_id , $created , $currentuid=0 , $isAdmin=0 , $cat_id=0 , $user_id=0 ){
		if( empty($isAdmin) ){
	        $criteria = new criteriaCompo(new criteria('user_id', $currentuid));
	        $criteria->add(new criteria('private', 'N'), 'OR');
		}else{
			$criteria = NULL ;
		}
        $criteria = new criteriaCompo($criteria);
		if( $cat_id > 0){
	        $criteria->add(new criteria('cat_id', $cat_id));
		}
        if ($user_id > 0) {
            $criteria->add(new criteria('user_id', $user_id));
        }
		return $this->handler->getPrevNextBlog_id($blog_id , $created , $criteria) ;
    }

/**
* get count of categories specified in array
* @author hodaka <hodaka@hodaka.net>
*/
    function getCountByCategoryArray($currentuid, $cid_array=array(), $user_id=0) {
        $criteria = new criteriaCompo(new criteria('user_id', $currentuid));
        $criteria->add(new criteria('private', 'N'), 'OR');
        $criteria = new criteriaCompo($criteria);
		$criteria->add(new Criteria('cat_id', "(".implode(',', $cid_array).")", 'IN'));

        if ($user_id > 0) {
            $criteria->add(new criteria('user_id', $user_id));
        }
        return $this->handler->getCount($criteria);
    }



	function getEntriesForArchives($currentuid , $blogger_id , $date , $cat_id , $start=0 , $perPage=0 , $order='DESC' , $useroffset=0 ){
		$date = intval($date) ;
		// basic
        $criteria = new criteriaCompo(new criteria('user_id', $currentuid));
        $criteria->add(new criteria('private', 'N'), 'OR');
        $criteria = new criteriaCompo($criteria);
		// by user_id
		if( $blogger_id > 0 )
			$criteria->add( new criteria('user_id', $blogger_id) ) ;
		// by category
		if( $cat_id > 0 )
			$criteria->add( new criteria('cat_id', $cat_id) ) ;
		// by date
		if( $date > 0 ){
			$date_num = strlen(strval($date)) ;
			$criteria->add( new criteria(sprintf('left(from_unixtime(created+%d)+0,%d)',$useroffset*3600 , $date_num) , $date) ) ;
		}
		// order , start , Limit
        $criteria->setSort('created');
        $criteria->setOrder($order);
        if ($start > 0) {
            $criteria->setStart($start);
        }
        if ($perPage > 0 ) {
            $criteria->setLimit($perPage);
        }
        $result =& $this->handler->getObjects($criteria, false, 'details' , $useroffset);
        return $result;
	}

/**
* get entries of categories specified in array sorted order by created for archive.php
* @author hodaka <hodaka@hodaka.org>
*/
    function getLatestEntriesByCategoryArray($currentuid, $cid_array=array(), $user_id=0, $start=0, $perPage=0, $order='DESC') {
        $criteria = new criteriaCompo(new criteria('user_id', $currentuid));
        $criteria->add(new criteria('private', 'N'), 'OR');
        $criteria = new criteriaCompo($criteria);
		$criteria->add(new Criteria('cat_id', "(".implode(',', $cid_array).")", 'IN'));
        if ($user_id > 0) {
            $criteria->add(new criteria('user_id', $user_id));
        }
        $criteria->setSort('created');
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

/**
* get entries of categories specified in array sorted order by cat_id, created for index.php
* @author hodaka <hodaka@hodaka.org>
*/
    function getEntriesByCategoryArray($currentuid, $cid_array=array(), $user_id=0, $start=0, $perPage=0, $order='DESC') {
        $criteria = new criteriaCompo(new criteria('user_id', $currentuid));
        $criteria->add(new criteria('private', 'N'), 'OR');
        $criteria = new criteriaCompo($criteria);
		$criteria->add(new Criteria('cat_id', "(".implode(',', $cid_array).")", 'IN'));
        if ($user_id > 0) {
            $criteria->add(new criteria('user_id', $user_id));
        }
        $criteria->setSort('cat_id, created');
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

}
?>
