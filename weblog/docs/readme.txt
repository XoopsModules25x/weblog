README FIRST
-----------------------

/**************************************************************
*  If you find bugs or you have requests, please tell me.
*  Fixed Language package is most wellcome.
*  webmaster@tohokuaiki.jp
*  http://xoopsdevelopment.tohokuaiki.jp
**************************************************************/

Main new function is trackback system which many Blog system are already equipped .
Sending update ping function is also able.

And .....
- You can syndicate weBLogs through not only RSS2.0 but also RSS1.0(RDF).
- You can set default option setting when you post weblog .
 (Options are invalidate HTML tag/Private mode/Send update Ping.)
- weBlog module's peculiar style sheet which is located in /weblog/styles.css is automatically included .
- Some bugs fix.


2 cautions I have to mention about language files.
( english / espanol / french / german / italian / japanese / spanish / x_brazilian_portuguese)
There is only 2 languages english and japanese , since I have no another linguistic knowledges.
Ver.1.3 seems including many languages. So, if you localize them , please teach me.
I'll include them when I release (and after release).

For sending update ping , /weblog/language/******/commonping_servers.inc.php file is required.
This file is only common update ping servers terminated by new line.
As I don't know common update ping servers in the sphere of French , Spanish.... etc ,
/weblog/language/*******/commonping_servers.inc.php is blank.


How to Update (1.3 to 1.4)

0. At first expand tarball file.
1. Change your old weblog directory to expanded one.
2. At SYSTEM ADMIN->Modules in Administration menu , you can update weBLog module.
3. At SYSTEM ADMIN->Templates , you can update weBlog templates set.
 ( you have to update only weblog_entries.html and weblog_details.html)
4. At DATABASE in weBLog module admin menu, you can check database tables structure.Click Go!.
5. You will find these messages "Column trackbacks not found ......" and "Create table 'weblog_trackback'" . Please create a column and a table.




How to duplicate and change install directory name.

This module can duplicate. But install directory name is required weblog+number. For example weblog / weblog0 / weblog1 .....
number must be unique.
I prepare for weblog0 and weblog1 . If you want to weblog 1 as second blog module , only you have to do is
copy weblog directory and rename weblog1 and install as you do usually.

 If you want to use weblog3 or weblog 4 and so on, You have to change some of files. See below.
1. copy all files and rename as weblog3.
2. rename like this
/weblog3/sql/mysql1.sql -> /weblog/sql/mysql3.sql
/weblog3/templates/weblog1_archive.html -> /weblog3/templates/weblog3_archive.html
/weblog3/templates/weblog1_calblock.css.html -> /weblog3/templates/weblog3_calblock.css.html
/weblog3/templates/weblog1_details.html -> /weblog3/templates/weblog3_details.html
/weblog3/templates/weblog1_entries.html -> /weblog3/templates/weblog3_entries.html
/weblog3/templates/weblog1_imagemanager.html -> /weblog3/templates/weblog3_imagemanager.html
/weblog3/templates/weblog1_post.html -> /weblog3/templates/weblog3_post.html
/weblog3/templates/weblog1_print.html -> /weblog3/templates/weblog3_print.html
/weblog3/templates/weblog1_rss.html -> /weblog3/templates/weblog3_rss.html
/weblog3/templates/blocks/weblog1_block_archive.html -> /weblog3/templates/blocks/weblog3_block_archive.html
/weblog3/templates/blocks/weblog1_block_calendar.html -> /weblog3/templates/blocks/weblog3_block_calendar.html
/weblog3/templates/blocks/weblog1_block_category_list.html -> /weblog3/templates/blocks/weblog3_block_category_list.html
/weblog3/templates/blocks/weblog1_block_links.html -> /weblog3/templates/blocks/weblog3_block_links.html
/weblog3/templates/blocks/weblog1_block_recent_com.html -> /weblog3/templates/blocks/weblog3_block_recent_com.html
/weblog3/templates/blocks/weblog1_block_recent_image.html -> /weblog3/templates/blocks/weblog3_block_recent_image.html
/weblog3/templates/blocks/weblog1_block_recent_tb.html -> /weblog3/templates/blocks/weblog3_block_recent_tb.html
/weblog3/templates/blocks/weblog1_block_recent.html -> /weblog3/templates/blocks/weblog3_block_recent.html
/weblog3/templates/blocks/weblog1_block_top_weblogs.html -> /weblog3/templates/blocks/weblog3_block_top_weblogs.html
/weblog3/templates/blocks/weblog1_block_users_weblogs.html -> /weblog3/templates/blocks/weblog3_block_users_weblogs.html
3. change mysql3.sql file. All 'weblog1' to 'weblog3'.
4. install.

