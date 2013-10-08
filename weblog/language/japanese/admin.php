<?php
/**
 * $Id: admin.php 11979 2013-08-25 20:45:24Z beckmi $
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
 * Foundation, Inc., 59 Temple Place
 */

if( defined( 'FOR_XOOPS_LANG_CHECKER' ) || ! defined( 'WEBLOG_AM_LOADED' ) ) {
global $xoopsModule ;
define( 'WEBLOG_AM_LOADED' , 1 ) ;

define('_AM_WEBLOG_CONFIG', $xoopsModule->name().'設定');
define('_AM_WEBLOG_PREFERENCES', _PREFERENCES);
define('_AM_WEBLOG_PREFERENCESDSC', '基本的な動作を設定します');
define('_AM_WEBLOG_GO', _GO);
define('_AM_WEBLOG_CANCEL', _CANCEL);
define('_AM_WEBLOG_DELETE', _DELETE);
define('_AM_WEBLOG_MODIFY', '変更する');
define('_AM_WEBLOG_TITLE', 'Title');

define('_AM_WEBLOG_DBMANAGER', 'データベース管理');
define('_AM_WEBLOG_DBMANAGERDSC', 'モジュールのバージョンアップの際にデータベースを操作します');
define('_AM_WEBLOG_TABLE', 'テーブル');
define('_AM_WEBLOG_SYNCCOMMENTS', 'コメント数の同期');
define('_AM_WEBLOG_SYNCCOMMENTSDSC', 'もし各エントリのコメント数が誤っていると思われる場合は、データの再集計を行うことができます。<br />v1.02より以前のバージョンではコメント数を正しく扱っていなかったためであることがあります。');
define('_AM_WEBLOG_CHECKTABLE', 'テーブル構成確認');
define('_AM_WEBLOG_CHECKTABLEDSC', '新しいバージョンでは新規のテーブルやカラムが必要な場合があります。<br />バージョンとテーブルの整合性を確認、修正することができます。');
define('_AM_WEBLOG_CREATETABLE', '\'%s\'テーブルを作成する');
define('_AM_WEBLOG_CREATETABLEDSC', '\'%s\'という名のテーブルを新規に作成します。');

define('_AM_WEBLOG_ADD', '\'%s\'カラムが見つかりません');
define('_AM_WEBLOG_ADDDSC', '\'<b>%s</b>\'カラムがデータベースのテーブルにありません。このカラムは現在ご使用のバージョンに必要なカラムです。<br />ボタンを押してこのカラムを追加してください。<br />データベースのバックアップをお勧めします。');
define('_AM_WEBLOG_NOADD', '\'%s\'テーブルは準備完了です！');
define('_AM_WEBLOG_NOADDDSC', '\'%s\'テーブルは現在ご使用のバージョンでお使いになれる状態です。');
define('_AM_WEBLOG_DBUPDATED', 'データベースを更新しました');
define('_AM_WEBLOG_UNSUPPORTED', 'サポートしていないリクエストです。処理は行われませんでした');
define('_AM_WEBLOG_TABLEADDED', 'テーブルが新たに作成されました');
define('_AM_WEBLOG_TABLENOTADDED', 'テーブルが作成できませんでした: %s');
define('_AM_WEBLOG_COLADDED', 'カラムが新たに作成されました');
define('_AM_WEBLOG_COLNOTADDED', 'カラムを追加することができませんでした: %s');

define('_AM_WEBLOG_CATMANAGER', 'カテゴリ');
define('_AM_WEBLOG_CATMANAGERDSC', 'カテゴリの追加/修正/削除');
define('_AM_WEBLOG_ADDCAT', 'カテゴリの追加');
define('_AM_WEBLOG_ADDMAINCAT', 'メインカテゴリの追加');
define('_AM_WEBLOG_ADDSUBCAT', 'サブカテゴリの追加');
define('_AM_WEBLOG_CAT', 'カテゴリ');
define('_AM_WEBLOG_IMGURL', '画像URL');
define('_AM_WEBLOG_ERRORTITLE', 'タイトルを入力してください');
define('_AM_WEBLOG_NEWCATADDED', 'カテゴリが追加されました');
define('_AM_WEBLOG_CATNOTADDED', 'カテゴリを追加することができませんでした');
define('_AM_WEBLOG_CATMODED', 'カテゴリが修正されました');
define('_AM_WEBLOG_CATNOTMODED', 'カテゴリを修正することができませんでした');
define('_AM_WEBLOG_MODCAT', 'カテゴリの修正');
define('_AM_WEBLOG_PCAT', '親カテゴリ');
define('_AM_WEBLOG_CHOSECAT', '選択したカテゴリ');
define('_AM_WEBLOG_DELCONFIRM', '\'%s\' カテゴリとそのサブカテゴリを削除してもよろしいですか？<br />これらのカテゴリに属するすべてのエントリも削除されます。');
define('_AM_WEBLOG_CATDELETED', 'カテゴリが削除されました');
define('_AM_WEBLOG_CAT_GPERM', 'このカテゴリに投稿を許可するグループ');
define('_AM_WEBLOG_CAT_OPERATE', '操作');
define('_AM_WEBLOG_CAT_SETALL', '全てのグループ毎のカテゴリ投稿権限を一度に設定する');


define('_AM_WEBLOG_MYBLOCKSADMIN', 'ブロック・アクセス権限');
define('_AM_WEBLOG_MYBLOCKSADMINDSC', 'ブロックごと・グループごとの設定を行います。');
define('_AM_WEBLOG_TEMPLATE_MANEGER', 'テンプレート');
define('_AM_WEBLOG_TEMPLATE_MANEGERDSC', 'テンプレート・マネージャへのショートカット');

define('_AM_WEBLOG_PRIVMANAGER_WEBLOG', '権限管理(従来のweBLogシステム)');
define('_AM_WEBLOG_PRIVMANAGER_WEBLOG_DSC', '投稿できる条件を設定します');
define('_AM_WEBLOG_PRIVMANAGER_WEBLOG_CAUTION', 'ここでの設定は、一般設定で権限管理システムに"weBLog simple system"を選んだ場合に有効です。');
define('_AM_WEBLOG_PRIVMANAGER_XOOPS', '権限管理(XOOPSの標準システム)');
define('_AM_WEBLOG_PRIVMANAGER_XOOPS_DSC', '投稿・閲覧できる条件を設定します');
define('_AM_WEBLOG_PRIVMANAGER_XOOPS_CAUTION', 'ここでの設定は、一般設定で権限管理システムに"XOOPS standard system"を選んだ場合に有効です。');
define('_AM_WEBLOG_ADDPRIV', _ADD);
define('_AM_WEBLOG_DELETEPRIV', _DELETE);
define('_AM_WEBLOG_NONPRIV', '投稿権限なし');
define('_AM_WEBLOG_PRIV', '投稿権限あり');

define('_AM_WEBLOG_GROUPPERM_GLOBAL' , 'グループ毎の権限管理' );
define('_AM_WEBLOG_GROUPPERM_GLOBALDESC' , 'グループ毎の権限管理を行います。<br />ただし、ゲストに投稿・編集・削除の権限を与えても機能しません。' );
define('_AM_WEBLOG_GPERMUPDATED' , 'グループ毎の権限をアップデートしました。' );
define('_AM_WEBLOG_PRIV_EDIT' , '投稿・編集・削除権限') ;
define('_AM_WEBLOG_PRIV_READINDEX' , '一覧閲覧権限(index/rss/rdf)') ;
define('_AM_WEBLOG_PRIV_READDETAIL' , '詳細閲覧権限(details.php)') ;
//1.47

//directories
define('_AM_WEBLOG_AVAILABLE2',"<span style='color : green;'>Available. </span>");
define('_AM_WEBLOG_NOTAVAILABLE2',"<span style='color : red;'>is not available. </span>");
define('_AM_WEBLOG_NOTWRITABLE2',"<span style='color : red;'>".' should have permission ( %1$d ), but it has ( %2$d )'. '</span>');
define('_AM_WEBLOG_CREATETHEDIR2','Create it');
define('_AM_WEBLOG_SETMPERM2','Set the permission');

define('_AM_WEBLOG_DIRCREATED2','The directory has been created');
define('_AM_WEBLOG_DIRNOTCREATED2','The directory can not be created');
define('_AM_WEBLOG_PERMSET2','The permission has been set');
define('_AM_WEBLOG_PERMNOTSET2','The permission can not be set');


}
?>
