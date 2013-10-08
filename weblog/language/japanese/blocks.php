<?php
/*
 * $Id: blocks.php 11979 2013-08-25 20:45:24Z beckmi $
 * Copyright (c) 2003 by Jeremy N. Cowgar <jc@cowgar.com>
 * Copyright (c) 2003 by Hiro SAKAI (http://wellwine.zive.net/)
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
 */

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( 'WEBLOG_MB_LOADED' ) ) {

define( 'WEBLOG_MB_LOADED' , 1 ) ;

define('_MB_WEBLOG_EDIT_NUMBER_OF_ENTRIES','表示するエントリ数');
define('_MB_WEBLOG_EDIT_LINK_TO_LIST', '執筆者名をエントリリストにリンクする(0=いいえ,1=はい)');
define('_MB_WEBLOG_EDIT_MAX_TITLE_LENGTH','表示タイトル長');
define('_MB_WEBLOG_EDIT_MAX_CONTENTS_LENGTH','表示コンテンツ長(0の場合は非表示・-1を指定でエントリの前半部分を表示)');
define('_MB_WEBLOG_EDIT_DATE_FORMAT','日付書式');
define('_MB_WEBLOG_EDIT_USE_AVATARS','アバターを使用する(0=いいえ,1=はい)');
define('_MB_WEBLOG_EDIT_TYPE','タイプ(1=小,2=中,3=大)');

define('_MB_WEBLOG_EDIT_NUMBER_OF_USERS', '表示するユーザーの数');
define('_MB_WEBLOG_EDIT_NUMBER_OF_TRACKBACKS', '表示するトラックバックの数');
define('_MB_WEBLOG_EDIT_NUMBER_OF_COMMENTS', '表示するコメントの数');
define('_MB_WEBLOG_EDIT_ORDER_BY', '表示順(0=最終投稿日,1=合計の閲覧数)');
define('_MB_WEBLOG_EDIT_LINKS_MODULE', '参照するリンクモジュール<br />(mylinksとweblinksが使えます)');
define('_MB_WEBLOG_EDIT_LINKS_NUMBER', '表示するリンクの数');
define('_MB_WEBLOG_EDIT_LINKS_ONLYPOST', '投稿者がブログを投稿する時のみ表示(0=いいえ , 1=はい)');
define('_MB_WEBLOG_EDIT_LINKS_SHOWDSC', 'リンクの説明を表示(0=いいえ , 1=はい)');
define('_MB_WEBLOG_EDIT_CAT_ORDERBY', '並び順');	// added for category list by hodaka
define('_MB_WEBLOG_EDIT_CAT_TITLE', 'カテゴリ名');
define('_MB_WEBLOG_EDIT_CAT_ID', 'カテゴリID');
define('_MB_WEBLOG_EDIT_SHOW_FORBIDDEN_PICTURE', 'ユーザにエントリの閲覧が許可されていない場合でも画像を表示 (0=表示しない , 1=表示する )');
define('_MB_WEBLOG_EDIT_SHOW_CONTENTS', 'コンテンツを表示する (0=表示しない , 1=表示する )');
define('_MB_WEBLOG_EDIT_TBCENTER_ENTRIES', 'トラックバックセンターに載せるエントリ数');
define('_MB_WEBLOG_EDIT_TBCENTER_CONTENTS_NUM', 'トラックバックセンターに表示するコンテンツの文字数');
define('_MB_WEBLOG_EDIT_TBCENTER_CATEGORY', 'トラックバックセンターとして扱うカテゴリ名');
// added for archive list by hodaka
define('_MB_WEBLOG_EDIT_ARCHIVE_NUMBER_PER_PAGE', '表示する年月の数');
define('_MB_WEBLOG_LANG_SORT_ARCHIVE', '過去ログの並べ替え');
// added for calendar by hodaka
define('_MB_WEBLOG_LANG_PREVMONTH', '&laquo;');
define('_MB_WEBLOG_LANG_NEXTMONTH', '&raquo;');
define('_MB_WEBLOG_LANG_PREVYEAR', '&laquo;');
define('_MB_WEBLOG_LANG_NEXTYEAR', '&raquo;');
define('_MB_WEBLOG_LANG_PREVMONTH_TITLE', '前の月');
define('_MB_WEBLOG_LANG_NEXTMONTH_TITLE', '次の月');
define('_MB_WEBLOG_LANG_PREVYEAR_TITLE', '前の年');
define('_MB_WEBLOG_LANG_NEXTYEAR_TITLE', '次の年');
define('_MB_WEBLOG_LANG_THIS_MONTH_TITLE', 'この月のアーカイブ');
define('_MB_WEBLOG_LANG_SUNDAY', '日');
define('_MB_WEBLOG_LANG_MONDAY', '月');
define('_MB_WEBLOG_LANG_TUESDAY', '火');
define('_MB_WEBLOG_LANG_WEDNESDAY', '水');
define('_MB_WEBLOG_LANG_THURSDAY', '木');
define('_MB_WEBLOG_LANG_FRIDAY', '金');
define('_MB_WEBLOG_LANG_SATURDAY', '土');

define('_MB_WEBLOG_LANG_TITLE', 'タイトル');
define('_MB_WEBLOG_LANG_ENTRIES', '最近のエントリ');
define('_MB_WEBLOG_LANG_AUTHOR', '執筆者');
define('_MB_WEBLOG_LANG_COMMENTS', 'コメント数');
define('_MB_WEBLOG_LANG_POSTED', '投稿日');
define('_MB_WEBLOG_LANG_READS', '閲覧数');
define('_MB_WEBLOG_LANG_MOREWEBLOGS', 'うぇブログへ');
define('_MB_WEBLOG_LANG_TRACKBACKS', 'トラックバック数'); //eng
define('_MB_WEBLOG_LANG_TB_TITLE', 'タイトル');
define('_MB_WEBLOG_LANG_TB_WEBLOGTITLE', 'トラックバックエントリ');
define('_MB_WEBLOG_LANG_TB_BLOGNAME', 'ブログ名');
define('_MB_WEBLOG_LANG_TB_POSTED', '日付');
define('_MB_WEBLOG_LANG_COM_TITLE', 'コメント');
define('_MB_WEBLOG_LANG_COM_UNAME', 'ユーザ');
define('_MB_WEBLOG_LANG_COM_WEBLOGTITLE', 'コメントのエントリ');
define('_MB_WEBLOG_LANG_COM_POSTED', '日付');
define('_MB_WEBLOG_LANG_LINKS_FOR','%sさんのリンク');
define('_MB_WEBLOG_LANG_LINKS_FOR_EVERYONE','リンク集');
define('_MB_WEBLOG_LANG_DENOTE_PERMIT','<span style=\'color:#FF0000;font-size:10px\'>*</span>印のエントリは閲覧不可');

define('_MB_WEBLOG_USERS_SORT_READS', '累計閲覧数');
define('_MB_WEBLOG_USERS_SORT_UPDATE', '最終更新');

}
?>