And you can change weblog directory name with some efforts.
if you want to install as "myblog", all you have to do is change mysql.sql.
please change string "weblog" to myblog.

Combination these method , you can create hisblog1 , herblog3 ..etc.





CHANGES
version 1.41 -- 04/08/2005
    1. Include ticket system when blogger post , edit or delete entries. ( Special thanks to GIJOE )
    2. 4 Blocks add . Link module integrated , Recent entries group by Bloggers , Recent comment of weBLog , Recent  Trackback
    3. Function which divide Entry first half and latter half
    4. Function which makes member only readable part
    5. Some bugs fix.
    6. Test mode --- image manager added. It is based on GIJOE's myalbum-P Xoops core imagemanager integration fucntion.
         If you want to use this function , you have to create new DB table "weblogmyalbum_photos" via weBLog module admin menu "Database".

version 1.42 -- 05/06/2005
    At First :: You have to check your database via Admin menu -> weblog -> database . New column "permission_group" exists in 'weblog' table.
    Mainly add privilege functions
    1. Entry permission to every group  : You can add permission which make some groups not to read the entry.You can also set show only title mode.
    2. Category permission to bloggers : You can adjust which group is able to post a entry to the category or not.
            Attention :: If a group is forbidden to post a category , they also can't post its child ones.
    3. Change category navigation.
    4. XOOPS standard privilege system is added. You can choose which privilege system to use XOOPS standard system or weBLog simple one.
    5. Module duplicate function . You can install 2 or more weBLog module. Copy weblog directory to weblog0 , weblog1 ....
        ( Sorry,  I only prepare only weblog0/weblog1.  )
    6. Category list block is added. (thx hodaka)
    -- 06/18/2005
    7. add mini-calendar block.(thx hodaka)
    8. add archive block.(thx hodaka)
    9. renewal archive page.(thx hodaka)
    10.Recent entries block and Recent users entries block can display contents of entry.
    11.sort next|prev navigation in details.php from blog_id to created time.
    -- 08/15/2005
    12.consider user's timezone.
    13.able to set "HTML FORBIDDEN MODE"
    14.able to set "BLOG NAME" when transmit trackback to another blog.
    15.able to set whether count up XOOPS user posts or not when user post entry.
    16. PHP5 ready
    17.some bugs fix.
	18.Add Spanish language pack.(thanks lunallena)
    Thanks for all users who tell me bugs or requests at http://xoopsdevelopment.tohokuaiki.jp

----------- Japanese UTF-8 ------------
 1.3からの新機能は、

・トラックバック機能
トラックバックは、エントリの詳細表示時に送受信のどちらともサマリが表示されます。投稿後やトラックバック受信後の削除は、エントリの編集画面から行えます。
送信したトラックバックを削除する時は、トラックバックURL記入欄から当該URLを消して投稿すれば削除できます。もちろん、相手側のサーバから消せるという訳ではありません。
受信したトラックバックを削除する時は、削除したいトラックバックをチェックして投稿すれば削除できます。
更新Pingは、Pingサーバ一覧を/weblog/language/japanese/commonping_servers.inc.phpにありますので、ご希望に合わせて増減させてください。なお、このファイル内では#でコメントアウトできます。


・RDF形式でのエントリ配信
RSSに加えてRDF形式でもエントリを配信できます


