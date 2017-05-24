<?php
/*
 * $Id: post.php 11979 2013-08-25 20:45:24Z beckmi $
 * Copyright (c) 2003 by Hiro SAKAI <http://wellwine.net>
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

include('header.php');
include_once(sprintf('%s/modules/%s/class/class.weblogtree.php', XOOPS_ROOT_PATH, $xoopsModule->dirname())) ;
include_once(sprintf('%s/modules/%s/class/class.weblog.php', XOOPS_ROOT_PATH, $xoopsModule->dirname()));
include_once(sprintf('%s/modules/%s/class/class.weblogcategories.php', XOOPS_ROOT_PATH, $xoopsModule->dirname()));
include_once(sprintf('%s/modules/%s/class/class.weblogtrackback.php', XOOPS_ROOT_PATH, $xoopsModule->dirname()));
include_once(sprintf('%s/modules/%s/include/gtickets.php', XOOPS_ROOT_PATH, $xoopsModule->dirname())) ;

$weblog = Weblog::getInstance();
$weblogcat = WeblogCategories::getInstance();
$tb_operator = Weblog_Trackback_Operator::getInstance() ;

function &getEntry($post) {
    global $xoopsModuleConfig ;

    $weblog = Weblog::getInstance();

    $permission_group = "" ;
    if( isset($post['permission_group']) && is_array($post['permission_group']) ){
        $permission_group = '|' . implode("|" , $post['permission_group']) . '|' ;
    }else{
        $permission_group = ( isset($post['permission_group']) ) ? $post['permission_group'] : "" ;
    }

    $entry = $weblog->newInstance();
    $entry->setVar('blog_id', $post['blog_id']);
    $entry->setVar('user_id', $post['user_id']);
    $entry->setVar('cat_id', $post['cat_id']);
    $entry->setVar('created', $post['created']);
    $entry->setVar('title', $post['title']);
    if( isset($post['ent_trackbackurl']) )
      $entry->setVar('ent_trackbackurl', $post['ent_trackbackurl']);
    $entry->setVar('contents', $post['contents']);
    $entry->setVar('private', ! empty($post['private']) ? 'Y' : 'N');
    $entry->setVar('permission_group', $permission_group);
    $entry->setVar('updateping', ! empty($post['updateping']) ? 1 : 0);
    $entry->setVar('specify_created', ! empty($post['specify_created']) ? 1 : 0);
    // dohtml check
    if( $xoopsModuleConfig['disable_html'] ){
        $entry->setVar('dohtml', 0);
        $entry->setVar('dobr', 1);
    }else{
        $entry->setVar('dohtml', empty($post['dohtml']) ? 1 : 0);
        $entry->setVar('dobr', empty($post['dobr']) ? 0 : 1);
    }

    return $entry;
}

function weblog_confirm($hiddens, $action, $msg, $submit='', $back=false) {
    global $xoopsGTicket ;
    $submit = ($submit != '') ? trim($submit) : _SUBMIT;
    echo '<div class="confirmMsg"><h4>'.$msg.'</h4><form method="post" action="'.$action.'">';
    $input_hidden = "" ;
    foreach ($hiddens as $name => $value) {
        if (is_array($value)) {
            foreach ($value as $caption => $newvalue) {
                $input_hidden .= '<input type="radio" name="'.$name.'" value="'.$newvalue.'" /> '.$caption;
            }
            $input_hidden .= '<br />';
        } else {
            $input_hidden .= '<input type="hidden" name="'.$name.'" value="'.$value.'" />';
        }
    }
    echo $input_hidden . '<input type="submit" name="continue" value="'.$submit.'" /></form>';
    if( $back ){
        $input_hidden_back = "" ;
        if( isset($hiddens['op']) )
            unset($hiddens['op']) ;
        foreach ($hiddens as $name => $value) {
            $input_hidden_back .= '<input type="hidden" name="'.$name.'" value="'.$value.'" />';
        }
       echo '<form method="post" action="'.$action.'">' . $input_hidden_back . '<input type="submit" name="preview" value="'._CANCEL.'">' ;
    }
    echo '</div>';
}

if (is_object($xoopsUser)) {
    $currentUser = $xoopsUser;
} else {
    $currentUser = new XoopsUser();
    $currentUser->setVar('uid', 0);
}
$isAdmin = $currentUser->isAdmin($xoopsModule->mid());
$currentuid = $currentUser->getVar('uid');

// Check to ensure this user can post. Anonymous reject. Admin always OK.
include_once(sprintf('%s/modules/%s/include/privilege.inc.php', XOOPS_ROOT_PATH, $xoopsModule->dirname())) ;
if( !$isAdmin && (! $currentuid || ! checkprivilege( "edit" , $xoopsModule->dirname() ) ) ){
    redirect_header(sprintf('%s/modules/%s/index.php', XOOPS_URL, $xoopsModule->dirname()),
                    5, _BL_ANON_CANNOT_POST_SORRY);
    exit();
}

// change specify date to created time.
$offset = ( get_class($xoopsUser) == "xoopsuser" ) ? $xoopsUser->timezone() - $xoopsConfig['server_TZ'] : 0 ;
if( isset($_POST['created_date']['date']) && is_array($_POST['created_date'])){
    $_POST['created'] = strtotime($_POST['created_date']['date']) + $_POST['created_date']['time'] - ( $offset * 3600 );
}
// Save the post
if (!empty($_POST['post'])) {
  if ( ! $xoopsGTicket->check() ) {
     redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
  }
  $entry =& getEntry($_POST);
  if (strlen($entry->getVar('contents', 'n')) < $xoopsModuleConfig['minentrysize']) {
    // Include the page header
    include(XOOPS_ROOT_PATH.'/header.php');
    weblog_confirm(array('blog_id'=>$entry->getVar('blog_id'),
                         'private'=>$entry->isPrivate(),
                         'dohtml'=>!$entry->doHtml(),
                         'dobr'=>$entry->doBr(),
                         'updateping'=>$entry->isUpdateping(),
                         'specify_created'=>$entry->isSpecifycreated(),
                         'user_id'=>$entry->getVar('user_id'),
                         'cat_id'=>$entry->getVar('cat_id'),
                         'created'=>$entry->getVar('created'),
                         'title'=>$entry->getVar('title', 'f'),
                         'contents'=>$entry->getVar('contents', 'f'),
                         'ent_trackbackurl'=>$entry->getVar('ent_trackbackurl'),
                         'permission_group'=>$entry->getVar('permission_group'),
                         'XOOPS_G_TICKET' => $xoopsGTicket->issue( __LINE__ ) ,
                         'continue'=>1),
                   'post.php',
                   sprintf(_BL_POST_TOO_SMALL, $xoopsModuleConfig['minentrysize'],
                           strlen($entry->getVar('contents', 'n'))),
                   _BL_CONTINUE_EDITING);
    // Include the page footer
    include(XOOPS_ROOT_PATH.'/footer.php');
    exit();
  }

  $notification_handler =& xoops_gethandler('notification');
  $pageUri = sprintf('%s/modules/%s/details.php?blog_id=', XOOPS_URL, $xoopsModule->dirname());

  if ($entry->getVar('blog_id') > 0) {
    $e_entry = $weblog->getEntry($entry->getVar('user_id'), $entry->getVar('blog_id'));

    $old_trackbackurl = $tb_operator->handler->getTrackbackurl_string( $entry->getVar('blog_id') , "transmit" ) ;

    $isEditable = ($e_entry->getVar('user_id')==$currentuid || $isAdmin);
    if ($isEditable) {
      $e_entry->setVar('title', $entry->getVar('title', 'n'));
      $e_entry->setVar('cat_id', $entry->getVar('cat_id'));
      $e_entry->setVar('created', $entry->getVar('created'));
      $e_entry->setVar('contents', $entry->getVar('contents', 'n'));
      $e_entry->setVar('ent_trackbackurl', $entry->getVar('ent_trackbackurl' , 'n'));
      $e_entry->setVar('private', $entry->getVar('private', 'n'));
      $e_entry->setVar('dohtml', $entry->getVar('dohtml'));
      $e_entry->setVar('dobr', $entry->getVar('dobr'));
      $e_entry->setVar('updateping', $entry->getVar('updateping'));
      $e_entry->setVar('specify_created', $entry->getVar('specify_created'));
      $e_entry->setVar('permission_group', $entry->getVar('permission_group'));
     $ret = $weblog->saveEntry($e_entry);
      if ($ret) {
          $goodPost = true;
      } else {
          $goodPost = false;
      }
    } else {
      $goodPost = false;
    }
    $pageUri .= $entry->getVar('blog_id');
  } else {
//    $entry->setVar('created', time());    // comment out as change created function added
    $old_trackbackurl = "" ;
    $entry->setVar('user_id', $currentuid);
    $ret = $weblog->saveEntry($entry);
    if ($ret) {
        $goodPost = true;
    } else {
        $goodPost = false;
    }
    $pageUri .= $entry->getVar('blog_id');

    // Send notifications only if the entry not private.
    if ($ret && $entry->isPrivate()==false) {
      $notification_handler->triggerEvent ('blog', $xoopsUser->getVar('uid'), 'add',
                                           $extra_tags=array('TITLE'=>$entry->getVar('title'),
                                                             'PAGE_URI'=>$pageUri),
                                           $user_list=array(), $module_id=null,
                                           $omit_user_id=null);
    }
  }

  /*
   * If they did not affect any rows, they were probably trying to update
   * an entry that was not theirs. So check!
   */
  if ($goodPost) {
    // trackback post
    $trackback_result_msg = "" ;
    if( trim($entry->getVar('ent_trackbackurl' , 'n')) || $old_trackbackurl ){
//        $blog_name = $xoopsModule->name() . " - " . sprintf(_BL_WHOS_BLOG,$xoopsUser->getVar('uname','E')) . " - " . $xoopsConfig['sitename'] ;
        $before_bn = array('{MODULE_NAME}','{USER_NAME}','{SITE_NAME}') ;
        $after_bn = array($xoopsModule->name(), $xoopsUser->getVar('uname','E'), $xoopsConfig['sitename']) ;
        $blog_name = str_replace($before_bn, $after_bn, $xoopsModuleConfig['transmit_blogname']) ;
        $blog_url = sprintf('%s/modules/%s/details.php?blog_id=%d', XOOPS_URL, $xoopsModule->dirname(),$entry->getVar('blog_id')) ;
        $tb_operator->Create_Trackback_Data( $entry , $blog_name , $blog_url ) ;
        $trackback =& $tb_operator->newInstance() ;
        foreach( $tb_operator->Get_Trackback_Url( $entry , $old_trackbackurl ) as $trackback_url=>$type ){
            if( $type=="add" ){
                $tb_operator->Create_Trackback_Data( $entry , $blog_name , $blog_url ) ;
                if( $tb_operator->Weblog_Post_Trackback( $trackback_url ) ){
                    // get trackback as quote .
                    $response_xml = $tb_operator->Get_RSS_from_trackback_URL( $trackback_url ) ;
                    $tb_rss_data = $tb_operator->Parse_XML( $response_xml ) ;
                    $tb_operator->Set_Trackback_Values( $trackback , $tb_rss_data , $trackback_url ,  "transmit" , $entry ) ;
                    $tb_operator->saveTrackback($trackback) ;
                }
            }elseif( $type=="del" ){
                $tb_operator->removeTrackback( $entry->getVar('blog_id') , $trackback_url , "transmit") ;
            }
        }

        $trackback_result_msg = $tb_operator->Xoops_Weblog_Msg() ;
        if( $trackback_result_msg ){
            $trackback_result_msg =  "<br />\n---- &nbsp; Trackback &nbsp; ----\n<br />\n" . $trackback_result_msg . "<br />" ;
        }
    }
    // trackback(recieved) delete
    if( isset($_POST['delete_trackback']) && is_array($_POST['delete_trackback']) ){
        $tb_del_num = 0 ;
        foreach( $_POST['delete_trackback'] as $tb_url ){
            if( ! empty($tb_url) && $tb_operator->removeTrackback( $entry->getVar('blog_id') , $tb_url , "recieved" ) ){
                $tb_del_num-- ;
            }
        }
        if( $tb_del_num != 0 )
            $weblog->handler->incrementTrackbacks( $entry->getVar('blog_id') , $tb_del_num ) ;
    }
    // common ping
    $common_ping_result_msg = "" ;
    if( $entry->isUpdateping() ){
        include_once(sprintf('%s/modules/%s/class/class.commonping.php', XOOPS_ROOT_PATH, $xoopsModule->dirname())) ;
        $weblog_url = sprintf('%s/modules/%s/', XOOPS_URL, $xoopsModule->dirname() ) ;
        if( ! isset($blog_name) )
            $blog_name = $xoopsModule->name() . " - " . sprintf(_BL_WHOS_BLOG,$xoopsUser->getVar('uname','E')) . " - " . $xoopsConfig['sitename'] ;
        $Common_Updateping = new Weblog_Commonping( $blog_name ,  $weblog_url ) ;
        $commonping_server_file = sprintf('%s/modules/%s/language/%s/commonping_servers.inc.php',
                                                                    XOOPS_ROOT_PATH, $xoopsModule->dirname() , $xoopsConfig['language']) ;
        $common_ping_result_msg = $Common_Updateping->xoops_weblog_commonping_process( $commonping_server_file ) ;
        $common_ping_result_msg =  "<br />\n---- &nbsp; Update &nbsp; Ping &nbsp; ----\n<br />\n" . $common_ping_result_msg . "<br />" ;
    }
    // redirect
    redirect_header($pageUri, 2, $trackback_result_msg . $common_ping_result_msg . _BL_ENTRY_POSTED );
    exit();
  } else {
    redirect_header(sprintf('%s/modules/%s/index.php', XOOPS_URL, $xoopsModule->dirname()),
                    5, _BL_ANON_CANNOT_POST_SORRY);
    exit();
  }
} else if (!empty($_POST['delete']) || (!empty($_POST['op']) && $_POST['op'] == 'delete')) {
  if ( ! $xoopsGTicket->check() ) {
    redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
  }
  $entry =& getEntry($_POST);
  if (!empty($_POST['ok'])) {
    // After delete, return the user to the user's blog they were reading.
    $link = sprintf('%s/modules/%s/index.php?user_id=%d',
                    XOOPS_URL, $xoopsModule->dirname(), $entry->getVar('user_id'));
    $isEditable = ($entry->getVar('user_id')==$currentuid || $isAdmin);
    if ($isEditable) {
      $ret = $weblog->removeEntry($entry->getVar('blog_id'));
      if ($ret) {
        // delete trackbacks
        $tb_operator->removeTrackback( $entry->getVar('blog_id') ) ;
        xoops_comment_delete($xoopsModule->getVar('mid'), $entry->getVar('blog_id'));
        xoops_notification_deletebyitem($xoopsModule->getVar('mid'),
                                        'blog_entry', $entry->getVar('blog_id'));
        redirect_header($link, 2 ,_BL_BLOG_DELETED);
      } else {
        redirect_header($link, 5 ,_BL_BLOG_NOT_DELETED);
      }
    } else {
      redirect_header($link, 5 ,_BL_BLOG_NOT_DELETED); // TODO
    }
  } else {
    require(XOOPS_ROOT_PATH.'/header.php');
    weblog_confirm(array(
                        'blog_id' => $entry->getVar('blog_id'),
                        'private'=>$entry->isPrivate(),
                        'dohtml'=>!$entry->doHtml(),
                        'dobr'=> $entry->doBr(),
                        'updateping'=>$entry->isUpdateping(),
                        'specify_created'=>$entry->isSpecifycreated(),
                        'user_id' => $entry->getVar('user_id'),
                        'cat_id' => $entry->getVar('cat_id'),
                        'created' => $entry->getVar('created'),
                        'title' => $entry->getVar('title', 'n'),
                        'contents' => $entry->getVar('contents', 'n'),
                        'ent_trackbackurl'=>$entry->getVar('ent_trackbackurl'),
                        'permission_group'=>$entry->getVar('permission_group'),
                        'XOOPS_G_TICKET' => $xoopsGTicket->issue( __LINE__ ),
                        'op' => 'delete',
                        'ok' => 1),
                  'post.php', sprintf(_BL_CONFIRM_DELETE, stripslashes($entry->getVar('title'))) , false ,true );
    require(XOOPS_ROOT_PATH.'/footer.php');
  }
} else {
  // Edit/Create/Preview the post
  if( ! empty($_POST['preview']) || ! empty($_POST['continue']) ){  // in case of Preview|Continue check GTicket
    if ( ! $xoopsGTicket->check() )
       redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
  }
  $blog_id=!empty($_GET['blog_id']) ? $_GET['blog_id'] : 0;
  if ($blog_id>0 && empty($_POST['preview'])) {    // edit mode
    $entry =& $weblog->getEntry( $currentuid, $blog_id, (!$isAdmin)?$currentuid:0 ) ;
    if( empty($entry) ){
        redirect_header(sprintf('%s/modules/%s/index.php', XOOPS_URL, $xoopsModule->dirname()),
                    5, _BL_CANNOT_EDIT);
        exit() ;
    }
    $selbox = $weblogcat->getMySelectBox($entry->getVar('cat_id'));
    // add trackback data
    $tb_operator = Weblog_Trackback_Operator::getInstance() ;
    $ent_trackback = $tb_operator->handler->getTrackbackurl_string( $blog_id , "transmit" ) ;
    $entry->setVar( 'ent_trackbackurl' , $ent_trackback );
    $recieved_trackback = $tb_operator->handler->get( $blog_id , "recieved" ) ;
  } elseif (!empty($_POST['preview']) || !empty($_POST['continue'])) {    // editting mode
    $_POST['title'] = stripslashes($_POST['title']);
    $_POST['contents'] = stripslashes($_POST['contents']);
    $entry =& getEntry($_POST);
    $selbox = $weblogcat->getMySelectBox($entry->getVar('cat_id'));
    // add trackback data
    $tb_operator = Weblog_Trackback_Operator::getInstance() ;
    $recieved_trackback = $tb_operator->handler->get( $entry->getVar('blog_id') , "recieved" ) ;
  } else {    // first post
    $entry = $weblog->newInstance();
    $entry->setVar('user_id', -1);
    $selbox = $weblogcat->getMySelectBox();
    $entry->setVar( 'permission_group' , $xoopsModuleConfig['default_permission'] );
  }
  $xoopsOption['template_main'] = 'weblog'.$mydirnumber . '_post.html';
  include_once XOOPS_ROOT_PATH."/include/xoopscodes.php";

  if( isset($recieved_trackback) && is_array($recieved_trackback) && count($recieved_trackback)>0 ){
      $recieved_trackback_url_array = array();
      foreach( $recieved_trackback as $trackback_obj ){
          $recieved_trackback_url_array[] = $trackback_obj->getVar('tb_url') ;
      }
      $entry->setVar( 'delete_trackback' , $recieved_trackback_url_array ) ;
  }

  // Include the page header
  include(XOOPS_ROOT_PATH.'/header.php');

  // Generate our form promatically
  include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
//  include_once(sprintf('%s/modules/%s/include/myformdatetime.php', XOOPS_ROOT_PATH, $xoopsModule->dirname())) ;
//  include_once(sprintf('%s/modules/%s/include/myformtextdateselect.php', XOOPS_ROOT_PATH, $xoopsModule->dirname())) ;
  $blog_form = new XoopsThemeForm(_BL_POST, 'blogform', "post.php");
  $blog_form->addElement(new XoopsFormHidden('user_id',$entry->getVar('user_id')));
  $blog_form->addElement(new XoopsFormHidden('blog_id',$entry->getVar('blog_id')));
  $blog_form->addElement($xoopsGTicket->getTicketXoopsForm( __LINE__ ));
  $blog_form->addElement(new XoopsFormLabel('',sprintf(_BL_POST_TIMEOUT, $xoopsModuleConfig['GTicket_timeout'])));
  if ($xoopsModuleConfig['minentrysize'] > 0) {
    $blog_form->addElement(new XoopsFormLabel('',sprintf(_BL_POST_MUST_BE,
                                                         $xoopsModuleConfig['minentrysize'])));
  }
  if( $xoopsModuleConfig['disable_html'] ){
    $blog_form->addElement(new XoopsFormLabel(_BL_CAUTION_NOHTML, _BL_FORBIDDEN_HTML_TAGS));
  }
  $blog_form->addElement(new XoopsFormText(_BL_TITLE,'title',
                                           $xoopsModuleConfig['editorwidth'],
                                           80, $entry->getVar('title', 'e')), true);
  //$selbox_tray = new XoopsFromElementTray('_BL_CATEGORY', '');
  $blog_form->addElement(new XoopsFormLabel(_BL_CATEGORY, $selbox));
/*
  $blog_form->addElement(new XoopsFormDhtmlTextArea(_BL_CONTENTS,'contents',
                                                    $entry->getVar('contents', 'e'),
                                                    $xoopsModuleConfig['editorheight'],
                                                    $xoopsModuleConfig['editorwidth']), true);
*/

//    $optionsTrayNote = new XoopsFormElementTray(_AM_NAR_NOTE, '<br />');
if (class_exists('XoopsFormEditor')) {
        $options['name'] = 'contents';
        $options['value'] = $entry->getVar('contents');
        $options['rows'] = 10;
        $options['cols'] = '100%';
        $options['width'] = '100%';
        $options['height'] = '200px';
    $formmnote  = new XoopsFormEditor('', $xoopsModuleConfig['form_options'], $options, $nohtml = false, $onfailure = 'textarea');
//        $optionsTrayNote->addElement($formmnote );
    } else {
    $formmnote  = new XoopsFormDhtmlTextArea(_BL_CONTENTS, 'contents', $entry->getVar('contents', 'e'), '100%', '100%');
//        $optionsTrayNote->addElement($formmnote );
}

    $blog_form->addElement($formmnote );

    // add image manager
    if( isset($xoopsModuleConfig['use_imagemanager']) && $xoopsModuleConfig['use_imagemanager'] ){
        $imagemanager_valid = false ;
        // check GD2 module extension is loaded or not.
        if( function_exists("gd_info") ){
            $gd_infomation = gd_info() ;
            if( preg_match('/2\./', $gd_infomation['GD Version']) )    // GD version 2 is required
                $imagemanager_valid = true ;
        }
        if( $imagemanager_valid ){
            $imagemanager_tag = sprintf('<img onmouseover=\'style.cursor="hand"\' onclick=\'javascript:openWithSelfMain("%s/modules/%s/weblog-imagemanager.php?target=contents","imgmanager",400,430);\' src="%s/modules/%s/images/weblog_imagemanager.gif" alt="image" />' ,
                                                            XOOPS_URL , $xoopsModule->dirname() , XOOPS_URL , $xoopsModule->dirname() );
        }else{
            $imagemanager_tag = _BL_WEBLOG_IMAGEMANAGER_CAUTION ;
        }
        $imagemanager_tray = new XoopsFormElementTray( _BL_WEBLOG_IMAGEMANAGER ,'');
        $imagemanager_button = new XoopsFormLabel('' , $imagemanager_tag ) ;
        $imagemanager_tray->addElement($imagemanager_button) ;
        $blog_form->addElement($imagemanager_tray) ;
        // one click image upload
//        if( $imagemanager_valid ){
//            $oneclick_im_tray = new XoopsFormElementTray("one click IM" ,'') ;
//            $oneclick_im_tray->addElement(new XoopsFormFile("you can post image file at ease.<br />" , 'oneclick_image_file', 5000000)) ;
///            $blog_form->addElement($oneclick_im_tray) ;
//        }
    }
    // add devide entry separator
    if( isset($xoopsModuleConfig['use_separator']) && $xoopsModuleConfig['use_separator'] ){
        $separator_tray = new XoopsFormElementTray( _BL_ENTRY_SEPARATOR ,'');
        $separator_button = new XoopsFormButton(_BL_ENTRY_SEPARATOR_CAPTION . "<br />" , '' , _BL_ENTRY_SEPARATOR_VALUE , 'button') ;
        $separator_button->setExtra("onclick='xoopsCodeSmilie(\"contents\", \"\\n" . _BL_ENTRY_SEPARATOR_DELIMETER . "\\n\");'") ;
        $separator_tray->addElement($separator_button) ;
        $blog_form->addElement($separator_tray) ;
    }
    // add member only javascript
    if( isset($xoopsModuleConfig['use_memberonly']) && $xoopsModuleConfig['use_memberonly'] ){
        $membershow_tray = new XoopsFormElementTray( _BL_MEMBER_ONLY_READ ,'');
        $membershow_button = new XoopsFormButton(_BL_MEMBER_ONLY_READ_CAPTION . "<br />" , '' , _BL_MEMBER_ONLY_READ_VALUE , 'button') ;
        $membershow_button->setExtra("onclick='xoopsCodeSmilie(\"contents\", \"\\n" . MEMBER_ONLY_READ_DELIMETER . "\\n\");'") ;
        $membershow_tray->addElement($membershow_button) ;
        $blog_form->addElement($membershow_tray) ;
    }
    // options check box
    $checkbox_tray = new XoopsFormElementTray(_BL_OPTIONS ,'<br />');
    if( ! $xoopsModuleConfig['disable_html'] ){
        $checkbox_dohtml = new XoopsFormCheckBox('', 'dohtml', !$entry->doHtml());
        $checkbox_dohtml->addOption('dohtml', _BL_DISABLEHTML);
            $checkbox_tray->addElement($checkbox_dohtml);
        $checkbox_dobr = new XoopsFormCheckBox('', 'dobr', $entry->doBr());
        $checkbox_dobr->addOption('dobr', _BL_WRAPLINES);
            $checkbox_tray->addElement($checkbox_dobr);
    }
    $checkbox_private = new XoopsFormCheckBox('', 'private', $entry->isPrivate());
    $checkbox_private->addOption('private', _BL_PRIVATE);
        $checkbox_tray->addElement($checkbox_private);
    $checkbox_updateping = new XoopsFormCheckBox('', 'updateping', $entry->isUpdateping());
    $checkbox_updateping->addOption('updateping', _BL_UPDATEPING );
        $checkbox_tray->addElement($checkbox_updateping);
    $checkbox_published = new XoopsFormCheckBox('', 'specify_created', $entry->isSpecifycreated());
    $checkbox_published->addOption('specify_created', _BL_SPECIFY_TIME );
        $checkbox_tray->addElement($checkbox_published);
    $checkbox_publish = new XoopsFormDateTime(_BL_SPECIFY_TIME_DSC, 'created_date', 15, $entry->getVar('created')) ;
        $checkbox_tray->addElement($checkbox_publish);
    $blog_form->addElement($checkbox_tray);
    // trackback url textbox
    $trackback_tray = new XoopsFormElementTray(_BL_TRACKBACK, '<br />') ;
    $trackback_textarea = new XoopsFormTextArea(_BL_TRACKBACK_DSC."<br />",'ent_trackbackurl',
                                                    $entry->getVar('ent_trackbackurl', 'e'),
                                                    4,
                                                    $xoopsModuleConfig['editorwidth'] ) ;
    $trackback_tray->addElement($trackback_textarea);
    $blog_form->addElement($trackback_tray) ;

    // recieved trackback administrator tray
    if( isset($recieved_trackback) && is_array($recieved_trackback) && count($recieved_trackback)>0 ){
        $recieved_trackback_tray = new XoopsFormElementTray(_BL_TRACKBACK_ADMIN ,'<br />');
        foreach( $recieved_trackback as $key=>$tb_obj ){
            $checked = ( isset($_POST['delete_trackback'][$key]) ) ? true : false ;
            $recieved_trackback = new XoopsFormCheckBox(_BL_TRACKBACK_DELETE, "delete_trackback[$key]", $checked );
            $recieved_trackback->addOption($tb_obj->getVar('tb_url'), htmlspecialchars(xoops_substr($tb_obj->getVar('title','n') . ':' . $tb_obj->getVar('description','n'),0,60) . "..." ));
            $recieved_trackback_tray->addElement($recieved_trackback);
        }
        $blog_form->addElement($recieved_trackback_tray);
    }
  // permission control
  if( isset($xoopsModuleConfig['use_permissionsystem']) && $xoopsModuleConfig['use_permissionsystem'] ){
    $permission_tray = new XoopsFormElementTray( _BL_PERMISSION , '<br />');
//  $permission_tab = new XoopsFormSelect('Please set read permission of this Entry.<br />L to R :Your group\'s, Other group\'s and Anonymous users\'. "r" means readable.<br />', "permission" , $entry->getVar('permission') ) ;
//  $permission_tab->addOptionArray( array('111'=>'r-r-r' , '110'=>'r-r--' , '100'=>'r----' , '010'=>'--r--') ) ;
    $member_handler =& xoops_gethandler('group');
    $groups = $member_handler->getObjects();
    $group_option = array() ;
    $group_array = array() ;
    foreach( $groups as $group ){
           if( $group->getVar('groupid') == 1 ) continue ;
        $group_option[$group->getVar('groupid')] = $group->getVar('name') ;
        $group_array[] = $group->getVar('groupid') ;
    }
    if( is_array($entry->getVar('permission_group','n')) ){
       $default_permission = $entry->getVar('permission_group','n') ;
    }else{
       if( $entry->getVar('permission_group','n') == "all" ){
           $default_permission = $group_array ;
       }else{
           $default_permission = explode('|',$entry->getVar('permission_group','n')) ;
       }
    }
    $permission_tab = new XoopsFormSelect(_BL_PERMISSION_CAPTION."<br />" , "permission_group" , $default_permission , 4 , true ) ;
    $permission_tab->addOptionArray($group_option) ;
    $permission_tray->addElement($permission_tab) ;
    $blog_form->addElement($permission_tray);
  }

  $button_tray = new XoopsFormElementTray('' ,'');
  if ($entry->getVar('blog_id') > 0 && $isAdmin || $currentuid == $entry->getVar('user_id')) {
    $button_tray->addElement(new XoopsFormButton('','delete', _BL_DELETE_BUTTON, 'submit'));
  }
  $button_tray->addElement(new XoopsFormButton('', 'preview', _BL_PREVIEW_BUTTON, 'submit'));
  $button_tray->addElement(new XoopsFormButton('', 'post', _BL_POST_BUTTON, 'submit'));
  $blog_form->addElement($button_tray);
  $xoopsTpl->assign('form', $blog_form->render());

  if (!empty($_POST['preview'])) {
    $use_avatar = 0;
    $avatar_img = '';
    $avatar_width = 0;

    if ($xoopsModuleConfig['showavatar']) {
      $avatar = $currentUser->getVar('user_avatar', 'E');
      if (!empty($avatar) && $avatar != 'blank.gif') {
        $use_avatar = 1;
        $avatar_img = sprintf('%s/uploads/%s', XOOPS_URL, $avatar);
//        $dimension = ( ini_get('allow_url_fopen') ) ? getimagesize($avatar_img) : "" ;
//        $avatar_width = ( is_array($dimension) ) ? $dimension[0] : "" ;
      }
    }

    $xoopsTpl->assign('preview', $entry->getVar('contents', 's'));
    $xoopsTpl->assign('use_avatar', $use_avatar);
    $xoopsTpl->assign('avatar_width', $avatar_width);
    $xoopsTpl->assign('avatar_img', $avatar_img);
    $xoopsTpl->assign('title', $entry->getVar('title', 's'));
    $xoopsTpl->assign('is_preview',1);
    $xoopsTpl->assign('sample_date', formatTimestamp(time(),
                                                     $xoopsModuleConfig['dateformat'],
                                                     $xoopsConfig['default_TZ']));
  } else {
    $xoopsTpl->assign('is_preview',0);
    $xoopsTpl->assign('title','');
    $xoopsTpl->assign('preview','');
    $xoopsTpl->assign('sample_date','');
  }

  // Include the page footer
  include(XOOPS_ROOT_PATH.'/footer.php');
}
