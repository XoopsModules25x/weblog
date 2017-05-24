<?php
// ------------------------------------------------------------------------- //
//                      myAlbum-P - XOOPS photo album                        //
//                        <http://www.peak.ne.jp/>                           //
// ------------------------------------------------------------------------- //

// constants
define( 'PIPEID_GD' , 0 ) ;
define( 'PIPEID_IMAGICK' , 1 ) ;
define( 'PIPEID_NETPBM' , 2 ) ;

function myalbum_get_thumbnail_wh( $width , $height )
{
    global $myalbum_thumbsize , $myalbum_thumbrule ;

    switch( $myalbum_thumbrule ) {
        case 'w' :
            $new_w = $myalbum_thumbsize ;
            $scale = $width / $new_w ;
            $new_h = intval( round( $height / $scale ) ) ;
            break ;
        case 'h' :
            $new_h = $myalbum_thumbsize ;
            $scale = $height / $new_h ;
            $new_w = intval( round( $width / $scale ) ) ;
            break ;
        case 'b' :
            if( $width > $height ) {
                $new_w = $myalbum_thumbsize ;
                $scale = $width / $new_w ;
                $new_h = intval( round( $height / $scale ) ) ;
            } else {
                $new_h = $myalbum_thumbsize ;
                $scale = $height / $new_h ;
                $new_w = intval( round( $width / $scale ) ) ;
            }
            break ;
        default :
            $new_w = $myalbum_thumbsize ;
            $new_h = $myalbum_thumbsize ;
            break ;
    }

    return array( $new_w , $new_h ) ;
}

// create_thumb Wrapper
// return value
//   0 : read fault
//   1 : complete created
//   2 : copied
//   3 : skipped
//   4 : icon gif (not normal exts)
function myalbum_create_thumb( $src_path , $node , $ext )
{
    global $myalbum_imagingpipe , $myalbum_makethumb , $myalbum_normal_exts ;

    if( ! in_array( strtolower( $ext ) , $myalbum_normal_exts ) ) {
        return myalbum_copy_thumb_from_icons( $src_path , $node , $ext ) ;
    }

    if( ! $myalbum_makethumb ) return 3 ;

    if( $myalbum_imagingpipe == PIPEID_IMAGICK ) {
        return myalbum_create_thumb_by_imagick( $src_path , $node , $ext ) ;
    } else if( $myalbum_imagingpipe == PIPEID_NETPBM ) {
        return myalbum_create_thumb_by_netpbm( $src_path , $node , $ext ) ;
    } else {
        return myalbum_create_thumb_by_gd( $src_path , $node, $ext ) ;
    }
}

// Copy Thumbnail from directory of icons
function myalbum_copy_thumb_from_icons( $src_path , $node , $ext )
{
    global $mod_path , $thumbs_dir ;

    @unlink( "$thumbs_dir/$node.gif" ) ;
    if( file_exists( "$mod_path/icons/$ext.gif" ) ) {
        $copy_success = copy( "$mod_path/icons/$ext.gif" , "$thumbs_dir/$node.gif" ) ;
    }
    if( empty( $copy_success ) ) {
        @copy( "$mod_path/icons/default.gif" , "$thumbs_dir/$node.gif" ) ;
    }

    return 4 ;
}