・投稿時のオプションのデフォルト設定
HTMLタグを無効/プライベート/更新pingを送るについて、投稿時に最初からチェックが入っているかどうかの設定ができます。

・スタイルシートの自動読み込み
従来は、手でtheme.htmlなどにコピーしなければならなかったのを、自動で/weblog/styles.cssを読み込むようにしています。

バグや要望などは、
http://xoopsdevelopment.tohokuaiki.jp/


なお、うぇブログ 1.3から1.4へのアップデート方法です。
（うぇブログ+Trackbackからでもアップデートできます）

0.    ダウンロードしたファイルを展開してweblogディレクトリを取り出す。
1.    既存の/modules/weblogディレクトリを、先ほど展開したweblogディレクトリと入れ替え
2.    管理画面の「SYSTEM ADMIN」→「モジュール管理」でうぇブログのアップデート操作
3.    管理画面の「SYSTEM ADMIN」→「テンプレートセット・マネジャー」でうぇブログのテンプレートセット作成
        weblog_details.htmlとweblog_entries.htmlのみアップデートすればO.Kです
4.    管理画面の「うぇブログ」→「データベース管理」から「テーブル構成確認」を行う
5.    テーブル: 'weblog'にて、'trackbacks'カラムが見つかりません    というのと、
        テーブル: 'weblog_trackback'にて、'weblog_trackback'テーブルを作成する    というのが出るので作成

これで、使えるようになります。


注意：トラックバックを受けるには、ゲストに対してモジュールのアクセス権限を解放する必要があります。




・うぇブログモジュールの複製法方法とインストールディレクトリ名の変更

このモジュールはGIJOEさんのPEAKシリーズのように複製ができます。また、インストールディレクトリ名も
変更できますが、PEAKシリーズよりちょっとだけ手間を必要とします。
複製する時には、"半角英数"+数字という形で、数字が重ならなければいくつでも行けるはずです。
weblogというのは、数字無しというたった一つのケースと言えます。

weblog0とweblog1は用意しておきましたので、weblogディレクトリを丸ごとコピーして、
weblog0と名前を付け替えて、普通にモジュールインストールを行えば大丈夫です。

もし、weblog3とかweblog4とかを作りたくなった場合は、以下のようにしてください。
1. まず、weblogディレクトリのまるごとコピーをして、weblog3と名称をつけます.
2. 次のファイルを右のように名前を付け替えます。
/weblog3/sql/mysql1.sql -> /weblog/sql/mysql3.sql
/weblog3/templates/weblog1_archive.html -> /weblog3/templates/weblog3_archive.html
/weblog3/templates/weblog1_calblock.css.html -> /weblog3/templates/weblog3_calblock.css.html
/weblog3/templates/weblog1_details.html -> /weblog3/templates/weblog3_details.html
/weblog3/templates/weblog1_entries.html -> /weblog3/templates/weblog3_entries.html
/weblog3/templates/weblog1_imagemanager.html -> /weblog3/templates/weblog3_imagemanager.html
/weblog3/templates/weblog1_post.html -> /weblog3/templates/weblog3_post.html
/weblog3/templates/weblog1_print.html -> /weblog3/templates/weblog3_print.html
/weblog3/templates/weblog1_rss.html -> /weblog3/templates/weblog3_rss.html
/weblog3/templates/blocks/weblog1_block_archive.html -> /weblog3/templates/blocks/weblog3_block_archive.html
/weblog3/templates/blocks/weblog1_block_calendar.html -> /weblog3/templates/blocks/weblog3_block_calendar.html
/weblog3/templates/blocks/weblog1_block_category_list.html -> /weblog3/templates/blocks/weblog3_block_category_list.html
/weblog3/templates/blocks/weblog1_block_links.html -> /weblog3/templates/blocks/weblog3_block_links.html
/weblog3/templates/blocks/weblog1_block_recent_com.html -> /weblog3/templates/blocks/weblog3_block_recent_com.html
/weblog3/templates/blocks/weblog1_block_recent_image.html -> /weblog3/templates/blocks/weblog3_block_recent_image.html
/weblog3/templates/blocks/weblog1_block_recent_tb.html -> /weblog3/templates/blocks/weblog3_block_recent_tb.html
/weblog3/templates/blocks/weblog1_block_recent.html -> /weblog3/templates/blocks/weblog3_block_recent.html
/weblog3/templates/blocks/weblog1_block_top_weblogs.html -> /weblog3/templates/blocks/weblog3_block_top_weblogs.html
/weblog3/templates/blocks/weblog1_block_users_weblogs.html -> /weblog3/templates/blocks/weblog3_block_users_weblogs.html
3. mysql3.sqlファイルにある 'weblog1'という部分を全て'weblog3'にします。
4. 普通にインストールします。

