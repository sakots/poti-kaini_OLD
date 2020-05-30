# POTI-board改二

<img width="300" alt="0z674" src="https://user-images.githubusercontent.com/31465648/83109430-5ebf0080-a0fc-11ea-8acc-87f2fbbd2070.PNG">

phpお絵かき掲示板スクリプトPOTI-boardをさらに改良していくプロジェクトです。  
php5.5～ 対応。
  
<a href="https://github.com/funige/neo/">PaintBBS NEO</a>  
<a href="https://github.com/sakots/poti-kai/">POTI-board改</a>  

## ！デジタルインク(windows Ink)の機能について！

デジタルインク(Window Ink)がオンの状態でPaintBBS NEO / しぃペインターを操作すると、
例えばペンを左から右に大きくスライドさせるとブラウザが前の画面に戻る、などの誤動作が発生する可能性が大きくあります。PaintBBS NEO / しぃペインターご利用の際はデジタルインク(Window Ink)をオフにお願いいたします。

<img width="200" alt="0z676" src="https://user-images.githubusercontent.com/31465648/83109254-0c7ddf80-a0fc-11ea-9627-7b4afe5ae193.PNG">
  
## 概要
POTI-board改で使用しているテンプレートエンジン「htmltemplate.inc」は老朽化が進んでいる… 
ということでなんか新しいテンプレートエンジンはないか探したところ、
  
<a href="http://skinny.sx68.net/">Skinny</a>  
  
見つけました！これに移植します！ → できました！

## サンプル/サポート

[POTI改公式サイト](https://poti-k.info/) をオープンしました。質問や動作確認にご利用ください。

## 使い方
基本的に <a href="https://github.com/sakots/poti-kai/">POTI-board改</a> と同じですが、テーマ(スキン/テンプレート機能)を置くフォルダができました。  
config.phpの設定をし、アップロードしてpotiboard.phpにアクセスするだけです。  
さとぴあさんの[詳しい記事](http://stp.sblo.jp/article/185357941.html) (改1.55系のバージョンのものですのでそこだけ注意。わたしも書かねば)。

## 古いpotiboardから改二へのバージョンアップ
ログの形式は同じですが、config.phpが若干違います。また、テーマ(テンプレート)の形式は全く違います。configを同じように設定して、対応したテーマをスキンフォルダに入れてpotiboard.php、config.php、テーマフォルダをアップロードすればOKのはずです。質問はPOTI改公式まで！！

## テーマ(テンプレート)機能について

このスクリプトはテーマ(テンプレート)機能に対応しています。作り方（変数名）はデフォルトテーマのreadmeに詳しく書いています。

---

## 履歴

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

### [2020/05/22]

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

### [2020/05/19]

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