// Creating Thumbnail by GD
function myalbum_create_thumb_by_gd( $src_path , $node , $ext )
{
    global $myalbum_forcegd2 , $thumbs_dir ;

    $bundled_2 = false ;
    if( ! $myalbum_forcegd2 && function_exists( 'gd_info' ) ) {
        $gd_info = gd_info() ;
        if( substr( $gd_info['GD Version'] , 0 , 10 ) == 'bundled (2' ) $bundled_2 = true ;
    }

    if( ! is_readable( $src_path ) ) return 0 ;
    @unlink( "$thumbs_dir/$node.$ext" ) ;
    list( $width , $height , $type ) = getimagesize( $src_path ) ;
    switch( $type ) {
        case 1 :
            // GIF (skip)
            @copy( $src_path , "$thumbs_dir/$node.$ext" ) ;

            return 2 ;
        case 2 :
            // JPEG
            $src_img = imagecreatefromjpeg( $src_path ) ;
            break ;
        case 3 :
            // PNG
            $src_img = imagecreatefrompng( $src_path ) ;
            break ;
        default :
            @copy( $src_path , "$thumbs_dir/$node.$ext" ) ;

            return 2 ;
    }

    list( $new_w , $new_h ) = myalbum_get_thumbnail_wh( $width , $height ) ;

    if( $width <= $new_w && $height <= $new_h ) {
        // only copy when small enough
        copy( $src_path , "$thumbs_dir/$node.$ext" ) ;

        return 2 ;
    }

    if( $bundled_2 ) {
        $dst_img = imagecreate( $new_w , $new_h ) ;
        imagecopyresampled( $dst_img , $src_img , 0 , 0 , 0 , 0 , $new_w , $new_h , $width , $height ) ;
    } else {
        $dst_img = @imagecreatetruecolor( $new_w , $new_h ) ;
        if( ! $dst_img ) {
            $dst_img = imagecreate( $new_w , $new_h ) ;
            imagecopyresized( $dst_img , $src_img , 0 , 0 , 0 , 0 , $new_w , $new_h , $width , $height ) ;
        } else {
            imagecopyresampled( $dst_img , $src_img , 0 , 0 , 0 , 0 , $new_w , $new_h , $width , $height ) ;
        }
    }

    switch( $type ) {
        case 2 :
            // JPEG
            imagejpeg( $dst_img, "$thumbs_dir/$node.$ext" ) ;
            imagedestroy( $dst_img ) ;
            break ;
        case 3 :
            // PNG
            imagepng( $dst_img, "$thumbs_dir/$node.$ext" ) ;
            imagedestroy( $dst_img ) ;
            break ;
    }

    imagedestroy( $src_img ) ;

    return 1 ;
}

// Creating Thumbnail by ImageMagick
function myalbum_create_thumb_by_imagick( $src_path , $node , $ext )
{
    global $myalbum_imagickpath , $thumbs_dir ;

    // Check the path to binaries of imaging packages
    if( trim( $myalbum_imagickpath ) != '' && substr( $myalbum_imagickpath , -1 ) != DIRECTORY_SEPARATOR ) {
        $myalbum_imagickpath .= DIRECTORY_SEPARATOR ;
    }

    if( ! is_readable( $src_path ) ) return 0 ;
    @unlink( "$thumbs_dir/$node.$ext" ) ;
    list( $width , $height , $type ) = getimagesize( $src_path ) ;

    list( $new_w , $new_h ) = myalbum_get_thumbnail_wh( $width , $height ) ;

    if( $width <= $new_w && $height <= $new_h ) {
        // only copy when small enough
        copy( $src_path , "$thumbs_dir/$node.$ext" ) ;

        return 2 ;
    }

    // Make Thumb and check success
    exec( "{$myalbum_imagickpath}convert -geometry {$new_w}x{$new_h} $src_path $thumbs_dir/$node.$ext" ) ;
    if( ! is_readable( "$thumbs_dir/$node.$ext" ) ) {
        // can't exec convert, big thumbs!
        copy( $src_path , "$thumbs_dir/$node.$ext" ) ;

        return 2 ;
    }

    return 1 ;
}