もし、weblogというディレクトリ名を変えたい場合は、英数半角でお好きなものに変えてください。
そこ後、もし"myblog"という名前にしたら、/myblog/sql/mysql.sqlにあるweblogという文字列を
myblogにすればオッケーです。その後、インストールしてください。

この二つの方法を組み合わせることで、hisblog1 , herblog3 ..といった複数インストールも出来ます。





変更履歴
version 1.41 -- 2005/04/08
バージョンアップする為には、モジュールアップデートを行ってください。
    1. GIJOEさんが作ったチケットシステムをブログ投稿/編集/削除の所に導入
    2. 4つの新しいブロックを追加（リンクモジュール統合ブロック・ユーザごとの最新の投稿一覧・最近のweBLogへのコメント・最近のトラックバック）
    3. エントリの前半と後半を分ける機能
    4. エントリの一部を登録ユーザのみが読めるような機能
    5. 試験的にイメージマネージャを付けています。myalbum-Pモジュールのイメージマネージャ統合機能を移植したものです。
    6. これを使う為には、weBLogの管理画面から「データベース管理」を開いて、「weblogmyalbum_photos」テーブルを作る必要があります。

version 1.42 -- 2005/05/06
バージョンアップする為には、モジュールアップデートを行ってください。また、今回のバージョンアップで
'weblog'テーブルにpermission_groupというフィールドが増えましたので、うぇブログの「データベース管理」から当該フィールドを増やしてください。
今回は、主に権限管理について強化を行いました。
    1. エントリの投稿時にこのエントリを閲覧できるグループを設定できます。タイトルだけ見せて本文を見せないモードにすることもできます。
    2. グループ毎に「どのカテゴリに投稿することができるか」という設定をすることができます。
        ご注意：あるカテゴリを投稿禁止にした場合は、そのカテゴリに属する全てのサブカテゴリに投稿できなくなります。
    3. カテゴリのナビゲーション表示を変えました。
    4. 投稿権限や閲覧権限をXOOPSの標準の権限管理方法といままでのうぇブログの権限管理方法のどちらにするかを選べます。
    5. モジュール複製機能が使えます。weblog0・weblog1・・・とできます。weblog0/1は用意しましたが、それ以上は逐次お願いします。
    6. カテゴリリストのブロックを加えました。(thx hodaka)
    -- 2005/06/18
    7. ミニカレンダーブロックを加えました。(thx hodaka)
    8. アーカイブブロックを加えました。(thx hodaka)
    9. アーカイブページを改良しました。(thx hodaka)
    10. 最近の投稿 / 最近のユーザ毎の投稿のブロックでエントリの内容を表示できるようにしました。
    11. details.phpの「次のエントリ」「前のエントリ」をブログIDから時系列でソートするようにしました。
    -- 2005/08/15
    12. タイムゾーンの設定がきちんと効くようにしました。
    13. HTMLタグを全面禁止に出来るようにしました。
    14.トラックバック送信時のブログ名の設定が出来るようにしました。
    15.XOOPSの「投稿数」に反映するかどうか設定できるようにしました。
    16.PHP5対応
    17.その他もろもろのバグフィックス
	18.スペイン語対応(lunallenaさんありがとうございます)
    http://xoopsdevelopment.tohokuaiki.jpで様々なバグやリクエストをくださった全てのユーザに感謝します。

