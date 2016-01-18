<?php

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( 'MYALBUM_CNST_LOADED' ) ) {

define( 'MYALBUM_CNST_LOADED' , 1 ) ;

// System Constants (Don't Edit)
define( "GPERM_INSERTABLE" , 1 ) ;
define( "GPERM_SUPERINSERT" , 2 ) ;
define( "GPERM_EDITABLE" , 4 ) ;
define( "GPERM_SUPEREDIT" , 8 ) ;
define( "GPERM_DELETABLE" , 16 ) ;
define( "GPERM_SUPERDELETE" , 32 ) ;
define( "GPERM_TOUCHOTHERS" , 64 ) ;
define( "GPERM_SUPERTOUCHOTHERS" , 128 ) ;
define( "GPERM_RATEVIEW" , 256 ) ;
define( "GPERM_RATEVOTE" , 512 ) ;

// Global Group Permission
define( "_ALBM_GPERM_G_INSERTABLE" , "投稿可（要承認）" ) ;
define( "_ALBM_GPERM_G_SUPERINSERT" , "投稿可（承認不要）" ) ;
define( "_ALBM_GPERM_G_EDITABLE" , "編集可（要承認）" ) ;
define( "_ALBM_GPERM_G_SUPEREDIT" , "編集可（承認不要）" ) ;
define( "_ALBM_GPERM_G_DELETABLE" , "削除可（要承認）" ) ;
define( "_ALBM_GPERM_G_SUPERDELETE" , "削除可（承認不要）" ) ;
define( "_ALBM_GPERM_G_TOUCHOTHERS" , "他ユーザのイメージを編集・削除可（要承認）" ) ;
define( "_ALBM_GPERM_G_SUPERTOUCHOTHERS" , "他ユーザのイメージを編集・削除可（承認不要）" ) ;
define( "_ALBM_GPERM_G_RATEVIEW" , "投票閲覧可" ) ;
define( "_ALBM_GPERM_G_RATEVOTE" , "投票可" ) ;

// Caption
define( "_BL_ALBM_CAPTION_TOTAL" , "Total:" ) ;
define( "_BL_ALBM_CAPTION_GUESTNAME" , "ゲスト" ) ;
define( "_BL_ALBM_CAPTION_REFRESH" , "更新" ) ;
define( "_BL_ALBM_CAPTION_IMAGEXYT" , "サイズ" ) ;
define( "_BL_ALBM_CAPTION_CATEGORY" , "カテゴリー" ) ;

    // encoding conversion if possible and needed
    function myalbum_callback_after_stripslashes_local( $text )
    {
        if( function_exists( 'mb_convert_encoding' ) && mb_internal_encoding() !=  mb_http_output() ) {
            return mb_convert_encoding( $text , mb_internal_encoding() , mb_detect_order() ) ;
        } else {
            return $text ;
        }
    }

}