// Creating Thumbnail by NetPBM
function myalbum_create_thumb_by_netpbm( $src_path , $node , $ext )
{
    global $myalbum_netpbmpath , $thumbs_dir ;

    // Check the path to binaries of imaging packages
    if( trim( $myalbum_netpbmpath ) != '' && substr( $myalbum_netpbmpath , -1 ) != DIRECTORY_SEPARATOR ) {
        $myalbum_netpbmpath .= DIRECTORY_SEPARATOR ;
    }

    if( ! is_readable( $src_path ) ) return 0 ;
    @unlink( "$thumbs_dir/$node.$ext" ) ;
    list( $width , $height , $type ) = getimagesize( $src_path ) ;
    switch( $type ) {
        case 1 :
            // GIF
            $pipe0 = "{$myalbum_netpbmpath}giftopnm" ;
            $pipe2 = "{$myalbum_netpbmpath}ppmquant 256 | {$myalbum_netpbmpath}ppmtogif" ;
            break ;
        case 2 :
            // JPEG
            $pipe0 = "{$myalbum_netpbmpath}jpegtopnm" ;
            $pipe2 = "{$myalbum_netpbmpath}pnmtojpeg" ;
            break ;
        case 3 :
            // PNG
            $pipe0 = "{$myalbum_netpbmpath}pngtopnm" ;
            $pipe2 = "{$myalbum_netpbmpath}pnmtopng" ;
            break ;
        default :
            @copy( $src_path , "$thumbs_dir/$node.$ext" ) ;

            return 2 ;
    }

    list( $new_w , $new_h ) = myalbum_get_thumbnail_wh( $width , $height ) ;

    if( $width <= $new_w && $height <= $new_h ) {
        // only copy when small enough
        copy( $src_path , "$thumbs_dir/$node.$ext" ) ;

        return 2 ;
    }

    $pipe1 = "{$myalbum_netpbmpath}pnmscale -xysize $new_w $new_h" ;

    // Make Thumb and check success
    exec( "$pipe0 < $src_path | $pipe1 | $pipe2 > $thumbs_dir/$node.$ext" ) ;
    if( ! is_readable( "$thumbs_dir/$node.$ext" ) ) {
        // can't exec convert, big thumbs!
        copy( $src_path , "$thumbs_dir/$node.$ext" ) ;

        return 2 ;
    }

    return 1 ;
}

// modifyPhoto Wrapper
function myalbum_modify_photo( $src_path , $dst_path )
{
    global $myalbum_imagingpipe , $myalbum_forcegd2 , $myalbum_normal_exts ;

    $ext = substr( strrchr( $dst_path , '.' ) , 1 ) ;

    if( ! in_array( strtolower( $ext ) , $myalbum_normal_exts ) ) {
        rename( $src_path , $dst_path ) ;
    }

    if( $myalbum_imagingpipe == PIPEID_IMAGICK ) {
        myalbum_modify_photo_by_imagick( $src_path , $dst_path ) ;
    } else if( $myalbum_imagingpipe == PIPEID_NETPBM ) {
        myalbum_modify_photo_by_netpbm( $src_path , $dst_path ) ;
    } else {
        if( $myalbum_forcegd2 ) myalbum_modify_photo_by_gd( $src_path , $dst_path ) ;
        else rename( $src_path , $dst_path ) ;
    }
}

