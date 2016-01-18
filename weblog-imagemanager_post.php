<?php
// ------------------------------------------------------------------------- //
//                      myAlbum-P - XOOPS photo album                        //
//                        <http://www.peak.ne.jp/>                           //
//                         compatible with weBLog                              //
//                   http://xoopsdevelopment.tohokuaiki.jp                   //
// ------------------------------------------------------------------------- //

include_once "../../mainfile.php" ;
$mydirname = basename( dirname( __FILE__ ) ) ;
include_once XOOPS_ROOT_PATH."/modules/".$mydirname."/include/myalbum_imagemanager/include/read_configs.php";
// include_once XOOPS_ROOT_PATH."/modules/".$mydirname."/include/myalbum_imagemanager/include/get_perms.php";
include_once XOOPS_ROOT_PATH."/modules/".$mydirname."/include/myalbum_imagemanager/include/functions.php";
include_once XOOPS_ROOT_PATH."/modules/".$mydirname."/include/myalbum_imagemanager/include/draw_functions.php";
include_once XOOPS_ROOT_PATH . "/modules/" . $mydirname . "/include/gtickets.php" ;
include_once XOOPS_ROOT_PATH . "/modules/" . $mydirname . "/class/class.weblogtree.php";
include_once XOOPS_ROOT_PATH . '/modules/'.$mydirname.'/include/myalbum_imagemanager/class/myuploader.php' ;
include_once XOOPS_ROOT_PATH . '/modules/'.$mydirname.'/include/myalbum_imagemanager/class/myalbum.textsanitizer.php' ;

// check post privilege
include_once(sprintf('%s/modules/%s/include/privilege.inc.php', XOOPS_ROOT_PATH, $xoopsModule->dirname())) ;
if( ! is_object($xoopsUser) || !$xoopsUser->isAdmin($xoopsModule->mid()) && ( ! checkprivilege( "edit" , $xoopsModule->dirname() ) ) ){
     redirect_header( $mod_url. "/weblog-imagemanager_close.php" , 5 , _BL_ALBM_MUSTREGFIRST );
    exit() ;
}else{
    $post_privilege = true ;
}

$myts =& MyAlbumTextSanitizer::getInstance() ;
$cattree = new WeblogTree( $table_cat ,"cat_id" , "cat_pid" ) ;

// GET variables
$caller = empty( $_GET['caller'] ) ? '' : $_GET['caller'] ;

// POST variables
$preview_name = empty( $_POST['preview_name'] ) ? '' : $_POST['preview_name'] ;

/*
// check INSERTABLE
if( ! ( $global_perms & GPERM_INSERTABLE ) ) {
    redirect_header( XOOPS_URL."/user.php" , 2 , _BL_ALBM_MUSTREGFIRST ) ;
    exit ;
} */

// check Categories exist
$result = $xoopsDB->query( "SELECT count(cat_id) as count FROM $table_cat" ) ;
list( $count ) = $xoopsDB->fetchRow( $result ) ;
if( $count < 1 ) {
    redirect_header( XOOPS_URL."/modules/$mydirname/weblog-imagemanager_post.php" , 2 , _BL_ALBM_MUSTADDCATFIRST ) ;
    exit ;
}

// check file_uploads = on
if( ! ini_get( "file_uploads" ) ) $file_uploads_off = true ;

// get flag of safe_mode
$safe_mode_flag = ini_get( "safe_mode" ) ;

// check or make photos_dir
if( ! is_dir( $photos_dir ) ) {
    if( $safe_mode_flag ) {
        redirect_header(XOOPS_URL."/modules/$mydirname/weblog-imagemanager_post.php",10,"At first create & chmod 777 '$photos_dir' by ftp or shell.");
        exit ;
    }

    $rs = mkdir( $photos_dir , 0777 ) ;
    if( ! $rs ) {
        redirect_header(XOOPS_URL."/modules/$mydirname/weblog-imagemanager_post.php",10,"$photos_dir is not a directory");
        exit ;
    } else @chmod( $photos_dir , 0777 ) ;
}

// check or make thumbs_dir
if( $myalbum_makethumb && ! is_dir( $thumbs_dir ) ) {
    if( $safe_mode_flag ) {
        redirect_header(XOOPS_URL."/modules/$mydirname/weblog-imagemanager_post.php",10,"At first create & chmod 777 '$thumbs_dir' by ftp or shell.");
        exit ;
    }

    $rs = mkdir( $thumbs_dir , 0777 ) ;
    if( ! $rs ) {
        redirect_header(XOOPS_URL."/modules/$mydirname/weblog-imagemanager_post.php",10,"$thumbs_dir is not a directory");
        exit ;
    } else @chmod( $thumbs_dir , 0777 ) ;
}

