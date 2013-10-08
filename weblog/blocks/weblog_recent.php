<?php
/*
 * $Id: weblog_recent.php 11979 2013-08-25 20:45:24Z beckmi $
 * Copyright (c) 2003 by Jeremy N. Cowgar <jc@cowgar.com>
 * Copyright (c) 2003 by wellwine <http://wellwine.zive.net>
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

if( ! defined( 'WEBLOG_BLOCK_RECENT_INCLUDED' ) ) {
define( 'WEBLOG_BLOCK_RECENT_INCLUDED' , 1 ) ;


// $options[0] is always weblog dirname.
/*
 * $options[1] = max results
 * $options[2] = use avatars
 * $options[3] = link to entry list
 */
function b_weblog_top_weblogs_show($options) {
    global $xoopsDB, $xoopsUser;


	$mydirname = empty( $options[0] ) ? basename( dirname( dirname( __FILE__ ) ) ) : $options[0] ;
    $max_results = $options[1];
    $use_avatars = $options[2];
    $link_entries = $options[3];

    $currentuid = !empty($xoopsUser) ? $xoopsUser->getVar('uid','E') : 0;

    $block = array();
    $rank = 0;

    $sql = sprintf('SELECT b.user_id, b.created, u.uname, u.user_avatar, count(b.blog_id) as count FROM %s as b, %s as u',
                   $xoopsDB->prefix($mydirname), $xoopsDB->prefix('users'));
    $sql = sprintf('%s WHERE (b.private = \'N\' OR b.user_id=\'%d\')  AND b.user_id=u.uid GROUP BY b.user_id ORDER BY count DESC, b.created DESC',
                   $sql, $currentuid);
    $result = $xoopsDB->query($sql, $max_results, 0);

    while ($myrow = $xoopsDB->fetchArray($result)) {
        $rank++;

        // Get the user, and retrieve his avatar
        $entry = array();
        $entry['rank'] = $rank;

        // Retrieve his/her avatar
        $entry['use_avatar'] = 0;
        $entry['avatar_img'] = '';
        if ($use_avatars==1) {
            $avatar = $myrow['user_avatar'];
            if (!empty($avatar) && $avatar != 'blank.gif') {
                $entry['use_avatar'] = 1;
                $entry['avatar_img'] = sprintf('%s/uploads/%s', XOOPS_URL, $avatar);
            }
        }
        if ($link_entries==1) {
            $entry['profile_uri'] = sprintf('%s/modules/%s/index.php?user_id=%d',
                                            XOOPS_URL, $mydirname, $myrow['user_id']);
        } else {
            $entry['profile_uri'] = sprintf('%s/userinfo.php?uid=%d', XOOPS_URL, $myrow['user_id']);
        }
        $entry['uname'] = $myrow['uname'];
        $entry['count'] = $myrow['count'];
        $block['entries'][] = $entry;
    }
    return $block;
}

/*
 * $options[1] = max results
 * $options[2] = use avatars
 * $options[3] = link to entry list
 */
function b_weblog_top_weblogs_edit($options) {
	$mydirname = empty( $options[0] ) ? basename( dirname( dirname( __FILE__ ) ) ) : $options[0] ;
    $form  = '<table>';
    $form .= "<input type='hidden' name='options[]' value='$mydirname' />\n" ;
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%d" /></td></tr>',
                     _MB_WEBLOG_EDIT_NUMBER_OF_ENTRIES, intval($options[1]));
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%d" /></td></tr>',
                     _MB_WEBLOG_EDIT_USE_AVATARS, intval($options[2]));
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%d" /></td></tr>',
                     _MB_WEBLOG_EDIT_LINK_TO_LIST, intval($options[3]));
    $form .= '</table>';
    return $form;
}

/*
 * $options[1] = number of entries to show
 * $options[2] = max size of the title
 * $options[3] = date format
 * $options[4] = use avatars (only in large mode)
 * $options[5] = size (1=small, 2=midium, 3=large)
 * $options[6] = link to entry list (only when author names shown)
 * $options[7] = show entry content or not
 * $options[8] = max size of entry.(if this value is zero , not show )
 */

