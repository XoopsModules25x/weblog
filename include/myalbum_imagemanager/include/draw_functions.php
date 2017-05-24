<?php

// for older files
function myalbum_header()
{
    global $mod_url , $mydirname ;

    $tpl = new XoopsTpl() ;
    $tpl->assign( array( 'mod_url' => $mod_url ) ) ;
    $tpl->display( "db:{$mydirname}_header.html" ) ;
}

// for older files
function myalbum_footer()
{
    global $mod_copyright , $mydirname ;

    $tpl = new XoopsTpl() ;
    $tpl->assign( array( 'mod_copyright' => $mod_copyright ) ) ;
    $tpl->display( "db:{$mydirname}_footer.html" ) ;
}

// returns appropriate name from uid
function myalbum_get_name_from_uid( $uid )
{
    global $myalbum_nameoruname ;

    if( $uid > 0 ) {
        $member_handler =& xoops_gethandler('member');
        $poster =& $member_handler->getUser( $uid ) ;

        if( is_object( $poster ) ) {
            if( $myalbum_nameoruname == 'uname' ) {
                $name = $poster->uname() ;
            } else {
                $name = htmlspecialchars( $poster->name() ) ;
                if( $name == "" ) $name = $poster->uname() ;
            }
        } else {
            $name = _ALBM_CAPTION_GUESTNAME ;
        }

    } else {
        $name = _ALBM_CAPTION_GUESTNAME ;
    }

    return $name ;
}

// Get photo's array to assign into template (heavy version)
function myalbum_get_array_for_photo_assign( $fetched_result_array , $summary = false )
{
    global $my_uid , $isadmin , $global_perms ;
    global $photos_url , $thumbs_url , $thumbs_dir , $mod_url , $mod_path ;
    global $myalbum_makethumb , $myalbum_thumbsize , $myalbum_popular , $myalbum_newdays , $myalbum_normal_exts ;

    include_once( "$mod_path/class/myalbum.textsanitizer.php" ) ;

    $myts = MyAlbumTextSanitizer::getInstance() ;

    extract( $fetched_result_array ) ;

    if( in_array( strtolower( $ext ) , $myalbum_normal_exts ) ) {
        $imgsrc_thumb = "$thumbs_url/$lid.$ext" ;
        $imgsrc_photo = "$photos_url/$lid.$ext" ;
        $ahref_photo = "$photos_url/$lid.$ext" ;
        $is_normal_image = true ;

        // Width of thumb
        $width_spec = "width='$myalbum_thumbsize'" ;
        if( $myalbum_makethumb ) {
            list( $width , $height , $type ) = getimagesize( "$thumbs_dir/$lid.$ext" ) ;
            // if thumb images was made, 'width' and 'height' will not set.
            if( $width <= $myalbum_thumbsize ) $width_spec = '' ;
        }
    } else {
        $imgsrc_thumb = "$thumbs_url/$lid.gif" ;
        $imgsrc_photo = "$thumbs_url/$lid.gif" ;
        $ahref_photo = "$photos_url/$lid.$ext" ;
        $is_normal_image = false ;
        $width_spec = '' ;
    }

    // Voting stats
    if( $rating > 0 ) {
        if( $votes == 1 ) {
            $votestring = _ALBM_ONEVOTE ;
        } else {
            $votestring = sprintf( _ALBM_NUMVOTES , $votes ) ;
        }
        $info_votes = number_format( $rating , 2 )." ($votestring)";
    } else {
        $info_votes = '0.00 ('.sprintf( _ALBM_NUMVOTES , 0 ) . ')' ;
    }

    // Submitter's name
    $submitter_name = myalbum_get_name_from_uid( $submitter ) ;

    // Category's title
    $cat_title = empty( $cat_title ) ? '' : $cat_title ;

    // Summarize description
    if( $summary ) $description = $myts->extractSummary( $description ) ;

    return array(
        'lid' => $lid ,
        'cid' => $cid ,
        'ext' => $ext ,
        'res_x' => $res_x ,
        'res_y' => $res_y ,
        'window_x' => $res_x + 16 ,
        'window_y' => $res_y + 16 ,
        'title' => $myts->htmlSpecialChars( $title ) ,
        'datetime' => formatTimestamp( $date , 'm' ) ,
        'description' => $myts->displayTarea( $description , 0 , 1 , 1 , 1 , 1 , 1 ) ,
        'imgsrc_thumb' => $imgsrc_thumb ,
        'imgsrc_photo' => $imgsrc_photo ,
        'ahref_photo' => $ahref_photo ,
        'width_spec' => $width_spec ,
        'can_edit' => ( ( $global_perms & GPERM_EDITABLE ) && ( $my_uid == $submitter || $isadmin ) ) ,
        'submitter' => $submitter ,
        'submitter_name' => $submitter_name ,
        'hits' => $hits ,
        'rating' => $rating ,
        'rank' => floor( $rating - 0.001 ) ,
        'votes' => $votes ,
        'info_votes' => $info_votes ,
        'comments' => $comments ,
        'is_normal_image' => $is_normal_image ,
        'is_newphoto' => ( $date > time() - 86400 * $myalbum_newdays && $status == 1 ) ,
        'is_updatedphoto' => ( $date > time() - 86400 * $myalbum_newdays && $status == 2 ) ,
        'is_popularphoto' => ( $hits >= $myalbum_popular ) ,
        'info_morephotos' => sprintf( _ALBM_MOREPHOTOS , $submitter_name ) ,
        'cat_title' => $myts->htmlSpecialChars( $cat_title )
    ) ;
}

