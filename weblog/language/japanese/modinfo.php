<?php
/**
 * $Id: modinfo.php 11979 2013-08-25 20:45:24Z beckmi $
 * Copyright (c) 2003 by Jeremy N. Cowgar <jc@cowgar.com>
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
/*
 * version j0.90 2003/05/18 JST
 * Traslated into Japanese by wellwine (http://wellwine.zive.net)
 * Copyright (c) 2003 by wellwine
 *
 * version j0.91 2003/05/19 JST
 */
if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( 'WEBLOG_MI_LOADED' ) ) {

define( 'WEBLOG_MI_LOADED' , 1 ) ;

define('_MI_WEBLOG_NAME','うぇブログ');
define('_MI_WEBLOG_DESC','ブログ/ジャーナルシステム');
define('_MI_WEBLOG_SMNAME1','マイブログ');
define('_MI_WEBLOG_SMNAME2','ブログ投稿');
define('_MI_WEBLOG_SMNAME3','アーカイブ');

// submenu name
define('_MI_WEBLOG_DBMANAGER', 'データベース管理');
define('_MI_WEBLOG_CATMANAGER', 'カテゴリ管理');
define('_MI_WEBLOG_PRIVMANAGER', '権限管理(weBLog simple)');
define('_MI_WEBLOG_MYGROUPSADMIN', '権限管理(XOOPS standard)');
define('_MI_WEBLOG_MYBLOCKSADMIN', 'ブロック・アクセス権限');
define('_MI_WEBLOG_TEMPLATE_MANEGER', 'テンプレート・マネージャ');

define('_MI_WEBLOG_NOTIFY','表示中のブログ');
define('_MI_WEBLOG_NOTIFYDSC','このブログに変更があった場合に通知する');
define('_MI_WEBLOG_ENTRY_NOTIFY','表示中のブログエントリ');
define('_MI_WEBLOG_ENTRY_NOTIFYDSC','このブログエントリに変更があった場合に通知する');

define('_MI_WEBLOG_ADD_NOTIFY','新規投稿');
define('_MI_WEBLOG_ADD_NOTIFYCAP','新しいエントリが投稿された場合に通知する');
define('_MI_WEBLOG_ADD_NOTIFYDSC','新しいエントリが投稿されたとき');
define('_MI_WEBLOG_ADD_NOTIFYSBJ','新規ブログ投稿');

define('_MI_WEBLOG_ENTRY_COMMENT','追加されたコメント');
define('_MI_WEBLOG_ENTRY_COMMENTDSC','このエントリに新しいコメントが投稿された場合に通知する');

define('_MI_WEBLOG_RECENT_BNAME1','最新ブログエントリ');
define('_MI_WEBLOG_RECENT_BNAME1_DESC','最新ブログエントリ');
define('_MI_WEBLOG_TOP_WEBLOGS','トップブログエントリ');
define('_MI_WEBLOG_TOP_WEBLOGS_DESC','もっとも読まれているブログエントリ');
define('_MI_WEBLOG_USERS_WEBLOGS','投稿者別エントリ');
define('_MI_WEBLOG_USERS_WEBLOGS_DESC','ユーザー毎の最新ブログエントリ');
define('_MI_WEBLOG_RECENT_TRACKBACKS','最近のトラックバック');
define('_MI_WEBLOG_RECENT_TRACKBACKS_DESC','最近受信したトラックバック一覧');
define('_MI_WEBLOG_RECENT_COMMENTS','最近のweBLogのコメント');
define('_MI_WEBLOG_RECENT_COMMENTS_DESC','weBLogのエントリに最近付けられたコメント');
define('_MI_WEBLOG_LINKS','weBLog投稿者用リンク');
define('_MI_WEBLOG_LINKS_DESC','リンクモジュール統合のリンクリスト');
define('_MI_WEBLOG_RECENT_IMAGES','最近のイメージ');
define('_MI_WEBLOG_RECENT_IMAGES_DESC','weBLogで最近使われたイメージ');
define('_MI_WEBLOG_CATEGORY_LIST', 'カテゴリ一覧');
define('_MI_WEBLOG_CATEGORY_LIST_DESC', '件数つきカテゴリ一覧');
define('_MI_WEBLOG_TB_CENTER', 'トラックバックセンター');
define('_MI_WEBLOG_TB_CENTERDSC', 'トラックバックを特に集めたいエントリの一覧');
// hodaka added archive list block
define('_MI_WEBLOG_ARCHIVE_LIST', 'アーカイブ');
define('_MI_WEBLOG_ARCHIVE_LIST_DESC', '並べ替え可能なアーカイブ');
// hodaka added calendar block
define('_MI_WEBLOG_CALENDAR', 'うぇブログ カレンダー');
define('_MI_WEBLOG_CALENDAR_DESC', '１ヶ月カレンダー');
// hodaka added latest contents block
define('_MI_WEBLOG_LATEST_BLOG_CONTENTS','最新ブログコンテンツ');
define('_MI_WEBLOG_LATEST_BLOG_CONTENTS_DESC','最新ブログコンテンツ');

// Config setting
define('_MI_WEBLOG_NUMPERPAGE','１ページ毎に表示するエントリ数');
define('_MI_WEBLOG_NUMPERPAGEDSC','');
define('_MI_WEBLOG_DATEFORMAT','日付書式');
define('_MI_WEBLOG_DATEFORMATDSC','');
define('_MI_WEBLOG_TIMEFORMAT','時刻書式');
define('_MI_WEBLOG_TIMEFORMATDSC','');
define('_MI_WEBLOG_RECENT_DATEFORMAT','最新エントリブロック日付書式');
define('_MI_WEBLOG_RECENT_DATEFORMATDSC','');
define('_MI_WEBLOG_SHOWAVATAR','各エントリにアバターを表示する');
define('_MI_WEBLOG_SHOWAVATARDSC','');
define('_MI_WEBLOG_ALIGNAVATAR','アバターの整列');
define('_MI_WEBLOG_ALIGNAVATARDSC','');
define('_MI_WEBLOG_MINENTRYSIZE','エントリの最低サイズ');
define('_MI_WEBLOG_MINENTRYSIZEDSC','0を指定するとサイズをチェックしません');
define('_MI_WEBLOG_IMGURL', '画像URL');
define('_MI_WEBLOG_IMGURLDSC', '印刷用ページおよびRSSに表示または記述される画像のURLを設定します。');
define('_MI_WEBLOG_OPDOHTML', 'オプション/HTMLタグを無効') ;
define('_MI_WEBLOG_OPDOHTMLDSC', '「HTMLタグを無効」のオプションをデフォルトでチェックの状態にする場合は"はい"に') ;
define('_MI_WEBLOG_OPDOBR', 'オプション/改行を自動挿入する') ;
define('_MI_WEBLOG_OPDOBRDSC', '「改行を自動挿入する」のオプションをデフォルトでチェックの状態にする場合は"はい"に') ;
define('_MI_WEBLOG_OPPRIVATE', 'オプション/プライベート') ;
define('_MI_WEBLOG_OPPRIVATEDSC', '「プライベート」のオプションをデフォルトでチェックの状態にする場合は"はい"に') ;
define('_MI_WEBLOG_OPUPDATEPING', 'オプション/更新Pingを送る') ;
define('_MI_WEBLOG_OPUPDATEPINGDSC', '「更新Pingを送る」のオプションをデフォルトでチェックの状態にする場合は"はい"に') ;

define('_MI_WEBLOG_UPDATE_READS_WHEN','閲覧数をカウントアップするのは');
define('_MI_WEBLOG_UPDATE_READS_WHENDSC','「詳細が閲覧されたとき」では詳細画面でエントリを閲覧したとき、「ユーザのリストが閲覧されたとき」ではあるユーザだけのエントリをリストアップしたとき、「リストが閲覧されたとき」ではどんな形であれエントリがリストアップされたとき、それぞれ閲覧数が更新されます。');
define('_MI_WEBLOG_UPDATE_READS_WHEN1','詳細が閲覧されたとき');
define('_MI_WEBLOG_UPDATE_READS_WHEN2','ユーザのリストが閲覧されたとき');
define('_MI_WEBLOG_UPDATE_READS_WHEN3','リストが閲覧されたとき');
define('_MI_WEBLOG_GTICKETTIMEOUT','投稿する時の制限時間');
define('_MI_WEBLOG_GTICKETTIMEOUTDSC','投稿ページを開いてから、この分数以内に投稿しないとタイムアウト処理されます。');

define('_MI_WEBLOG_TEMPLATE_ENTRIESDSC','指定されたブログのエントリを表示する');
define('_MI_WEBLOG_TEMPLATE_POSTDSC','新しいブログエントリを投稿する');
define('_MI_WEBLOG_TEMPLATE_DETAILSDSC','ブログエントリの詳細を表示する');
define('_MI_WEBLOG_TEMPLATE_RSSFEEDDSC','最新エントリのRSSフィード');
define('_MI_WEBLOG_TEMPLATE_PRINTDSC','印刷用ページ');
define('_MI_WEBLOG_TEMPLATE_ARCHIVEDSC','月ごとのアーカイブリスト');
define('_MI_WEBLOG_TEMPLATE_IMAGEMANAGERDSC','うぇブログ用のイメージマネージャ');
define('_MI_WEBLOG_CALBLOCKCSS_DSC','カレンダーブロック用のスタイルシート');
// myarchive.php by hodaka
define('_MI_WEBLOG_TEMPLATE_MYARCHIVEDSC', '並べ替え用アーカイブリスト');

define('_MI_WEBLOG_EDITORHEIGHT','編集用テキストエリアの高さ(行)');
define('_MI_WEBLOG_EDITORHEIGHTDSC','編集用テキストエリアの高さを行数で設定します。');
define('_MI_WEBLOG_EDITORWIDTH','編集用テキストエリアの幅(文字)');
define('_MI_WEBLOG_EDITORWIDTHDSC','編集用テキストエリアの幅を字数で設定します。');
define('_MI_WEBLOG_ONLYADMIN',"管理人グループユーザのみが投稿できる");
define('_MI_WEBLOG_ONLYADMINDSC','「いいえ」を設定するとすべての登録ユーザがエントリを投稿することができます。つまり「はい」を設定すると管理人グループのユーザだけが投稿できます。(この設定は権限管理方法に"weBLog"を選んだときに有効です)');
define('_MI_WEBLOG_POST_COUNTUP',"投稿をユーザーの投稿数に反映");
define('_MI_WEBLOG_POST_COUNTUPDSC','エントリを投稿した時にXOOPSユーザーの投稿数としてカウントするかどうかの設定です');
define('_MI_WEBLOG_DISABLE_HTML',"HTMLタグを強制的に使用不可にします");
define('_MI_WEBLOG_DISABLE_HTMLDSC','信頼できないユーザーが登録でき、エントリを投稿できる場合、セキュリティ上「はい」にしてください');
define('_MI_WEBLOG_TB_BLOGNAME',"トラックバック送信時に「ブログ名」として使う文字列");
define('_MI_WEBLOG_TB_BLOGNAMEDSC','{MODULE_NAME}をモジュール名、{USER_NAME}をユーザー名、{SITE_NAME}をサイト名として使えます');

// wellwine for read cookie
define('_MI_WEBLOG_EXPIRATION','閲覧済みの有効期限(単位:秒)');
define('_MI_WEBLOG_EXPIRATIONDSC','ブログエントリごとの閲覧済み有効期限を設定してください。この期間を過ぎて同じエントリを閲覧すると閲覧数が更新されます。');
define('_MI_WEBLOG_RSSSHOW','RSSアイコンを表示する');
define('_MI_WEBLOG_RSSSHOWDSC','');
define('_MI_WEBLOG_RSSMAX','RSSにフィードされるエントリ数');
define('_MI_WEBLOG_RSSMAXDSC','');

define('_MI_WEBLOG_USESEPARATOR','エントリの前後半分け機能の使用');
define('_MI_WEBLOG_USESEPARATORDSC','区切り文字を入れることで、エントリを前半と後半に分けることができます。一覧には前半部分のみが表示され、詳細ページにはエントリの全部が表示されます。');
define('_MI_WEBLOG_USESMEMBERONLY','登録ユーザのみ閲覧可能部分の使用');
define('_MI_WEBLOG_USESMEMBERONLYDSC','区切り文字を入れることで、エントリのそれ以降の部分は登録ユーザのみが読めるようになります。');
define('_MI_WEBLOG_USEIMAGEMANAGER','うぇブログ用イメージマネージャの使用');
define('_MI_WEBLOG_USEIMAGEMANAGERDSC','標準のイメージマネージャとは別に、サムネイル表示など投稿時に画像が扱えます。');
define('_MI_WEBLOG_USEPERMITSYSTEM','各エントリごとのパーミション設定機能の使用');
define('_MI_WEBLOG_USEPERMITSYSTEMDSC','この機能は、エントリの投稿時にどのグループに閲覧権限を与えるかの設定ができます');
define('_MI_WEBLOG_DEFAULT_PERMIT','パーミッションを有効にしている時のデフォルト値');
define('_MI_WEBLOG_DEFAULT_PERMITDSC','投稿時にデフォルトで選ばれているパーミションです。');
define('_MI_WEBLOG_PERMIT_SHOWTITLE','閲覧権限がないユーザにもタイトルだけは表示する') ;
define('_MI_WEBLOG_PERMIT_SHOWTITLEDSC','タイトルのみ表示させることで、どんなエントリがあるか気づかせることができます。ただし、この機能をONにした場合、ユーザは禁止されているエントリのトラックバックとコメントは読むことができるようになります。') ;
//define('_MI_WEBLOG_PERMIT_INGROUP' , '「同じグループ」の定義');
//define('_MI_WEBLOG_PERMIT_INGROUPDSC' , '');
//define('_MI_WEBLOG_PERMIT_OUTGROUP' , '「異なるグループ」の定義');
//define('_MI_WEBLOG_PERMIT_OUTGROUPDSC' , '');
//define('_MI_WEBLOG_PERMIT_G_COMPLETE_S','全ての所属グループが完全に一致（完全一致）');
//define('_MI_WEBLOG_PERMIT_G_PARTIAL_S','1つでも所属グループが一致（部分一致）');
//define('_MI_WEBLOG_PERMIT_G_COMPLETE_D','所属するグループで一致するものが無い（完全排他）');
//define('_MI_WEBLOG_PERMIT_G_PARTIAL_D','1つでも所属グループが異なっている（部分排他）');
define('_MI_WEBLOG_PRIVILEGE_SYSTEM','うぇブログモジュールの権限管理方法');
define('_MI_WEBLOG_PRIVILEGE_SYSTEMDSC','バージョン1.41までのうぇブログ独自のシンプルな権限管理方法と、XOOPS標準の権限管理方法のどちらを使うかを選べます');
define('_MI_WEBLOG_SHOWCATEGORY','カテゴリ一覧の表示');
define('_MI_WEBLOG_SHOWCATEGORY_DSC','index.phpでカテゴリ一覧を見せる場合は、"はい"にしてください。');
define('_MI_WEBLOG_CAT_POSTPERM','投稿時のカテゴリ選択の制限機能');
define('_MI_WEBLOG_CAT_POSTPERM_DSC','有効にした場合、ユーザが投稿できるカテゴリをグループ毎に設定できます。');
define('_MI_WEBLOG_CAT_LISTCOL','カテゴリ一覧の段数');
define('_MI_WEBLOG_CAT_LISTCOL_DSC','カテゴリ一覧を表示する場合、1列に何個表示するか');

// myalbum-P image manager
// Config Items
define( "_MI_ALBM_CFG_PHOTOSPATH" , "画像ファイルの保存先ディレクトリ" ) ;
define( "_MI_ALBM_CFG_DESCPHOTOSPATH" , "XOOPSインストール先からのパスを指定（最初の'/'は必要、最後の'/'は不要）<br />Unixではこのディレクトリへの書込属性をONにして下さい" ) ;
define( "_MI_ALBM_CFG_THUMBSPATH" , "サムネイルファイルの保存先ディレクトリ" ) ;
define( "_MI_ALBM_CFG_DESCTHUMBSPATH" , "「画像ファイルの保存先ディレクトリ」と同じです" ) ;
//define( "_MI_ALBM_CFG_THUMBWIDTH" , "サムネイル画像の幅" ) ;
//define( "_MI_ALBM_CFG_DESCTHUMBWIDTH" , "生成されるサムネイル画像の高さは、幅から自動計算されます" ) ;
define( "_MI_ALBM_CFG_THUMBSIZE" , "サムネイル画像サイズ(pixel)" ) ;
define( "_MI_ALBM_CFG_THUMBRULE" , "サムネイル生成法則" ) ;
define( "_MI_ALBM_CFG_WIDTH" , "最大画像幅" ) ;
define( "_MI_ALBM_CFG_DESCWIDTH" , "画像アップロード時に自動調整されるメイン画像の最大幅。<br />GDモードでTrueColorを扱えない時には単なるサイズ制限" ) ;
define( "_MI_ALBM_CFG_HEIGHT" , "最大画像高" ) ;
define( "_MI_ALBM_CFG_DESCHEIGHT" , "最大幅と同じ意味です" ) ;
define( "_MI_ALBM_CFG_FSIZE" , "最大ファイルサイズ" ) ;
define( "_MI_ALBM_CFG_DESCFSIZE" , "アップロード時のファイルサイズ制限(byte)" ) ;
define( "_MI_ALBM_CFG_MIDDLEPIXEL" , "シングルビューでの最大画像サイズ" ) ;
//define( "_MI_ALBM_CFG_DESCMIDDLEPIXEL" , "幅x高さ で指定します。<br />（例 480x480）" ) ;

define( "_MI_ALBUM_OPT_CALCFROMWIDTH" , "指定数値を幅として、高さを自動計算" ) ;
define( "_MI_ALBUM_OPT_CALCFROMHEIGHT" , "指定数値を高さとして、幅を自動計算" ) ;
define( "_MI_ALBUM_OPT_CALCWHINSIDEBOX" , "幅か高さの大きい方が指定数値になるよう自動計算" ) ;
// hodaka added for trackback spam
define('_MI_WEBLOG_CHECK_TRACKBACK','tracbackのタイトルとブログ名の日本語チェック<br />(ひらがな、カタカナ、漢字含めた必要文字数を選択する)');
define('_MI_WEBLOG_CHECK_TRACKBACKDSC','日本語を必須とする場合に有効になります');
define('_MI_WEBLOG_NO_CHECK_TRACKBACK','チェックしない');
define('_MI_WEBLOG_TRACKBACK_NEEDLETTER1','1文字以上');
define('_MI_WEBLOG_TRACKBACK_NEEDLETTER2','2文字以上');
define('_MI_WEBLOG_TRACKBACK_NEEDLETTER3','3文字以上');

define('_MI_WEBLOG_SPAM_WORDS','trackback撃退ワード');
define('_MI_WEBLOG_SPAM_WORDS_DESC','各語の区切りは|です');

define('_MI_WEBLOG_CHECK_RBL','RBLチェックを行う');
define('_MI_WEBLOG_CHECK_RBL_DSC','');
define('_MI_WEBLOG_RBL_LIST','RBLのurl<br />各語の区切りは|です');
define('_MI_WEBLOG_RBL_LIST_DESC','');
define('_MI_WEBLOG_SEND_TO_ADMIN','spamが来たらメール通知する');
define('_MI_WEBLOG_SEND_TO_ADMIN_DSC','');
define('_MI_WEBLOG_SEND_ADDRESS','spam到着通知先メールアドレス(複数ある場合は|で区切る)<br />特に指定しない場合は管理者宛');
define('_MI_WEBLOG_SEND_ADDRESS_DSC','');
}
?>