function b_weblog_recent_show($options) {
    global $xoopsDB, $xoopsUser, $xoopsConfig;

	$mydirname = empty( $options[0] ) ? basename( dirname( dirname( __FILE__ ) ) ) : $options[0] ;
    $max_entries = $options[1];
    $max_size = $options[2];
    $date_format = $options[3];
    $use_avatars = $options[4];
    $block_size = $options[5];
    $link_entries = $options[6];
	$show_contents = intval($options[7]);
	$max_size_contents = intval($options[8]);

    $currentuid = !empty($xoopsUser) ? $xoopsUser->getVar('uid','E') : 0;
    $user_id = !empty($_GET['user_id']) ? intval($_GET['user_id']) : 0;

	if (is_object($xoopsUser)) {
		$useroffset = $xoopsUser->timezone() - $xoopsConfig['server_TZ'] ;
	} else {
		$useroffset = $xoopsConfig['default_TZ'] - $xoopsConfig['server_TZ'] ; ;
	}


    $myts =& MyTextSanitizer::getInstance();
    $block = array();

    include_once(sprintf('%s/modules/%s/config.php', XOOPS_ROOT_PATH, $mydirname ));
    $wb_configs = get_wb_moduleconfig($mydirname) ;

    list( $bl_contents_field , $permission_group_sql ) = weblog_create_permissionsql($wb_configs , $mydirname) ;

    $sql = sprintf('SELECT bl.blog_id, bl.created+%d as created, bl.user_id, bl.title, %s as contents, bl.comments, bl.`reads`, bl.trackbacks, u.uname, u.user_avatar FROM %s as bl, %s as u',
                   $useroffset*3600, $bl_contents_field , $xoopsDB->prefix( $mydirname ) , $xoopsDB->prefix('users'));
    $sql = sprintf('%s WHERE ((bl.private = \'N\' OR bl.user_id=\'%d\') AND bl.user_id=u.uid %s ) ',
                   $sql, $currentuid , $permission_group_sql );
    if( $user_id )
        $sql = sprintf('%s and bl.user_id=%d' , $sql , $user_id) ;
    $sql .= ' ORDER BY bl.created DESC';

    // Need to figure out how to access the module config while in a block
    //$result = $xoopsDB->query($sql, $xoopsModuleConfig['numinrecentblock'], 0);
    $result = $xoopsDB->query($sql, $max_entries, 0);

    $alart = false ;
    while ($myrow=$xoopsDB->fetchArray($result)) {
        $entry = array();
        $title = $myts->htmlSpecialChars($myrow['title']);
        if ( $block_size != 3) {
            if (strlen($title) >= $max_size) {
                $title = xoops_substr($title, 0, ($max_size -1)) ;
            }
        }

        if( $show_contents && $max_size_contents ){
			if( ! class_exists('WeblogEntryBase') )
				include_once XOOPS_ROOT_PATH . "/modules/" . $mydirname . "/class/entry.php" ;
			$get_entries_mode = ( $max_size_contents < 0 ) ? 'index' : 'rss' ;
            $contents = WeblogEntryBase::parse_viewmode( strip_tags($myts->displayTarea($myrow['contents'])) , $myrow['blog_id'] , $get_entries_mode , $mydirname) ;
			if($max_size_contents > 0)
                $contents = xoops_substr( $contents , 0 , $max_size_contents ) ;
        }else{
			$contents = "" ;
		}
        // Retrieve his/her avatar
        $entry['use_avatar'] = 0;
        $entry['avatar_img'] = '';
        if ($use_avatars==1) {
            $avatar = $myrow['user_avatar'];
            if (!empty($avatar) && $avatar != 'blank.gif') {
                $entry['use_avatar'] = 1;
                $entry['avatar_img'] = sprintf('%s/uploads/%s', XOOPS_URL, $avatar);
            }
        }

        $entry['uname'] = $myts->htmlSpecialChars($myrow['uname']);
        if ($link_entries==1) {
            $entry['profile_uri'] = sprintf('%s/modules/%s/index.php?user_id=%d',
                                            XOOPS_URL, $mydirname, $myrow['user_id']);
        } else {
            $entry['profile_uri'] = sprintf('%s/userinfo.php?uid=%d', XOOPS_URL, $myrow['user_id']);
        }
        $entry['title'] = $title;
        $entry['contents'] = $contents ;
        $entry['entry_url'] = sprintf('%s/modules/%s/details.php?blog_id=%d',
                                      XOOPS_URL, $mydirname, $myrow['blog_id']);
        $entry['date'] = formatTimestamp($myrow['created'], $date_format, $xoopsConfig['default_TZ']);
        $entry['comments'] = $myrow['comments'];
        $entry['trackbacks'] = $myrow['trackbacks'];
        $entry['reads'] = $myrow['reads'];
        $entry['permission'] = bl_permission_alart($myrow['contents']) ;
        if( $entry['permission'] ) $alart = true ;
        $block['entries'][] = $entry;

    }

    $block['type'] = $block_size;
    $block['show_contents'] = ( $show_contents && $max_size_contents ) ? true : false ;
    $block['lang_title'] = _MB_WEBLOG_LANG_TITLE;
    $block['lang_author'] = _MB_WEBLOG_LANG_AUTHOR;
//    $block['lang_comments'] = _MB_WEBLOG_LANG_COMMENTS;
//    $block['lang_trackbacks'] = _MB_WEBLOG_LANG_TRACKBACKS;
    $block['lang_comments'] = 'Com.';
    $block['lang_trackbacks'] = 'TB.';
    $block['lang_posted'] = _MB_WEBLOG_LANG_POSTED;
    $block['lang_reads'] = _MB_WEBLOG_LANG_READS;
    $block['weblogs_url'] = sprintf('%s/modules/%s/index.php', XOOPS_URL, $mydirname);
    $block['lang_moreweblogs'] = _MB_WEBLOG_LANG_MOREWEBLOGS;
	if ($user_id > 0 ) {
	    $blogOwner = new XoopsUser($user_id);
	    $block['lang_whose'] = sprintf(_BL_ENTRIES_FOR, $blogOwner->getVar('uname','E'));
	}
	if( $alart ){
		$block['lang_denote'] = _MB_WEBLOG_LANG_DENOTE_PERMIT ;
	}
/*	if ($user_id > 0 || $currentuid > 0 ) {
		$uid = ( $user_id > 0 )? $user_id : $currentuid ;
	    $blogOwner = new XoopsUser($uid);
	    $block['lang_whosentries'] = sprintf(_BL_ENTRIES_FOR, $blogOwner->getVar('uname','E'));
	} */
	 return $block;
}