// Modifying Original Photo by GD
function myalbum_modify_photo_by_gd( $src_path , $dst_path )
{
    global $myalbum_width , $myalbum_height ;

    if( ! is_readable( $src_path ) ) return 0 ;

    list( $width , $height , $type ) = getimagesize( $src_path ) ;

    switch( $type ) {
        case 1 :
            // GIF
            @rename( $src_path, $dst_path ) ;

            return 2 ;
        case 2 :
            // JPEG
            $src_img = imagecreatefromjpeg( $src_path ) ;
            break ;
        case 3 :
            // PNG
            $src_img = imagecreatefrompng( $src_path ) ;
            break ;
        default :
            @rename( $src_path, $dst_path ) ;

            return 2 ;
    }

    if( $width > $myalbum_width || $height > $myalbum_height ) {
        if( $width / $myalbum_width > $height / $myalbum_height ) {
            $new_w = $myalbum_width ;
            $scale = $width / $new_w ;
            $new_h = intval( round( $height / $scale ) ) ;
        } else {
            $new_h = $myalbum_height ;
            $scale = $height / $new_h ;
            $new_w = intval( round( $width / $scale ) ) ;
        }
        $dst_img = imagecreatetruecolor( $new_w , $new_h ) ;
        imagecopyresampled( $dst_img , $src_img , 0 , 0 , 0 , 0 , $new_w , $new_h , $width , $height ) ;
    }

    if( isset( $_POST['rotate'] ) && function_exists( 'imagerotate' ) ) switch( $_POST['rotate'] ) {
        case 'rot270' :
            if( ! isset( $dst_img ) || ! is_resource( $dst_img ) ) $dst_img = $src_img ;
            // patch for 4.3.1 bug
            $dst_img = imagerotate( $dst_img , 270 , 0 ) ;
            $dst_img = imagerotate( $dst_img , 180 , 0 ) ;
            break ;
        case 'rot180' :
            if( ! isset( $dst_img ) || ! is_resource( $dst_img ) ) $dst_img = $src_img ;
            $dst_img = imagerotate( $dst_img , 180 , 0 ) ;
            break ;
        case 'rot90' :
            if( ! isset( $dst_img ) || ! is_resource( $dst_img ) ) $dst_img = $src_img ;
            $dst_img = imagerotate( $dst_img , 270 , 0 ) ;
            break ;
        default :
        case 'rot0' :
            break ;
    }

    if( isset( $dst_img ) && is_resource( $dst_img ) ) switch( $type ) {
        case 2 :
            // JPEG
            imagejpeg( $dst_img , $dst_path ) ;
            imagedestroy( $dst_img ) ;
            break ;
        case 3 :
            // PNG
            imagepng( $dst_img , $dst_path ) ;
            imagedestroy( $dst_img ) ;
            break ;
    }

    imagedestroy( $src_img ) ;
    if( ! is_readable( $dst_path ) ) {
        // didn't exec convert, rename it.
        @rename( $src_path , $dst_path ) ;

        return 2 ;
    } else {
        @unlink( $src_path ) ;

        return 1 ;
    }
}

// Modifying Original Photo by ImageMagick
function myalbum_modify_photo_by_imagick( $src_path , $dst_path )
{
    global $myalbum_width , $myalbum_height , $myalbum_imagickpath ;

    // Check the path to binaries of imaging packages
    if( trim( $myalbum_imagickpath ) != '' && substr( $myalbum_imagickpath , -1 ) != DIRECTORY_SEPARATOR ) {
        $myalbum_imagickpath .= DIRECTORY_SEPARATOR ;
    }

    if( ! is_readable( $src_path ) ) return 0 ;

    // Make options for imagick
    $option = "" ;
    $image_stats = getimagesize( $src_path ) ;
    if( $image_stats[0] > $myalbum_width || $image_stats[1] > $myalbum_height ) {
        $option .= " -geometry {$myalbum_width}x{$myalbum_height}" ;
    }
    if( isset( $_POST['rotate'] ) ) switch( $_POST['rotate'] ) {
        case 'rot270' :
            $option .= " -rotate 270" ;
            break ;
        case 'rot180' :
            $option .= " -rotate 180" ;
            break ;
        case 'rot90' :
            $option .= " -rotate 90" ;
            break ;
        default :
        case 'rot0' :
            break ;
    }

    // Do Modify and check success
    if( $option != "" ) exec( "{$myalbum_imagickpath}convert $option $src_path $dst_path" ) ;

    if( ! is_readable( $dst_path ) ) {
        // didn't exec convert, rename it.
        @rename( $src_path , $dst_path ) ;

        return 2 ;
    } else {
        @unlink( $src_path ) ;

        return 1 ;
    }
}

