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
define( "_ALBM_GPERM_G_INSERTABLE" , "Post (need approve)" ) ;
define( "_ALBM_GPERM_G_SUPERINSERT" , "Super Post" ) ;
define( "_ALBM_GPERM_G_EDITABLE" , "Edit (need approve)" ) ;
define( "_ALBM_GPERM_G_SUPEREDIT" , "Super Edit" ) ;
define( "_ALBM_GPERM_G_DELETABLE" , "Delete (need approve)" ) ;
define( "_ALBM_GPERM_G_SUPERDELETE" , "Super Delete" ) ;
define( "_ALBM_GPERM_G_TOUCHOTHERS" , "Touch photos posted by others" ) ;
define( "_ALBM_GPERM_G_SUPERTOUCHOTHERS" , "Super Touch others" ) ;
define( "_ALBM_GPERM_G_RATEVIEW" , "View Rate" ) ;
define( "_ALBM_GPERM_G_RATEVOTE" , "Vote" ) ;

// Caption
define( "_BL_ALBM_CAPTION_TOTAL" , "Gesamt:" ) ;
define( "_BL_ALBM_CAPTION_GUESTNAME" , "Gast" ) ;
define( "_BL_ALBM_CAPTION_REFRESH" , "aktualisieren" ) ;
define( "_BL_ALBM_CAPTION_IMAGEXYT" , "Größe(Type)" ) ;
define( "_BL_ALBM_CAPTION_CATEGORY" , "Kategorie" ) ;

}

?>