function b_weblog_recent_edit($options) {
	$mydirname = empty( $options[0] ) ? basename( dirname( dirname( __FILE__ ) ) ) : $options[0] ;
    $form  = '<table>';
    $form .= "<input type='hidden' name='options[]' value='$mydirname' />\n" ;
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%d" /></td></tr>',
                     _MB_WEBLOG_EDIT_NUMBER_OF_ENTRIES, intval($options[1]));
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%d" /></td></tr>',
                     _MB_WEBLOG_EDIT_MAX_TITLE_LENGTH, intval($options[2]));
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%s" /></td></tr>',
                     _MB_WEBLOG_EDIT_DATE_FORMAT, $options[3]);
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%s" /></td></tr>',
                     _MB_WEBLOG_EDIT_USE_AVATARS, $options[4]);
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%s" /></td></tr>',
                     _MB_WEBLOG_EDIT_TYPE, $options[5]);
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%d" /></td></tr>',
                     _MB_WEBLOG_EDIT_LINK_TO_LIST, intval($options[6]));
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%d" /></td></tr>',
                     _MB_WEBLOG_EDIT_SHOW_CONTENTS, intval($options[7]));
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%d" /></td></tr>',
                     _MB_WEBLOG_EDIT_MAX_CONTENTS_LENGTH, intval($options[8]));
    $form .= '</table>';
    return $form;
}



/*
 * $options[1] = number of users to show
 * $options[2] = number of entries to show
 * $options[3] = date format
 * $options[4] = use avatars (only in large mode)
 * $options[5] = size (1=small, 2=midium, 3=large)
 * $options[6] = what order by (Last entry time or Total Reads)
 * $options[7] = max size of the title
 * $options[8] = show entry content or not
 * $options[9] = max size of entry.(if this value is zero , not show )
 */