// Get photo's array to assign into template (light version)
function myalbum_get_array_for_photo_assign_light( $fetched_result_array , $summary = false )
{
    global $my_uid , $isadmin , $global_perms ;
    global $photos_url , $thumbs_url , $thumbs_dir ;
    global $myalbum_makethumb , $myalbum_thumbsize , $myalbum_normal_exts ;

    $myts = MyTextSanitizer::getInstance() ;

    extract( $fetched_result_array ) ;

    if( in_array( strtolower( $ext ) , $myalbum_normal_exts ) ) {
        $imgsrc_thumb = "$thumbs_url/$lid.$ext" ;
        $imgsrc_photo = "$photos_url/$lid.$ext" ;
        $is_normal_image = true ;
        // Width of thumb
        $width_spec = "width='$myalbum_thumbsize'" ;
        if( $myalbum_makethumb && $ext != 'gif' ) {
            // if thumb images was made, 'width' and 'height' will not set.
            $width_spec = '' ;
        }
    } else {
        $imgsrc_thumb = "$thumbs_url/$lid.gif" ;
        $imgsrc_photo = "$thumbs_url/$lid.gif" ;
        $is_normal_image = false ;
        $width_spec = "" ;
    }

    return array(
        'lid' => $lid ,
        'cid' => $cid ,
        'ext' => $ext ,
        'res_x' => $res_x ,
        'res_y' => $res_y ,
        'window_x' => $res_x + 16 ,
        'window_y' => $res_y + 16 ,
        'title' => $myts->htmlSpecialChars( $title ) ,
        'imgsrc_thumb' => $imgsrc_thumb ,
        'imgsrc_photo' => $imgsrc_photo ,
        'width_spec' => $width_spec ,
        'can_edit' => ( ( $global_perms & GPERM_EDITABLE ) && ( $my_uid == $submitter || $isadmin ) ) ,
        'hits' => $hits ,
        'rating' => $rating ,
        'rank' => floor( $rating - 0.001 ) ,
        'votes' => $votes ,
        'comments' => $comments ,
        'is_normal_image' => $is_normal_image
    ) ;
}

// get list of sub categories in header space
function myalbum_get_sub_categories( $parent_id , $cattree )
{
    global $xoopsDB , $table_cat ;

    $myts = MyTextSanitizer::getInstance() ;

    $ret = array() ;

    $crs = $xoopsDB->query( "SELECT cid, title, imgurl FROM $table_cat WHERE pid=$parent_id ORDER BY title") or die( "Error: Get Category." ) ;

    while( list( $cid , $title , $imgurl ) = $xoopsDB->fetchRow( $crs ) ) {

        // Show first child of this category
        $subcat = array() ;
        $arr = $cattree->getFirstChild( $cid , "title" ) ;
        foreach( $arr as $child ) {
            $subcat[] = array(
                'cid' => $child['cid'] ,
                'title' => $myts->htmlSpecialChars( $child['title'] ) ,
                'photo_small_sum' => myalbum_get_photo_small_sum_from_cat( $child['cid'] , "status>0" ) ,
                'number_of_subcat' => sizeof( $cattree->getFirstChildId( $child['cid'] ) )
            ) ;
        }

        // Category's banner default
        if( $imgurl == "http://" ) $imgurl = '' ;

        // Total sum of photos
        $cids = $cattree->getAllChildId( $cid ) ;
        array_push( $cids , $cid ) ;
        $photo_total_sum = myalbum_get_photo_total_sum_from_cats( $cids , "status>0" ) ;

        $ret[] = array(
            'cid' => $cid ,
            'imgurl' => $myts->htmlSpecialChars( $imgurl ) ,
            'photo_small_sum' => myalbum_get_photo_small_sum_from_cat( $cid , "status>0" ) ,
            'photo_total_sum' => $photo_total_sum ,
            'title' => $myts->htmlSpecialChars( $title ) ,
            'subcategories' => $subcat
        ) ;
    }

    return $ret ;
}

// get attributes of <img> for preview image
function myalbum_get_img_attribs_for_preview( $preview_name )
{
    global $photos_url , $mod_url , $mod_path , $myalbum_normal_exts , $myalbum_thumbsize ;

    $ext = substr( strrchr( $preview_name , '.' ) , 1 ) ;

    if( in_array( strtolower( $ext ) , $myalbum_normal_exts ) ) {
        return array( "$photos_url/$preview_name" , "width='$myalbum_thumbsize'" , "$photos_url/$preview_name" ) ;

    } else {
        if( file_exists( "$mod_path/icons/$ext.gif" ) ) {
            return array( "$mod_url/icons/mp3.gif" , '' , "$photos_url/$preview_name" ) ;
        } else {
            return array( "$mod_url/icons/default.gif" , '' , "$photos_url/$preview_name" ) ;
        }
    }
}