// Modifying Original Photo by NetPBM
function myalbum_modify_photo_by_netpbm( $src_path , $dst_path )
{
    global $myalbum_width , $myalbum_height , $myalbum_netpbmpath ;

    // Check the path to binaries of imaging packages
    if( trim( $myalbum_netpbmpath ) != '' && substr( $myalbum_netpbmpath , -1 ) != DIRECTORY_SEPARATOR ) {
        $myalbum_netpbmpath .= DIRECTORY_SEPARATOR ;
    }

    if( ! is_readable( $src_path ) ) return 0 ;

    list( $width , $height , $type ) = getimagesize( $src_path ) ;

    $pipe1 = '' ;
    switch( $type ) {
        case 1 :
            // GIF
            $pipe0 = "{$myalbum_netpbmpath}giftopnm" ;
            $pipe2 = "{$myalbum_netpbmpath}ppmquant 256 | {$myalbum_netpbmpath}ppmtogif" ;
            break ;
        case 2 :
            // JPEG
            $pipe0 = "{$myalbum_netpbmpath}jpegtopnm" ;
            $pipe2 = "{$myalbum_netpbmpath}pnmtojpeg" ;
            break ;
        case 3 :
            // PNG
            $pipe0 = "{$myalbum_netpbmpath}pngtopnm" ;
            $pipe2 = "{$myalbum_netpbmpath}pnmtopng" ;
            break ;
        default :
            @rename( $src_path, $dst_path ) ;

            return 2 ;
    }

    if( $width > $myalbum_width || $height > $myalbum_height ) {
        if( $width / $myalbum_width > $height / $myalbum_height ) {
            $new_w = $myalbum_width ;
            $scale = $width / $new_w ;
            $new_h = intval( round( $height / $scale ) ) ;
        } else {
            $new_h = $myalbum_height ;
            $scale = $height / $new_h ;
            $new_w = intval( round( $width / $scale ) ) ;
        }
        $pipe1 .= "{$myalbum_netpbmpath}pnmscale -xysize $new_w $new_h |" ;
    }

    if( isset( $_POST['rotate'] ) ) switch( $_POST['rotate'] ) {
        case 'rot270' :
            $pipe1 .= "{$myalbum_netpbmpath}pnmflip -r90 |" ;
            break ;
        case 'rot180' :
            $pipe1 .= "{$myalbum_netpbmpath}pnmflip -r180 |" ;
            break ;
        case 'rot90' :
            $pipe1 .= "{$myalbum_netpbmpath}pnmflip -r270 |" ;
            break ;
        default :
        case 'rot0' :
            break ;
    }

    // Do Modify and check success
    if( $pipe1 ) {
        $pipe1 = substr( $pipe1 , 0 , -1 ) ;
        exec( "$pipe0 < $src_path | $pipe1 | $pipe2 > $dst_path" ) ;
    }

    if( ! is_readable( $dst_path ) ) {
        // didn't exec convert, rename it.
        @rename( $src_path , $dst_path ) ;

        return 2 ;
    } else {
        @unlink( $src_path ) ;

        return 1 ;
    }
}

// Clear templorary files
function myalbum_clear_tmp_files( $dir_path , $prefix = 'tmp_' )
{
    // return if directory can't be opened
    if( ! ( $dir = @opendir( $dir_path ) ) ) {
        return 0 ;
    }

    $ret = 0 ;
    $prefix_len = strlen( $prefix ) ;
    while( ( $file = readdir( $dir ) ) !== false ) {
        if( strncmp( $file , $prefix , $prefix_len ) === 0 ) {
            if( @unlink( "$dir_path/$file" ) ) $ret ++ ;
        }
    }
    closedir( $dir ) ;

    return $ret ;
}

//updates rating data in itemtable for a given item
function myalbum_updaterating( $lid )
{
    global $xoopsDB , $table_votedata , $table_photos ;

    $query = "SELECT rating FROM $table_votedata WHERE lid=$lid" ;
    $voteresult = $xoopsDB->query( $query ) ;
    $votesDB = $xoopsDB->getRowsNum( $voteresult ) ;
    $totalrating = 0 ;
    while( list( $rating ) = $xoopsDB->fetchRow( $voteresult ) ) {
        $totalrating += $rating ;
    }
    $finalrating = number_format( $totalrating / $votesDB , 4 ) ;
    $query = "UPDATE $table_photos SET rating=$finalrating, votes=$votesDB WHERE lid = $lid" ;

    $xoopsDB->query( $query ) or die( "Error: DB update rating." ) ;
}