function b_weblog_users_weblogs_show($options) {

    global $xoopsDB, $xoopsUser, $xoopsConfig ;

	$mydirname = empty( $options[0] ) ? basename( dirname( dirname( __FILE__ ) ) ) : $options[0] ;
    $max_users = $options[1];
    $max_entries = $options[2];
    $date_format = $options[3];
    $use_avatars = $options[4];
    $block_size = $options[5];
    $order_by = $options[6];
    $max_size = $options[7];
	$show_contents = intval($options[8]);
	$max_size_contents = intval($options[9]);

    $currentuid = !empty($xoopsUser) ? $xoopsUser->getVar('uid','E') : 0;

    $myts =& MyTextSanitizer::getInstance();
    $block = array();

	if (is_object($xoopsUser)) {
		$useroffset = $xoopsUser->timezone() - $xoopsConfig['server_TZ'] ;
	} else {
		$useroffset = $xoopsConfig['default_TZ'] - $xoopsConfig['server_TZ'] ; ;
	}

	if( $order_by == 0 ){	 // last entry time
	    $user_sql = sprintf('select user_id,uname,user_avatar,max(created)+%d as sort_value from %s as bl , %s as u where bl.user_id=u.uid ',
					$useroffset*3600, $xoopsDB->prefix( $mydirname ) , $xoopsDB->prefix('users') );
		$sort_value = _MB_WEBLOG_USERS_SORT_UPDATE ;
	}else{		// order by most reads user
	    $user_sql = sprintf('select user_id,uname,user_avatar,sum(`reads`) as sort_value from %s as bl , %s as u where bl.user_id=u.uid ',
					$xoopsDB->prefix( $mydirname ) , $xoopsDB->prefix('users') );
		$sort_value = _MB_WEBLOG_USERS_SORT_READS ;
	}
	$user_sql .= ' group by user_id order by sort_value desc ' ;

    // Need to figure out how to access the module config while in a block
    //$result = $xoopsDB->query($sql, $xoopsModuleConfig['numinrecentblock'], 0);
    $result_user = $xoopsDB->query($user_sql, $max_users, 0);
    include_once(sprintf('%s/modules/%s/config.php', XOOPS_ROOT_PATH, $mydirname ));
	$wb_configs = get_wb_moduleconfig($mydirname) ;
    list( $bl_contents_field , $permission_group_sql ) = weblog_create_permissionsql($wb_configs , $mydirname) ;

	$users = array() ;
	$alart = false ;
    while ($userrow=$xoopsDB->fetchArray($result_user)) {
		$user_id = $myts->htmlSpecialChars($userrow['user_id']) ;
		$user_sortvalue = ( $order_by == 0 ) ? date(  $date_format , $myts->htmlSpecialChars($userrow['sort_value'])  ) : $myts->htmlSpecialChars($userrow['sort_value']) ;
		$users[$user_id] = array(
				'uname' => $myts->htmlSpecialChars($userrow['uname']) ,
				'avatar_img' => sprintf( '%s/uploads/%s', XOOPS_URL, $myts->htmlSpecialChars($userrow['user_avatar']) ) ,
				'sort_value' => $user_sortvalue ,
				'profile_uri' => sprintf('%s/userinfo.php?uid=%d', XOOPS_URL, $user_id) ,
				'user_blog_uri' => sprintf('%s/modules/%s/index.php?user_id=%d', XOOPS_URL, $mydirname , $user_id) ,
				'entries' => array() ) ;
		// get user's entries
		$entry_sql = sprintf('select blog_id,user_id,created+%d as created,title,%s as contents,`reads`,comments,trackbacks from %s as bl where user_id=%d and (private=\'N\' or user_id=\'%d\')  %s order by created desc ',
								 $useroffset*3600, $bl_contents_field , $xoopsDB->prefix( $mydirname ) , $user_id , $currentuid , $permission_group_sql);
		$result_entry = $xoopsDB->query($entry_sql , $max_entries , 0) ;
		$users[$user_id]['entry_num_plus1'] = $xoopsDB->getRowsNum($result_entry) +1  ;	// used by template rowspan
		while( $entryrow = $xoopsDB->fetchArray($result_entry) ){
	        $entry_url = sprintf('%s/modules/%s/details.php?blog_id=%d', XOOPS_URL, $mydirname, $entryrow['blog_id']);
            if( $show_contents && $max_size_contents ){
				if( ! class_exists('WeblogEntryBase') )
					include_once XOOPS_ROOT_PATH . "/modules/" . $mydirname . "/class/entry.php" ;
				$get_entries_mode = ( $max_size_contents < 0 ) ? 'index' : 'rss' ;
				$contents = WeblogEntryBase::parse_viewmode( strip_tags($myts->displayTarea($entryrow['contents'])) , $entryrow['blog_id'] ,$get_entries_mode , $mydirname) ;
				if($max_size_contents > 0)
					$contents = xoops_substr( $contents , 0 , $max_size_contents ) ;
			}else{
				$contents = "" ;
			}
			$users[$user_id]['entries'][] = array(
				'created' => date( $date_format , $myts->htmlSpecialChars($entryrow['created']) ) ,
				'title' => xoops_substr($myts->htmlSpecialChars($entryrow['title']) , 0 , $max_size),
				'contents' => $contents ,
				'entry_url' => $entry_url ,
				'reads' => $myts->htmlSpecialChars($entryrow['reads']) ,
				'comments' => $myts->htmlSpecialChars($entryrow['comments']) ,
				'trackbacks' => $myts->htmlSpecialChars($entryrow['trackbacks']) ,
				'permission' => bl_permission_alart($entryrow['contents'])
			) ;
			if( bl_permission_alart($entryrow['contents']) ) $alart = true ;
		}
	}
	$block['users'] =& $users ;
    $block['type'] = $block_size;
    $block['show_contents'] = ( $show_contents && $max_size_contents ) ? true : false ;
    $block['use_avatars'] = $use_avatars ;
    $block['lang_user_sort_value'] = $sort_value;
    $block['lang_title'] = _MB_WEBLOG_LANG_TITLE;
    $block['lang_entries'] = _MB_WEBLOG_LANG_ENTRIES;
    $block['lang_author'] = _MB_WEBLOG_LANG_AUTHOR;
//    $block['lang_comments'] = _MB_WEBLOG_LANG_COMMENTS;
//    $block['lang_trackbacks'] = _MB_WEBLOG_LANG_TRACKBACKS;
    $block['lang_comments'] = 'Com.';
    $block['lang_trackbacks'] = 'TB.';
    $block['lang_posted'] = _MB_WEBLOG_LANG_POSTED;
    $block['lang_reads'] = _MB_WEBLOG_LANG_READS;
    $block['lang_moreweblogs'] = _MB_WEBLOG_LANG_MOREWEBLOGS;
    $block['weblogs_url'] = sprintf('%s/modules/%s/index.php', XOOPS_URL, $mydirname);
	if( $alart ){
		$block['lang_denote'] = _MB_WEBLOG_LANG_DENOTE_PERMIT ;
	}

    return $block;
}

function b_weblog_users_weblogs_edit($options) {
	$mydirname = empty( $options[0] ) ? basename( dirname( dirname( __FILE__ ) ) ) : $options[0] ;
    $form  = '<table>';
    $form .= "<input type='hidden' name='options[]' value='$mydirname' />\n" ;
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%d" /></td></tr>',
                     _MB_WEBLOG_EDIT_NUMBER_OF_USERS, intval($options[1]));
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%d" /></td></tr>',
                     _MB_WEBLOG_EDIT_NUMBER_OF_ENTRIES, intval($options[2]));
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%s" /></td></tr>',
                     _MB_WEBLOG_EDIT_DATE_FORMAT, $options[3]);
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%s" /></td></tr>',
                     _MB_WEBLOG_EDIT_USE_AVATARS, $options[4]);
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%s" /></td></tr>',
                     _MB_WEBLOG_EDIT_TYPE, $options[5]);
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%d" /></td></tr>',
                     _MB_WEBLOG_EDIT_ORDER_BY, intval($options[6]));
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%d" /></td></tr>',
                     _MB_WEBLOG_EDIT_MAX_TITLE_LENGTH, intval($options[7]));
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%d" /></td></tr>',
                     _MB_WEBLOG_EDIT_SHOW_CONTENTS, intval($options[8]));
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%d" /></td></tr>',
                     _MB_WEBLOG_EDIT_MAX_CONTENTS_LENGTH, intval($options[9]));
    $form .= '</table>';
    return $form;
}


