<?php
// original script is myalbum-P (http://www.peak.ne.jp/xoops/)
require_once '../../mainfile.php' ;

// when this script is included by core's imagemanager.php
$mydirname = basename( dirname( __FILE__ ) ) ;
include_once XOOPS_ROOT_PATH . '/modules/' . $mydirname . '/include/myalbum_imagemanager/include/read_configs.php' ;
// include_once XOOPS_ROOT_PATH . "/modules/" . $mydirname . "/include/myalbum_imagemanager/include/get_perms.php" ;
include_once XOOPS_ROOT_PATH . "/modules/" . $mydirname . "/include/myalbum_imagemanager/include/functions.php" ;
include_once XOOPS_ROOT_PATH . "/modules/" . $mydirname . "/include/myalbum_imagemanager/include/draw_functions.php" ;
include_once XOOPS_ROOT_PATH . "/modules/" . $mydirname . "/include/gtickets.php" ;
include_once XOOPS_ROOT_PATH . "/modules/" . $mydirname . "/header.php";
include_once XOOPS_ROOT_PATH . "/modules/" . $mydirname . "/class/class.weblogtree.php";
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
include_once XOOPS_ROOT_PATH . '/class/template.php';
// check post privilege
include_once(sprintf('%s/modules/%s/include/privilege.inc.php', XOOPS_ROOT_PATH, $xoopsModule->dirname())) ;
if( ! is_object($xoopsUser) || !$xoopsUser->isAdmin($xoopsModule->mid()) && ( ! checkprivilege( "edit" , $xoopsModule->dirname() ) ) ){
     redirect_header( $mod_url. "/weblog-imagemanager_close.php" , 5 , _BL_ALBM_MUSTREGFIRST );
    exit() ;
}else{
    $post_privilege = true ;
}
$myts = MyTextSanitizer::getInstance();    // MyTextSanitizer object
$cattree = new WeblogTree( $table_cat , "cat_id" , "cat_pid" ) ;

// Get variables
if( empty( $_GET['target'] ) ) exit ;
$num = empty( $_GET['num'] ) ? 10 : intval( $_GET['num'] ) ;
$cid = !isset($_GET['cid']) ? 0 : intval($_GET['cid']);
// POST variables
$lid = empty( $_POST['lid'] ) ? 0 : intval( $_POST['lid'] ) ;

// Do Delete
if( ! empty( $_POST['do_delete'] ) ) {
    if ( ! $xoopsGTicket->check() ){
       redirect_header( $mod_url. "/weblog-imagemanager.php?target=contents" , 3 , $xoopsGTicket->getErrors() );
       exit() ;
    }
/*	if( ! ( $global_perms & GPERM_DELETABLE ) ) {
        redirect_header( $mod_url. "weblog-imagemanager.php?target=contents" , 3 , _NOPERM ) ;
        exit ;
    }

    // anti-CSRF
    if( ! xoops_refcheck() ) die( "XOOPS_URL is not included in your REFERER" ) ;
*/
    // get and check lid is valid
    if( $lid < 1 ) die( "Invalid photo id." ) ;

    $whr = "lid=$lid" ;
    if( ! $isadmin ) $whr .= " AND submitter=$my_uid" ;

    myalbum_delete_photos( $whr ) ;

    redirect_header( $mod_url . "/weblog-imagemanager.php?target=contents" , 3 , _BL_ALBM_DELETINGPHOTO ) ;
    exit ;
}

// Confirm Delete
if( isset($_POST['op']) && $_POST['op'] == "conf_delete" ) {
    if ( ! $xoopsGTicket->check() ){
       redirect_header( $mod_url. "/weblog-imagemanager.php?target=contents" , 3 , $xoopsGTicket->getErrors() );
       exit() ;
    }
/*	if( ! ( $global_perms & GPERM_DELETABLE ) ) {
        redirect_header( $mod_url . "weblog-imagemanager.php?target=contents" , 3 , _NOPERM ) ;
        exit ;
    }
    include( XOOPS_ROOT_PATH."/include/cp_functions.php" ) ;
    include_once( "../../header.php" ) ;
*/

    $result = $xoopsDB->query( "SELECT l.ext FROM $table_photos l WHERE l.lid=$lid" ) ;
    list( $ext ) = $xoopsDB->fetchRow( $result ) ;
    if( ! in_array( strtolower( $ext ) , $myalbum_normal_exts ) ) $ext = 'gif' ;
    $xoopsTpl = new XoopsTpl();
    $xoopsTpl->assign('delete_mode', 1 );
    $xoopsTpl->assign('image_tag' , "<img src='" . $thumbs_url . "/" . $lid ."." . $ext . "' />" );
    $xoopsTpl->assign('lid', $lid);
    $xoopsTpl->assign('lang_photodel', _BL_ALBM_PHOTODEL);
    $xoopsTpl->assign('lang_yes', _YES);
    $xoopsTpl->assign('lang_no', _NO);
    $xoopsTpl->assign('xoopsGTicket', $xoopsGTicket->getTicketHtml( __LINE__ ));

//	include( XOOPS_ROOT_PATH . "/footer.php" ) ;
    $xoopsTpl->display( "db:{$mydirname}_imagemanager.html" ) ;
    exit ;
}