// check or set permissions of photos_dir
if( ! is_writable( $photos_dir ) || ! is_readable( $photos_dir ) ) {
    $rs = chmod( $photos_dir , 0777 ) ;
    if( ! $rs ) {
        redirect_header(XOOPS_URL."/modules/$mydirname/weblog-imagemanager_post.php",5,"chmod 0777 into $photos_dir failed");
        exit ;
    }
}

// check or set permissions of thumbs_dir
if( $myalbum_makethumb && ! is_writable( $thumbs_dir ) ) {
    $rs = chmod( $thumbs_dir , 0777 ) ;
    if( ! $rs ) {
        redirect_header(XOOPS_URL."/modules/$mydirname/weblog-imagemanager_post.php",5,"chmod 0777 into $thumbs_dir failed");
        exit ;
    }
}

if( ! empty( $_POST['submit'] ) ) {

    // anti-CSRF
    if( ! xoops_refcheck() ) die( "XOOPS_URL is not included in your REFERER" ) ;

    $submitter = $my_uid ;

    $cid = empty( $_POST['cid'] ) ? 0 : intval( $_POST['cid'] ) ;
    $newid = $xoopsDB->genId( $table_photos."_lid_seq" ) ;

    // Check if cid is valid
    if( $cid <= 0 ) {
        redirect_header(XOOPS_URL."/modules/$mydirname/weblog-imagemanager_post.php" , 2 , 'Category is not specified.' ) ;
        exit ;
    }

    // Check if upload file name specified
    $field = $_POST["xoops_upload_file"][0] ;
    if( empty( $field ) || $field == "" ) {
        die( "UPLOAD error: file name not specified" ) ;
    }
    $field = $_POST['xoops_upload_file'][0] ;

    if( $_FILES[$field]['name'] == '' ) {
        // No photo uploaded

        if( trim( $_POST["title"] ) === "" ) {
            $_POST['title'] = 'no title' ;
        }

        if( $preview_name != '' && is_readable( "$photos_dir/$preview_name" ) ) {
            $tmp_name = $preview_name ;
        } else {
            if( empty( $myalbum_allownoimage ) ) {
                redirect_header( 'weblog-imagemanager_post.php' , 2 , _BL_ALBM_NOIMAGESPECIFIED ) ;
                exit ;
            } else {
                @copy( "$mod_path/images/pixel_trans.gif" , "$photos_dir/pixel_trans.gif" ) ;
                $tmp_name = 'pixel_trans.gif' ;
            }
        }

    } else if( $_FILES[$field]['tmp_name'] == "" ) {
        // Fail to upload (wrong file name etc.)
        redirect_header( 'weblog-imagemanager_post.php' , 2 , _BL_ALBM_FILEERROR ) ;
        exit ;

    } else {
        if( $myalbum_canresize ) $uploader = new MyXoopsMediaUploader( $photos_dir , $array_allowed_mimetypes , $myalbum_fsize , null , null , $array_allowed_exts ) ;
        else $uploader = new MyXoopsMediaUploader( $photos_dir , $array_allowed_mimetypes , $myalbum_fsize , $myalbum_width , $myalbum_height , $array_allowed_exts ) ;

        $uploader->setPrefix( 'tmp_' ) ;
        if( $uploader->fetchMedia( $field ) && $uploader->upload() ) {
            // Succeed to upload

            // The original file name will be the title if title is empty
            if( trim( $_POST["title"] ) === "" ) {
                $_POST['title'] = $uploader->getMediaName() ;
            }

            $tmp_name = $uploader->getSavedFileName() ;

        } else {
            // Fail to upload (sizeover etc.)
            include(XOOPS_ROOT_PATH."/header.php");

            echo $uploader->getErrors();
            @unlink( $uploader->getSavedDestination() ) ;

            include( XOOPS_ROOT_PATH . "/footer.php" ) ;
            exit ;
        }
    }

    if( ! is_readable( "$photos_dir/$tmp_name" ) ) {
        redirect_header(XOOPS_URL."/modules/$mydirname/weblog-imagemanager_post.php" , 2 , _BL_ALBM_FILEREADERROR ) ;
        exit ;
    }

    $title = $myts->stripSlashesGPC( $_POST["title"] ) ;
//	$desc_text = $myts->stripSlashesGPC( $_POST["desc_text"] ) ;
    $date = time() ;
    $ext = substr( strrchr( $tmp_name , '.' ) , 1 ) ;
//	$status = ( $global_perms & GPERM_SUPERINSERT ) ? 1 : 0 ;
    $status = 1 ;
    $sql = "INSERT INTO $table_photos (lid, cid, title, ext, submitter, status, date) VALUES ($newid, $cid, '".addslashes($title)."', '$ext', $submitter, $status, $date)";
    $xoopsDB->query( $sql ) or die( "DB error: INSERT photo table" . $sql ) ;
    if( $newid == 0 ) {
        $newid = $xoopsDB->getInsertId();
    }

    myalbum_modify_photo( "$photos_dir/$tmp_name" , "$photos_dir/$newid.$ext" ) ;
    $dim = GetImageSize( "$photos_dir/$newid.$ext" ) ;
    if( $dim ) $xoopsDB->query( "UPDATE $table_photos SET res_x='{$dim[0]}', res_y='{$dim[1]}' WHERE lid='$newid'") ;

    if( ! myalbum_create_thumb( "$photos_dir/$newid.$ext" , $newid , $ext ) ) {
        $xoopsDB->query( "DELETE FROM $table_photos WHERE lid=$newid" ) ;
        redirect_header(XOOPS_URL."/modules/$mydirname/weblog-imagemanager_post.php" , 2 , _BL_ALBM_FILEREADERROR ) ;
        exit ;
    }

//	$xoopsDB->query( "INSERT INTO $table_text (lid, description) VALUES ($newid, '".addslashes($desc_text)."')") or die( "DB error: INSERT text table" ) ;

    // Update User's Posts (Should be modified when need admission.)
//	$xoopsDB->query( "UPDATE ".$xoopsDB->prefix('users')." SET posts=posts+'$myalbum_addposts' WHERE uid='$submitter'" ) ;
/*
    // Trigger Notification
    if( $status ) {
        $notification_handler =& xoops_gethandler( 'notification' ) ;

        // Global Notification
        $notification_handler->triggerEvent( 'global' , 0 , 'new_photo' , array( 'PHOTO_TITLE' => $title , 'PHOTO_URI' => "$mod_url/photo.php?lid=$newid&cid=$cid" ) ) ;

        // Category Notification
        $rs = $xoopsDB->query( "SELECT title FROM $table_cat WHERE cid=$cid" ) ;
        list( $cat_title ) = $xoopsDB->fetchRow( $rs ) ;
        $notification_handler->triggerEvent( 'category' , $cid , 'new_photo' , array( 'PHOTO_TITLE' => $title , 'CATEGORY_TITLE' => $cat_title , 'PHOTO_URI' => "$mod_url/photo.php?lid=$newid&cid=$cid" ) ) ;
    }
*/
    // Clear tempolary files
    myalbum_clear_tmp_files( $photos_dir ) ;

//	$redirect_uri = "viewcat.php?cid=$cid&amp;orderby=dateD" ;
//	if( $caller == 'imagemanager' ) $redirect_uri = 'close.php' ;
    $redirect_uri = XOOPS_URL."/modules/$mydirname/weblog-imagemanager_close.php" ;
    redirect_header( $redirect_uri , 2 , _BL_ALBM_RECEIVED ) ;
    exit ;
}