function b_weblog_recent_tb_show($options) {
    global $xoopsDB, $xoopsUser, $xoopsConfig ;

	$mydirname = empty( $options[0] ) ? basename( dirname( dirname( __FILE__ ) ) ) : $options[0] ;
    $max_trackbacks = $options[1];
    $max_size = $options[2];
    $date_format = $options[3];
    $block_size = $options[4];

    $currentuid = !empty($xoopsUser) ? $xoopsUser->getVar('uid','E') : 0;
    $user_id = !empty($_GET['user_id']) ? intval($_GET['user_id']) : 0;

	if (is_object($xoopsUser)) {
		$useroffset = $xoopsUser->timezone() - $xoopsConfig['server_TZ'] ;
	} else {
		$useroffset = $xoopsConfig['default_TZ'] - $xoopsConfig['server_TZ'] ; ;
	}

    include_once(sprintf('%s/modules/%s/config.php', XOOPS_ROOT_PATH, $mydirname ));
    $wb_configs = get_wb_moduleconfig($mydirname) ;
    list( $bl_contents_field , $permission_group_sql ) = weblog_create_permissionsql($wb_configs , $mydirname) ;

    $myts =& MyTextSanitizer::getInstance();
    $block = array();
	$block['trackbacks'] = array() ;
    $sql = sprintf('SELECT bl.user_id, t.blog_id, if(t.tb_url!=\'\',t.tb_url,t.link) as link , t.blog_name, t.title as tb_title, t.trackback_created+%d as trackback_created ,bl.title as entry_title,%s as contents FROM %s as t , %s as bl',
                   $useroffset*3600, $bl_contents_field , $xoopsDB->prefix($mydirname.'_trackback') ,$xoopsDB->prefix($mydirname) );
    $sql = sprintf('%s WHERE t.blog_id=bl.blog_id and (bl.private = \'N\' OR bl.user_id=\'%d\') and t.direction=\'recieved\' %s ',
                   $sql, $currentuid, $permission_group_sql );
    if( $user_id )
        $sql = sprintf('%s and bl.user_id=%d' , $sql , $user_id) ;
    $sql .= ' ORDER BY t.trackback_created DESC';


    // Need to figure out how to access the module config while in a block
    //$result = $xoopsDB->query($sql, $xoopsModuleConfig['numinrecentblock'], 0);
    $result = $xoopsDB->query($sql, $max_trackbacks, 0);
	$alart = false ;
    while ($myrow=$xoopsDB->fetchArray($result)) {
        $trackback = array();
        $tb_title = $myts->htmlSpecialChars($myrow['tb_title']);
        if ( $block_size != 3) {
            if (strlen($tb_title) >= $max_size) {
                $tb_title = xoops_substr($tb_title, 0, ($max_size -1)) ;
            }
        }

        $trackback['blog_name'] = $myts->htmlSpecialChars($myrow['blog_name']);
        $trackback['link'] = $myts->htmlSpecialChars($myrow['link']);
        $trackback['tb_title'] = $tb_title;
        $trackback['entry_url'] = sprintf('%s/modules/%s/details.php?blog_id=%d',
                                      XOOPS_URL, $mydirname, $myrow['blog_id']);
        $trackback['date'] = formatTimestamp($myrow['trackback_created'], $date_format, $xoopsConfig['default_TZ']);
        $trackback['entry_title'] = $myts->htmlSpecialChars($myrow['entry_title']);
        $trackback['permission'] = bl_permission_alart($myrow['contents']) ;
        if( $trackback['permission'] ) $alart = true ;
        $block['trackbacks'][] = $trackback;
    }

    $block['type'] = $block_size;
    $block['lang_tbtitle'] = _MB_WEBLOG_LANG_TB_TITLE;
    $block['lang_entrytitle'] = _MB_WEBLOG_LANG_TB_WEBLOGTITLE;
    $block['lang_blogname'] = _MB_WEBLOG_LANG_TB_BLOGNAME;
    $block['lang_posted'] = _MB_WEBLOG_LANG_TB_POSTED;
	if ($user_id > 0 ) {
	    $blogOwner = new XoopsUser($user_id);
	    $block['lang_whose'] = sprintf(_BL_TRACKBACKS_FOR, $blogOwner->getVar('uname','E'));
	}
	if( $alart ){
		$block['lang_denote'] = _MB_WEBLOG_LANG_DENOTE_PERMIT ;
	}
    return $block;
}


function b_weblog_recent_tb_edit($options) {
	$mydirname = empty( $options[0] ) ? basename( dirname( dirname( __FILE__ ) ) ) : $options[0] ;
    $form  = '<table>';
    $form .= "<input type='hidden' name='options[]' value='$mydirname' />\n" ;
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%d" /></td></tr>',
                     _MB_WEBLOG_EDIT_NUMBER_OF_TRACKBACKS, intval($options[1]));
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%d" /></td></tr>',
                     _MB_WEBLOG_EDIT_MAX_TITLE_LENGTH, intval($options[2]));
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%s" /></td></tr>',
                     _MB_WEBLOG_EDIT_DATE_FORMAT, $options[3]);
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%s" /></td></tr>',
                     _MB_WEBLOG_EDIT_TYPE, $options[4]);
    $form .= '</table>';
    return $form;
}