$xoopsTpl = new XoopsTpl();
$xoopsTpl->assign('lang_imgmanager', _IMGMANAGER);
$xoopsTpl->assign('sitename', $xoopsConfig['sitename']);
$target = htmlspecialchars($_GET['target'], ENT_QUOTES);
$xoopsTpl->assign('target', $target);
$xoopsTpl->assign('mod_url', $mod_url);
$xoopsTpl->assign('cid', $cid);
//$xoopsTpl->assign('can_add', ( $global_perms & GPERM_INSERTABLE ) && $cid );
$xoopsTpl->assign('can_add', ( ($post_privilege || $xoopsUser->isAdmin()) && $cid)  );
$cats = $cattree->getChildTreeArray( 0 , 'cat_title' ) ;
$xoopsTpl->assign('makethumb', $myalbum_makethumb);
$xoopsTpl->assign('lang_imagesize', _BL_ALBM_CAPTION_IMAGEXYT);
$xoopsTpl->assign('lang_align', _ALIGN);
$xoopsTpl->assign('lang_add', _ADD);
$xoopsTpl->assign('lang_close', _CLOSE);
$xoopsTpl->assign('lang_left', _LEFT);
$xoopsTpl->assign('lang_center', _CENTER);
$xoopsTpl->assign('lang_right', _RIGHT);
$xoopsTpl->assign('xoopsGTicket', $xoopsGTicket->getTicketHtml( __LINE__ ));