// Returns the number of photos included in a Category
function myalbum_get_photo_small_sum_from_cat( $cid , $whr_append = "" )
{
    global $xoopsDB , $table_photos ;

    if( $whr_append ) $whr_append = "AND ($whr_append)" ;

    $sql = "SELECT COUNT(lid) FROM $table_photos WHERE cid=$cid $whr_append" ;
    $rs = $xoopsDB->query( $sql ) ;
    list( $numrows ) = $xoopsDB->fetchRow( $rs ) ;

    return $numrows ;
}

// Returns the number of whole photos included in a Category
function myalbum_get_photo_total_sum_from_cats( $cids , $whr_append = "" )
{
    global $xoopsDB , $table_photos ;

    if( $whr_append ) $whr_append = "AND ($whr_append)" ;

    $whr = "cid IN (" ;
    foreach( $cids as $cid ) {
        $whr .= "$cid," ;
    }
    $whr = "$whr 0)" ;

    $sql = "SELECT COUNT(lid) FROM $table_photos WHERE $whr $whr_append" ;
    $rs = $xoopsDB->query( $sql ) ;
    list( $numrows ) = $xoopsDB->fetchRow( $rs ) ;

    return $numrows ;
}

// Update a photo
function myalbum_update_photo( $lid , $cid , $title , $desc , $valid = null , $ext = "" , $x = "" , $y = "" )
{
    global $xoopsDB, $xoopsConfig, $xoopsModule;
    global $table_photos , $table_text , $table_cat , $mod_url , $isadmin ;

    if( isset( $valid ) ) {
        $set_status = "status='$valid'," ;

        // Trigger Notification
        if( $valid == 1 ) {
            $notification_handler =& xoops_gethandler( 'notification' ) ;

            // Global Notification
            $notification_handler->triggerEvent( 'global' , 0 , 'new_photo' , array( 'PHOTO_TITLE' => $title , 'PHOTO_URI' => "$mod_url/photo.php?lid=$lid&cid=$cid" ) ) ;

            // Category Notification
            $rs = $xoopsDB->query( "SELECT title FROM $table_cat WHERE cid=$cid" ) ;
            list( $cat_title ) = $xoopsDB->fetchRow( $rs ) ;
            $notification_handler->triggerEvent( 'category' , $cid , 'new_photo' , array( 'PHOTO_TITLE' => $title , 'CATEGORY_TITLE' => $cat_title , 'PHOTO_URI' => "$mod_url/photo.php?lid=$lid&cid=$cid" ) ) ;
        }
    } else {
        $set_status = '' ;
    }

    // not admin can only touch photos status>0
    $whr_status = $isadmin ? '' : 'AND status>0' ;

    if( $ext == "" ) {
        // modify only text
        $xoopsDB->query("UPDATE $table_photos SET cid='$cid',title='".addslashes($title)."', $set_status date=".time()." WHERE lid='$lid' $whr_status") ;
    } else {
        // modify text and image
        $xoopsDB->query("UPDATE $table_photos SET cid='$cid',title='".addslashes($title)."', $set_status date=".time().", ext='$ext',res_x='$x',res_y='$y' WHERE lid='$lid' $whr_status");
    }

    $xoopsDB->query("UPDATE $table_text SET description='".addslashes($desc)."' WHERE lid='$lid'");

    redirect_header( "editphoto.php?lid=$lid" , 0 , _ALBM_DBUPDATED ) ;
}

// Delete photos hit by the $whr clause
function myalbum_delete_photos( $whr )
{
    global $xoopsDB ;
    global $photos_dir , $thumbs_dir , $myalbum_mid ;
    global $table_photos , $table_text , $table_votedata ;

    $prs = $xoopsDB->query("SELECT lid, ext FROM $table_photos WHERE $whr" ) ;
    while( list( $lid , $ext ) = $xoopsDB->fetchRow( $prs ) ) {

        xoops_comment_delete( $myalbum_mid , $lid ) ;
        xoops_notification_deletebyitem( $myalbum_mid , 'photo' , $lid ) ;

//		$xoopsDB->query( "DELETE FROM $table_votedata WHERE lid=$lid" ) or die( "DB error: DELETE votedata table." ) ;
//		$xoopsDB->query( "DELETE FROM $table_text WHERE lid=$lid" ) or die( "DB error: DELETE text table." ) ;
        $xoopsDB->query( "DELETE FROM $table_photos WHERE lid=$lid" ) or die( "DB error: DELETE photo table." ) ;

        @unlink( "$photos_dir/$lid.$ext" ) ;
        @unlink( "$photos_dir/$lid.gif" ) ;
        @unlink( "$thumbs_dir/$lid.$ext" ) ;
        @unlink( "$thumbs_dir/$lid.gif" ) ;
    }
}