function b_weblog_recent_comment_show($options) {
    global $xoopsDB, $xoopsUser, $xoopsConfig ;

	$mydirname = empty( $options[0] ) ? basename( dirname( dirname( __FILE__ ) ) ) : $options[0] ;
    $max_comments = $options[1];
    $max_size = $options[2];
    $date_format = $options[3];
    $block_size = $options[4];

    $currentuid = !empty($xoopsUser) ? $xoopsUser->getVar('uid','E') : 0;
    $user_id = !empty($_GET['user_id']) ? intval($_GET['user_id']) : 0;

	if (is_object($xoopsUser)) {
		$useroffset = $xoopsUser->timezone() - $xoopsConfig['server_TZ'] ;
	} else {
		$useroffset = $xoopsConfig['default_TZ'] - $xoopsConfig['server_TZ'] ; ;
	}

    include_once(sprintf('%s/modules/%s/config.php', XOOPS_ROOT_PATH, $mydirname ));
    $wb_configs = get_wb_moduleconfig($mydirname) ;
    list( $bl_contents_field , $permission_group_sql ) = weblog_create_permissionsql($wb_configs , $mydirname) ;

    $myts =& MyTextSanitizer::getInstance();
    $block = array();
	$block['comments'] = array() ;
    $sql = sprintf('SELECT com_modified+%d as com_modified, com_uid, com_title, com_itemid, title,%s as contents, uname FROM %s as xc , %s as m , %s as bl , %s as u' ,
                    $useroffset*3600, $bl_contents_field , $xoopsDB->prefix('xoopscomments') , $xoopsDB->prefix('modules') ,$xoopsDB->prefix($mydirname) ,$xoopsDB->prefix('users') );
    $sql = sprintf('%s WHERE com_modid=mid and dirname=\'%s\' and (bl.private = \'N\' OR bl.user_id=\'%d\') and blog_id=com_itemid and com_status=2 and (com_uid=uid or com_uid=0) %s group by com_id',
                   $sql, $mydirname ,$currentuid , $permission_group_sql );
    if( $user_id )
        $sql = sprintf('%s and bl.user_id=%d' , $sql , $user_id) ;
    $sql .= ' ORDER BY com_modified DESC';

    // Need to figure out how to access the module config while in a block
    //$result = $xoopsDB->query($sql, $xoopsModuleConfig['numinrecentblock'], 0);
    $result = $xoopsDB->query($sql, $max_comments, 0);
	$alart = false ;
    while ($myrow=$xoopsDB->fetchArray($result)) {
        $comment = array();
        $com_title = $myts->htmlSpecialChars($myrow['com_title']);
        if ( $block_size != 3) {
            if (strlen($com_title) >= $max_size) {
                $com_title = xoops_substr($com_title, 0, ($max_size -1)) ;
            }
        }

        $comment['com_title'] = $com_title;
        $comment['date'] = formatTimestamp($myrow['com_modified'], $date_format, $xoopsConfig['default_TZ']);
        $comment['entry_url'] = sprintf('%s/modules/%s/details.php?blog_id=%d',
                                      XOOPS_URL, $mydirname, $myrow['com_itemid']);
        $comment['entry_title'] = $myts->htmlSpecialChars($myrow['title']);
        $comment['permission'] = bl_permission_alart($myrow['contents']) ;
		if( $comment['permission'] ) $alart = true ;
		$comment['com_uname'] = ( $myrow['com_uid'] == 0 ) ? $xoopsConfig['anonymous'] : $myrow['uname'] ;
		if( $myrow['com_uid'] != 0 )
			$comment['profile_uri'] = sprintf('%s/userinfo.php?uid=%d', XOOPS_URL, $myts->htmlSpecialChars($myrow['com_uid']) );
        $block['comments'][] = $comment ;
    }

    $block['type'] = $block_size;
    $block['lang_comuname'] = _MB_WEBLOG_LANG_COM_UNAME;
    $block['lang_comtitle'] = _MB_WEBLOG_LANG_COM_TITLE;
    $block['lang_entrytitle'] = _MB_WEBLOG_LANG_COM_WEBLOGTITLE;
    $block['lang_posted'] = _MB_WEBLOG_LANG_COM_POSTED;
	if ($user_id > 0 ) {
	    $blogOwner = new XoopsUser($user_id);
	    $block['lang_whose'] = sprintf(_BL_COMMENTS_FOR, $blogOwner->getVar('uname','E'));
	}
	if( $alart ){
		$block['lang_denote'] = _MB_WEBLOG_LANG_DENOTE_PERMIT ;
	}
    return $block;
}


function b_weblog_recent_comment_edit($options) {
	$mydirname = empty( $options[0] ) ? basename( dirname( dirname( __FILE__ ) ) ) : $options[0] ;
    $form  = '<table>';
    $form .= "<input type='hidden' name='options[]' value='$mydirname' />\n" ;
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%d" /></td></tr>',
                     _MB_WEBLOG_EDIT_NUMBER_OF_COMMENTS, intval($options[1]));
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%d" /></td></tr>',
                     _MB_WEBLOG_EDIT_MAX_TITLE_LENGTH, intval($options[2]));
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%s" /></td></tr>',
                     _MB_WEBLOG_EDIT_DATE_FORMAT, $options[3]);
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%s" /></td></tr>',
                     _MB_WEBLOG_EDIT_TYPE, $options[4]);
    $form .= '</table>';
    return $form;
}