if( sizeof( $cats ) > 0 ) {
    $xoopsTpl->assign('lang_refresh', _BL_ALBM_CAPTION_REFRESH);

    // WHERE clause for ext
    // $whr_ext = "ext IN ('" . implode( "','" , $myalbum_normal_exts ) . "')" ;
    $whr_ext = ( $isadmin ) ? '1' : "submitter=" . $my_uid ;    // show all when user has weblog admin
    $select_is_normal = "ext IN ('" . implode( "','" , $myalbum_normal_exts ) . "')" ;

    // select box for category
    $cat_options = "<option value='0'>--</option>\n" ;
//	$prs = $xoopsDB->query( "SELECT cid,COUNT(lid) FROM $table_photos WHERE status>0 AND $whr_ext GROUP BY cid" ) ;
    $prs = $xoopsDB->query( "SELECT cid,COUNT(lid) FROM $table_photos WHERE $whr_ext GROUP BY cid" ) ;
    $photo_counts = array() ;
    while( list( $c , $p ) = $xoopsDB->fetchRow( $prs ) ) {
        $photo_counts[ $c ] = $p ;
    }
    foreach( $cats as $cat ) {
        $newcat = $myts->htmlSpecialChars($cat['cat_title']) ;
        $prefix = str_replace( '.' , '--' , substr( $cat['prefix'] , 1 ) ) ;
        $photo_count = isset( $photo_counts[ $cat['cat_id'] ] ) ? $photo_counts[ $cat['cat_id'] ] : 0 ;
        if( $cid == $cat['cat_id'] ) $cat_options .= "<option value='{$cat['cat_id']}' selected='selected'>$prefix{$newcat} ($photo_count)</option>\n" ;
        else $cat_options .= "<option value='{$cat['cat_id']}'>$prefix{$newcat} ($photo_count)</option>\n" ;
    }
    $xoopsTpl->assign('cat_options', $cat_options);

    if( $cid > 0 ) {

        $xoopsTpl->assign('lang_addimage', _ADDIMAGE);

        $rs = $xoopsDB->query( "SELECT COUNT(*) FROM $table_photos WHERE cid='$cid' AND status>0 AND $whr_ext") ;
        list( $total ) = $xoopsDB->fetchRow( $rs ) ;
        if ($total > 0) {
            $start = empty( $_GET['start'] ) ? 0 : intval( $_GET['start'] ) ;
            $prs = $xoopsDB->query( "SELECT lid,cid,title,ext,submitter,res_x,res_y,$select_is_normal AS is_normal FROM $table_photos WHERE cid='$cid' AND status>0 AND $whr_ext ORDER BY date DESC LIMIT $start,$num" ) ;
            $xoopsTpl->assign('image_total', $total);
            $xoopsTpl->assign('lang_image', _IMAGE);
            $xoopsTpl->assign('lang_imagename', _IMAGENAME);

            if( $total > $num ) {
                $nav = new XoopsPageNav( $total , $num , $start , 'start' , "target=$target&amp;cid=$cid&amp;num=$num" ) ;
                $xoopsTpl->assign( 'pagenav' , $nav->renderNav() ) ;
            }

            // use [siteimg] or [img]
            if( empty( $myalbum_usesiteimg ) ) {
                // using links with XOOPS_URL
                $img_tag = 'img' ;
                $url_tag = 'url' ;
                $pdir = $photos_url ;
                $tdir = $thumbs_url ;
            } else {
                // using links without XOOPS_URL
                $img_tag = 'siteimg' ;
                $url_tag = 'siteurl' ;
                $pdir = substr( $myalbum_photospath , 1 ) ;
                $tdir = substr( $myalbum_thumbspath , 1 ) ;
            }

            $i = 1 ;
            while( list( $lid , $cid , $title , $ext , $submitter , $res_x , $res_y , $is_normal ) = $xoopsDB->fetchRow( $prs ) ) {

                // Width of thumb
                if( ! $is_normal ) {
                    $width_spec = '' ;
                    $image_ext = 'gif' ;
                } else {
                    $width_spec = "width='$myalbum_thumbsize'" ;
                    $image_ext = $ext ;
                    if( $myalbum_makethumb ) {
                        list( $width , $height , $type ) = getimagesize( "$thumbs_dir/$lid.$ext" ) ;
                        if( $width <= $myalbum_thumbsize ) $width_spec = '' ;
                    }
                }
//				echo $my_uid . "::my_uid<br>" . $submitter . "::submitter<br>" . $isadmin . "::isadmin<br>" ;
                $xcodel = "[$url_tag=$pdir/{$lid}.{$ext}][$img_tag align=left]$tdir/{$lid}.{$image_ext}[/$img_tag][/$url_tag]";
                $xcodec = "[$url_tag=$pdir/{$lid}.{$ext}][$img_tag]$tdir/{$lid}.{$image_ext}[/$img_tag][/$url_tag]";
                $xcoder = "[$url_tag=$pdir/{$lid}.{$ext}][$img_tag align=right]$tdir/{$lid}.{$image_ext}[/$img_tag][/$url_tag]";
                $xcodebl = "[$img_tag align=left]$pdir/{$lid}.{$ext}[/$img_tag]";
                $xcodebc = "[$img_tag]$pdir/{$lid}.{$ext}[/$img_tag]";
                $xcodebr = "[$img_tag align=right]$pdir/{$lid}.{$ext}[/$img_tag]";
                $xoopsTpl->append( 'photos' , array(
                    'lid' => $lid ,
                    'ext' => $ext ,
                    'res_x' => $res_x ,
                    'res_y' => $res_y ,
                    'nicename' => $myts->htmlSpecialChars( $title ) ,
                    'src' => "$thumbs_url/{$lid}.{$image_ext}" ,
//					'can_edit' => ( ( $global_perms & GPERM_EDITABLE ) && ( $my_uid == $submitter || $isadmin ) ) ,
                    'can_edit' => ( $post_privilege && ( $my_uid == $submitter || $isadmin ) ) ,
                    'width_spec' => $width_spec ,
                    'xcodel' => $xcodel ,
                    'xcodec' => $xcodec ,
                    'xcoder' => $xcoder ,
                    'xcodebl' => $xcodebl ,
                    'xcodebc' => $xcodebc ,
                    'xcodebr' => $xcodebr ,
                    'is_normal' => $is_normal ,
                    'count' => $i ++
                ) ) ;
            }

        } else {
            $xoopsTpl->assign('image_total', 0);
        }
    }
    $xoopsTpl->assign('xsize', 600);
    $xoopsTpl->assign('ysize', 400);
} else {
    $xoopsTpl->assign('xsize', 400);
    $xoopsTpl->assign('ysize', 180);
}

$xoopsTpl->display( "db:{$mydirname}_imagemanager.html" ) ;
exit ;
