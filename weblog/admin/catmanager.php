<?php
/*
 * $Id: catmanager.php 11990 2013-08-28 13:23:21Z cesag $
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
 */
include('../../../mainfile.php');
include(sprintf('%s/include/cp_header.php', XOOPS_ROOT_PATH));
include_once(sprintf('%s/modules/%s/header.php', XOOPS_ROOT_PATH, $xoopsModule->dirname()));
include('admin.inc.php');
include_once(sprintf('%s/modules/%s/class/class.weblogtree.php', XOOPS_ROOT_PATH, $xoopsModule->dirname()));
include_once dirname(__FILE__) . '/admin_header.php';


$myts =& MyTextSanitizer::getInstance();
$mytree = new WeblogTree($xoopsDB->prefix($mydirname . '_category'), 'cat_id', 'cat_pid');

$action = (isset($_POST['action']))? $_POST['action'] : "" ;
$action = (isset($_GET['action']))?  $_GET['action']  : $action ;

switch ($action) {
    case "modCat":
        modifyCategory($_POST);
        break;
    case "modCatS":
        modifyCategoryS($_POST);
        break;
    case "modCatall":
        modifyCategoryAll();
        break;
    case "addCat":
        addCategory($_POST);
        break;
    case "delCat":
        delCategory($_POST, $_GET);
        break;
    default:
        catManager();
        break;
}


/*
if (isset($_POST)) {
	foreach ($_POST as $k => $v) {
		${$k} = $v;
	}
}

if (isset($_GET['action'])) {
	$action = $_GET['action'];
}
*/

function &getCategory($post) {
    $handler =& xoops_getmodulehandler('category');
    $cat = $handler->create();

    $cat->setVar('cat_pid', (isset($post['cat_pid']))?intval($post['cat_pid']):0);
    $cat->setVar('cat_id', (isset($post['cat_id']))?intval($post['cat_id']):0);
    $cat->setVar('cat_created', (isset($post['cat_created']))?intval($post['cat_created']):0);
    $cat->setVar('cat_title', (isset($post['cat_title']))?$post['cat_title']:"");
    $cat->setVar('cat_description', (isset($post['desc']))?$post['desc']:"");
    $cat->setVar('cat_imgurl', (isset($post['imgurl']))?$post['imgurl']:"");
    $cat->setVar('postgroup', (isset($post['postgroup']))?$post['postgroup']:array());

    return $cat;
}

function catManagerLink() {
    global $xoopsModule;

    return sprintf('<a href=\'%s/modules/%s/admin/catmanager.php\'>%s</a>',
                   XOOPS_URL, $xoopsModule->dirname(), _AM_WEBLOG_CATMANAGER);
}