function b_weblog_recent_image_show($options) {
    global $xoopsDB, $xoopsUser, $xoopsConfig ;

	if( function_exists("gd_info") ){
		$gd_infomation = gd_info() ;
		if( substr( $gd_infomation['GD Version'] , 0 , 10 ) != 'bundled (2' )	// GD version 2 is required
			return false ;
	}

	$mydirname = empty( $options[0] ) ? basename( dirname( dirname( __FILE__ ) ) ) : $options[0] ;
    $max_images = $options[1];
    $max_title_length = $options[2];
    $max_contents_length = $options[3];
    $date_format = $options[4];
    $show_forbidden_pic = $options[5];
    $block = array();
	$block['images'] = array() ;

    $currentuid = !empty($xoopsUser) ? $xoopsUser->getVar('uid','E') : 0;
    $user_id = !empty($_GET['user_id']) ? intval($_GET['user_id']) : 0;

	if (is_object($xoopsUser)) {
		$useroffset = $xoopsUser->timezone() - $xoopsConfig['server_TZ'] ;
	} else {
		$useroffset = $xoopsConfig['default_TZ'] - $xoopsConfig['server_TZ'] ; ;
	}

    include_once(sprintf('%s/modules/%s/config.php', XOOPS_ROOT_PATH, $mydirname ));
    $wb_configs = get_wb_moduleconfig($mydirname) ;
    list( $bl_contents_field , $permission_group_sql ) = weblog_create_permissionsql($wb_configs , $mydirname) ;

    $myts =& MyTextSanitizer::getInstance();

	$weblog_image_url = XOOPS_URL . $wb_configs['weblog_myalbum_photospath'] ;
    $sql = sprintf('SELECT blog_id, user_id , uname, created+%d as created, title, contents , %s as contents_p FROM %s as bl , %s as u' ,
                   $useroffset*3600, $bl_contents_field , $xoopsDB->prefix($mydirname) , $xoopsDB->prefix('users') );
    $sql = sprintf('%s WHERE bl.user_id=u.uid and (bl.private = \'N\' OR bl.user_id=\'%d\') and bl.contents like \'%s\' %s ',
                   $sql , $currentuid , '%'.$weblog_image_url.'%' , $permission_group_sql );
    if( $user_id )
        $sql = sprintf('%s and bl.user_id=%d' , $sql , $user_id) ;
    $sql .= ' ORDER BY created DESC';

    // Need to figure out how to access the module config while in a block
    //$result = $xoopsDB->query($sql, $xoopsModuleConfig['numinrecentblock'], 0);
    $result = $xoopsDB->query($sql, $max_images, 0);
	$alart = false ;
    while ($myrow=$xoopsDB->fetchArray($result)) {
        $image = array();
		$title = "" ;
		if( $max_title_length > 0 ){
	        $title = $myts->htmlSpecialChars($myrow['title']);
	       	if (strlen($title) >= $max_title_length)
	                $title = xoops_substr($title, 0, ($max_title_length -1)) ;
		}
		$contents = "" ;
		if( $max_contents_length > 0 ){
	        $contents = strip_tags( $myts->xoopsCodeDecode($myrow['contents']) );
	       	if (strlen($contents) >= $max_contents_length)
                $contents = xoops_substr($contents, 0, ($max_contents_length -1)) ;
		}

		$image['permission'] = bl_permission_alart($myrow['contents_p']) ;
		if( $image['permission'] ) $alart = true ;

		$img_pattern = "/\[img( align=['\"]?)?(left|center|right)?]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
		$image['image_url'] = array() ;
		if( ! (! $show_forbidden_pic && $image['permission']) ){
	        if( preg_match_all( $img_pattern  , $myrow['contents'] , $matches) ){
				$image['image_uri'] = str_replace( $wb_configs['weblog_myalbum_photospath'] , $wb_configs['weblog_myalbum_thumbspath'] , $matches[3] );
			}
		}
        $image['title'] = $title ;
        $image['contents'] = $contents ;
		$image['uname'] = $myrow['uname'] ;
        $image['date'] = formatTimestamp($myrow['created'], $date_format, $xoopsConfig['default_TZ']);
        $image['entry_url'] = sprintf('%s/modules/%s/details.php?blog_id=%d',
                                      XOOPS_URL, $mydirname, $myts->htmlSpecialChars($myrow['blog_id']) );
		$image['blog_uri'] = sprintf('%s/modules/%s/index.php?user_id=%d',
                                      XOOPS_URL, $mydirname , $myts->htmlSpecialChars($myrow['user_id']) ) ;
        $block['images'][] = $image ;
    }
	if ($user_id > 0 ) {
	    $blogOwner = new XoopsUser($user_id);
	    $block['lang_whose'] = sprintf(_BL_ENTRY_FOR, $blogOwner->getVar('uname','E'));
	}
	if( $alart ){
		$block['lang_denote'] = _MB_WEBLOG_LANG_DENOTE_PERMIT ;
	}
    return $block;
}