// Editing Display

//if( $caller == 'imagemanager' ) {
    echo "<html><head>
		<link rel='stylesheet' type='text/css' media='all' href='".XOOPS_URL."/xoops.css' />
		<link rel='stylesheet' type='text/css' media='all' href='".XOOPS_URL."/modules/system/style.css' />
		<meta http-equiv='content-type' content='text/html; charset='"._CHARSET."' />
		<meta http-equiv='content-language' content='"._LANGCODE."' />
		</head><body>\n" ;
/*
} else {
    include( XOOPS_ROOT_PATH . "/header.php" ) ;
    OpenTable() ;
    myalbum_header() ;
}
*/

include_once( "../../class/xoopsformloader.php" ) ;
include_once( "../../include/xoopscodes.php" ) ;

// Preview
/*
if( $caller != 'imagemanager' && ! empty( $_POST['preview'] ) ) {
    $photo['description'] = $myts->stripSlashesGPC( $_POST["desc_text"] ) ;
    $photo['title'] = $myts->stripSlashesGPC( $_POST["title"] ) ;
    $photo['cid'] = empty( $_POST['cid'] ) ? 0 : intval( $_POST['cid'] ) ;

    $field = $_POST['xoops_upload_file'][0] ;
    if( is_readable( $_FILES[$field]['tmp_name'] ) ) {
        // new preview
        if( $myalbum_canresize ) $uploader = new MyXoopsMediaUploader( $photos_dir , $array_allowed_mimetypes , $myalbum_fsize , null , null , $array_allowed_exts ) ;
        else $uploader = new MyXoopsMediaUploader( $photos_dir , $array_allowed_mimetypes , $myalbum_fsize , $myalbum_width , $myalbum_height , $array_allowed_exts ) ;
        $uploader->setPrefix( 'tmp_' ) ;
        if( $uploader->fetchMedia( $field ) && $uploader->upload() ) {
            $tmp_name = $uploader->getSavedFileName() ;
            $preview_name = str_replace( 'tmp_' , 'tmp_prev_' , $tmp_name ) ;
            myalbum_modify_photo( "$photos_dir/$tmp_name" , "$photos_dir/$preview_name" ) ;
            list( $imgsrc , $width_spec , $ahref ) = myalbum_get_img_attribs_for_preview( $preview_name ) ;
        } else {
            @unlink( $uploader->getSavedDestination() ) ;
            $imgsrc = "$mod_url/images/pixel_trans.gif" ;
            $width_spec = "width='$myalbum_thumbsize' height='$myalbum_thumbsize'" ;
            $ahref = '' ;
        }
    } else if( $preview_name != '' && is_readable( "$photos_dir/$preview_name" ) ) {
        // old preview
        list( $imgsrc , $width_spec , $ahref ) = myalbum_get_img_attribs_for_preview( $preview_name ) ;
    } else {
        // preview without image
        $imgsrc = "$mod_url/images/pixel_trans.gif" ;
        $width_spec = "width='$myalbum_thumbsize' height='$myalbum_thumbsize'" ;
        $ahref = '' ;
    }

    // Display Preview
    $photo_for_tpl = array(
        'description' => $myts->displayTarea( $photo['description'] , 0 , 1 , 1 , 1 , 1 , 1 ) ,
        'title' => $myts->htmlSpecialChars( $photo['title'] ) ,
        'width_spec' => $width_spec ,
        'submitter' => $my_uid ,
        'submitter_name' => myalbum_get_name_from_uid( $my_uid ) ,
        'imgsrc_thumb' => $imgsrc ,
        'ahref_photo' => $ahref
    ) ;
    $tpl = new XoopsTpl() ;
    include( 'include/assign_globals.php' ) ;
    $tpl->assign( $myalbum_assign_globals ) ;
    $tpl->assign( 'photo' , $photo_for_tpl ) ;
    echo "<table class='outer' style='width:100%;'>" ;
    $tpl->display( "db:{$mydirname}_photo_in_list.html" ) ;
    echo "</table>\n" ;

} else {
*/
    $photo = array(
        'cid' => ( empty( $_GET['cid'] ) ? 0 : intval( $_GET['cid'] ) ) ,
        'description' => '' ,
        'title' => ''
    ) ;
