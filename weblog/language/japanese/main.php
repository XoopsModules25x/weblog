<?php
/**
 * $Id: main.php 11979 2013-08-25 20:45:24Z beckmi $
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

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( 'WEBLOG_BL_LOADED' ) ) {

define( 'WEBLOG_BL_LOADED' , 1 ) ;

// Basic's
define('_BL_DISABLEHTML', _DISABLEHTML);
define('_BL_WRAPLINES', '改行を自動的に改行タグにする');
define('_BL_TITLE', 'タイトル');
define('_BL_CATEGORY', 'カテゴリ');
define('_BL_CATEGORIES', 'カテゴリ');
define('_BL_MAIN', 'メイン');
define('_BL_CONTENTS','エントリ');
define('_BL_POST','ブログ投稿');
define('_BL_PREVIEW',_PREVIEW);
define('_BL_PRIVATE','プライベート');
define('_BL_OPTIONS','オプション');
define('_BL_COMMENTS','コメント');
define('_BL_READ_USERS_BLOG','%sさんのブログを読む');
define('_BL_EDIT',_EDIT);
define('_BL_READ','読む');
define('_BL_SHOW','表示');
define('_BL_DELETE',_DELETE);
define('_BL_BLOG','ブログ');
define('_BL_BLOGGER','ユーザー');
define('_BL_GATHERING','あなたのブログエントリを集めています');
define('_BL_GATHERING_SORRY','申し訳ありません、マイブログは登録ユーザのみ使用することができます');
define('_BL_PRIVATE_NOTEXIST_SORRY', '申し訳ありません、このエントリはプライベートまたは存在しません。');
define('_BL_ENTRY_POSTED','ブログエントリが投稿されました');
define('_BL_MOST_RECENT','最新エントリ');
define('_BL_ENTRIES_FOR','%sさんのエントリ');
define('_BL_ENTRY_FOR','%sさんのエントリ');
define('_BL_NUMBER_OF_READS','閲覧数');
define('_BL_NUMBER_OF_TRACKBACKS','トラックバック数');
define('_BL_CONFIRM_DELETE','ブログエントリ(%s)を削除してもいいですか？');
define('_BL_BLOG_DELETED','選択されたブログエントリは削除されました');
define('_BL_BLOG_NOT_DELETED','選択されたブログエントリは削除されませんでした。アクセス権限を確認してください');
define('_BL_WHOS_BLOG','%sさんのブログ');
define('_BL_ANON_CANNOT_POST_SORRY','申し訳ありません。投稿権限のあるユーザのみ投稿することができます。<br />投稿を希望する場合はサイト管理者に問い合わせてください');
define('_BL_CANNOT_READ_SORRY','申し訳ありませんが、閲覧権限が与えられていないので、読むことができません。<br />閲覧を希望する場合は、登録するかサイト管理者に問い合わせてください。');
define('_BL_CANNOT_EDIT','投稿されたエントリは投稿者か管理者でないと編集できません。');
define('_BL_DELETE_BUTTON',_DELETE);
define('_BL_PREVIEW_BUTTON',_PREVIEW);
define('_BL_POST_BUTTON','投稿');
define('_BL_POST_TOO_SMALL','エントリは最低%d文字が必要です。あなたのエントリは%d文字です。内容を追加してください');
define('_BL_POST_TIMEOUT','エントリを<b>%d</b>分以内に投稿してください。');
define('_BL_POST_MUST_BE','エントリは最低<b>%d</b>文字必要です。');
define('_BL_CONTINUE_EDITING','編集を続ける');
define('_BL_RSS_RECENT', '最新エントリ配信');
define('_BL_RSS_RECENT_FOR', '%sさんのエントリ配信');
define('_BL_UPDATEPING','更新Pingを送る');
define('_BL_SPECIFY_TIME' , '投稿日を下記の時間で指定する') ;
define('_BL_SPECIFY_TIME_DSC' , '&nbsp;&nbsp;&nbsp; 投稿日時') ;
define('_BL_TRACKBACK','トラックバックURL');
define('_BL_TRACKBACKS','トラックバック数');
define('_BL_TRACKBACK_DSC','トラックバックURLを複数記入する場合は改行で区切ってください。');
define('_BL_TRACKBACK_ADMIN','受信したトラックバック');
define('_BL_PERMISSION','閲覧可能グループ');
define('_BL_PERMISSION_CAPTION','このエントリを閲覧できるグループを設定できます。');
define('_BL_TRACKBACK_DELETE',_DELETE);
define('_BL_GROUP_PERMIT', "申し訳ありませんが、このエントリは特定グループのみに閲覧権限が与えられています。");
define('_BL_SELECT_ALL', 'すべて選択');
define('_BL_CAUTION_NOHTML', '<b>ご注意</b>');
define('_BL_FORBIDDEN_HTML_TAGS', '<b>全てのHTMLタグは使えません</b>(BBタグを使ってください)');

// %s is your site name
define('_BL_INTARTICLE','%sで見つけた興味深いブログ');
define('_BL_INTARTFOUND','以下は%sで見つけた非常に興味深いブログです：');

define('_BL_PRINTERPAGE','印刷用ページ');
define('_BL_SENDSTORY','友達に送る');

define('_BL_POSTED', '投稿日時');
define('_BL_AUTHOR', '執筆者');
// %s is your site name
define('_BL_COMESFROM', '%sにて更に多くのブログを読むことができます。');
define('_BL_PARMALINK', 'このブログのＵＲＬ');

// %s is count
define('_BL_THEREAREINTOTAL', '計%s件のエントリがあります。');
define('_BL_NOARCHIVEDESC', 'エントリはありません。');
define('_BL_ARCHIVE', '過去のアーカイブ');
define('_BL_ARCHIVE_FOR', '%sさんの過去のブログ');
define('_BL_READS', '閲覧数');
define('_BL_NEXT', '次のエントリ');
define('_BL_PREV', '前のエントリ');

// division separator
define('_BL_ENTRY_SEPARATOR' , '前後半の区切り');
define('_BL_ENTRY_SEPARATOR_CAPTION' , 'この区切り文字以降はエントリの詳細表示で閲覧できるようになります。');
define('_BL_ENTRY_SEPARATOR_VALUE' , '区切り文字を入れるには、このボタンをクリック');
define('_BL_ENTRY_SEPARATOR_NEXT' , '...続きを読む');

// member only delimeter
define('_BL_MEMBER_ONLY_READ' , 'メンバーのみ閲覧');
define('_BL_MEMBER_ONLY_READ_CAPTION' , 'この区切り文字以降はメンバーでないと読めないようになります。');
define('_BL_MEMBER_ONLY_READ_VALUE' , '区切り文字を入れるには、このボタンをクリック');
define('_BL_MEMBER_ONLY_READ_MORE' , 'このエントリを全て読むにはメンバー登録が必要です。');

// image manager tray
define('_BL_WEBLOG_IMAGEMANAGER' , 'イメージマネージャ');
define('_BL_WEBLOG_IMAGEMANAGER_CAUTION' , 'うぇブログ用イメージマネージャはPHPのGDモジュールがバージョン2以上で使用できます。');

// %s is trackback
define('_BL_TRACKBACK_ANOUNCE' , 'このエントリのトラックバックURL');
define('_BL_TRACKBACK_TRANSMIT' , 'このエントリは以下のURLにトラックバックしています。');
define('_BL_TRACKBACK_RECIEVED' , 'このエントリが受けたトラックバック');

// %s is uname
define('_BL_TRACKBACKS_FOR',' (%sさんのエントリへ)');
define('_BL_COMMENTS_FOR','(%sさんのエントリに)');

// use weBLog imagemanager :: myalbum-P
define("_BL_ALBM_PHOTOUPLOAD","画像アップロード");
define("_BL_ALBM_MAXPIXEL","サイズ上限");
define("_BL_ALBM_MAXSIZE","サイズ上限(byte)");
define("_BL_ALBM_PHOTOTITLE","タイトル");
define("_BL_ALBM_PHOTOCAT","カテゴリ");
define("_BL_ALBM_SELECTFILE","画像選択");

define("_BL_ALBM_PHOTODEL","画像を削除しますか?");
define("_BL_ALBM_DELETINGPHOTO","削除中");
define("_BL_ALBM_RECEIVED","画像を登録しました。ご投稿有難うございます。");
define("_BL_ALBM_MUSTREGFIRST","申し訳ありませんがアクセス権限がありません。<br>登録するか、ログイン後にお願いします。");
define("_BL_ALBM_NOIMAGESPECIFIED","画像未選択：アップロードすべき画像ファイルを選択して下さい。");
define("_BL_ALBM_FILEERROR","画像アップロードに失敗：画像ファイルが見つからないか容量制限を越えてます。");
define("_BL_ALBM_FILEREADERROR","画像読込失敗：なんらかの理由でアップロードされた画像ファイルを読み出せません。");

}
