<?php
/*
 * $Id: weblog_links.php,v 1.4 2005/06/18 17:56:01 tohokuaiki Exp $
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

if( ! defined( 'WEBLOG_BLOCK_LINKS_INCLUDED' ) ) {
define( 'WEBLOG_BLOCK_LINKS_INCLUDED' , 1 ) ;

/*
 * $options[0] = links module
 * $options[1] = links number
 * $options[2] = show only post or not
 * $options[3] = show link description or not
 */
function b_weblog_links_show($options) {
    global $xoopsDB, $xoopsUser;

	$mydirname = empty( $options[0] ) ? basename( dirname( dirname( __FILE__ ) ) ) : $options[0] ;
    $link_module = $options[1];
    $link_num = $options[2];
    $only_post = $options[3];
    $showdsc = $options[4];

	if( $only_post == "1" ){
		if( ! preg_match( "|weblog\d*/post\.php$|" , $_SERVER['SCRIPT_NAME']) )
		return false ;
	}
    
    $currentuid = !empty($xoopsUser) ? $xoopsUser->getVar('uid','E') : 0;
    $user_id = !empty($_GET['user_id']) ? intval($_GET['user_id']) : 0;
	$submitter = ( empty($user_id) )? $currentuid : $user_id ;

    $block = array();
	if( preg_match( "/^mylinks\d*$/" , $options[0] ) ){
		// in case of mylinks module
	    $sql = sprintf('SELECT c.title as category, l.title as title, url, description as dsc FROM %s as c, %s as l, %s as d WHERE c.cid=l.cid and d.lid=l.lid and status=1 ',
	                   $xoopsDB->prefix($options[0] . '_cat'), $xoopsDB->prefix($options[0] . '_links'), $xoopsDB->prefix($options[0] . '_text'));
		if( $submitter )
		    $sql = sprintf('%s and submitter=%d' , $sql, $submitter);
		$sql .= " order by l.cid,l.date " ;

	}elseif( preg_match( "/^weblinks\d*$/" , $options[0] ) ){
		// in case of weblink module
	    $sql = sprintf('select link.title as title, link.url as url, link.description as dsc, cat.title as category from %s as cat, %s as link, %s as clink where link.lid=clink.lid and clink.cid=cat.cid ',
	                   $xoopsDB->prefix($options[0] . '_category'), $xoopsDB->prefix($options[0] . '_link'), $xoopsDB->prefix($options[0] . '_catlink') );
		if( $submitter )
		    $sql = sprintf('%s and link.uid=%d' , $sql, $submitter) ;
		$sql .= " order by clink.cid,clink.lid " ;
	}

	if( ! isset($sql) )
		return array() ;
		
    $result = $xoopsDB->query($sql, $link_num, 0);
    while ($myrow = $xoopsDB->fetchArray($result)) {
		$category = $myrow['category'] ;
        if( ! isset($block['links'][$category]) )
			$block['links'][$category] = array() ;
		$block['links'][$category][] = array(
			"title" => $myrow['title'] ,
			"url" => $myrow['url'] ,
			"dsc" => $myrow['dsc'] 
		) ;
    }

	if( $submitter ){
	    $blogOwner = new XoopsUser($submitter);
	    $block['lang_whose'] = sprintf(_MB_WEBLOG_LANG_LINKS_FOR,$blogOwner->getVar('uname','E'));
	}else{
	    $block['lang_whose'] = _MB_WEBLOG_LANG_LINKS_FOR_EVERYONE;
	}
	if( $showdsc ){
	    $block['showdsc'] = 1 ;
	}
    return $block;
}

/*
 * $options[0] = links module
 * $options[1] = links number
 * $options[2] = show only post or not
 * $options[3] = show link description or not
 */
function b_weblog_links_edit($options) {
    global $xoopsDB, $xoopsUser;

	$mydirname = empty( $options[0] ) ? basename( dirname( dirname( __FILE__ ) ) ) : $options[0] ;
	$apply_linkmodules = array('mylinks','weblinks') ;
	$linkmods = "";
	foreach( $apply_linkmodules as $modulename ){
		$linkmods .=  "dirname like '" . $modulename . "%' or " ; 
	}
	$mod_sql = sprintf("select dirname from %s where isactive=1 and (%s) " , $xoopsDB->prefix('modules') ,  rtrim( $linkmods , " or" ) ) ;

	$mod_result = $xoopsDB->query($mod_sql) ;
	if( $xoopsDB->getRowsNum($mod_result) == 0 )
		return false ;

	include_once XOOPS_ROOT_PATH."/class/xoopsform/formelement.php" ;
	include_once XOOPS_ROOT_PATH."/class/xoopsform/formselect.php" ;
	$selectbox = new XoopsFormSelect("", "options[]", $options[0]) ;
	$selectbox->addOption("" , "---") ;
	while( $modinfo = $xoopsDB->fetchArray( $mod_result ) ){
		$selectbox->addOption($modinfo['dirname']) ;
	}
	$link_module_selectbox = $selectbox->render() ;

    $form  = '<table>';
    $form .= "<input type='hidden' name='options[]' value='$mydirname' />\n" ;
    $form .= sprintf('<tr><td><b>%s</b>:</td><td>%s</td></tr>',
                     _MB_WEBLOG_EDIT_LINKS_MODULE, $link_module_selectbox );
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%d" size="2" maxlength="2" /></td></tr>',
                     _MB_WEBLOG_EDIT_LINKS_NUMBER, intval($options[1]));
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%d" size="2" maxlength="2"  /></td></tr>',
                     _MB_WEBLOG_EDIT_LINKS_ONLYPOST, intval($options[2]));
    $form .= sprintf('<tr><td><b>%s</b>:</td><td><input type="text" name="options[]" value="%d" size="2" maxlength="2"  /></td></tr>',
                     _MB_WEBLOG_EDIT_LINKS_SHOWDSC, intval($options[3]));
    $form .= '</table>';
    return $form;
}

}
?>