//}


// Show the form
$form = new XoopsThemeForm( _BL_ALBM_PHOTOUPLOAD , "uploadphoto" , "weblog-imagemanager_post.php?caller=$caller" ) ;
$pixels_text = "$myalbum_width x $myalbum_height" ;
if( $myalbum_canresize ) $pixels_text .= " (auto resize)" ;
$pixels_label = new XoopsFormLabel( _BL_ALBM_MAXPIXEL , $pixels_text ) ;
if( $myalbum_fsize >= 1000000 ){
    $myalbum_fsize_desc = number_format($myalbum_fsize/1000000 , 1) . "Mbyte" ;
}elseif( $myalbum_fsize >= 1000 ){
    $myalbum_fsize_desc = number_format($myalbum_fsize/1000 , 1) . "Kbyte" ;
}else{
    $myalbum_fsize_desc = $myalbum_fsize ;
}
$size_label = new XoopsFormLabel( _BL_ALBM_MAXSIZE , $myalbum_fsize_desc . ( empty( $file_uploads_off ) ? "" : ' &nbsp; <b>"file_uploads" off</b>' ) ) ;
$form->setExtra( "enctype='multipart/form-data'" ) ;

$title_text = new XoopsFormText( _BL_ALBM_PHOTOTITLE , "title" , 50 , 255 , $myts->htmlSpecialChars( $photo['title'] ) ) ;