// Substitution of opentable()
function myalbum_opentable()
{
    echo "<div style='border: 2px solid #2F5376;padding:8px;width:95%;' class='bg4'>\n" ;
}

// Substitution of closetable()
function myalbum_closetable()
{
    echo "</div>\n" ;
}

// returns extracted string for options from table with xoops tree
function myalbum_get_cat_options( $order = 'title' , $preset = 0 , $prefix = '--' , $none = null , $table_name_cat = null , $table_name_photos = null )
{
    global $xoopsDB ;

    $myts = MyTextSanitizer::getInstance() ;

    if( empty( $table_name_cat ) ) $table_name_cat = $GLOBALS['table_cat'] ;
    if( empty( $table_name_photos ) ) $table_name_photos = $GLOBALS['table_photos'] ;

    $cats[0] = array( 'cid' => 0 , 'pid' => -1 , 'next_key' => -1 , 'depth' => 0 , 'title' => '' , 'num' => 0 ) ;

    $rs = $xoopsDB->query( "SELECT c.title,c.cid,c.pid,COUNT(p.lid) AS num FROM $table_name_cat c LEFT JOIN $table_name_photos p ON c.cid=p.cid GROUP BY c.cid ORDER BY pid ASC,$order DESC" ) ;

    $key = 1 ;
    while( list( $title , $cid , $pid , $num ) = $xoopsDB->fetchRow( $rs ) ) {
        $cats[ $key ] = array( 'cid' => intval( $cid ) , 'pid' => intval( $pid ) , 'next_key' => $key + 1 , 'depth' => 0 , 'title' => $myts->htmlSpecialChars( $title ) , 'num' => intval( $num ) ) ;
        $key ++ ;
    }
    $sizeofcats = $key ;

    $loop_check_for_key = 1024 ;
    for( $key = 1 ; $key < $sizeofcats ; $key ++ ) {
        $cat =& $cats[ $key ] ;
        $target =& $cats[ 0 ] ;
        if( -- $loop_check_for_key < 0 ) $loop_check = -1 ;
        else $loop_check = 4096 ;

        while( 1 ) {
            if( $cat['pid'] == $target['cid'] ) {
                $cat['depth'] = $target['depth'] + 1 ;
                $cat['next_key'] = $target['next_key'] ;
                $target['next_key'] = $key ;
                break ;
            } else if( -- $loop_check < 0 ) {
                $cat['depth'] = 1 ;
                $cat['next_key'] = $target['next_key'] ;
                $target['next_key'] = $key ;
                break ;
            } else if( $target['next_key'] < 0 ) {
                $cat_backup = $cat ;
                array_splice( $cats , $key , 1 ) ;
                array_push( $cats , $cat_backup ) ;
                -- $key ;
                break ;
            }
            $target =& $cats[ $target['next_key'] ] ;
        }
    }

    if( isset( $none ) ) $ret = "<option value=''>$none</option>\n" ;
    else $ret = '' ;
    $cat =& $cats[ 0 ]  ;
    for( $weight = 1 ; $weight < $sizeofcats ; $weight ++ ) {
        $cat =& $cats[ $cat['next_key'] ] ;
        $pref = str_repeat( $prefix , $cat['depth'] - 1 ) ;
        $selected = $preset == $cat['cid'] ? "selected='selected'" : '' ;
        $ret .= "<option value='{$cat['cid']}' $selected>$pref {$cat['title']} ({$cat['num']})</option>\n" ;
    }

    return $ret ;
}