function catManager() {
    global $mytree , $xoopsModule , $xoopsModuleConfig ;

	include_once(sprintf('%s/modules/%s/class/class.weblogcategories.php', XOOPS_ROOT_PATH, $xoopsModule->dirname()));
    include_once(sprintf('%s/modules/%s/admin/mygrouppermform.php', XOOPS_ROOT_PATH, $xoopsModule->dirname())) ;
    include_once(sprintf('%s/modules/%s/include/gtickets.php', XOOPS_ROOT_PATH, $xoopsModule->dirname())) ;

    xoops_cp_header();
//    echo sprintf('<h4>%s&nbsp;&raquo;&raquo;&nbsp;%s</h4>', indexLink(), _AM_WEBLOG_CATMANAGER);

    $gperm_handler =& xoops_gethandler('groupperm');
	$weblogcats =& WeblogCategories::getInstance();

//    $mytree->makeMySelBox('cat_title', 'cat_title');
    $indexAdmin = new ModuleAdmin();
    echo $indexAdmin->addNavigation('catmanager.php');


	include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
	$form_add = new XoopsThemeForm(_AM_WEBLOG_ADDCAT, 'weblog_cat_form', 'catmanager.php');
	$form_add->addElement(new XoopsFormText(_AM_WEBLOG_TITLE, 'cat_title', 50, 255, ''), true);
	$form_add->addElement(new XoopsFormLabel(_AM_WEBLOG_PCAT, $weblogcats->getMySelectBox(0 , 1, 'cat_pid')) );
	if( isset($xoopsModuleConfig['category_post_permission']) && $xoopsModuleConfig['category_post_permission'] ){
		$form_add->addElement(new XoopsFormSelectGroup(_AM_WEBLOG_CAT_GPERM, 'postgroup', true, '' , 5, true));
	}
	$form_add->addElement(new XoopsFormHidden('action', 'addCat'));
	$form_add->addElement(new XoopsFormButton('', 'catadd_button', _SUBMIT, 'submit'));
	$form_add->display();

	$form_mod = new XoopsThemeForm(_AM_WEBLOG_MODCAT, 'weblog_cat_form', 'catmanager.php');
	$form_mod->addElement(new XoopsFormLabel(_AM_WEBLOG_CAT, $weblogcats->getMySelectBox(0, 0, 'cat_id')));
	$form_mod->addElement(new XoopsFormButton('', 'catmod_button', _AM_WEBLOG_GO, 'submit'));
	$form_mod->addElement(new XoopsFormHidden('action', 'modCat'));
	$form_mod->display();

	// All category permit list
	if( isset($xoopsModuleConfig['category_post_permission']) && $xoopsModuleConfig['category_post_permission'] ){
		$wb_cat_array = $weblogcats->getChildTreeArray();

		$global_perms_array = array();
		foreach( $wb_cat_array as $category_data ){
			$global_perms_array[$category_data['cat_id']] = $category_data['cat_title'] ;

		}
		$form_catgperm = new MyXoopsGroupPermForm( '' , $xoopsModule->mid() , 'weblog_cat_post' , "<br /><hr /><br /><h4>"._AM_WEBLOG_CAT_SETALL."</h4>" ) ;
		foreach( $global_perms_array as $perm_id => $perm_name ) {
			$form_catgperm->addItem( $perm_id , $perm_name ) ;
		}
		$form_catgperm->addElement(new XoopsFormHidden('action', 'modCatall')) ;
		echo $form_catgperm->render() ;
	}

/*
    echo "<table width='100%' class='outer' cellspacing='1'>\r\n";
    echo sprintf("<tr><th colspan='2'>%s</th></tr>", _AM_WEBLOG_CATMANAGER);

    echo sprintf('<tr valign=\'top\' align=\'left\'><form method=\'post\', action=\'catmanager.php\'><td class=\'head\'>%s<br /><br />',
                 _AM_WEBLOG_ADDCAT);
    echo "<div style='font-weight:normal;'>";
    echo sprintf('%s: <input type=\'text\' name=\'title\' size=\'30\' maxlength=\'50\'><br />', _AM_WEBLOG_TITLE);
    if ($count > 0) {
        echo sprintf('%s: ', _AM_WEBLOG_PCAT);
        $mytree->makeMySelBox('cat_title', 'cat_title', 0, 1, 'cat_pid');
    } else {
        echo "<input type=hidden name=cat_pid value='0'>\r\n";
    }
    echo "<input type=hidden name=desc value=''>\r\n";
    echo "<input type=hidden name=imgurl value=''>\r\n";
    echo "<input type=hidden name=action value=addCat>\r\n";
    echo "</dev>";
    echo "</td>";
    echo "<td class='even'>\r\n";
    echo sprintf('<input type=submit value=\'%s\'><br />', _AM_WEBLOG_GO);
    echo "</td></form></tr>\r\n";

    if ($count > 0) {
        // Modify Category
        echo sprintf('<tr valign=\'top\' align=\'left\'><form method=\'post\', action=\'catmanager.php\'><td class=\'head\'>%s<br /><br />',
                     _AM_WEBLOG_MODCAT);
        echo "<div style='font-weight:normal;'>";
        echo sprintf('%s: ', _AM_WEBLOG_CAT);
        $mytree->makeMySelBox('cat_title', 'cat_title');
        echo "<input type=hidden name=action value=modCat>\r\n";
        echo "</dev>";
        echo "</td>";
        echo "<td class='even'>\r\n";
        echo sprintf('<input type=submit value=\'%s\'><br />', _AM_WEBLOG_GO);
        echo "</td></form></tr>\r\n";
    }

    echo "</table>\r\n";
*/
    xoops_cp_footer();
}