$cat_select = new XoopsFormSelect( _BL_ALBM_PHOTOCAT , "cid" , $photo['cid'] ) ;
$cat_select->addOption( '' , '----' ) ;
$tree = $cattree->getChildTreeArray( 0 , "cat_title" ) ;
foreach( $tree as $leaf ) {
//	$leaf['prefix'] = substr( $leaf['prefix'] , 0 , -1 ) ;
//	$leaf['prefix'] = str_replace( "." , "--" , $leaf['prefix'] ) ;
//	$cat_select->addOption( $leaf['cid'] , $leaf['prefix'] . $leaf['title'] ) ;
    $cat_select->addOption( $leaf['cat_id'] , $leaf['cat_title'] ) ;
}

/* $cat_link = new XoopsFormLabel( "<a href='javascript:location.href=\"viewcat.php?cid=\"+document.uploadphoto.cid.value;'>"._GO."</a>" ) ;
$cat_tray = new XoopsFormElementTray( _BL_ALBM_PHOTOCAT , '&nbsp;' ) ;
$cat_tray->addElement( $cat_select ) ;
$cat_tray->addElement( $cat_link ) ; */

//$desc_tarea = new XoopsFormDhtmlTextArea( _BL_ALBM_PHOTODESC , "desc_text" , $myts->makeTareaData4Edit( $photo['description'] ) , 10 , 50 ) ;

$file_form = new XoopsFormFile( _BL_ALBM_SELECTFILE , "photofile" , $myalbum_fsize ) ;
$file_form->setExtra( "size='40'" ) ;

/*if( $myalbum_canrotate ) {
    $rotate_radio = new XoopsFormRadio( _BL_ALBM_RADIO_ROTATETITLE , 'rotate' , 'rot0' ) ;
    $rotate_radio->addOption( 'rot0' , _BL_ALBM_RADIO_ROTATE0." &nbsp; " ) ;
    $rotate_radio->addOption( 'rot90' , "<img src='images/icon_rotate90.gif' alt='"._BL_ALBM_RADIO_ROTATE90."' title='"._BL_ALBM_RADIO_ROTATE90."' /> &nbsp; " ) ;
    $rotate_radio->addOption( 'rot180' , "<img src='images/icon_rotate180.gif' alt='"._BL_ALBM_RADIO_ROTATE180."' title='"._BL_ALBM_RADIO_ROTATE180."' /> &nbsp; " ) ;
    $rotate_radio->addOption( 'rot270' , "<img src='images/icon_rotate270.gif' alt='"._BL_ALBM_RADIO_ROTATE270."' title='"._BL_ALBM_RADIO_ROTATE270."' /> &nbsp; " ) ;
}*/

$op_hidden = new XoopsFormHidden( "op" , "submit" ) ;
$counter_hidden = new XoopsFormHidden( "fieldCounter" , 1 ) ;
$preview_hidden = new XoopsFormHidden( "preview_name" , htmlspecialchars( $preview_name ) , ENT_QUOTES ) ;

$submit_button = new XoopsFormButton( "" , "submit" , _SUBMIT , "submit" ) ;
$preview_button = new XoopsFormButton( "" , "preview" , _PREVIEW , "submit" ) ;
$reset_button = new XoopsFormButton( "" , "reset" , _CANCEL , "reset" ) ;
$submit_tray = new XoopsFormElementTray( '' ) ;
//if( $caller != 'imagemanager' ) $submit_tray->addElement( $preview_button ) ;
$submit_tray->addElement( $submit_button ) ;
$submit_tray->addElement( $reset_button ) ;

$form->addElement( $pixels_label ) ;
$form->addElement( $size_label ) ;
$form->addElement( $title_text ) ;
//$form->addElement( $desc_tarea ) ;
$form->addElement( $cat_select ) ;
$form->setRequired( $cat_select ) ;
$form->addElement( $file_form ) ;
//if( $myalbum_canrotate ) $form->addElement( $rotate_radio ) ;
$form->addElement( $preview_hidden ) ;
$form->addElement( $counter_hidden ) ;
$form->addElement( $op_hidden ) ;
$form->addElement( $submit_tray ) ;
// $form->setRequired( $file_form ) ;
$form->display() ;

//if( $caller == 'imagemanager' ) {
    echo "</body></html>" ;
/*
} else {
    CloseTable() ;
    myalbum_footer() ;
    include( XOOPS_ROOT_PATH . "/footer.php" ) ;
}
*/;