function b_weblog_recent_image_edit($options) {
	$mydirname = empty( $options[0] ) ? basename( dirname( dirname( __FILE__ ) ) ) : $options[0] ;
    $form  = '<table>';
    $form .= "<input type='hidden' name='options[]' value='$mydirname' />\n" ;
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%d" /></td></tr>',
                     _MB_WEBLOG_EDIT_NUMBER_OF_ENTRIES, intval($options[1]));
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%d" /></td></tr>',
                     _MB_WEBLOG_EDIT_MAX_TITLE_LENGTH, intval($options[2]));
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%s" /></td></tr>',
                     _MB_WEBLOG_EDIT_MAX_CONTENTS_LENGTH, $options[3]);
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%s" /></td></tr>',
                     _MB_WEBLOG_EDIT_DATE_FORMAT, $options[4]);
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%s" /></td></tr>',
                     _MB_WEBLOG_EDIT_SHOW_FORBIDDEN_PICTURE, $options[5]);
    $form .= '</table>';
    return $form;
}

/**
 *@remarks show category list with counts by hodaka
 **/
function b_weblog_category_list_show($options) {
    global $xoopsDB, $xoopsUser, $xoopsConfig ;


	$mydirname = empty( $options[0] ) ? basename( dirname( dirname( __FILE__ ) ) ) : $options[0] ;
    include_once(sprintf('%s/modules/%s/config.php', XOOPS_ROOT_PATH, $mydirname ));
	$sort_order = ($options[1] == 1)? "cat_id" : "cat_title";

    $currentuid = !empty($xoopsUser) ? $xoopsUser->getVar('uid','E') : 0;
    $user_id = !empty($_GET['user_id']) ? intval($_GET['user_id']) : 0;

	include_once(sprintf('%s/modules/%s/class/class.weblogtree.php', XOOPS_ROOT_PATH, $mydirname ));
    $cattree = new WeblogTree($xoopsDB->prefix($mydirname . '_category'), 'cat_id', 'cat_pid');
    $array = $cattree->getChildTreeArray(0, $sort_order);

    $myts =& MyTextSanitizer::getInstance();
    $block = array();
	foreach ( $array as $cat ) {
		$category = array();
		$category['cat_id'] = $cat['cat_id'];
		$category['cat_title'] = $myts->htmlSpecialChars($cat['cat_title']);
			$count_sql = sprintf("select count(*) from %s where (user_id=%d or private='N') and cat_id=%d ", $xoopsDB->prefix($mydirname), $currentuid , $cat['cat_id']) ;
			if( $user_id ) $count_sql .= sprintf(" and user_id=%d ", $user_id ) ;
		list($category['count']) = $xoopsDB->fetchRow($xoopsDB->query($count_sql));
		$prefix_num = intval(strlen($cat['prefix']));
		if( $prefix_num == 1 ){
			$category['margin'] = "8px 0px 0px 0px" ;
		}elseif( $prefix_num == 2 ){
			$category['margin'] = "4px 0px 0px 0px" ;
		}else{
			$category['margin'] = "0px 0px 0px 4px" ;
		}
		$prefix_space = "" ;
		if( $prefix_num <= 3 ){
				$category['prefix'] = sprintf("<img src='%s/modules/%s/images/cat%d.gif'>" , XOOPS_URL , $mydirname , $prefix_num) ;
		}else{
			for( $i=3; $i<$prefix_num; $i++ ){
				$prefix_space .= sprintf("<img src='%s/modules/%s/images/cat_space.gif'>" , XOOPS_URL , $mydirname )  ;
			}
				$category['prefix'] = $prefix_space . sprintf("<img src='%s/modules/%s/images/cat%d.gif'>" , XOOPS_URL , $mydirname , '3' ) ;
		}
		$block['categories'][] = $category;
	}
	return $block;
}

function b_weblog_category_list_edit($options) {
	$mydirname = empty( $options[0] ) ? basename( dirname( dirname( __FILE__ ) ) ) : $options[0] ;
	$sel1 = ($options[1] == 1)? " selected='selected'" : "";
	$sel2 = ($options[1] == 2)? " selected='selected'" : "";
    $select_box = "<select name='options[]'>";
	$select_box .= "<option value='1'$sel1>"._MB_WEBLOG_EDIT_CAT_ID."</option>";
	$select_box .= "<option value='2'$sel2>"._MB_WEBLOG_EDIT_CAT_TITLE."</option>";
	$select_box .= "</select>";
    $form  = '<table>';
    $form .= "<input type='hidden' name='options[]' value='$mydirname' />\n" ;
    $form .= sprintf('<tr><td><b>%s</b>:</td><td>%s</td></tr>',
                     _MB_WEBLOG_EDIT_CAT_ORDERBY, $select_box);
    $form .= '</table>';
    return $form;
}




/*************************
 *  functions for blocks
 *  @private
 *************************/
// alart string
function bl_permission_alart( $contents ){

	$alart = "<span style='color:#FF0000;font-size:10px'>*</span>" ;
	if( preg_match("/^GROUP_PERMIT$/i",$contents) ){
		return $alart ;
	}else{
		return "" ;
	}
}

// get weblog module configs
function get_wb_moduleconfig($mydirname){
	global $xoopsDB ;

	$config = array() ;
	$sql = sprintf('select conf_name,conf_value from %s,%s where mid=conf_modid and dirname=\'%s\' ' ,
					$xoopsDB->prefix('config') , $xoopsDB->prefix('modules') , $mydirname ) ;
	$result = $xoopsDB->query($sql);
	while( $rows = $xoopsDB->fetchArray($result) ){
		$config[$rows['conf_name']] = $rows['conf_value'] ;
	}
	return $config ;
}

}
?>