function delCategory($post, $get) {
    global $xoopsConfig, $xoopsModule;
    $cat_handler =& xoops_getmodulehandler('category');
    $gperm_handler =& xoops_gethandler('groupperm');

    if ( !isset($post['ok']) || $post['ok'] != 1 ) {
        $category =& $cat_handler->get($get['cat_id']);
        xoops_cp_header();
        xoops_confirm(array('action' => 'delCat', 'cat_id' => intval($get['cat_id']), 'ok' => 1),
                      'catmanager.php', sprintf(_AM_WEBLOG_DELCONFIRM, $category->getVar('cat_title')));
        xoops_cp_footer();
    } else {
        $entry_handler =& xoops_getmodulehandler('entry');
        $id_arr = $cat_handler->getAllChildrenIds($post['cat_id']);

        array_push($id_arr, $post['cat_id']);
        foreach ($id_arr as $id) {
            $criteria = new criteria('cat_id', $id);
            $entries = $entry_handler->getObjects($criteria);
            foreach ($entries as $entry) {
                if ($entry_handler->delete($entry)) {
                    xoops_comment_delete($xoopsModule->getVar('mid'),
                                         $entry->getVar('blog_id'));
                    xoops_notification_deletebyitem($xoopsModule->getVar('mid'),
                                                    'blog_entry', $entry->getVar('blog_id'));
                }
            }
            $category =& $cat_handler->create();
            $category->setVar('cat_id', $id);
            $cat_handler->delete($category);	// delete category
            $gperm_handler->deleteByModule($xoopsModule->getVar('mid'), 'weblog_cat_post', $id) ;	// delete gperm
/******
            xoops_notification_deleteitem($xoopsModule->getVar('mid'), 'category', $id);
******/
        }
        redirect_header('catmanager.php', 2, _AM_WEBLOG_CATDELETED);
        exit();
    }
}

function addCategory($post) {
	global $xoopsModule ;

	$modid = $xoopsModule->getVar('mid') ;

    $cat = getCategory($post);
    if (strlen(trim($cat->getVar('cat_title', 'n')))<1) {
        redirect_header('catmanager.php', 2, _AM_WEBLOG_ERRORTITLE);
        exit();
    }
    $cat->setVar('cat_created', time());
    $cat_handler =& xoops_getmodulehandler('category');
    $ret = $cat_handler->insert($cat);	// insert category to weblog_category
	if( $ret ){	// insert group_permission
		$cat_id = $cat_handler->db->getInsertId();
		$postgroup = $cat->vars['postgroup']['value'] ;
		$ret_gperm = true ;
		if( is_array($postgroup) && ! empty($postgroup) ){
			$gperm_handler =& xoops_gethandler('groupperm');
			foreach($postgroup as $group_id){
				$gperm =& $gperm_handler->create();
				$gperm->setVar('gperm_groupid', $group_id);
				$gperm->setVar('gperm_name', "weblog_cat_post");
				$gperm->setVar('gperm_modid', $modid);
				$gperm->setVar('gperm_itemid', $cat_id);
				if (!$gperm_handler->insert($gperm)) {
					$ret_gperm = false ;
				}
			}
		}
	}

    if ($ret && $ret_gperm) {
        redirect_header('catmanager.php', 2, _AM_WEBLOG_NEWCATADDED);
    } else {
        redirect_header('catmanager.php', 2, _AM_WEBLOG_CATNOTADDED);
    }
}


function modifyCategoryAll(){
    global $xoopsModule , $xoopsUser ;
	include_once(sprintf('%s/modules/%s/admin/mygroupperm.php', XOOPS_ROOT_PATH, $xoopsModule->dirname()));
	redirect_header( XOOPS_URL."/modules/".$xoopsModule->dirname()."/admin/catmanager.php" , 3 , _AM_WEBLOG_GPERMUPDATED );
}


