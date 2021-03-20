# POTI-board改二

![POTI-board改二](https://user-images.githubusercontent.com/31465648/83109430-5ebf0080-a0fc-11ea-8acc-87f2fbbd2070.PNG)

phpお絵かき掲示板スクリプトPOTI-boardをさらに改良していくプロジェクトです。  
php5.5～ 対応。  
English translated version is here. [POTI-board Kai Ni-EN](https://github.com/sakots/poti-kaini-EN)
  
[PaintBBS NEO](https://github.com/funige/neo/)
~~POTI-board改~~ POTI-board改は保守作業およびサポートを終了しました。

## ！デジタルインク(windows Ink)の機能について

デジタルインク(Window Ink)がオンの状態でPaintBBS NEO / しぃペインターを操作すると、
例えばペンを左から右に大きくスライドさせるとブラウザが前の画面に戻る、などの誤動作が発生する可能性が大きくあります。PaintBBS NEO / しぃペインターご利用の際はデジタルインク(Window Ink)をオフにお願いいたします。

![デジタルインク](https://user-images.githubusercontent.com/31465648/83109254-0c7ddf80-a0fc-11ea-9627-7b4afe5ae193.PNG)

また、マウスジェスチャーの拡張機能とも競合する可能性があります。不具合を感じたらオフにしてみてください。

## 概要

POTI-board改で使用しているテンプレートエンジン「htmltemplate.inc」は老朽化が進んでいる…
ということでなんか新しいテンプレートエンジンはないか探したところ、
  
[Skinny](http://skinny.sx68.net/)
  
見つけました！これに移植します！ → できました！

## サンプル/サポート

[POTI改公式サイト](https://poti-k.info/) をオープンしました。質問や動作確認にご利用ください。

## 使い方

基本的に [POTI-board改](https://github.com/sakots/poti-kai/) と同じですが、テーマ(スキン/テンプレート機能)を置くフォルダができました。  
config.phpの設定をし、アップロードしてpotiboard.phpのあるディレクトリにアクセスするだけです。  
さとぴあさんの[詳しい記事](http://stp.sblo.jp/article/185357941.html)

## ちょっとした注意

2020/07/23以降のバージョンにはindex.phpというファイルが同梱されています。  
これは、potiboardの設置をより簡単にするためのものです。  
念のため、potiboard.phpをindex.phpにリネームしてお使いの方は、上書きしないようお願いいたします。  
また、新規設置される方はできるだけpotiboard.phpのリネームを行わないようにお願いいたします。

## 古いpotiboardから改二へのバージョンアップ

ログの形式は同じですが、config.phpが若干違います。また、テーマ(テンプレート)の形式は全く違います。configを同じように設定して、対応したテーマをテーマフォルダに入れてpotiboard.php、config.php、テーマフォルダをアップロードすればOKのはずです。質問はPOTI改公式まで！！

## テーマ(テンプレート)機能について

このスクリプトはテーマ(テンプレート)機能に対応しています。作り方（変数名）はデフォルトテーマのreadmeに詳しく書いています。  
[テーマリポジトリ](https://github.com/sakots/poti-kaini-themes)を参照ください。

- [POTI-board改二テーマリンク集](https://poti-k.info/themes.html)

## 履歴

### [2021/03/20] v2.26.6 lot.210320.0

- レス先が無い投稿の時に添付ファイルでアップロードした画像が画像ディレクトリに残るバグを修正。
- 続きから描いた時に画像の縦横比が正しくセットされないバグを修正。
- v2.12.7 lot.200822 から関数化されたpng2jpgの作業ディレクトリが、srcやtmpではなく、potiboard.phpと同じディ- レクトリだったのを変更。関数化以前はsrc、今回の変更でtmpにした。
- `config.php`の設定例にメイン名の例示のために予約されているセカンドレベルドメインexample.comを使用。

(by さとぴあ)

### [2021/03/09] v2.26.5 lot.210308.0

- v2.26.3の「e-mailとして不正な形式のものはリンクに出さないようにした」のミス修正 (by さとぴあ)

### [2021/03/07] v2.26.3 lot.210306.0

- レス先が存在しないレス投稿の時にE_WARNINGレベルのエラーが発生するのを修正 (by さとぴあ)
  - POTIv1.33b1とは別の方法で、img.logへの書き込み処理が発生しないようにした。
- ユーザー削除のエラー処理でfopen()したファイルが閉じられていなかったのを修正。
- 管理者の削除画面のバグを修正 (by さとぴあ)
  - メールアドレスを検証フィルタに通し、e-mailとして不正な形式のものはリンクに出さないようにした。
  - MD5が表示されなくなっていたのを修正。

### [2021/02/17] v2.26.2 lot.210217.0

- E_WARNINGレベルのエラーが発生していたのを修正 (by さとぴあ)
  - いまのところは些細な問題ですが今後のために。詳しくは[こちら](https://github.com/sakots/poti-kaini/pull/192)
  - `potiboard.php`のほかに`picpsot.php`に変更があります。

### [2021/02/15] v2.26.1 lot.210215.0

- ログに記録されているメールやURLの形式が正しく無い時は、出力時にチェックしてリンクに出さないよう修正 (by さとぴあ)
- 記事のHTML更新の時に、form()関数がHTMLの枚数分コールされていたのを変数に代入して一回ですむようにした (by さとぴあ)
  - ごくわずかな、わからないぐらいの差です。

### [2021/02/13] v2.26.0 lot.210213.0

- v2.23.1 lot.210203で発生したXSS脆弱性を修正 (by さとぴあ)
  - かなり危険ですので、必ずアップデートをお願いします。
- Cookieにセットできない文字列があったのを修正 (by さとぴあ)

### [2021/02/13] v2.23.9 lot.210212.1

- アニメファイル(.pch/.spch)アップロード時のエラーメッセージをtemplate_ini.phpで設定できるようにした (by さとぴあ)

### [2021/02/12] v2.23.8 lot.210212.0

- 最大ログ保持数が未設定または未定義の時はエラーになるようにした (by さとぴあ)
- また、最大ログ保持数の最低値を1000件に設定 (by さとぴあ)
  - 誤った設定によりログファイルと投稿画像を失うリスクの軽減です。
- v2.23.5 lot.210209.0で発生していた、最終ページが10件未満の時に直前のページのログが入り込むバグを修正 (by さとぴあ)
  - おかしくなっていたのは表示部分でログファイルへの影響はありません。
- 最大ログ数をオーバーした時のコードを書き直した  (by さとぴあ)

### [2021/02/10] v2.23.7 lot.210210.0

- 管理者パスワードが未定義の時はエラーにするように (by さとぴあ)
- 管理モードでのみ規制するための文字列が通常の書き込みでも拒絶されていたのを修正 (by さとぴあ)
  - themeの `template_ini.php` にメッセージの追加があります。

### [2021/02/09] v2.23.6 lot.210209.1

- v2.23.5で発生したバグ修正 (by さとぴあ)
  - これはアップデートお願いします。ほんと。

### [2021/02/09] v2.23.5 lot.210209.0

- パスワードまたはadminがNULL時かつ、管理パスの変数が設定されていない時に管理モードが開いてしまう事があるのを修正 (by さとぴあ)
  - config.phpが正しく設定されていればこれまでのバージョンでも問題ありません。

### [2021/02/07] v2.23.3 lot.210207.0

- お絵かきのCookieが無い時に、通常のコメントのCookieがエラーで取得できなくなっていたのを修正 (by さとぴあ)
  - `loadcookie.js`の上書きアップデートをお願いします。

### [2021/02/06] theme MONO v1.18.6

- 同梱のテーマ「MONO」をv1.18.6に更新

### [2021/02/06] theme MONO v1.18.5

- 同梱のテーマ「MONO」をv1.18.5に更新

### [2021/02/06] v2.23.2 lot.210204.0

- sessionの多重送信による操作性上の問題が見つかったため、v2.23.2に巻き戻し
  - v2.25.0は問題解決方法を探しながら開発します。

### [2021/02/06] theme MONO v1.18.4

- 同梱のテーマ「MONO」をv1.18.4に更新

### [2021/02/05] v2.25.0 lot.210205.0

- 管理者による投稿・編集の時に、sessionとワンタイムトークンを使いHTMLのソースに管理パスを出さない方式に移行 (by さとぴあ)
  - 管理パスをhiddenフィールドにいれて認証する古い方式を破棄することにより、セキュリティが向上しました。

### [2021/02/05] theme

- 同梱のテーマ「MONO」をv1.18.3に更新。

### [2021/02/04] v2.23.2 lot.210204.0

- コード整理 (by さとぴあ)
  - コメントのエスケープの関数、入力チェック、改行コードの関数を統合。

### [2021/02/04] theme

- 同梱のテーマ「MONO」をv1.18.2に更新。

### [2021/02/03] v2.23.1 lot.210203.0

- メアド欄にメールアドレスとして正しい文字列が入っていないとリンクを作成しないように修正 (by さとぴあ)
  - sage とか。
- 定数に置き換えたはずのメッセージに日本語が残っていたのを修正 (by さとぴあ)
- ログ保持件数を超えた時にログファイルに大量の空行が発生する現象を修正 (by さとぴあ)
- クッキーの二重Encodeを修正 (by さとぴあ)
- readme.txt 改定 (by さこつ)

### [2021/02/03] picpsot.php, thumbnail.php

- コード整理 (by さこつ)
  - 機能自体は変わっていないので、必ずしもアップデートが必要というわけではありません。

### [2021/02/02] v2.23.0 lot.210202.0

- filter_input()で取得可能な変数を関数の引数に使用しない (by さとぴあ)
- 入力チェックの関数化 (by さとぴあ)
- コード整理 (by さとぴあ)

### [2021/01/31] v2.22.7 lot.210131.1

- コード整理
  - バージョン表記の位置変更など (by さこつ)
  - modeのSwitch文のreturnの見直し (by さとぴあ)

### [2021/01/30] v2.22.6 lot.210130.0

- picpost.systemlogの設定をpicpost.phpに移動 (by さとぴあ)
  - 詳しくは[こちら](https://github.com/sakots/poti-kaini/pull/175)
- コード整理 (by さこつ)

### [2021/01/27] v2.22.5 lot.210127.0

- コード整理 (by さとぴあ)

### [2021/01/26] v2.22.3 lot.210126.0

- レス0件省略と表示される事があるバグを修正 (by さとぴあ)
- php8環境での軽微なエラーを修正 (by さこつ)

### [2021/01/18] search.php

- PHP8環境で致命的エラーが出るバグを修正 (by さとぴあ)
- 1発言分のログが4096バイト以上の時に処理できなくなるバグを修正 (by さとぴあ)

### [2021/01/05] v2.22.2 lot.210105.0

- 多国語対応。投稿通知メールのメッセージをtemplate_ini.phpで設定できるようにした。(by さとぴあ)
  - `theme/template_ini.php` に設定項目が増えていますが、そもままでも大丈夫です。

### [2021/01/02] v2.22.1 lot.210102.0

- php8でタイムスタンプがログに存在しない時に致命的エラーが発生するのを回避 (by さとぴあ)

### [2021/01/01] picpost.php

- picpost.systemlogのパーミッションもconfig.phpで設定できるようにした。(前回の作業の漏れ)

### [2020/12/25] LICENSE

- LICENSEを改訂。CC BY-NC-SA 3.0 の「継承」を誤解していたため (by さこつ)

### [2020/12/24] v2.22.0 lot.201224.0

- config.phpの設定項目を分類しなおし (by さとぴあ)
  - くわしくは[こちら](https://github.com/sakots/poti-kaini/pull/168)

### [2020/12/22] v2.21.6 lot.201222.0

- タイムゾーンをconfig.phpで設定できるようにした (by さとぴあ)
- 安全のため著作表示から wondercatstudio のhttpを削除 (by さこつ)

`potiboard.php` `picpsot.php` を上書き、`config.php` に設定追加。

### [2020/12/21] v2.21.5 lot.201221.2

- 定数が2重定義になるバグを修正 (by さとぴあ)
- 投稿者名の敬称の設定がthemeのHTMLに反映されるようにした (by さとぴあ)
  - 対応テーマが必要です。(mono、nee2は対応済み)

### [2020/12/21] v2.21.4 lot.201221.1

- template_ini_phpで設定した投稿者名の敬称がthemeのHTMLに自動的に反映されるように (by さとぴあ)
  - 主に外国語版向け機能です。

### [2020/12/21] v2.21.3 lot.201221

- 多国語対応。template_ini.phpで、すべてのメッセージを設定できるようにした。(by さとぴあ)

### [2020/12/20] v2.21.2 lot.201220a

- ログファイルに投稿時間(UNIXtimestamp)が記録されていない時に致命的エラーが発生するバグを修正。(by さとぴあ)

### [2020/12/20] v2.21.1 lot.201220

- PHPが出力するファイル、ディレクトリのパーミッションの値を設定できるようにした（by さとぴあ）

### [2020/12/20]

- 英訳作業開始（POTI-board Kai Ni-EN）
- template.iniの英文エラーメッセージの翻訳改善

### [2020/12/18] thumbnail_gd.php

- webp形式からのサムネイル作成に対応（by さとぴあ）

### [2020/12/18] v2.21.0 lot.201218

- **重要** php8環境で画像から続きを描くとエラーになるのを修正（by さとぴあ）
  - 新しいpicpost.php、search.phpへの差し換えをお願いします。PHP5.5～PHP7.xでも動作します。
- 動画表示モード、続きを描くでの時に画像がない時はエラーにする（by さとぴあ）
  - 画像が無い時はエラー表示「記事がありません」になります。
- webp仮対応（by さとぴあ）
  -画像がアップロードできるだけです。サムネイルは作成されません。しぃペインターやオリジナルのPaintBBSはwebpから続きを描けないので、何を選択してもNEOが起動します。

potiboard.php、picpost.php、search.php のアップデートをお願いします。

### [2020/12/14] v2.20.8 lot.201214

- $_REQUESTによる$modeの取得を廃止

### [2020/12/13] v2.20.7 lot.201213

- HTMLパートを含まないPHPscriptのPHPの終了タグを除去

### [2020/12/12] v2.20.6 lot.201212

- コード整理。

### [2020/12/10] v2.20.5 lot.201209

- `config.php`
  - 「新規投稿でコンティニューする時にも削除キーが必要 必要:1 不要:0」のデフォルト値を0に変更。（パスワードを公開しなくても合作ができる事がわかりにくく、パスワードが公開される事例が多いため。）

- `picpost.php` `readme.txt`
  - 著作リンク（ちょむ工房URL）が現在はまったく別のサイトになっているのを修正。

- `potiboard.php`
  - コード整理。

### [2020/12/07] v2.20.3 lot.201207

- php8ではE_WARNINGレベルのエラーになるUndefined offsetを修正（by さとぴあ）
- `palette.txt` せぴあ→セピア(ひらがなからカタカナへ)

### [2020/12/02] v2.20.2 lot.201130

- 旧PaintBBSのPCHファイルの動画再生と続きを描くに対応
- 旧PaintBBSのPCHファイルアップロードペイントが可能に
  - (テンプレートに更新があります）

### [2020/11/27] v2.20.1 lot.201127

- 描画時間関連の軽微なエラーを修正（by さとぴあ）

### [2020/11/25] v2.20.0 lot.201123

- 続きを描いた時の描画時間を合計表示に
  - 従来は1時間10分2秒+2分6秒のように+で描画時間をつないでいましたが、1時間12分8秒のような描画時間の合計になります。従来の表記も選択可能です。くわしくは[こちら](https://github.com/sakots/poti-kaini/pull/151)
- 今回更新が必要なファイルは、`potiboard.php`、`thumbnail_gd.php`（できれば）、`config.php`（設定を変更したい場合のみ）です。

### [2020/11/22] v2.19.5 lot.201121

- 動画から続きを描く時に動画ファイルをしらべて、元のアプレットで開くようにする処理を追加しました。
- 設定を変更してお絵かきできるテーマを使用した時に発生していたバグおよび問題を修正しました。（by さとぴあ）

### [2020/11/21] v2.19.3 lot.201120

- 続きを描く時に元の画像と違うサイズのキャンバスが開くことがあるバグを修正(by さとぴあ)
  - v2.18.10 lot.201103でキャンバスサイズをCookieに記録するようになりましたが、そのキャンバスサイズが続きを描く時のキャンバスサイズにも反映されてしまうバグが見つかり報告がありました。続きを描く時のキャンバスにCookieのキャンバスサイズは使用されなくなります。

### [2020/11/17] v2.19.2 lot.201117

- lot.201110（2.19.0～）の投稿完了時間が記録されないバグを修正。
  - picpost.phpの上書きアップデートが必要です。

### [2020/11/13] v2.19.1 lot.201112

- フォームが閉じられているスレッドへの投稿はエラーにする(by さとぴあ)

### [2020/11/11] v2.19.0 lot.201110

- 未投稿画像からの投稿でもレス先への投稿になるように(by さとぴあ)
  - 詳しくは[こちら](https://github.com/sakots/poti-kaini/pull/145)
  - potiboard.phpとpicpost.phpの更新が必要です。

### [2020/11/08] v2.18.11 lot.201108

- ファイルサイズが2MBを超えている時のエラー処理の追加(by さとぴあ)
  - `$_FILES['userfile']['error']`を使ったエラーチェックを行うようにし、ファイルサイズが大きすぎる時のエラー処理が入るようにしました。サイズが大きすぎて画像がアップロードされなかった時はエラー表示になりスクリプトの実行は中断されます。

### [2020/11/04] v2.18.10 lot.201103

- キャンバスサイズ、パレット、アプレット選択の値をCookieに保存(by さとぴあ)
  - potiboard.phpとloadcookie.jsの更新が必要です。

### [2020/11/02] v2.18.9 lot.201101

- リファクター及びオートリンクの仕様変更とloadcookie.jsの更新(by さとぴあ)
  - 詳しくは[こちら](https://github.com/sakots/poti-kaini/pull/141)

### [2020/10/30] v2.18.8 lot.201028

- 逆変換テーブルのリファクターと関連するバグを修正(by さとぴあ)

### [2020/10/28] v2.18.7 lot.201026

- 軽微なエラーの修正とわかりやすい変数名への変更(by さとぴあ)

### [2020/10/24] v2.18.6 lot.201024

- 描画時間の$ptimeが未定義変数になるケースがあったのを修正。(by さとぴあ)

### [2020/10/24] v2.18.5 lot.201023

- 書き込み可能かどうかのチェックの必要がないファイルをチェックから除外。関数整理。（by さとぴあ）

### [2020/10/09] v2.18.3 lot.201008

- 画像なしのチェックボックスによるチェックは面倒なので、その機能を使用しないオプションを追加(by さとぴあ)
  - 詳しくは[こちら](https://github.com/sakots/poti-kaini/pull/136)

### [2020/10/02] v2.18.2 lot.201002

- 管理パスの厳密化(by さとぴあ)
- hiddenフィールドにパスワードの入力値がそのまま表示されていたのを修正(by さとぴあ)
- search.php
  - 波ダッシュと全角チルダを区別しない、ログの区切りのカンマの数がひとつ多かったのを修正(by さとぴあ)
- config.php
  - ログ保存件数1000は少なすぎるので2000に増やした

### [2020/09/22] theme、NEO

- 一度投稿ボタンを押すと2度目は無効になる、jQueryを使ったスクリプトをthemeに追加。
- NEO更新
  - 投稿ボタン連打でテンポラリに同じ画像が何枚も入る、続きを描く時にNEOの投稿ボタンを連打→画像がありませんとなる、このような問題を回避できます。

### [2020/09/18] おしらせ

- v2.18.1で、一旦開発の速度を落とすことにしました。バグ等の修正は行います。

### [2020/09/15] v2.18.1 lot.200915

- potiboardphp search.php search.htmlに変更があります。
  - potiboard.php コード整理。行数を30行短縮。
  - search.php search.htmlの不具合の修正
  - ページ番号未入力の時にページ数が正しく認識されていなかったバグを修正。
  - 反復処理が入れ子になっていた箇所を修正。

### [2020/09/10] v2.18.0 lot.200910

- パレット切り替え機能でパレット名を任意に設定できるように(by さとぴあ)
  - potiboard.phpとconfig.phpに変更があります
  - 詳しくは[こちら](https://github.com/sakots/poti-kaini/pull/131)

### [2020/09/08] v2.17.0 lot.200908

- 記事編集しても投稿日時を変更しないようにする設定をconfig.phpに追加他(by さとぴあ)
  - 詳しくは[こちら](https://github.com/sakots/poti-kaini/pull/130)
  - potiboard.php、config.php、thumbnail_gd.php に変更があります。

### [2020/09/06] v2.16.3 lot.200906

- コード整理(by さとぴあ)
  - function filter_input_default 一箇所でしか使っていない、他の変数で使う訳でもない、ユーザー定義関数を整理
  - thumbnail_gd.php サムネイルの作成に失敗したときの返り値をFalseに変更

### [2020/09/03] v2.16.2 lot.200903

- コード整理(by さとぴあ)

### [2020/09/02] v2.16.1 lot.200902

- コード整理(by さとぴあ)

### [2020/09/02] v2.16.0 lot.200902

- パレットデータファイル切り替え機能を追加(by さとぴあ)
  - **config.phpに設定の追加があります**
- クリックしても何も起きない箇所のlabelタグを整理(by さとぴあ)

### [2020/08/31] v2.15.2 lot.200831

- コード整理(by さとぴあ)
- thumbnail_gd.php
  - サムネイルの作成に失敗していても、幅と高さの情報が返り値に入る可能性があったのを修正(by さとぴあ)

### [2020/08/31] v2.15.1 lot.200831

- **重要** 親の投稿が最新のときにレス画面が表示されないバグ修正

### [2020/08/30] v2.15.0 lot.200830

- 投稿途中の画像の本人確認の処理を修正 (by さとぴあ)
  - config.phpの設定に変更があります。詳しくは[こちら](https://github.com/sakots/poti-kaini/pull/122)
  - thumbnail_gd.phpも更新。

- コード整理 (by きつねこ)
  - 詳しくは[こちら](https://github.com/sakots/poti-kaini/pull/123)

### [2020/08/29] v2.14.1 lot.200829

- コード整理(by さとぴあ)
  -「お絵かきコメント」「画像差し換え」のglobal変数をローカル変数に。
  - 描画時間の計算を「投稿フォーム」から「お絵かきコメント」に移動。

### [2020/08/28] v2.14.0 lot.200828

- 「投稿途中の絵」からの投稿でも描画時間がでるように (by さとぴあ)
- 描画時間に関する情報をuserdataに記録する方式への移行 (by さとぴあ)
  - コメントを記入しなかったお絵かき画像の投稿は「投稿途中の絵」からの投稿になります。しかし、その場合は描画時間を表示する事ができませんでした。また、描画時間が入っていても信頼性がかなり低い状態でした。 これらの問題を解決するため、描画時間の元となるお絵かき開始時間と終了時間を、userdataに記録するようにしました。**potiboard.phpだけでなく、picpost.phpの更新も必要になります。picpost.phpの上書きアップデートを同時に行わなければ描画時間は表示されなくなります。**

### [2020/08/27]

- search.php
  - 負荷削減。画像検索でis_file()のチェックが２重になっていたのを修正(by さとぴあ)

### [2020/08/26] v.2.13.0 lot.200826

- 画像をアップロードして続きを描くと縦横比が正しく表示されない事があるのを修正(by さとぴあ)
- thumbnail_gd.phpの返り値に幅と高さが入るように(by さとぴあ)
  - thumbnail_gd.phpの更新も必要
  - 詳しくは[こちら](https://github.com/sakots/poti-kaini/pull/118)

### [2020/08/25] v.2.12.11 lot.200825

- カタログのHTMLの縦横比が正しくなかったの修正(by さとぴあ)
- ソースコード整理(by さとぴあ＆きつねこ)
  - 使用されていない定数USE_MBを削除
  - create_res() にオプション引数を追加して、動画チェックを行うかどうかを選べるようにした
  - 親レスでしか使われていない値のセットをcreate_res() の外に出した

### [2020/08/24] v.2.12.10 lot.200824

- ソースコード整理(by さとぴあ)

### [2020/08/23] v.2.12.9 lot.200823

- ソースコード整理(by きつねこ)
  - img.log 行からレス表示用データの生成ロジックを関数化など

### [2020/08/23] v.2.12.8 lot.200823

- $pictmp,$picfileを関数内に移動。カタログモードの時の画像のHTMLHTMLの幅と高さを出力できるようにした。(by さとぴあ)
  - くわしくは[こちら](https://github.com/sakots/poti-kaini/pull/112)

### [2020/08/22] v.2.12.7 lot.200822

- ソースコード整理(by きつねこ)
  - くわしくは[こちら](https://github.com/sakots/poti-kaini/pull/111)

### [2020/08/18] v.2.12.6 lot.200822

- v2.8.9の削除キー未入力時のバグを修正(by さとぴあ)

### [2020/08/18] v.2.12.5 lot.200818

- template_ini.phpのパスと未定義定数の確認(by さとぴあ)

### [2020/08/17] v.2.12.4 lot.200817

- ソースコード整理(by きつねこ)
  - 異常系を前に出してネストを浅くした

### [2020/08/17] v.2.12.3 lot.200817

- ソースコード整理(by きつねこ)
  - くわしくは[こちら](https://github.com/sakots/poti-kaini/pull/107)

### [2020/08/16]

- search.php radioボタン未チェックの時の動作を修正(by さとぴあ)

### [2020/08/15] v2.12.2 lot.200815

- ソースコード整理(by きつねこ)
  - 不要なネストを除去

### [2020/08/14] v2.12.1 lot.200814

- ピンチインの有効無効の設定箇所を整理
- search.php

### [2020/08/13] v2.12.0 lot.200813

- ソースコード整理(by きつねこ)
  - ファイルとディレクトリの存在チェックを関数化
  - テンプレートが利用できない場合にもエラーを出せるよう修正

### [2020/08/12] v2.11.0 lot.200812

- ソースコード整理(by きつねこ)
  - コードのスリム化
  - 動画表示時には `$shi` がなくても動画ファイルの存在で種類をチェックするよう修正

### [2020/08/10] v2.10.2 lot.200810

- ソースコード整理(by きつねこ)
  - 元画像、サムネ、動画　を削除している箇所を関数化

### [2020/08/10] v2.10.1 lot.200810

- ソースコード整理(by きつねこ)
  - pchかspchかチェックする場所を関数化
  - is_file ～ unlink を関数化

### [2020/08/10] v2.10.0 lot.200810

- サーバーのPHPバージョンが古い場合エラーを出して動作を停止するよう修正
  - poti改二の動作にはphp5.5以上の環境が必要です。

### [2020/08/08] v2.9.13 lot.200808

- ソースコード整理(by きつねこ)
  -描写時間計算ロジックを関数化

### [2020/08/07] v2.9.12 lot.200807

- ソースコード整理(by きつねこ)
  - 描写時間の計算部分をすっきりさせた

### [2020/08/07] v2.9.11 lot.200807

- ソースコード整理(by きつねこ)
  - 不要なunsetを削除
  - unset直後に参照されず代入されている、またはスコープ内で利用されないことを確認したもの

### [2020/08/06]

- テーマ

### [2020/08/05] v2.9.10 lot.200806

- ソースコード整理(by きつねこ)
  - 参照代入を値代入に変更、不要なunsetを削除
  - foreach内で1行ずつ改行コードを追加しているのを implodeで挟むよう修正

### [2020/08/05] v2.9.9 lot.200805

- ソースコード整理(by きつねこ)
  - 不要なforeachを削除

### [2020/08/03] v2.9.8 lot.200803

- カタログにレス数を表示できるように変数を追加(by funige)

### [2020/08/03] v2.9.7 lot.200803

- ソースコード整理(by きつねこ)
  - 不要なunsetを除去
  - 不要なforeachを削除
  - pch, spch の場合の処理はほぼ同じなのでまとめた
  - 参照されない代入を除去

### [2020/08/03] v2.9.6 lot.200803

- ソースコード整理(by きつねこ)

### [2020/08/02] v2.9.5 lot.200802

- ソースコード整理(by さとぴあ)

### [2020/08/02] v2.9.4 lot.200802

- ソースコード整理(by きつねこ)

### [2020/08/02] v2.9.3 lot.200802

- ソースコード整理(by きつねこ)
  - すべての処理が終わった後のunsetは不要
  - globalをローカル変数に置換
  - 関数内でしか使わない変数は関数内で代入するよう修正

### [2020/08/02] v2.9.2 lot.200802

- ソースコード整理(by きつねこ)
  - `switch($mode)` の場所を移動
  - 冒頭の `$mode` 取得周りの冗長性を排除

### [2020/08/01] v2.9.1 lot.200801

- updatelogのページャを微修正、カタログのページャをリファクタリング(by きつねこ)

### [2020/08/01] v2.9.0 lot.200801

- ページャのレンダリング部分をリファクタリング(by きつねこ)

### [2020/08/01] v2.8.14 lot.200801

- 新規投稿時に画像ファイルがあるときは、画像のアップロードが成功しましたとでていたが、でなくなっていたのを修正。
- オートリンク関連(by さとぴあ)

### [2020/08/01] v2.8.13 lot.200801

- ソースコード整理(by きつねこ)
  - 三項演算子化

### [2020/08/01] v2.8.12 lot.200801

- ソースコード整理(by きつねこ)
  - 関数化

### [2020/07/31] v2.8.11 lot.200731

- ソースコード整理(by さとぴあ)
  - `CleanStr()` に引数を追加して、コメントのみの時の処理もそれ以外の時の処理もひとつの関数で行えるように

### [2020/07/31] v2.8.10 lot.200731

- ソースコード整理(by さとぴあ)
  - pchファイルアップロードの位置とき書き方を変更

### [2020/07/31] v2.8.9 lot.200731

- ソースコード整理(by きつねこ)
  - if/elseを三項演算子化
  - 不要なコードを除去

### [2020/07/31] v2.8.8 lot.200731

- ソースコード整理(by きつねこ)
  - 冗長な処理を関数化

### [2020/07/31] v2.8.7 lot.200731

- ソースコード整理(by きつねこ)
  - 必ず代入される箇所を三項演算子に置換
  - 各種処理後のリダイレクトを関数化

### [2020/07/31] v2.8.6 lot.200731

- 続きを描くでneoのpchなのにしぃペインターが起動してしまうバグ修正(by さとぴあ)

### [2020/07/29] v2.8.5 lot.200729

- トリップ廃止の後方互換性確保
- `function head()` → `function basicpart()`

### [2020/07/29] v2.8.3 lot.200729

- トリップ廃止
- メールアドレスが入っていてもIDを表示。
  - ともに「何の説明もないから」

### [2020/07/29] v2.8.2 lot.200728

- コード簡略化、バグの原因になるものを除去(byさとぴあ、きつねこ)

### [2020/07/28] v2.8.1 lot.200727

- `$textonly` が画像なしの時にファイルアップロード欄に画像がセットされていた時のワークファイル削除処理を追加(by さとぴあ)
  - （ほぼ念の為）

### [2020/07/26] v2.8.0 lot.200726

- 管理者画面にアップロード欄を出す時の処理を変更(by さとぴあ)
  - 画像アップ禁止コメントのみも禁止の時は投稿フォームをださない
- テーマ

### [2020/07/25] v2.7.9 lot.200725

- 画像アップロード禁止でも管理者は許可
- コメントのみの新規投稿を拒否するしないの新規設定項目を追加。

### [2020/07/25] v2.7.8 lot.200725

- 画像アップロード機能を使う、使わないを設定可能できるように

### [2020/07/24] v2.7.7 lot.200723

- 負荷削減。カタログモードの時はpchの存在確認の処理をしない(by さとぴあ)

### [2020/07/23]

- index.php(初期動作用)を同梱
  - 設置がより簡単になりました。
- テーマ更新

### [2020/07/22]

- テーマ更新

### [2020/07/20]

- search.php 負荷削減

### [2020/07/18]

- search イラストの単位を「枚」コメントの単位を「件」に
- theme CSS

### [2020/07/16]

- テーマ更新、readmeを除去。（テーマのreadmeはテーマのリポジトリ参照）

### [2020/07/15]

- テーマのcss更新

### [2020/07/14] v2.7.6 lot.200714

- `<% echo (oya/encoded_name) %>` `<% echo (oya/res/encoded_name) %>`追加(by さとぴあ)
- search.phpをリポジトリに統合

### [2020/07/13] v2.7.5 lot.200712

- 文字列のエラーチェックを先に行いGDを使った画像関連の処理はそのあとで(by さとぴあ)

### [2020/07/12] v2.7.4 lot.200711

- 規定容量を超えるとJPEGに変換、JPEGとPNGを比較してファイルサイズが小さなほうを出力(by さとぴあ)
  - くわしくは[こちら](https://github.com/sakots/poti-kaini/pull/51)

### [2020/07/10]

- テーマ開発用のファイルを削除しリポジトリを分離[poti-kaini-themes](https://github.com/sakots/poti-kaini-themes)

### [2020/07/10] v2.7.3 lot.200708

- 投稿されたPNG画像が指定kbを超えた時にJPEG化する処理の調整(by さとぴあ)
  - くわしくは[こちら](https://github.com/sakots/poti-kaini/pull/50)

### [2020/07/05] v2.7.2 lot.200704

- 本文へのURLの書き込みを禁止時、通常の投稿でも削除キーが管理パスと一致すればurlの投稿ができるように
- さくらでなくても自動で作成されるため設置方法のディレクトリに関する説明を修正
- 古いurlを変更など(by さとぴあ)

### [2020/07/01] v2.7.1 lot.200701

- neoのpchかどうかの確認時に使用する関数をバイナリセーフなものに(by さとぴあ)
- neo更新

### [2020/06/30] v2.7.0 lot.200630

- 動画再生の時にNEOのpchかJavaのpchかを判定(by さとぴあ)

### [2020/06/26] v2.6.9 lot.200626

- v2.6.8で追加した箇所の修正(by さとぴあ)

### [2020/06/26] v2.6.8 lot.200625

- php5.6,php7.2の時に致命的エラーが発生していたv2.6.3以降のバージョンの文法ミスを修正。
- 画像アップロードやNEOのPNGファイルも設定したファイルサイズの上限を超過した時はJPEGに変換、そのJPEG画像がファイルサイズに違反していなければ投稿できるようにした。
- それにともないHTMLのフォームによるファイルサイズの制限を2MB、picpost.phpで受信できる画像のファイルサイズを3MBにそれぞれ緩和。

### [2020/06/23] v2.6.7 lot.200622

- 軽微なバグ修正

### [2020/06/09] v2.6.6 lot.200609

- 動画表示モード時の修正

### [2020/06/09]

- neo更新
  - に伴ってテーマ更新

### [2020/06/08] (テーマ)

- chrome83のキャッシュが強くてneoが更新されないことがあるの対策

### [2020/06/08]

- なぜかsiihelpファイルが残っていたので削除

### [2020/06/03]

- テーマ修正

### [2020/06/03] v2.6.5 lot.200603

- コード整理

### [2020/06/02] v2.6.4 lot.200602

- 文字色選択のバグ修正
- config初期値変更
- メール通知設定整理 (by さとぴあ)

### [2020/06/02]

- loadcookie.js with文の見直し
- テーマ修正

### [2020/05/30] v2.6.3 lot.200530a

- 改行タグ関連のコードを元に戻した

### [2020/05/30] v2.6.2 lot.200530

- 改行タグ関連のコードを整理
- 安定版だと思う

### [2020/05/28]

- テーマ修正

### [2020/05/27]

- Skinny.php 設定変更（キャッシュを1時間→1日に）

### [2020/05/23]

- Skinny.php改変、またそれを明記。

### [2020/05/23] v2.6.1 lot.200523

- デバッグスイッチ実装(開発者用)

### [2020/05/22]

- suEXECを導入してあるサーバーで動かない可能性があるのを修正(Skinny.php)

### [2020/05/22] v2.6.0 lot.200522a

- `<SIIHELP>` 廃止
- config整理

### [2020/05/22] v2.5.2 lot.200522

- デフォルトでテーマのキャッシュを有効にした（Skinny.php）
- テーマのreadme修正
- コード整理

### [2020/05/21] v2.5.1 lot.200521

- デフォルトテーマを修正
- 安定版

### [2020/05/20] v2.5.0 lot.200520

- スキン/テンプレートの呼び名を「テーマ(テンプレート)」に
- デフォルトテーマ(テンプレート)のディレクトリ名変更

### [2020/05/19]

- リポジトリ名変更（kaizi → kaini）ご迷惑おかけします…

### [2020/05/19] v2.4.1 lot.200519a

- デフォルトスキン更新
- configに説明追加
- noticemailをpotiboard2ディレクトリから分離

### [2020/05/19] v2.4.0 lot.200519

- Firefox、およびcheerpJのインストールなしでもchromeでしぃペインターが使用可能に

### [2020/05/18] v2.3.4 lot.200518b

- スキンをかなり更新
- コード整理 (by さとぴあ)

### [2020/05/18] v2.3.3 lot.200518a

- 強制サムネイル機能を削除 (thumbnail_gd.phpのバージョンアップがあります)

### [2020/05/18] v2.3.2 lot.200518

- cookieを取得するだけで使用していなかった個所を削除 (by さとぴあ)

### [2020/05/17] v2.3.1 lot.200517f

- ユーザー削除の安全性向上(by さとぴあ)

### [2020/05/17] v2.3.0 lot.200517e

- ユーザー削除権限の変更(config.phpに変更があります) (by さとぴあ)
- スキンの安全性、ユーザビリティ向上。

### [2020/05/17] v2.2.8 lot.200517d

- デバッグのdumpが残っていたので削除(by さとぴあ)

### [2020/05/17] v2.2.7 lot.200517c

- 「投稿者名をコピー」機能搭載。(by さとぴあ)
- スキンをそれに対応。

### [2020/05/17] v2.2.6 lot.200517b

- デフォルトスキン修正

### [2020/05/17]

- skinディレクトリの.htaccess設定変更
- おまけスキンnee2更新

### [2020/05/17] v2.2.5 lot.200517a

- configの説明追加、デフォルト値変更。
- アプレットのセキュリティチェックに引っかかった場合のURL用htmlファイルを同梱。
  - デフォルト設定ではお絵かき掲示板のindex.htmlに飛ばされてしまうため、なぜ投稿失敗したのかがわからないから。
  - セキュリティにヒットした場合の飛び先を define('SECURITY_URL', './security_c.html'); に設定変更してください。

### [2020/05/17] v2.2.4 lot.200517

- デフォルトでしぃペインターに対応

### [2020/05/16] v2.2.3 lot.200516e

- readme_pch.html を追加。Javaのpchビューワー配布条件に必要でした。(by さとぴあ)
- thumbnail_re.php を削除。現在のバージョンのPOTIでは使っていない。(by さとぴあ)

### [2020/05/16] v2.2.2 lot.200516d

- 対応スキン用にしぃちゃんアプレットを本体に同梱。[Readme_Shichan.html](./potiboard2/Readme_Shichan.html)

### [2020/05/16] v2.2.1 lot.200516c

- Skinnyを配布用に設定変更

### [2020/05/16] v2.2.0 lot.200516b

- ミニレスフォームを日数経過で閉じる機能追加（スキンの仕様が変わりました）
- v2.1までとconfigの互換性がなくなっていますので注意。（CRYPT_PASSが大文字になっただけです）
- configデフォルト変更

### [2020/05/16]

- config説明追加

### [2020/05/16] v2.1.1 lot.200516a

- 軽微なエラーつぶし
- configの調整

### [2020/05/16] v2.1.0 lot.200516

- スキンともども安定版

### [2020/05/15] v2.0.9 lot.200515

- 改行できなくなっていたバグに完全対応

### [2020/05/15] v2.0.8 lot.200515

- 改行できなくなっていたのを暫定対処

### [2020/05/15] v2.0.7 lot.200515

- config.phpの整理(by さとぴあ) - 時系列のものを種類別にした

### [2020/05/15]

- デフォルトスキンバージョンアップ
- スキンのreadme整理

### [2020/05/15] v2.0.6　lot.200515

- PROXY_CHECK関連のコードを削除(by さとぴあ)

### [2020/05/15] v2.0.5　lot.200515

- 改行の抑制とProxyチェックを廃止によるエラーを修正

### [2020/05/15] v2.0.4　lot.200515

- 改行の抑制とProxyチェックを廃止(by さとぴあ)
- potiboard.phpの上のほうの説明を修正

### [2020/05/15] v2.0.3　lot.200515

- 独自タグ関連エラー修正(by さとぴあ)
- palette.txtの読み込み処理修正(by さとぴあ)
- デフォルトスキン

### [2020/05/15] v2.0.2　lot.200515

- palette.txtの読み込み処理(by さとぴあ)

### [2020/05/15] v2.0.1　lot.200515

- 独自タグ廃止に関するエラー修正。(2.0.0動かないです)
- スキン修正

### [2020/05/15] v2.0.0　lot.200515

- $getで記事の編集をできないように変更(byさとぴあ)
- 独自タグ廃止
- noticemailのutf-8以外を削除
- 満を持して2.0.0として公開

### [2020/05/14] v2.0.0a8

- 管理パスをグローバル定数から変数に変更(byさとぴあ)
- スキンフォルダのcss以外へアクセスできないように.htaccessを設定

### [2020/05/14] v2.0.0a7

- DEF_FONTCOLORの設定がないテンプレートの場合を想定

### [2020/05/14]

- 動画が再生されないの、スキンの問題でした。

### [2020/05/14] v2.0.0a6

- デフォルトスキン変更、スキンフォルダ作成 (config.php要再設定！)
- palleteの問題に暫定対処

### [2020/05/14] v2.0.0a5

- htmlの生成に成功(byさとぴあ) 大感謝。
- スキンのエラー修整。

### [2020/05/13] v2.0.0a4

- htmlは生成されないが、動く。

### [2020/05/07] v2.0.0a3

- いちからつくりなおす。
- ログが生成されるのは確認、HTML生成されず

### [2018/09/16] v2.0.0a2

- 記録

### [2018/09/15] v2.0.0a1

- プロジェクト開始
- ログが生成されるのは確認、HTML生成されず