function modifyCategoryS($post) {
    global $xoopsModule ;

    $modid = $xoopsModule->getVar('mid') ;

    $cat = getCategory($post);

    if (strlen(trim($cat->getVar('cat_title', 'n')))<1) {
        redirect_header('catmanager.php', 2, _AM_WEBLOG_ERRORTITLE);
        exit();
    }
    $handler =& xoops_getmodulehandler('category');
    $ret = $handler->insert($cat);

	if( $ret ){	// insert group_permission
		$postgroup = $cat->vars['postgroup']['value'] ;
		$ret_gperm = true ;
		if( is_array($postgroup) && ! empty($postgroup) ){
			$gperm_handler = xoops_gethandler('groupperm');
			if( false != $gperm_handler->deleteByModule($modid, 'weblog_cat_post', $cat->getVar('cat_id')) ){
				foreach($postgroup as $group_id){
					$gperm =& $gperm_handler->create();
					$gperm->setVar('gperm_groupid', $group_id);
					$gperm->setVar('gperm_name', "weblog_cat_post");
					$gperm->setVar('gperm_modid', $modid);
					$gperm->setVar('gperm_itemid', $cat->getVar('cat_id'));
					if (!$gperm_handler->insert($gperm)) {
						$ret_gperm = false ;
					}
				}
			}
		}
	}
    if ($ret && $ret_gperm) {
        redirect_header('catmanager.php', 2, _AM_WEBLOG_CATMODED);
    } else {
        redirect_header('catmanager.php', 2, _AM_WEBLOG_CATNOTMODED);
    }
}

function modifyCategory($post) {
    global $xoopsModule , $xoopsModuleConfig ;

    include_once(sprintf('%s/modules/%s/class/class.weblogcategories.php', XOOPS_ROOT_PATH, $xoopsModule->dirname()));

    $cat_id = (isset($post['cat_id'])) ? intval($post['cat_id']) : 0;
    $modid = $xoopsModule->getVar('mid') ;

    if( $cat_id ){
        $cathandler =& xoops_getmodulehandler('category');
        $gperm_handler =& xoops_gethandler('groupperm');
        $count = $cathandler->getCount();
        $weblogcats =& WeblogCategories::getInstance();
        $wb_cat =& $cathandler->get($cat_id) ;
		$cat_pid = $wb_cat->getVar('cat_pid');
		$cat_title = $wb_cat->getVar('cat_title','s');
    }else{
        redirect_header('catmanager.php', 2, _AM_WEBLOG_CATNOTMODED);
		exit() ;
	}

    xoops_cp_header();

    echo sprintf('<h4>%s&nbsp;&raquo;&raquo;&nbsp;%s&nbsp;&raquo;&raquo;&nbsp;%s</h4>',
                 indexLink(), catManagerLink(), _AM_WEBLOG_MODCAT);

	include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
	$form_add = new XoopsThemeForm(_AM_WEBLOG_MODCAT, 'weblog_cat_form', 'catmanager.php') ;
	$form_add->addElement(new XoopsFormLabel(_AM_WEBLOG_CHOSECAT, $cat_title ) );
	$form_add->addElement(new XoopsFormText(_AM_WEBLOG_TITLE, 'cat_title', 50, 255, $cat_title), true);
	$form_add->addElement(new XoopsFormLabel(_AM_WEBLOG_PCAT, $weblogcats->getMySelectBox($cat_pid, 1, 'cat_pid')) );
	if( isset($xoopsModuleConfig['category_post_permission']) && $xoopsModuleConfig['category_post_permission'] ){
		$form_add->addElement(new XoopsFormSelectGroup(_AM_WEBLOG_CAT_GPERM, 'postgroup', true, $gperm_handler->getGroupIds('weblog_cat_post', $cat_id, $modid), 5, true));
	}
	$form_add->addElement(new XoopsFormHidden('cat_id', $cat_id));
	$form_add->addElement(new XoopsFormHidden('action', 'modCatS'));
	$form_add->addElement(new XoopsFormLabel(_AM_WEBLOG_CAT_OPERATE, sprintf('<input type=submit value=\'%s\'>', _AM_WEBLOG_MODIFY) . "&nbsp;" .
                                                 sprintf('<input type=button value=\'%s\' onClick="location=\'catmanager.php?cat_pid=%d&amp;cat_id=%d&amp;action=delCat\'">',
                                                          _AM_WEBLOG_DELETE, $wb_cat->getVar('cat_pid'), $wb_cat->getVar('cat_id') ) . "&nbsp;" .
                                                 sprintf('<input type=button value="%s"  onclick="location=\'catmanager.php\'" />', _AM_WEBLOG_CANCEL)
	));


	$form_add->display();

    include_once dirname(__FILE__) . '/admin_footer.php';
}
