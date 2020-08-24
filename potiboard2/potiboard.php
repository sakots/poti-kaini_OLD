<?php
define('USE_DUMP_FOR_DEBUG','0');
//HTML出力の前に$datをdump しない:0 する:1 dumpしてexit：2 
// ini_set('error_reporting', E_ALL);
/*
  *
  * POTI-board改二 バージョン情報はちょっと下参照
  *   (C)sakots >> https://poti-k.info/
  *
  *----------------------------------------------------------------------------------
  * ORIGINAL SCRIPT
  *   POTI-board v1.32
  *     (C)SakaQ >> http://www.punyu.net/php/
  *   futaba.php v0.8 lot.031015 (gazou.php v3.0 CUSTOM)
  *     (C)futaba >> http://www.2chan.net/ ((C)ToR >> http://php.loglog.jp/)
  *
  * OEKAKI APPLET :
  *   PaintBBS   (test by v2.22_8)
  *   ShiPainter (test by v1.071all)
  *   PCHViewer  (test by v1.12)
  *     (C)shi-chan >> http://hp.vector.co.jp/authors/VA016309/
  *
  * PAINTBBS NEO
  *     (C)funige >> https://github.com/funige/neo/
  *
  * USE FUNCTION :
  *   Skinny.php            (C)Kuasuki   >> http://skinny.sx68.net/
  *   DynamicPalette        (C)NoraNeko  >> (http://wondercatstudio.com/)
  *----------------------------------------------------------------------------------

このスクリプトは「レッツPHP!」<http://php.loglog.jp/>のgazou.phpを改造した、
「ふたば★ちゃんねる」<http://www.2chan.net/>のfutaba.phpを
さらにお絵かきもできるようにして、HTMLテンプレートでデザイン変更できるように改造した
「ぷにゅねっと」<http://www.punyu.net/php/>のPOTI-boardを、
さらにphp7で動くように改造したものです。

配布条件はレッツPHP!に準じます。改造、再配布は自由にどうぞ。

このスクリプトの改造部分に関する質問は「レッツPHP!」
「ふたば★ちゃんねる」「ぷにゅねっと」に問い合わせないでください。
ご質問は、<https://poti-k.info/>までどうぞ。
*/

//バージョン
define('POTI_VER' , 'v2.12.9');
define('POTI_VERLOT' , 'v2.12.9 lot.200823');

if (($phpver = phpversion()) < "5.5.0") {
	die("本プログラムの動作には PHPバージョン 5.5.0 以上が必要です。<br>\n（現在のPHPバージョン：{$phpver}）");
}

//スパム無効化関数
function newstring($string) {
	$string = htmlspecialchars($string,ENT_QUOTES,'utf-8');
	$string = str_replace(",","，",$string);
	return $string;
}
//無効化ここまで

//INPUT_POSTから変数を取得

//var_dump($_POST);
$mode = isset($_REQUEST['mode']) ? $_REQUEST['mode'] : '';
$resto = filter_input(INPUT_POST, 'resto',FILTER_VALIDATE_INT);
$name = filter_input(INPUT_POST, 'name');
$email = filter_input(INPUT_POST, 'email');
$url = filter_input(INPUT_POST, 'url',FILTER_VALIDATE_URL);
$sub = filter_input(INPUT_POST, 'sub');
$com = filter_input(INPUT_POST, 'com');
$pwd = filter_input(INPUT_POST, 'pwd');
$shi = filter_input(INPUT_POST, 'shi',FILTER_VALIDATE_INT);
$picw = filter_input(INPUT_POST, 'picw',FILTER_VALIDATE_INT);
$pich = filter_input(INPUT_POST, 'pich',FILTER_VALIDATE_INT);
$anime = filter_input(INPUT_POST, 'anime',FILTER_VALIDATE_BOOLEAN);
$useneo = filter_input(INPUT_POST, 'useneo',FILTER_VALIDATE_BOOLEAN);
$no = filter_input(INPUT_POST, 'no',FILTER_VALIDATE_INT);
$pch = newstring(filter_input(INPUT_POST, 'pch'));
$ext = newstring(filter_input(INPUT_POST, 'ext'));
$ctype = newstring(filter_input(INPUT_POST, 'ctype'));
$type = newstring(filter_input(INPUT_POST, 'type'));
$ptime = newstring(filter_input(INPUT_POST, 'ptime'));
$del = filter_input(INPUT_POST,'del',FILTER_VALIDATE_INT,FILTER_REQUIRE_ARRAY);//$del は配列
$admin = newstring(filter_input(INPUT_POST, 'admin'));
$pass = newstring(filter_input(INPUT_POST, 'pass'));
$onlyimgdel = filter_input(INPUT_POST, 'onlyimgdel',FILTER_VALIDATE_BOOLEAN);

//v1.32 MONO WHITE
$fcolor = newstring(filter_input(INPUT_POST, 'fcolor'));
$quality = filter_input(INPUT_POST, 'quality',FILTER_VALIDATE_INT);

//INPUT_GETから変数を取得

//var_dump($_GET);
$res = filter_input(INPUT_GET, 'res',FILTER_VALIDATE_INT);
if(filter_input(INPUT_GET, 'mode')==="openpch"){
$pch = newstring(filter_input(INPUT_GET, 'pch'));
}
if(filter_input(INPUT_GET, 'mode')==="piccom"){
$stime = filter_input(INPUT_GET, 'stime',FILTER_VALIDATE_INT);
$resto = filter_input(INPUT_GET, 'resto',FILTER_VALIDATE_INT);
}
if(filter_input(INPUT_GET, 'mode')==="picrep"){
$no = filter_input(INPUT_GET, 'no',FILTER_VALIDATE_INT);
$pwd = newstring(filter_input(INPUT_GET, 'pwd'));
$repcode = newstring(filter_input(INPUT_GET, 'repcode'));
$stime = filter_input(INPUT_GET, 'stime',FILTER_VALIDATE_INT);
}
//INPUT_COOKIEから変数を取得
//var_dump($_COOKIE);

$pwdc = filter_input(INPUT_COOKIE, 'pwdc');
$usercode = filter_input(INPUT_COOKIE, 'usercode');//nullならuser-codeを発行

//設定の読み込み
if ($err = check_file('config.php')) {
	error($err);
}
require(__DIR__.'/config.php');

//HTMLテンプレート Skinny
if ($err = check_file(__DIR__.'/Skinny.php')) {
	error($err);
}
require_once(__DIR__.'/Skinny.php');

//Template設定ファイル
if ($err = check_file(__DIR__.'/'.SKIN_DIR.'template_ini.php')) {
	error($err);
}
require(__DIR__.'/'.SKIN_DIR.'template_ini.php');

$path = realpath("./").'/'.IMG_DIR;
$temppath = realpath("./").'/'.TEMP_DIR;

//サムネイルfunction
if((THUMB_SELECT==0 && gd_check()) || THUMB_SELECT==1){
	require(__DIR__.'/thumbnail_gd.php');
} else{
	function thumb(){
		return;
	}
}

//ユーザー削除権限 (0:不可 1:treeのみ許可 2:treeと画像のみ許可 3:tree,log,画像全て許可)
//※treeのみを消して後に残ったlogは管理者のみ削除可能
define('USER_DELETES', '3');

//メール通知クラスのファイル名
define('NOTICEMAIL_FILE' , 'noticemail.inc');

//タイムゾーン
date_default_timezone_set('Asia/Tokyo');

//ペイント画面の$pwdの暗号化
if(!defined('CRYPT_PASS')){//config.phpで未定義なら初期値が入る
	define('CRYPT_PASS','qRyFfhV6nyUggSb');//暗号鍵初期値
}
define('CRYPT_METHOD','aes-128-cbc');
define('CRYPT_IV','T3pkYxNyjN7Wz3pu');//半角英数16文字

//指定した日数を過ぎたスレッドのフォームを閉じる
if(!defined('ELAPSED_DAYS')){//config.phpで未定義なら0
	define('ELAPSED_DAYS','0');
}
//テーマに設定が無ければ代入
if(!defined('DEF_FONTCOLOR')){//文字色選択初期値
	define('DEF_FONTCOLOR',null);
}

if(!defined('ADMIN_DELGUSU')||!defined('ADMIN_DELKISU')){//管理画面の色設定
	define('ADMIN_DELGUSU',null);
	define('ADMIN_DELKISU',null);
}

//画像アップロード機能を 0.使わない 1.使う 
if(!defined('USE_IMG_UPLOAD')){//config.phpで未定義なら1
	define('USE_IMG_UPLOAD','1');
}

//画像のないコメントのみの新規投稿を拒否 しない:0 する:1 
if(!defined('DENY_COMMENTS_ONLY')){//config.phpで未定義なら0
	define('DENY_COMMENTS_ONLY', '0');
}

/*-----------Main-------------*/
init();		//←■■初期設定後は不要なので削除可■■
deltemp();

//user-codeの発行
if(!$usercode){//falseなら発行
	$userip = get_uip();
	$usercode = substr(crypt(md5($userip.ID_SEED.date("Ymd", time())),'id'),-12);
	//念の為にエスケープ文字があればアルファベットに変換
	$usercode = strtr($usercode,"!\"#$%&'()+,/:;<=>?@[\\]^`/{|}~","ABCDEFGHIJKLMNOabcdefghijklmn");
}
setcookie("usercode", $usercode, time()+86400*365);//1年間

switch($mode){
	case 'regist':
		if(ADMIN_NEWPOST && !$resto){
			if($pwd != $ADMIN_PASS){
				error(MSG029);
			}
			$admin=$pwd;
		}
		regist($name,$email,$sub,$com,$url,$pwd,$resto);
		break;
	case 'admin':
		valid($pass);
		if($admin==="del") admindel($pass);
		if($admin==="post"){
			$dat['post_mode'] = true;
			$dat['regist'] = true;
			$dat = array_merge($dat,form($res,'valid'));
			htmloutput(SKIN_DIR.OTHERFILE,$dat);
		}
		if($admin==="update"){
			updatelog();
			redirect(PHP_SELF2, 0);
		}
		break;
	case 'usrdel':
		if (!USER_DELETES) {
			error(MSG033);
		}
		usrdel($del,$pwd);
		updatelog();
		redirect(PHP_SELF2, 0);
		break;
	case 'paint':
		paintform($picw,$pich,$anime);
		break;
	case 'piccom':
		paintcom($resto);
		break;
	case 'openpch':
		openpch($pch);
		break;
	case 'continue':
		incontinue();
		break;
	case 'contpaint':
//パスワードが必要なのは差し換えの時だけ
		if(CONTINUE_PASS||$type==='rep') usrchk($no,$pwd);
		// if(ADMIN_NEWPOST) $admin=$pwd;
		paintform($picw,$pich,$anime,$pch);
		break;
	case 'newpost':
		$dat['post_mode'] = true;
		$dat['regist'] = true;
		// form($dat,'');
		$dat = array_merge($dat,form());
		htmloutput(SKIN_DIR.OTHERFILE,$dat);
		break;
	case 'edit':
		editform($del,$pwd);
		break;
	case 'rewrite':
		rewrite($no,$name,$email,$sub,$com,$url,$pwd,$admin);
		break;
	case 'picrep':
		replace($no,$pwd,$stime);
		break;
	case 'catalog':
		catalog();
		break;
	default:
		if($res){
			updatelog($res);
		}else{
			redirect(PHP_SELF2, 0);
		}
}

exit;

//GD版が使えるかチェック
function gd_check(){
	$check = array("ImageCreate","ImageCopyResized","ImageCreateFromJPEG","ImageJPEG","ImageDestroy");

	//最低限のGD関数が使えるかチェック
	if(get_gd_ver() && (ImageTypes() & IMG_JPG)){
		foreach ( $check as $cmd ) {
			if(!function_exists($cmd)){
				return false;
			}
		}
	}else{
		return false;
	}

	return true;
}

//gdのバージョンを調べる
function get_gd_ver(){
	if(function_exists("gd_info")){
	$gdver=gd_info();
	$phpinfo=$gdver["GD Version"];
	$end=strpos($phpinfo,".");
	$phpinfo=substr($phpinfo,0,$end);
	$length = strlen($phpinfo)-1;
	$phpinfo=substr($phpinfo,$length);
	return $phpinfo;
	} else{
	return false;
	}
}
//ユーザーip
function get_uip(){
	$userip = getenv("HTTP_CLIENT_IP");
	if(!$userip){
		$userip = getenv("HTTP_X_FORWARDED_FOR");
	} 
	if(!$userip){
		$userip = getenv("REMOTE_ADDR");
	} 
	return $userip;
	}

/* ベース */
function basicpart(){
	$dat['title'] = TITLE;
	$dat['home']  = HOME;
	$dat['self']  = PHP_SELF;
	$dat['self2'] = PHP_SELF2;
	$dat['paint'] = USE_PAINT ? true : false;
	$dat['applet'] = APPLET ? true : false;
	$dat['usepbbs'] = APPLET!=1 ? true : false;
	$dat['ver'] = POTI_VER;
	$dat['verlot'] = POTI_VERLOT;
	$dat['tver'] = TEMPLATE_VER;
	$dat['userdel'] = USER_DELETES;
	$dat['charset'] = 'UTF-8';
	$dat['skindir'] = SKIN_DIR;
	$dat['for_new_post'] = true;
	if(!USE_IMG_UPLOAD&&DENY_COMMENTS_ONLY){
		$dat['for_new_post'] = false;
	}
	//OGPイメージ シェアボタン
	$dat['rooturl'] = ROOT_URL;//設置場所url
	if (SHARE_BUTTON){
		$dat['sharebutton'] = true;//1ならシェアボタンを表示
	}
	return $dat;
}

/* 投稿フォーム */
function form($resno="",$adminin="",$tmp=""){
	global $addinfo,$stime;
	global $fontcolors,$quality,$qualitys;
	global $ADMIN_PASS;

	$admin = ($adminin === 'valid');

	$dat['form'] = true;
	if(!USE_IMG_UPLOAD && DENY_COMMENTS_ONLY && !$resno && !$admin){//コメントのみも画像アップロードも禁止
		$dat['form'] = false;//トップページのフォームを閉じる
		if(USE_PAINT==1 && !$resno && !$admin){
			$dat['paint2'] = true;
		}
	}
	if(USE_PAINT){
		$dat['pdefw'] = PDEF_W;
		$dat['pdefh'] = PDEF_H;
		$dat['anime'] = USE_ANIME ? true : false;
		$dat['animechk'] = DEF_ANIME ? ' checked' : '';
		$dat['pmaxw'] = PMAX_W;
		$dat['pmaxh'] = PMAX_H;
		if(USE_PAINT==2 && !$resno && !$admin){
			$dat['paint2'] = true;
			$dat['form'] = false;
		}
	}

	if($resno){
		$dat['resno'] = $resno;
		if(RES_UPLOAD) $dat['paintform'] = true;
	}else{
		$dat['paintform'] = true;
		$dat['notres'] = true;
	}

	if($admin) $dat['admin'] = $ADMIN_PASS;

	//描画時間
	if($stime && DSP_PAINTTIME){
		$dat['ptime'] = calcPtime($stime);
	}

	$dat['maxbyte'] = 2048 * 1024;//フォームのHTMLによるファイルサイズの制限 2Mまで
	$dat['usename'] = USE_NAME ? ' *' : '';
	$dat['usesub']  = USE_SUB ? ' *' : '';
	if(USE_COM||($resno&&!RES_UPLOAD)) $dat['usecom'] = ' *';
	//本文必須の設定では無い時はレスでも画像かコメントがあれば通る
	// if((!$resno && !$tmp) || (RES_UPLOAD && !$tmp)) $dat['upfile'] = true;
	if(!USE_IMG_UPLOAD && !$admin){//画像アップロード機能を使わない時
		$dat['upfile'] = false;
	} else{
		if((!$resno && !$tmp) || (RES_UPLOAD && !$tmp)) $dat['upfile'] = true;
	}
	$dat['maxkb']   = MAX_KB;//実際にアップロードできるファイルサイズ
	$dat['maxw']    = $resno ? MAX_RESW : MAX_W;
	$dat['maxh']    = $resno ? MAX_RESH : MAX_H;
	$dat['addinfo'] = $addinfo;

	//文字色
	if(USE_FONTCOLOR){
		foreach ( $fontcolors as $fontcolor ){
			list($color,$name) = explode(",", $fontcolor);
			$dat['fctable'][] = compact('color','name');
		}
	}

	//アプレット設定
	$undo = filter_input(INPUT_POST, 'undo',FILTER_VALIDATE_INT);
	$dat['undo'] = $undo ? $undo : UNDO;
	$undo_in_mg = filter_input(INPUT_POST, 'undo_in_mg',FILTER_VALIDATE_INT);
	$dat['undo_in_mg'] = $undo_in_mg ? $undo_in_mg : UNDO_IN_MG;
	$qline='';
	foreach ( $qualitys as $q ){
		$selq = ($q == $quality) ? ' selected' : '';
		$qline .= '<option value='.$q.$selq.'>'.$q."</option>\n";
	}
	$dat['qualitys'] = $qline;

	return $dat;
}

/* 記事部分 */
function updatelog($resno=0){
	global $path;

	$tree = file(TREEFILE);
	$st = null;
	if($resno){
		foreach($tree as $i => $value){
			//レス先検索
			if (strpos(trim($value) . ',', $resno . ',') === 0) {
				$st = $i;
				break;
			}
		}
		if ($st === null) {
			error(MSG001);
		}
	}

	$line = file(LOGFILE);
	foreach($line as $i =>$value){
		list($no,) = explode(",", $value);
		$lineindex[$no] = $i + 1; //逆変換テーブル作成
	}

	$counttree = count($tree);//190619
	for($page=0;$page<$counttree;$page+=PAGE_DEF){
		$oya = 0;	//親記事のメイン添字
		$dat = form($resno);
		if(!$resno){
			$st = $page;
		}
		for($i = $st; $i < $st+PAGE_DEF; ++$i){
			if(!isset($tree[$i])){
				continue;
			}

			$treeline = explode(",", rtrim($tree[$i]));
			$disptree = $treeline[0];
			$j=$lineindex[$disptree] - 1; //該当記事を探して$jにセット
			if($line[$j]==="") continue;   //$jが範囲外なら次の行

			$res = create_res($path, $line[$j]);

			$r_threads = false;
			if(ELAPSED_DAYS){//古いスレッドのフォームを閉じる日数が設定されていたら
				$ntime = time();
				$ltime=substr($res['time'],-13,-3);
				$elapsed_time = ELAPSED_DAYS*86400;
				if(($ntime-$ltime) <= $elapsed_time){//指定日数以内
					$r_threads = true;//フォームを表示する
				}
			} else{//フォームを閉じる日数が未設定なら
				$r_threads = true;
			}
			$disp_resform = true;
			if(!$r_threads){
				$disp_resform = false;//ミニレスフォームを閉じる
				if($resno){//レスなら
					$dat['form'] = false;//フォームを閉じる
					$dat['paintform'] = false;
				}
			}

			// ミニフォーム用
			$resub = USE_RESUB ? 'Re: ' . $res['sub'] : '';
			// レス省略
			$skipres = '';
			if(!$resno){
				$counttreeline = count($treeline);//190619
				$s=$counttreeline - DSP_RES;
				if(ADMIN_NEWPOST&&!DSP_RES) {$skipres = $s - 1;}
				elseif($s<1 || !DSP_RES) {$s=1;}
				elseif($s>1) {$skipres = $s - 1;}
				//レス画像数調整
				if(RES_UPLOAD){
					//画像テーブル作成
					$imgline=array();
					foreach($treeline as $k => $disptree){
						if($k<$s){//レス表示件数
							continue;
						}
						$j=$lineindex[$disptree] - 1;
						if($line[$j]==="") continue;
						list(,,,,,,,,,$rext,,,$rtime,,,) = explode(",", rtrim($line[$j]));
						$resimg = $path.$rtime.$rext;

						$imgline[] = ($rext && is_file($resimg)) ? 'img' : '0';
					}
					$resimgs = array_count_values($imgline);
					if(isset($resimgs['img'])){//未定義エラー対策
					while($resimgs['img'] > DSP_RESIMG){
						while($imgline[0]='0'){ //画像付きレスが出るまでシフト
							array_shift($imgline);
							$s++;
						}
						array_shift($imgline); //画像付きレス1つシフト
						$s++;
						$resimgs = array_count_values($imgline);
					}
					}
					if($s>1) {$skipres = $s - 1;}//再計算
				}
			}else{
				$s=1;
				$dat['resub'] = $resub; //レス画面用
			}

			// 親レス用の値
			$res['tab'] = $oya + 1; //TAB
			$res['disp_resform'] = $disp_resform;
			$res['limit'] = ($lineindex[$res['no']] - 1 >= LOG_MAX * LOG_LIMIT / 100) ? true : ''; // そろそろ消える。
			$res['skipres'] = $skipres;
			$res['resub'] = $resub;

			$dat['oya'][$oya] = $res;

			$oyaname = $res['name']; //投稿者名をコピー

			//レス作成
			$rres=array();
			$rresname = [];
			foreach($treeline as $k => $disptree){
				if($k<$s){//レス表示件数
					continue;
				}
				$j=$lineindex[$disptree] - 1;
				if($line[$j]==="") continue;

				$res = create_res($path, $line[$j]);
				$rres[$oya][] = $res;
				
				// 投稿者名を配列にいれる
				if ($oyaname != $res['name'] && !in_array($res['name'], $rresname)) { // 重複チェックと親投稿者除外
					$rresname[] = $res['name'];
				}
			}

			// レス記事一括格納
			if($rres){//レスがある時
				$dat['resname'] = $rresname ? implode('さん ',$rresname) : ''; // レス投稿者一覧
				$dat['oya'][$oya]['res'] = $rres[$oya];
			}

			clearstatcache(); //ファイルのstatをクリア
			$oya++;
			if($resno){break;} //res時はtree1行だけ
		}

		if(!$resno){ //res時は表示しない
			$prev = $st - PAGE_DEF;
			$next = $st + PAGE_DEF;
			// 改ページ処理
			if($prev >= 0){
				$dat['prev'] = $prev == 0 ? PHP_SELF2 : ($prev / PAGE_DEF) . PHP_EXT;
			}
			$paging = "";

			//表示しているページが20ページ以上または投稿数が少ない時はページ番号のリンクを制限しない
			$showAll = ($counttree <= PAGE_DEF * 21 || $i >= PAGE_DEF * 22);

			for($i = 0; $i < ($showAll ? $counttree : PAGE_DEF * 22); $i += PAGE_DEF){
				$pn = $i ? $i / PAGE_DEF : 0; // page_number
				$paging .= ($st === $i)
					? str_replace("<PAGE>", $pn, NOW_PAGE) // 現在ページにはリンクを付けない
					: str_replace("<PURL>", ($i ? $pn.PHP_EXT : PHP_SELF2),
						str_replace("<PAGE>", $i ? ($showAll || $i !== PAGE_DEF * 21 ? $pn : "≫") : $pn, OTHER_PAGE));
			}

	//改ページ分岐ここまで

			$dat['paging'] = $paging;
			if($oya >= PAGE_DEF && $counttree > $next){
				$dat['next'] = $next/PAGE_DEF.PHP_EXT;
			}
		}

		if($resno){htmloutput(SKIN_DIR.RESFILE,$dat);break;}
		$dat['resform'] = RES_FORM ? true : false;

		$buf = htmloutput(SKIN_DIR.MAINFILE,$dat,true);

		$logfilename = $page == 0 ? PHP_SELF2 : ($page / PAGE_DEF) . PHP_EXT;

		$fp = fopen($logfilename, "w");
		set_file_buffer($fp, 0);
		flock($fp, LOCK_EX); //*
		rewind($fp);
		fwrite($fp, $buf);
		fflush($fp);
		flock($fp, LOCK_UN);
		fclose($fp);
		//拡張子を.phpにした場合、↑で500エラーでるなら↓に変更
		if(PHP_EXT!='.php'){chmod($logfilename,0606);}
	}
	if (!$resno) {
		safe_unlink(($page/PAGE_DEF+1).PHP_EXT);
	}
}

/* オートリンク */
function auto_link($proto){
	if(!(stripos($proto,"script")!==false)){//scriptがなければ続行
		return preg_replace("{(https?|ftp)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)}","<a href=\"\\1\\2\" target=\"_blank\" rel=\"nofollow noopener noreferrer\">\\1\\2</a>",$proto);
	}
	return $proto;
}

/* 日付 */
function now_date($time){
	$youbi = array('日','月','火','水','木','金','土');
	$yd = $youbi[date("w", $time)] ;
	$now = date(DATE_FORMAT, $time);
	$now = str_replace("<1>", $yd, $now); //漢字の曜日セット1
	$now = str_replace("<2>", $yd.'曜', $now); //漢字の曜日セット2
	return $now;
}

/* エラー画面 */
function error($mes,$dest=''){
	safe_unlink($dest);
	$dat['err_mode'] = true;
	$dat['mes'] = $mes;
	if (defined('OTHERFILE')) {
		htmloutput(SKIN_DIR.OTHERFILE,$dat);
	} else {
		print $dat['mes'];
	}
	exit;
}

/* 文字列の類似性を見積もる */
function similar_str($str1,$str2){
	similar_text($str1, $str2, $p);
	return $p;
}

/* 記事書き込み */
function regist($name,$email,$sub,$com,$url,$pwd,$resto){
	global $path,$badstring,$pwdc;
	global $temppath,$ptime;
	global $fcolor,$usercode;
	global $admin,$badstr_A,$badstr_B,$badname;
	global $ADMIN_PASS;

	$pictmp = filter_input(INPUT_POST, 'pictmp',FILTER_VALIDATE_INT);
	$picfile = newstring(filter_input(INPUT_POST, 'picfile'));
	$upfile_name = isset($_FILES["upfile"]["name"]) ? $_FILES["upfile"]["name"] : "";//190603
	if (strpos($upfile_name, '/') !== false) {//ファイル名に/があったら中断
		error(MSG015);
	}

	$upfile = isset($_FILES["upfile"]["tmp_name"]) ? $_FILES["upfile"]["tmp_name"] : "";

	$textonly = filter_input(INPUT_POST, 'textonly',FILTER_VALIDATE_BOOLEAN);

	if($textonly){//画像なしの時
		safe_unlink($upfile);
		$upfile="";
		$upfile_name="";
	}

	$userip = get_uip();
	$mes="";

	// 時間
	$time = time();
	$tim = $time.substr(microtime(),2,3);

	// お絵かき絵アップロード処理
	if($pictmp==2){
		if(!$picfile) error(MSG002);
		$upfile = $temppath.$picfile;
		$upfile_name = $picfile;
		$picfile=pathinfo($picfile);
		$picfile = $picfile['filename']; //拡張子除去 190616
		$tim = KASIRA.$tim;
		//選択された絵が投稿者の絵か再チェック
		if (!$picfile || !is_file($temppath.$picfile.".dat")) {
			error(MSG007);
		}

		$fp = fopen($temppath.$picfile.".dat", "r");
		$userdata = fread($fp, 1024);
		fclose($fp);
		list($uip,$uhost,,,$ucode,) = explode("\t", rtrim($userdata));
		if(($ucode != $usercode) && (IP_CHECK && $uip != $userip)){error(MSG007);}
	}
	$dest='';
	$is_file_dest=false;
	if($upfile && is_file($upfile) && !$textonly){//アップロード
		$dest = $path.$tim.'.tmp';
		if($pictmp==2){
			copy($upfile, $dest);
		} else{//フォームからのアップロード
			if(!USE_IMG_UPLOAD && $admin!==$ADMIN_PASS){//アップロード禁止で管理画面からの投稿ではない時
				unlink($upfile);
				error(MSG006,$dest);
			}
			if(!preg_match('/\A(jpe?g|jfif|gif|png)\z/i', pathinfo($upfile_name, PATHINFO_EXTENSION))){//もとのファイル名の拡張子190606
				error(MSG004,$dest);
				}
				if(move_uploaded_file($upfile, $dest)){
					$upfile_name = CleanStr($upfile_name);
				} else{
					$upfile_name='';
					error(MSG003,$dest);
				}
			//↑でエラーなら↓に変更
			//copy($upfile, $dest);
		}

		$is_file_dest = is_file($dest);
		if(!$is_file_dest){
			error(MSG003,$dest);
		}
	}

	$REQUEST_METHOD = isset($_SERVER["REQUEST_METHOD"]) ? $_SERVER["REQUEST_METHOD"] : "";
	if($REQUEST_METHOD !== "POST") error(MSG006);

	//チェックする項目から改行・スペース・タブを消す
	$chk_com  = preg_replace("/\s/u", "", $com );
	$chk_name = preg_replace("/\s/u", "", $name );
	$chk_sub = preg_replace("/\s/u", "", $sub );
	$chk_email = preg_replace("/\s/u", "", $email );

	//本文に日本語がなければ拒絶
	if (USE_JAPANESEFILTER) {
		mb_regex_encoding("UTF-8");
		if (strlen($com) > 0 && !preg_match("/[ぁ-んァ-ヶー一-龠]+/u",$chk_com)) error(MSG035,$dest);
	}

	//本文へのURLの書き込みを禁止
	if(!($pwd===$ADMIN_PASS||$admin===$ADMIN_PASS)){//どちらも一致しなければ
		if(DENY_COMMENTS_URL && preg_match('/:\/\/|\.co|\.ly|\.gl|\.net|\.org|\.cc|\.ru|\.su|\.ua|\.gd/i', $com)) error(MSG036,$dest);
	}

	// 使えない文字チェック
	if (is_ngword($badstring, [$chk_com, $chk_sub, $chk_name, $chk_email])) {
		error(MSG032,$dest);
	}

	// 使えない名前チェック
	if (is_ngword($badname, $chk_name)) {
		error(MSG037,$dest);
	}

	//指定文字列が2つあると拒絶
	$bstr_A_find = is_ngword($badstr_A, [$chk_com, $chk_sub, $chk_name, $chk_email]);
	$bstr_B_find = is_ngword($badstr_B, [$chk_com, $chk_sub, $chk_name, $chk_email]);
	if($bstr_A_find && $bstr_B_find){
		error(MSG032,$dest);
	}

	// フォーム内容をチェック
	if(!$name||preg_match("/\A\s*\z/u",$name)) $name="";
	if(!$com||preg_match("/\A\s*\z/u",$com)) $com="";
	if(!$sub||preg_match("/\A\s*\z/u",$sub))   $sub="";
	if(!$email||preg_match("/\A\s*\z|&lt;|</ui",$email)) $email="";
	if(!$url||!preg_match("/\A *https?:\/\//",$url)||preg_match("/&lt;|</i",$url)) $url="";
	if(!USE_IMG_UPLOAD && $admin!==$ADMIN_PASS){
		$textonly=true;//画像なし
	}
	if(!$resto&&!$textonly&&!$is_file_dest) error(MSG007,$dest);
	if(!$resto&&DENY_COMMENTS_ONLY&&!$is_file_dest&&$admin!==$ADMIN_PASS) error(MSG039,$dest);
	if(RES_UPLOAD&&$resto&&!$textonly&&!$is_file_dest) error(MSG007,$dest);

	if(!$com&&!$is_file_dest) error(MSG008,$dest);

	if(USE_NAME&&!$name) error(MSG009,$dest);
	if(USE_COM&&!$com) error(MSG008,$dest);
	if(USE_SUB&&!$sub) error(MSG010,$dest);

	if(strlen($com) > MAX_COM) error(MSG011,$dest);
	if(strlen($name) > MAX_NAME) error(MSG012,$dest);
	if(strlen($email) > MAX_EMAIL) error(MSG013,$dest);
	if(strlen($sub) > MAX_SUB) error(MSG014,$dest);
	if(strlen($resto) > 10) error(MSG015,$dest);

	//ホスト取得
	$host = gethostbyaddr($userip);
	check_badip($host, $dest);

	// No.とパスと時間とURLフォーマット
	srand((double)microtime()*1000000);
	if(!$pwd){//nullでも8桁のパスをセット
		$pwd = $pwdc ? $pwdc : substr(rand(), 0, 8);
	}

	$c_pass = $pwd;
	$pass = ($pwd) ? password_hash($pwd,PASSWORD_BCRYPT,['cost' => 5]) : "*";
	$now = now_date($time);//日付取得
	if(DISP_ID){
			$now .= " ID:".substr(crypt(md5($userip.ID_SEED.date("Ymd", $time)),'id'),-8);
	}
	//カンマを変換
	$now = str_replace(",", "&#44;", $now);
	$ptime = str_replace(",", "&#44;", $ptime);
	//テキスト整形
	$email=strip_tags($email);
	$email= CleanStr($email);
	$email=preg_replace("/[\r\n]/","",$email);
	$sub  = CleanStr($sub);
	$sub  =preg_replace("/[\r\n]/","",$sub);
	$resto= CleanStr($resto); $resto=preg_replace("/[\r\n]/","",$resto);
	$url  = CleanStr($url);   $url  =preg_replace("/[\r\n]/","",$url);
	$url  = str_replace(" ", "", $url);
	$com  = CleanStr($com,true);
	$pwd= CleanStr($pwd);
	$pwd=preg_replace("/[\r\n]/","",$pwd);
	//管理モードで使用できるタグを制限
	if(preg_match('/< *?script|< *?\? *?php|< *?img|< *?a  *?onmouseover|< *?iframe|< *?frame|< *?div|< *?table|< *?meta|< *?base|< *?object|< *?embed|< *?input|< *?body|< *?style/i', $com)) error(MSG038,$dest);

	// 改行文字の統一。
	$com = str_replace("\r\n", "\n", $com);
	$com = str_replace("\r", "\n", $com);
	// 連続する空行を一行
	$com = preg_replace("/\n((　| )*\n){3,}/","\n",$com);
	$com = nl2br($com);		//改行文字の前に<br>を代入する

	$com = str_replace("\n", "", $com);	//\nを文字列から消す

	$name=preg_replace("/◆/","◇",$name);
	$name=preg_replace("/[\r\n]/","",$name);
	$name=CleanStr($name);

	//ログ読み込み
	$fp=fopen(LOGFILE,"r+");
	flock($fp, LOCK_EX);
	rewind($fp);
	$buf=fread($fp,5242880);
	if(!$buf){error(MSG019,$dest);}
	$buf = charconvert($buf);
	$line = explode("\n", trim($buf));
	foreach($line as $i => $value){//$i必要
		if($value!==""){//190624
			list($artno,)=explode(",", rtrim($value));	//逆変換テーブル作成
			$lineindex[$artno]=$i+1;
		}
	}

	// 連続・二重投稿チェック (v1.32:仕様変更)
	$chkline=20;//チェックする最大行数
	foreach($line as $i => $value){
		if($value!==""){
		list($lastno,,$lname,$lemail,$lsub,$lcom,$lurl,$lhost,$lpwd,,,,$ltime,) = explode(",", $value);
		$pchk=0;
		switch(POST_CHECKLEVEL){
			case 1:	//low
				if($host===$lhost
				){$pchk=1;}
				break;
			case 2:	//middle
				if($host===$lhost
				|| ($name===$lname)
				|| ($email===$lemail)
				|| ($url===$lurl)
				|| ($sub===$lsub)
				){$pchk=1;}
				break;
			case 3:	//high
				if($host===$lhost
				|| (similar_str($name,$lname) > VALUE_LIMIT)
				|| (similar_str($email,$lemail) > VALUE_LIMIT)
				|| (similar_str($url,$lurl) > VALUE_LIMIT)
				|| (similar_str($sub,$lsub) > VALUE_LIMIT)
				){$pchk=1;}
				break;
			case 4:	//full
				$pchk=1;
		}
			if($pchk){
			//KASIRAが入らない10桁のUNIX timeを取り出す
			if(strlen($ltime)>10){$ltime=substr($ltime,-13,-3);}
			if(RENZOKU && $time - $ltime < RENZOKU){error(MSG020,$dest);}
			if(RENZOKU2 && $time - $ltime < RENZOKU2 && $upfile_name){error(MSG021,$dest);}
			if($com){
					switch(D_POST_CHECKLEVEL){//190622
						case 1:	//low
							if($com === $lcom){error(MSG022,$dest);}
							break;
						case 2:	//middle
							if(similar_str($com,$lcom) > COMMENT_LIMIT_MIDDLE){error(MSG022,$dest);}
							break;
						case 3:	//high
							if(similar_str($com,$lcom) > COMMENT_LIMIT_HIGH){error(MSG022,$dest);}
							break;
						default:
							if($com === $lcom && !$upfile_name){error(MSG022,$dest);}
					}
				}
			}
		}
		if($i>=$chkline){break;}//チェックする最大行数
	}//ここまで

	// 移動(v1.32)
	if(!$name) $name=DEF_NAME;
	if(!$com) $com=DEF_COM;
	if(!$sub) $sub=DEF_SUB;

	// アップロード処理
	if($dest&&$is_file_dest){//画像が無い時は処理しない
	//画像フォーマット
		$fsize_dest=filesize($dest);
		if($fsize_dest > IMAGE_SIZE * 1024 || $fsize_dest > MAX_KB * 1024){//指定サイズを超えていたら
			if ($im_jpg = png2jpg($dest)) {
				if(filesize($im_jpg)<$fsize_dest){//JPEGのほうが小さい時だけ
					rename($im_jpg,$dest);//JPEGで保存
				} else{//PNGよりファイルサイズが大きくなる時は
					unlink($im_jpg);//作成したJPEG画像を削除
				}
			}
		}
		clearstatcache();
		if(filesize($dest) > MAX_KB * 1024){//ファイルサイズ再チェック
		error(MSG034,$dest);
		}
		$img_type=mime_content_type($dest);//190603

		if (!in_array($img_type, ['image/gif', 'image/jpeg', 'image/png'])) {
			error(MSG004,$dest);
		}

		$chk = md5_file($dest);
		check_badfile($chk, $dest); // 拒絶画像チェック

		chmod($dest,0606);

		list($W, $H) = getimagesize($dest);

		$ext = getImgType($img_type, $dest);

		// 画像表示縮小
		$max_w = $resto ? MAX_RESW : MAX_W;
		$max_h = $resto ? MAX_RESH : MAX_H;
		if($W > $max_w || $H > $max_h){
			$W2 = $max_w / $W;
			$H2 = $max_h / $H;
			($W2 < $H2) ? $key = $W2 : $key = $H2;
			$W = ceil($W * $key);
			$H = ceil($H * $key);
		}
		$mes = "画像 $upfile_name のアップロードが成功しました<br><br>";

		//重複チェック
		$chkline=200;//チェックする最大行数
		$j=1;
		foreach($line as $i => $value){ //画像重複チェック
			if($value!==""){
			list(,,,,,,,,,$extp,,,$timep,$chkp,) = explode(",", $value);
				if($extp){//拡張子があったら
				if($chkp===$chk&&is_file($path.$timep.$extp)){
				error(MSG005,$dest);
				}
				if($j>=20){break;}//画像を20枚チェックしたら
				++$j;
				}
			}
			if($i>=$chkline){break;}//チェックする最大行数
		}

		//PCHファイルアップロード
		if ($pchext = check_pch_ext($temppath.$picfile)) {
			$src = $temppath.$picfile.$pchext;
			$dst = PCH_DIR.$tim.$pchext;
			if(copy($src, $dst)){
				chmod($dst,0606);
				unlink($src);
			}
		}
	} else{//画像が無い時
		$ext=$W=$H=$chk="";
	}
	// ログ行数オーバー
	$countline = count($line);//必要
	if($countline >= LOG_MAX){
		for($d = $countline-1; $d >= LOG_MAX-1; $d--){
			if($line[$d]!==""){
			list($dno,,,,,,,,,$dext,,,$dtime,) = explode(",", $line[$d]);
			delete_files($path, $dtime, $dext);
			$line[$d] = "";
			treedel($dno);
				}
		}
	}
		
	list($lastno,) = explode(",", $line[0]);
	$no = $lastno + 1;
	$newline = "$no,$now,$name,$email,$sub,$com,$url,$host,$pass,$ext,$W,$H,$tim,$chk,$ptime,$fcolor\n";
	$newline.= implode("\n", $line);
	ftruncate($fp,0);
	set_file_buffer($fp, 0);
	rewind($fp);
	fwrite($fp, $newline);

	//ツリー更新
	$find = false;
	$newline = '';
	$tp=fopen(TREEFILE,"r+");
	set_file_buffer($tp, 0);
	flock($tp, LOCK_EX); //*
	rewind($tp);
	$buf=fread($tp,5242880);
	if(!$buf){error(MSG023,$dest);}
	$line = explode("\n", trim($buf));
	foreach($line as $i => $value){
		if($value!==""){
			$j=explode(",", rtrim($value));
			if($lineindex[$j[0]]==0){
				unset($line[$i]);
			}
		}
	}

	if($resto){
		foreach($line as $i => $value){
			$rtno = explode(",", rtrim($value));
			if($rtno[0]==$resto){
				$find = TRUE;
				$line[$i] = rtrim($value).','.$no;
				$j=explode(",", rtrim($line[$i]));
				if(!(stripos($email,'sage')!==false || (count($j)>MAX_RES))){
					$newline=$line[$i] . "\n";
					unset($line[$i]);
				}
				break;
			}
		}
	}

	if(!$find){if(!$resto){$newline="$no\n";}else{error(MSG025,$dest);}}
	$newline.=implode("\n", $line);
	ftruncate($tp,0);
	set_file_buffer($tp, 0);
	rewind($tp);
	fwrite($tp, $newline);
	fflush($tp);
	flock($tp, LOCK_UN);
	fclose($tp);
	fflush($fp);
	flock($fp, LOCK_UN);
	fclose($fp);

	//-- クッキー保存 --
	//漢字を含まない項目はこちらの形式で追加
	setcookie ("pwdc", $c_pass,time()+(SAVE_COOKIE*24*3600));
	setcookie ("fcolorc", $fcolor,time()+(SAVE_COOKIE*24*3600));

	//クッキー項目："クッキー名<>クッキー値"　※漢字を含む項目はこちらに追加 //190528
	$cooks = array("namec<>".$name,"emailc<>".$email,"urlc<>".$url);

	foreach ( $cooks as $cook ) {
		list($c_name,$c_cookie) = explode('<>',$cook);
		setcookie ($c_name, $c_cookie,time()+(SAVE_COOKIE*24*3600));
	}

	if($dest&&$is_file_dest){
		rename($dest,$path.$tim.$ext);
		if(USE_THUMB){thumb($path,$tim,$ext,$max_w,$max_h);}

		//ワークファイル削除
		safe_unlink($upfile);
		safe_unlink($temppath.$picfile.".dat");
	}
	updatelog();

	//メール通知
	if(is_file(NOTICEMAIL_FILE)	//メール通知クラスがある場合
	&& !(NOTICE_NOADMIN && $pwd == $ADMIN_PASS)){//管理者の投稿の場合メール出さない
		require(__DIR__.'/'.NOTICEMAIL_FILE);

		$data['to'] = TO_MAIL;
		$data['name'] = $name;
		$data['email'] = $email;
		$data['option'][] = 'URL,'.$url;
		$data['option'][] = '記事題名,'.$sub;
		if($ext) $data['option'][] = '投稿画像,'.ROOT_URL.IMG_DIR.$tim.$ext;//拡張子があったら
		if(is_file(THUMB_DIR.$tim.'s.jpg')) $data['option'][] = 'サムネイル画像,'.ROOT_URL.THUMB_DIR.$tim.'s.jpg';
		if ($_pch_ext = check_pch_ext(__DIR__.'/'.PCH_DIR.$tim)) {
			$data['option'][] = 'アニメファイル,'.ROOT_URL.PCH_DIR.$tim.$_pch_ext;
		}
		if($resto){
			$data['subject'] = '['.TITLE.'] No.'.$resto.'へのレスがありました';
			$data['option'][] = "\n".'記事URL,'.ROOT_URL.PHP_SELF.'?res='.$resto;
		}else{
			$data['subject'] = '['.TITLE.'] 新規投稿がありました';
			$data['option'][] = "\n".'記事URL,'.ROOT_URL.PHP_SELF.'?res='.$no;
		}

		$data['comment'] = SEND_COM ? preg_replace("#<br(( *)|( *)/)>#i","\n", $com) : '';

		noticemail::send($data);
	}

	redirect(
		PHP_SELF2 . (URL_PARAMETER ? "?{$time}" : ''),
		1,
		$mes . '画面を切り替えます'
	);
}

//ツリー削除
function treedel($delno){
	$fp=fopen(TREEFILE,"r+");
	set_file_buffer($fp, 0);
	flock($fp, LOCK_EX);
	rewind($fp);
	$buf=fread($fp,5242880);
	if(!$buf){error(MSG024);}
	$line = explode("\n", trim($buf));
	$countline=count($line);//必要
	$find=false;
	foreach($line as $i =>$value){
		$treeline = explode(",", rtrim($value));
		foreach($treeline as $j => $value){
			if($value == $delno){
				if($j==0){//スレ削除
					if($countline<3){//スレが1つしかない場合、エラー防止の為に削除不可
						fflush($fp);
						flock($fp, LOCK_UN);
						fclose($fp);
						error(MSG026);
					}else{
						unset($line[$i]);
					}
				}else{//レス削除
					unset($treeline[$j]);
					$line[$i]=implode(',', $treeline);
					$line[$i]=preg_replace("/,,/",",",$line[$i]);
					$line[$i]=preg_replace("/,$/","",$line[$i]);
					if (!$line[$i]) {
						unset($line[$i]);
					}
				}
				$find=true;
				break 2;
			}
		}
	}
	if($find){//ツリー更新
		ftruncate($fp,0);
		set_file_buffer($fp, 0);
		rewind($fp);
		fwrite($fp, implode("\n", $line));
	}
	fflush($fp);
	flock($fp, LOCK_UN);
	fclose($fp);
}

/* テキスト整形 */
function CleanStr($str,$com=''){
	global $admin,$ADMIN_PASS;
	$str = trim($str);//先頭と末尾の空白除去
	if($com){//コメント欄なら
		if($admin!==$ADMIN_PASS){//管理者はタグ許可
			$str = htmlspecialchars($str,ENT_QUOTES,'utf-8');//管理者以外タグ禁止
		}
	} else{//そのほかの入力欄は
		$str = htmlspecialchars($str,ENT_QUOTES,'utf-8');//タグ禁止
	}
	return str_replace(",", "&#44;", $str);//カンマを変換
}

/* ユーザー削除 */
function usrdel($del,$pwd){
	global $path,$pwdc,$onlyimgdel;
	global $ADMIN_PASS;

	if(!is_array($del)){
		return;
	}

	sort($del);
	reset($del);
	if($pwd==""&&$pwdc!="") $pwd=$pwdc;
	$fp=fopen(LOGFILE,"r+");
	set_file_buffer($fp, 0);
	flock($fp, LOCK_EX);
	rewind($fp);
	$buf=fread($fp,5242880);
	if(!$buf){error(MSG027);}
	$buf = charconvert($buf);
	$line = explode("\n", trim($buf));
	$flag = false;
	$find = false;
	foreach($line as $i => $value){//190701
		if($value!==""){
			list($no,,,,,,,$dhost,$pass,$ext,,,$tim,,) = explode(",",$value);

		if(in_array($no,$del) && (password_verify($pwd,$pass)||substr(md5($pwd),2,8) === $pass
		|| $ADMIN_PASS === $pwd)){
			if(!$onlyimgdel){	//記事削除
				treedel($no);
				if(USER_DELETES > 2){
					unset($line[$i]);
					$find = true;
				}
			}
			if(USER_DELETES > 1){
				delete_files($path, $tim, $ext);
				}
				$flag = true;
			}
		}
	}
	if(!$flag)error(MSG028);
	if($find){//ログ更新
		ftruncate($fp,0);
		set_file_buffer($fp, 0);
		rewind($fp);
		$newline = implode("\n", $line);
		fwrite($fp,$newline);
	}
	fflush($fp);
	flock($fp, LOCK_UN);
	fclose($fp);
}

/* パス認証 */
function valid($pass){
	global $ADMIN_PASS;
	if($pass && $pass != $ADMIN_PASS) error(MSG029);

	if(!$pass){
		$dat['admin_in'] = true;
		htmloutput(SKIN_DIR.OTHERFILE,$dat);
		exit;
	}
}

/* 管理者削除 */
function admindel($pass){
	global $path,$onlyimgdel,$del;

	if(is_array($del)){
		sort($del);
		reset($del);
		$fp=fopen(LOGFILE,"r+");
		set_file_buffer($fp, 0);
		flock($fp, LOCK_EX);
		rewind($fp);
		$buf=fread($fp,5242880);
		if(!$buf){error(MSG030);}
		$buf = charconvert($buf);
		$line = explode("\n", trim($buf));
		$find = false;
		foreach($line as $i => $value){
			if($value!==""){
				list($no,,,,,,,,,$ext,,,$tim,,) = explode(",",$value);
			if(in_array($no,$del)){
				if(!$onlyimgdel){	//記事削除
					treedel($no);
					unset($line[$i]);
					$find = true;
				}
				delete_files($path, $tim, $ext);
				}
			}
		}
		if($find){//ログ更新
			ftruncate($fp,0);
			set_file_buffer($fp, 0);
			rewind($fp);
			$newline = implode("\n", $line);
			fwrite($fp,$newline);
		}
		fflush($fp);
		flock($fp, LOCK_UN);
		fclose($fp);
	}
	// 削除画面を表示
	$dat['admin_del'] = true;
	$dat['pass'] = $pass;

	$all = 0;
	$line = file(LOGFILE);
	foreach($line as $j => $value){
		list($no,$now,$name,$email,$sub,$com,$url,
			 $host,$pw,$ext,$w,$h,$time,$chk,) = explode(",",$value);
		// フォーマット
		$now  = preg_replace("/( ID:.*)/","",$now);//ID以降除去
		$name = strip_tags($name);//タグ除去
		if(strlen($name) > 10) $name = mb_strcut($name,0,9).".";
		if(strlen($sub) > 10) $sub = mb_strcut($sub,0,9).".";
		if($email) $name="<a href=\"mailto:$email\">$name</a>";
		$com = preg_replace("{<br(( *)|( *)/)>}i"," ",$com);
		$com = htmlspecialchars($com,ENT_QUOTES,'utf-8');
		if(strlen($com) > 20) $com = mb_strcut($com,0,18) . ".";
		// 画像があるときはリンク
		if($ext && is_file($path.$time.$ext)){
			$clip = "<a href=\"".IMG_DIR.$time.$ext."\" target=\"_blank\" rel=\"noopener\">".$time.$ext."</a><br>";
			$size = filesize($path.$time.$ext);
			$all += $size;	//合計計算
			$chk= substr($chk,0,10);
		}else{
			$clip = "";
			$size = 0;
			$chk= "";
		}
		$bg = ($j % 2) ? ADMIN_DELGUSU : ADMIN_DELKISU;//背景色

		$dat['del'][$j] = compact('bg','no','now','sub','name','com','host','clip','size','chk');
	}
	$dat['all'] = ($all - ($all % 1024)) / 1024;
	htmloutput(SKIN_DIR.OTHERFILE,$dat);
	exit;
}

function init(){
	$err='';

	if(!is_writable(realpath("./")))error("カレントディレクトリに書けません<br>");

	if (!is_file(realpath(LOGFILE))) {
		$now = now_date(time());//日付取得
		if(DISP_ID) $now .= " ID:???";
		$tim = time() . substr(microtime(),2,3);
		$testmes="1,".$now.",".DEF_NAME.",,".DEF_SUB.",".DEF_COM.",,,,,,,".$tim.",,,\n";
		file_put_contents(LOGFILE, $testmes);
		chmod(LOGFILE, 0600);
	}
	$err .= check_file(LOGFILE);

	if (!is_file(realpath(TREEFILE))) {
		file_put_contents(TREEFILE, "1\n");
		chmod(TREEFILE, 0600);
	}
	$err .= check_file(TREEFILE);

	$err .= check_dir(IMG_DIR);
	USE_THUMB && $err .= check_dir(THUMB_DIR);
	USE_PAINT && $err .= check_dir(TEMP_DIR);
	if($err)error($err);
	if(!is_file(realpath(PHP_SELF2)))updatelog();
}

// ディレクトリ存在チェック　なければ作る
function check_dir ($dir, $name = '') {
	return check_path($dir, $name, true);
}
function check_file ($path, $name = '') {
	return check_path($path, $name, false);
}
function check_path ($path, $name, $is_dir = false) {
	!$name && $name = $path;
	if ($is_dir) {
		if (!is_dir($path)) {
			mkdir($path, 0707);
			chmod($path, 0707);
		}
		if (!is_dir($path)) return $name . "がありません<br>";
	} else {
		if (!is_file($path)) return $name . "がありません<br>";
	}
	if (!is_writable($path)) return $name . "を書けません<br>";
	if (!is_readable($path)) return $name . "を読めません<br>";
}

/* お絵描き画面 */
function paintform($picw,$pich,$anime,$pch=""){
	global $admin,$shi,$ctype,$type,$no,$pwd,$ext;
	global $resto,$mode,$quality,$qualitys,$usercode;
	global $useneo; //NEOを使う
	global $ADMIN_PASS;

//pchファイルアップロードペイント
if($admin===$ADMIN_PASS){
	
	$pchfilename = isset($_FILES['pch_upload']['name']) ? $_FILES['pch_upload']['name'] : '';

	if($pchfilename!==""){//空文字でなければ続行
		$pchfilename=CleanStr($pchfilename);
		if (strpos($pchfilename, '/') !== false) { //ファイル名に/があったら中断
			error(MSG015);
		}

		//拡張子チェック
		$tim = time().substr(microtime(),2,3);
		$ext=pathinfo($pchfilename, PATHINFO_EXTENSION);
		$ext=strtolower($ext);//すべて小文字に

		if ($ext == 'pch' || $ext == 'spch') {
			$pchup = TEMP_DIR.'pchup-'.$tim.'-tmp.'.$ext;//アップロードされるファイル名
			$pchtmp=$_FILES['pch_upload']['tmp_name'];
		} else{//拡張子が一致しなかったら
			$pchup="";
			$pchtmp="";
			echo "アニメファイルをアップしてください。";
		}

		if(move_uploaded_file($pchtmp, $pchup)){//アップロード成功なら続行
			$pchup=TEMP_DIR.basename($pchup);//ファイルを開くディレクトリを固定
			if(mime_content_type($pchup)==="application/octet-stream"){//mimetypeが正しければ続行
				$fp = fopen("$pchup", "rb");
				$line = bin2hex(fgets($fp ,4096)) ;
				if($ext==="pch"){
					$line = substr($line,0,6);
					if($line==="4e454f"){
						$useneo=true;
					} else{//NEOのpchでなければ
						echo"NEOのPCHではありません。";
						unlink($pchup);
					}
				} elseif($ext==="spch"){
					$line = substr($line,0,24);
					if($line==="6c617965725f636f756e743d"||$line==="000d0a"){
						$useneo=false;
					}else{//しぃぺのspchでなければ
						echo"しぃペインターのSPCHではありません。";
						unlink($pchup);
					}
				}
				fclose($fp);
				$dat['pchfile'] = $pchup;
			} else{//mime_content_typeが違ったら
				unlink($pchup);
				echo"アニメファイルをアップしてください。";
				// error(MSG001);
			}
		}
	}
}
//pchファイルアップロードペイントここまで

	if($picw < 300) $picw = 300;
	if($pich < 300) $pich = 300;
	if($picw > PMAX_W) $picw = PMAX_W;
	if($pich > PMAX_H) $pich = PMAX_H;
	if(!$useneo && $shi){
	$w = $picw + 510;//しぃぺの時の幅
	$h = $pich + 120;//しぃぺの時の高さ
	} else{
		$w = $picw + 150;//PaintBBSの時の幅
		$h = $pich + 172;//PaintBBSの時の高さ
	}
	// if($w < 400){$w = 400;}//PaintBBSの時の最低幅
	if($h < 560){$h = 560;}//共通の最低高
	//NEOを使う時はPaintBBSの設定
	// if($w < 610 && !$useneo && $shi){$w = 610;}//しぃぺの時の最低幅
	// if($h < 520 && !$useneo && $shi){$h = 520;}

	$dat['paint_mode'] = true;

	//ピンチイン
	if($picw>=700){//横幅700以上だったら
		$dat['pinchin'] = true;
	} elseif($picw>=500) {//横幅500以上だったら
		if (strpos($_SERVER['HTTP_USER_AGENT'],'iPad') === false){//iPadじゃなかったら
			$dat['pinchin'] = (strpos($_SERVER['HTTP_USER_AGENT'],'Mobile') !== false);
		}
	}
	
	$dat = array_merge($dat,form($resto));
		$dat['mode2'] = $mode;
	if($mode==="contpaint"){
		$dat['no'] = $no;
		$dat['pch'] = $pch;
		$dat['ctype'] = $ctype;
		$dat['type'] = $type;
		$dat['pwd'] = $pwd;
		$dat['ext'] = $ext;
		if(is_file(PCH_DIR.$pch.'.pch')){
			$dat['applet'] = false;
		}elseif(is_file(PCH_DIR.$pch.'.spch')){
			$dat['applet'] = true;
			$dat['usepbbs'] = false;
		}elseif(is_file(IMG_DIR.$pch.$ext)){
			$dat['applet'] = true;
			$dat['usepbbs'] = true;
		}
		if((C_SECURITY_CLICK || C_SECURITY_TIMER) && SECURITY_URL){
			$dat['security'] = true;
			$dat['security_click'] = C_SECURITY_CLICK;
			$dat['security_timer'] = C_SECURITY_TIMER;
		}
	}else{
		if((SECURITY_CLICK || SECURITY_TIMER) && SECURITY_URL){
			$dat['security'] = true;
			$dat['security_click'] = SECURITY_CLICK;
			$dat['security_timer'] = SECURITY_TIMER;
		}
		$dat['newpaint'] = true;
	}
	$dat['security_url'] = SECURITY_URL;

	$savetype = filter_input(INPUT_POST, 'savetype'); // JPEG or PNG or AUTO or それ以外 が来ることを想定
	$dat['image_jpeg'] = in_array($savetype, ['JPEG', 'AUTO']);
	$dat['image_size'] = in_array($savetype, ['PNG', 'AUTO']) ? IMAGE_SIZE : ($savetype == 'JPEG' ? 1 : 0);
	$dat['savetypes']
		= '<option value="AUTO"' . ($savetype == 'AUTO' ? ' selected' : '') . '>AUTO</option>'
		. '<option value="PNG"' . ($savetype == 'PNG' ? ' selected' : '') . '>PNG</option>'
		. '<option value="JPEG"' . ($savetype == 'JPEG' ? ' selected' : '') . '>JPEG</option>';

	$dat['compress_level'] = COMPRESS_LEVEL;
	$dat['layer_count'] = LAYER_COUNT;
	if($shi) $dat['quality'] = $quality ? $quality : $qualitys[0];
	//NEOを使う時はPaintBBSの設定
	if(!$useneo && $shi==1){ $dat['normal'] = true; }
	elseif(!$useneo && $shi==2){ $dat['pro'] = true; }
	else{ $dat['paintbbs'] = true; }

	$initial_palette = 'Palettes[0] = "#000000\n#FFFFFF\n#B47575\n#888888\n#FA9696\n#C096C0\n#FFB6FF\n#8080FF\n#25C7C9\n#E7E58D\n#E7962D\n#99CB7B\n#FCECE2\n#F9DDCF";';
	$pal=array();
	$DynP=array();
	$p_cnt=1;
	$lines = file(PALETTEFILE);
	foreach ( $lines as $line ) {
		$line=preg_replace("/[\t\r\n]/","",$line);
		list($pid,$pname,$pal[0],$pal[2],$pal[4],$pal[6],$pal[8],$pal[10],$pal[1],$pal[3],$pal[5],$pal[7],$pal[9],$pal[11],$pal[12],$pal[13]) = explode(",", $line);
		$DynP[]=CleanStr($pname);
		$palettes = 'Palettes['.$p_cnt.'] = "#'.$pal[0];
		ksort($pal);
		array_shift($pal);
		foreach ( $pal as $p ) {
			$palettes.='\n#'.$p;
		}
		$palettes.='";';//190622
		$arr_pal[$p_cnt] = $palettes;
		$p_cnt++;
	}
	$dat['palettes']=$initial_palette.implode('',$arr_pal);

	$dat['w'] = $w;
	$dat['h'] = $h;
	$dat['picw'] = $picw;
	$dat['pich'] = $pich;
	$dat['stime'] = time();
	if($pwd){
	$pwd=openssl_encrypt ($pwd,CRYPT_METHOD, CRYPT_PASS, true, CRYPT_IV);//暗号化
	$pwd=bin2hex($pwd);//16進数に
	}
	$resto = ($resto) ? '&amp;resto='.$resto : '';
	$dat['mode'] = 'piccom'.$resto;
	$dat['animeform'] = true;
	$dat['anime'] = ($anime) ? true : false;
	if($ctype=='pch'){
		if ($_pch_ext = check_pch_ext(__DIR__.'/'.PCH_DIR.$pch)) {
			$dat['pchfile'] = './'.PCH_DIR.$pch.$_pch_ext;
		}
	}
	if($ctype=='img'){
		$dat['animeform'] = false;
		$dat['anime'] = false;
		$dat['imgfile'] = './'.PCH_DIR.$pch.$ext;
	}

	$dat['palsize'] = count($DynP) + 1;
	foreach ($DynP as $p){
		$arr_dynp[] = '<option>'.$p.'</option>';
	}
	$dat['dynp']=implode('',$arr_dynp);
	$dat['usercode'] = $usercode;

	$dat['useneo'] = $useneo; //NEOを使う

	//差し換え時の認識コード追加
	if($type==='rep'){
		$time=time();
		$userip = get_uip();
		$repcode = substr(crypt(md5($no.$userip.$pwd.date("Ymd", $time)),$time),-8);
		//念の為にエスケープ文字があればアルファベットに変換
		$repcode = strtr($repcode,"!\"#$%&'()+,/:;<=>?@[\\]^`/{|}~","ABCDEFGHIJKLMNOabcdefghijklmn");
		$dat['mode'] = 'picrep&amp;no='.$no.'&amp;pwd='.$pwd.'&amp;repcode='.$repcode;
		$dat['usercode'] = $usercode.'&amp;repcode='.$repcode;
	}
	htmloutput(SKIN_DIR.PAINTFILE,$dat);
}

/* お絵かきコメント */
function paintcom($resto=''){
	global $admin,$usercode;
	$userip = get_uip();
	
	if(USE_RESUB && $resto) {
		$lines = file(LOGFILE);
		$flag = FALSE;
		foreach($lines as $line){
			list($cno,,,,$sub,,,,,,,,,,) = explode(",", charconvert($line));
			if($cno == $resto){
				$dat['sub'] = 'Re: '.$sub;
				$flag = TRUE;
				break;
			}
		}
		if(!$flag) $resto=''; //スレが削除されていた場合、新規投稿
	}

	//テンポラリ画像リスト作成
	$tmplist = array();
	$handle = opendir(TEMP_DIR);
	while ($file = readdir($handle)) {
		if(!is_dir($file) && preg_match("/\.(dat)$/i",$file)) {
			$fp = fopen(TEMP_DIR.$file, "r");
			$userdata = fread($fp, 1024);
			fclose($fp);
			list($uip,$uhost,$uagent,$imgext,$ucode,) = explode("\t", rtrim($userdata));
			$file_name = preg_replace("/\.(dat)$/i","",$file);
			if(is_file(TEMP_DIR.$file_name.$imgext)) //画像があればリストに追加
				$tmplist[] = $ucode."\t".$uip."\t".$file_name.$imgext;
		}
	}
	closedir($handle);
	$tmp = array();
	if(count($tmplist)!=0){
		//user-codeでチェック
		foreach($tmplist as $tmpimg){
			list($ucode,$uip,$ufilename) = explode("\t", $tmpimg);
			if($ucode == $usercode)
				$tmp[] = $ufilename;
		}
		//user-codeでhitしなければIPで再チェック
		if(count($tmp)==0){
			foreach($tmplist as $tmpimg){
				list($ucode,$uip,$ufilename) = explode("\t", $tmpimg);
				if(!IP_CHECK || $uip == $userip)
					$tmp[] = $ufilename;
			}
		}
	}

	$dat['post_mode'] = true;
	$dat['regist'] = true;
	if(IP_CHECK) $dat['ipcheck'] = true;
	if(count($tmp)==0){
		$dat['notmp'] = true;
		$dat['pictmp'] = 1;
	}else{
		$dat['pictmp'] = 2;
		sort($tmp);
		reset($tmp);
		foreach($tmp as $tmpfile){
			$src = TEMP_DIR.$tmpfile;
			$srcname = $tmpfile;
			$date = date("Y/m/d H:i", filemtime($src));
			$dat['tmp'][] = compact('src','srcname','date');
		}
	}

	$dat = array_merge($dat,form($resto,'',$tmp));

	htmloutput(SKIN_DIR.OTHERFILE,$dat);
}

/* 動画表示 */
function openpch($pch){

	$_pch = pathinfo($pch, PATHINFO_FILENAME); //拡張子除去

	if ($ext = check_pch_ext(PCH_DIR . $_pch)) {
		$dat['pchfile'] = './' . PCH_DIR . $_pch . $ext;
		if ($ext == '.spch') {
			$dat['normal'] = true;
		} elseif ($ext == '.pch') {
			$dat['paintbbs'] = true;

			//neoのpchかどうか調べる
			$fp = fopen($dat['pchfile'], "rb");
			$dat['type_neo'] = (fread($fp,3)==="NEO"); //先頭3byteを見る
			fclose($fp);
		}

		$dat['datasize'] = filesize($dat['pchfile']);
		list($dat['picw'], $dat['pich']) = getimagesize(IMG_DIR.$pch);
		$dat['w'] = ($dat['picw'] < 200 ? 200 : $dat['picw']);
		$dat['h'] = ($dat['pich'] < 200 ? 200 : $dat['pich']) + 26;

	} else {
		//動画が無い時は処理しない
	}

	$dat['pch_mode'] = true;
	$dat['speed'] = PCH_SPEED;
	$dat['stime'] = time();
	htmloutput(SKIN_DIR.PAINTFILE,$dat);
}

/* テンポラリ内のゴミ除去 */
function deltemp(){
	$handle = opendir(TEMP_DIR);
	while ($file = readdir($handle)) {
		if(!is_dir($file)) {
			$lapse = time() - filemtime(TEMP_DIR.$file);
			if($lapse > (TEMP_LIMIT*24*3600)){
				unlink(TEMP_DIR.$file);
			}
			//pchアップロードペイントファイル削除
			if(preg_match("/\A(pchup-.*-tmp\.s?pch)\z/i",$file)) {
				$lapse = time() - filemtime(TEMP_DIR.$file);
				if($lapse > (300)){//5分
					unlink(TEMP_DIR.$file);
				}
			}
		}
	}
	
	closedir($handle);
}

/* コンティニュー前画面 */
function incontinue(){
	global $addinfo;

	$no = filter_input(INPUT_GET, 'no',FILTER_VALIDATE_INT);

	$lines = file(LOGFILE);
	$flag = FALSE;
	foreach($lines as $line){
		list($cno,,,,,,,,,$cext,$picw,$pich,$ctim,,$cptime,) = explode(",", rtrim($line));
		if($cno == $no){
			$flag = TRUE;
			break;
		}
	}
	if(!$flag) error(MSG001);

	$dat['continue_mode'] = true;
//コンティニュー時は削除キーを常に表示
	$dat['passflag'] = true;
//新規投稿で削除キー不要の時 true
	if(! CONTINUE_PASS) $dat['newpost_nopassword'] = true;
	if($cext && is_file(IMG_DIR.$ctim.$cext)){//画像が無い時は処理しない
	$dat['picfile'] = IMG_DIR.$ctim.$cext;
	list($dat['picw'], $dat['pich']) = getimagesize($dat['picfile']);
	$dat['no'] = $no;
	$dat['pch'] = $ctim;
	$dat['ext'] = $cext;
	//描画時間
	if(DSP_PAINTTIME) $dat['painttime'] = $cptime;
	if(is_file(PCH_DIR.$ctim.'.pch')){
		$dat['applet'] = false;
		$dat['ctype_pch'] = true;
	}elseif(is_file(PCH_DIR.$ctim.'.spch')){
		$dat['applet'] = true;
		$dat['usepbbs'] = false;
		$dat['ctype_pch'] = true;
	}else{//画像しか無かった時
		$dat['applet'] = true;
		$dat['usepbbs'] = true;
	}
	}else{//画像が無かった時
	$dat['picfile'] = '';
	$dat['picw'] = '';
	$dat['pich'] = '';
	$dat['no'] = '';
	$dat['pch'] = '';
	$dat['ext'] = '';
	$dat['applet'] = false;
	$dat['usepbbs'] = false;
	$dat['ctype_pch'] = false;
	}
	$dat['ctype_img'] = true;
	$dat['addinfo'] = $addinfo;

	htmloutput(SKIN_DIR.PAINTFILE,$dat);
}

/* コンティニュー認証 */
function usrchk($no,$pwd){
	$lines = file(LOGFILE);
	$flag = FALSE;
	foreach($lines as $line){
		list($cno,,,,,,,,$cpwd,) = explode(",", $line);
		if($cno == $no && (password_verify($pwd,$cpwd)||substr(md5($pwd),2,8) === $cpwd)){
			$flag = TRUE;
			break;
		}
	}
	if(!$flag) error(MSG028);
}

/* 編集画面 */
function editform($del,$pwd){
	global $pwdc,$addinfo;
	global $fontcolors;
	global $ADMIN_PASS;

	if (!is_array($del)) {
		error(MSG031);
	}

	sort($del);
	reset($del);
	if($pwd==""&&$pwdc!="") $pwd=$pwdc;
	$fp=fopen(LOGFILE,"r");
	flock($fp, LOCK_EX);
	$buf=fread($fp,5242880);
	fflush($fp);
	flock($fp, LOCK_UN);
	fclose($fp);
	if(!$buf){error(MSG019);}
	$buf = charconvert($buf);
	$line = explode("\n", trim($buf));
	$flag = FALSE;
	foreach($line as $value){
		if($value){
			list($no,,$name,$email,$sub,$com,$url,$ehost,$pass,,,,,,,$fcolor) = explode(",", rtrim($value));
			if ($no == $del[0] && (password_verify($pwd,$pass) || substr(md5($pwd),2,8) === $pass|| $ADMIN_PASS === $pwd)){
				$flag = TRUE;
				break;
			}
		}
	}
	if(!$flag) {
		error(MSG028);
	}

	$dat['post_mode'] = true;
	$dat['rewrite'] = $no;
	if($ADMIN_PASS == $pwd) $dat['admin'] = $ADMIN_PASS;
	$dat['maxbyte'] = MAX_KB * 1024;
	$dat['maxkb']   = MAX_KB;
	$dat['addinfo'] = $addinfo;
	$dat['name'] = strip_tags($name);
	$dat['email'] = $email;
	$dat['sub'] = $sub;
	$com = preg_replace("{<br(( *)|( *)/)>}i","\n",$com); // <br>または<br />を改行へ戻す
	$dat['com'] = $com;
	$dat['url'] = $url;
	$dat['pwd'] = $pwd;

	//文字色
	if(USE_FONTCOLOR){
		foreach ( $fontcolors as $fontcolor ){
			list($color,$name) = explode(",", $fontcolor);
			$chk = ($color == $fcolor) ? true : false;
			$dat['fctable'][] = compact('color','name','chk');
		}
		if(!$fcolor) $dat['fctable'][0]['chk'] = true; //値が無い場合、先頭にチェック
	}

	htmloutput(SKIN_DIR.OTHERFILE,$dat);
}

/* 記事上書き */
function rewrite($no,$name,$email,$sub,$com,$url,$pwd,$admin){
	global $badstring;
	global $fcolor,$badstr_A,$badstr_B,$badname;
	global $ADMIN_PASS;
	$userip = get_uip();
	
	// 時間
	$time = time();

	$dest="";

	$REQUEST_METHOD = isset($_SERVER["REQUEST_METHOD"]) ? $_SERVER["REQUEST_METHOD"] : "";
	if($REQUEST_METHOD !== "POST") error(MSG006);

	//チェックする項目から改行・スペース・タブを消す
	$chk_com  = preg_replace("/\s/u", "", $com );
	$chk_name = preg_replace("/\s/u", "", $name );
	$chk_sub = preg_replace("/\s/u", "", $sub );
	$chk_email = preg_replace("/\s/u", "", $email );

	//本文に日本語がなければ拒絶
	if (USE_JAPANESEFILTER) {
		mb_regex_encoding("UTF-8");
		if (strlen($com) > 0 && !preg_match("/[ぁ-んァ-ヶー一-龠]+/u",$chk_com)) error(MSG035,$dest);
	}

	//本文へのURLの書き込みを禁止
	if(!($pwd===$ADMIN_PASS||$admin===$ADMIN_PASS)){//どちらも一致しなければ
		if(DENY_COMMENTS_URL && preg_match('/:\/\/|\.co|\.ly|\.gl|\.net|\.org|\.cc|\.ru|\.su|\.ua|\.gd/i', $com)) error(MSG036,$dest);
	}

	// 使えない文字チェック
	if (is_ngword($badstring, [$chk_com, $chk_sub, $chk_name, $chk_email])) {
		error(MSG032,$dest);
	}

	// 使えない名前チェック
	if (is_ngword($badname, $chk_name)) {
		error(MSG037,$dest);
	}

	//指定文字列が2つあると拒絶
	$bstr_A_find = is_ngword($badstr_A, [$chk_com, $chk_sub, $chk_name, $chk_email]);
	$bstr_B_find = is_ngword($badstr_B, [$chk_com, $chk_sub, $chk_name, $chk_email]);
	if($bstr_A_find && $bstr_B_find){
		error(MSG032,$dest);
	}

	// フォーム内容をチェック
	if(!$name||preg_match("/\A\s*\z/u",$name)) $name="";
	if(!$com||preg_match("/\A\s*\z/u",$com)) $com="";
	if(!$sub||preg_match("/\A\s*\z/u",$sub))   $sub="";
	if(!$email||preg_match("/\A\s*\z|&lt;|</ui",$email)) $email="";
	if(!$url||!preg_match("/\A *https?:\/\//",$url)||preg_match("/&lt;|</i",$url)) $url="";

	if(strlen($com) > MAX_COM) error(MSG011);
	if(strlen($name) > MAX_NAME) error(MSG012);
	if(strlen($email) > MAX_EMAIL) error(MSG013);
	if(strlen($sub) > MAX_SUB) error(MSG014);

	//ホスト取得
	$host = gethostbyaddr($userip);
	check_badip($host);

	// 時間とURLフォーマット
	$now = now_date($time);//日付取得
	$now .= UPDATE_MARK;
	if(DISP_ID){
			$now.=" ID:".substr(crypt(md5($userip.ID_SEED.date("Ymd", $time)),'id'),-8);
	}
	$now = str_replace(",", "&#44;", $now);//カンマを変換
	//テキスト整形
	$email=strip_tags($email);
	$email= CleanStr($email); 
	$email=preg_replace("/[\r\n]/","",$email);
	$sub  = CleanStr($sub);
	$sub  =preg_replace("/[\r\n]/","",$sub);
	$url  = CleanStr($url);
	$url  =preg_replace("/[\r\n]/","",$url);
	$url  = str_replace(" ", "", $url);
	$com  = CleanStr($com,true);
	$pwd= CleanStr($pwd);
	$pwd=preg_replace("/[\r\n]/","",$pwd);
	//管理モードで使用できるタグを制限
	if(preg_match('/< *?script|< *?\? *?php|< *?img|< *?a  *?onmouseover|< *?iframe|< *?frame|< *?div|< *?table|< *?meta|< *?base|< *?object|< *?embed|< *?input|< *?body|< *?style/i', $com)) error(MSG038,$dest);

	// 改行文字の統一。
	$com = str_replace("\r\n", "\n", $com);
	$com = str_replace("\r", "\n", $com);
	// 連続する空行を一行
	$com = preg_replace("#\n((　| )*\n){3,}#","\n",$com);
	$com = nl2br($com);		//改行文字の前に<br>を代入する
	$com = str_replace("\n", "", $com);	//\nを文字列から消す

	$name=preg_replace("/◆/","◇",$name);
	$name=preg_replace("/[\r\n]/","",$name);
	$name = CleanStr($name);

	//ログ読み込み
	$fp=fopen(LOGFILE,"r+");
	flock($fp, LOCK_EX);
	rewind($fp);
	$buf=fread($fp,5242880);
	if(!$buf){error(MSG019);}
	$buf = charconvert($buf);
	$line = explode("\n", trim($buf));

	// 記事上書き
	$flag = FALSE;
	foreach($line as $i => $value){
		list($eno,,$ename,,$esub,$ecom,$eurl,$ehost,$epwd,$ext,$W,$H,$tim,$chk,$ptime,$efcolor) = explode(",", rtrim($value));
	//		if($eno == $no && ($pass == $epwd /*|| $ehost == $host*/ || $ADMIN_PASS == $admin)){
		if($eno == $no && (password_verify($pwd,$epwd) ||$epwd=== substr(md5($pwd),2,8)|| $ADMIN_PASS === $admin)){
			if(!$name) $name = $ename;
			if(!$sub)  $sub  = $esub;
			if(!$com)  $com  = $ecom;
			if(!$fcolor) $fcolor = $efcolor;
			$line[$i] = "$no,$now,$name,$email,$sub,$com,$url,$host,$epwd,$ext,$W,$H,$tim,$chk,$ptime,$fcolor";
			$flag = TRUE;
			break;
		}
	}
	if(!$flag){
		fflush($fp);
		flock($fp, LOCK_UN);
		fclose($fp);
		error(MSG028);
	}

	ftruncate($fp,0);
	set_file_buffer($fp, 0);
	rewind($fp);
	$newline = implode("\n", $line);
	fwrite($fp, $newline);
	fflush($fp);
	flock($fp, LOCK_UN);
	fclose($fp);

	updatelog();

	redirect(
		PHP_SELF2 . (URL_PARAMETER ? "?{$time}" : ''),
		1,
		'画面を切り替えます'
	);
}

/* 画像差し換え */
function replace($no,$pwd,$stime){
	global $path,$temppath,$repcode;
	$userip = get_uip();
	$mes="";
	
	//ホスト取得
	$host = gethostbyaddr($userip);
	check_badip($host);

	/*--- テンポラリ捜査 ---*/
	$find=false;
	$handle = opendir(TEMP_DIR);
	while ($file = readdir($handle)) {
		if(!is_dir($file) && preg_match("/\.(dat)$/i",$file)) {
			$fp = fopen(TEMP_DIR.$file, "r");
			$userdata = fread($fp, 1024);
			fclose($fp);
			list($uip,$uhost,$uagent,$imgext,$ucode,$urepcode) = explode("\t", rtrim($userdata)."\t");//区切りの"\t"を行末に190610
			$file_name = preg_replace("/\.(dat)$/i","",$file);
			//画像があり、認識コードがhitすれば抜ける
			if($file_name && is_file(TEMP_DIR.$file_name.$imgext) && $urepcode === $repcode){$find=true;break;}
		}
	}
	closedir($handle);
	if(!$find){
	header("Content-type: text/html; charset=UTF-8");
		$str = '<!DOCTYPE html>'."\n".'<html lang="ja"><head><meta name="robots" content="noindex,nofollow"><title>画像が見当たりません</title>'."\n";
		$str.= '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">'."\n".'<meta charset="UTF-8"></head>'."\n";
		$str.= '<body>画像が見当たりません。数秒待ってリロードしてください。<BR><BR>リロードしてもこの画面がでるなら投稿に失敗している可能性があります。<BR>※諦める前に「<A href="'.PHP_SELF.'?mode=piccom">アップロード途中の画像</A>」を見ましょう。もしかしたら画像が見つかるかもしれません。</body></html>';
		echo $str;
		exit;
	}

	// 時間
	$time = time();
	$tim = KASIRA.$time.substr(microtime(),2,3);
	$now = now_date($time);//日付取得
	$now .= UPDATE_MARK;
	//描画時間
	if($stime && DSP_PAINTTIME){
		$ptime = calcPtime($stime);
	}

	//ログ読み込み
	$fp=fopen(LOGFILE,"r+");
	flock($fp, LOCK_EX);
	rewind($fp);
	$buf=fread($fp,5242880);
	if(!$buf){error(MSG019);}
	$buf = charconvert($buf);
	$line = explode("\n", trim($buf));

	// 記事上書き
	$flag = false;
	$pwd=hex2bin($pwd);//バイナリに
	$pwd=openssl_decrypt($pwd,CRYPT_METHOD, CRYPT_PASS, true, CRYPT_IV);//復号化

	foreach($line as $i => $value){
		list($eno,,$name,$email,$sub,$com,$url,$ehost,$epwd,$ext,$W,$H,$etim,,$eptime,$fcolor) = explode(",", rtrim($value));
	//		if($eno == $no && ($pwd == $epwd /*|| $ehost == $host*/ || $pwd == substr(md5($ADMIN_PASS),2,8))){
	//画像差し替えに管理パスは使っていない
		if($eno == $no && (password_verify($pwd,$epwd)||$epwd=== substr(md5($pwd),2,8))){
			$upfile = $temppath.$file_name.$imgext;
			$dest = $path.$tim.'.tmp';
			copy($upfile, $dest);
			
			if(!is_file($dest)) error(MSG003,$dest);
			$fsize_dest=filesize($dest);
			if($fsize_dest > IMAGE_SIZE * 1024 || $fsize_dest > MAX_KB * 1024){//指定サイズを超えていたら
				if ($im_jpg = png2jpg($dest)) {
					if(filesize($im_jpg)<$fsize_dest){//JPEGのほうが小さい時だけ
						rename($im_jpg,$dest);//JPEGで保存
					} else{//PNGよりファイルサイズが大きくなる時は
						unlink($im_jpg);//作成したJPEG画像を削除
					}
				}
			}
		
			$img_type=mime_content_type($dest);
			if (!in_array($img_type, ['image/gif', 'image/jpeg', 'image/png'])) {
				error(MSG004,$dest);
			}

			$chk = md5_file($dest);
			check_badfile($chk, $dest); // 拒絶画像チェック

			$imgext = getImgType($img_type, $dest);
	
			chmod($dest,0606);
			list($W, $H) = getimagesize($dest);
			rename($dest,$path.$tim.$imgext);
			$mes = "画像のアップロードが成功しました<br><br>";

			//サムネイル作成
			if(USE_THUMB) thumb($path,$tim,$imgext,$W,$H);
			//ワークファイル削除
			safe_unlink($upfile);
			safe_unlink($temppath.$file_name.".dat");
			//PCHファイルアップロード
			// .pch, .spch, ブランク どれかが返ってくる
			if ($pchext = check_pch_ext($temppath . $file_name)) {
				$src = $temppath . $file_name . $pchext;
				$dst = PCH_DIR . $tim . $pchext;
				if(copy($src, $dst)){
					chmod($dst, 0606);
					unlink($src);
				}
			}

			//旧ファイル削除
			delete_files($path, $etim, $ext);
			
			//ID付加
			if(DISP_ID){
					$now.=" ID:".substr(crypt(md5($userip.ID_SEED.date("Ymd", $time)),'id'),-8);
			}
			//描画時間追加
			if($eptime) $ptime=$eptime.'+'.$ptime;
			//カンマを変換
			$now = str_replace(",", "&#44;", $now);
			$ptime = str_replace(",", "&#44;", $ptime);

			$line[$i] = "$no,$now,".strip_tags($name).",$email,$sub,$com,$url,$host,$epwd,$imgext,$W,$H,$tim,$chk,$ptime,$fcolor";
			$flag = true;
			break;
		}
	}
	if(!$flag){
		fflush($fp);
		flock($fp, LOCK_UN);
		fclose($fp);
		error(MSG028);
	}

	ftruncate($fp,0);
	set_file_buffer($fp, 0);
	rewind($fp);
	$newline = implode("\n", $line);
	fwrite($fp, $newline);
	fflush($fp);
	flock($fp, LOCK_UN);
	fclose($fp);

	updatelog();

	redirect(
		PHP_SELF2 . (URL_PARAMETER ? "?{$time}" : ''),
		1,
		$mes . '画面を切り替えます'
	);
}

/* カタログ */
function catalog(){
	global $path;

	$page = filter_input_default(INPUT_GET, 'page',FILTER_VALIDATE_INT, 0);

	$line = file(LOGFILE);
	foreach($line as $i =>$value){
		list($no,) = explode(",", $value);
		$lineindex[$no]=$i + 1; //逆変換テーブル作成
	}

	$tree = file(TREEFILE);
	$counttree = count($tree);
	$x = 0;
	$y = 0;
	$pagedef = CATALOG_X * CATALOG_Y;//1ページに表示する件数
	$dat = form();
	for($i = $page; $i < $page+$pagedef; ++$i){
		//空文字ではなく未定義になっている
		if(!isset($tree[$i])){
			$dat['y'][$y]['x'][$x]['noimg'] = true;
		}else{
			$treeline = explode(",", rtrim($tree[$i]));
			$disptree = $treeline[0];
			$j=$lineindex[$disptree] - 1; //該当記事を探して$jにセット
			if($line[$j]==="") continue; //$jが範囲外なら次の行

			$res = create_res($path, $line[$j]);

			// カタログ専用ロジック
			if ($res['img_file_exists']) {
				if($res['w'] && $res['h']){
					if($res['w'] > CATALOG_W){
						$res['h'] = ceil($res['h'] * (CATALOG_W / $res['w']));//端数の切り上げ
						$res['w'] = CATALOG_W; //画像幅を揃える
					}
				}else{//ログに幅と高さが記録されていない時
					$res['w'] = CATALOG_W;
					$res['h'] = null;
				}
			}
			
			$res['txt'] = !$res['img_file_exists']; // 画像が無い時
			$res['rescount'] = count($treeline) - 1;

			// 記事格納
			$dat['y'][$y]['x'][$x] = $res;
		}

		$x++;
		if($x == CATALOG_X){$y++; $x=0;}
	}

	$prev = $page - $pagedef;
	$next = $page + $pagedef;
	// 改ページ処理
	if($prev >= 0) $dat['prev'] = PHP_SELF.'?mode=catalog&amp;page='.$prev;
	$paging = "";

	//表示しているページが20ページ以上または投稿数が少ない時はページ番号のリンクを制限しない
	$showAll = ($counttree <= $pagedef * 21 || $i >= $pagedef * 22);

	for($i = 0; $i < ($showAll ? $counttree : $pagedef * 22) ; $i += $pagedef){
		$pn = $i / $pagedef;
		$paging .= ($page === $i)
			? str_replace("<PAGE>", $pn, NOW_PAGE)
			: str_replace("<PURL>", PHP_SELF."?mode=catalog&amp;page=".$i,
				str_replace("<PAGE>", $showAll || $i !== $pagedef * 21 ? $pn : "≫", OTHER_PAGE));
	}

	//改ページ分岐ここまで
	
	$dat['paging'] = $paging;
	if($counttree > $next){
		$dat['next'] = PHP_SELF.'?mode=catalog&amp;page='.$next;
	}

	htmloutput(SKIN_DIR.CATALOGFILE,$dat);
}

/* 文字コード変換 */
function charconvert($str){
	mb_language(LANG);
		return mb_convert_encoding($str, "UTF-8", "auto");
}

/* HTML出力 */
function htmloutput($template,$dat,$buf_flag=''){
	global $Skinny;
	$dat += basicpart();//basicpart()で上書きしない
	//array_merge()ならbasicpart(),$datの順
	if($buf_flag){
		$buf=$Skinny->SkinnyFetchHTML($template, $dat );
		return $buf;
	}else{
		if(USE_DUMP_FOR_DEBUG){//Skinnyで出力する前にdump
			var_dump($dat);
			if(USE_DUMP_FOR_DEBUG==='2'){
				exit;
			}
		}
		$Skinny->SkinnyDisplay( $template, $dat );
	}
}

function redirect ($url, $wait = 0, $message = '') {
	header("Content-type: text/html; charset=UTF-8");
	echo '<!DOCTYPE html>'
		. '<html lang="ja"><head>'
		. '<meta http-equiv="refresh" content="' . $wait . '; URL=' . $url . '">'
		. '<meta name="robots" content="noindex,nofollow">'
		. '<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">'
		. '<meta charset="UTF-8"><title></title></head>'
		. '<body>' . $message . '</body></html>';
	exit;
}

function getImgType ($img_type, $dest) {
	switch ($img_type) {
		case "image/gif" : return ".gif";
		case "image/jpeg" : return ".jpg";
		case "image/png" : return ".png";
	}
	error(MSG004, $dest);
	exit;
}

/**
 * 日付とIDを分離
 * @param $now
 * @return array
 */
function separateDatetimeAndId ($now) {
	if (preg_match("/( ID:)(.*)/", $now, $regs)){
		return [$regs[2], preg_replace("/( ID:.*)/","",$now)];
	}
	return ['', $now];
}

/**
 * 名前とトリップを分離
 * @param $name
 * @return array
 */
function separateNameAndTrip ($name) {
	$name=strip_tags($name);//タグ除去
	if(preg_match("/(◆.*)/", $name, $regs)){
		return [preg_replace("/(◆.*)/","",$name), $regs[1]];
	}
	return [$name, ''];
}

/**
 * 日付と編集マークを分離
 * @param $now
 * @return array
 */
function separateDatetimeAndUpdatemark ($now) {
	if (UPDATE_MARK && strpos($now, UPDATE_MARK) !== false){
		return [str_replace(UPDATE_MARK,"",$now), UPDATE_MARK];
	}
	return [$now, ''];
}

/**
 * 描写時間を計算
 * @param $stime
 * @return string
 */
function calcPtime ($stime) {

	$psec = time() - $stime;

	$D = floor($psec / 86400);
	$H = floor($psec % 86400 / 3600);
	$M = floor($psec % 3600 / 60);
	$S = $psec % 60;

	return
		($D ? $D . PTIME_D : '')
		. ($H ? $H . PTIME_H : '')
		. ($M ? $M . PTIME_M : '')
		. ($S ? $S . PTIME_S : '');
}

/**
 * pchかspchか、それともファイルが存在しないかチェック
 * @param $filepath
 * @return string
 */
function check_pch_ext ($filepath) {
	if (is_file($filepath . ".pch")) {
		return ".pch";
	} elseif (is_file($filepath . ".spch")) {
		return ".spch";
	}
	return '';
}

/**
 * ファイルがあれば削除
 * @param $path
 * @return bool
 */
function safe_unlink ($path) {
	if ($path && is_file($path)) {
		return unlink($path);
	}
	return false;
}

/**
 * 一連の画像ファイルを削除（元画像、サムネ、動画）
 * @param $path
 * @param $filename
 * @param $ext
 */
function delete_files ($path, $filename, $ext) {
	safe_unlink($path.$filename.$ext);
	safe_unlink(THUMB_DIR.$filename.'s.jpg');
	safe_unlink(PCH_DIR.$filename.'.pch');
	safe_unlink(PCH_DIR.$filename.'.spch');
}

/**
 * NGワードチェック
 * @param $ngwords
 * @param string|array $strs
 * @return bool
 */
function is_ngword ($ngwords, $strs) {
	if (empty($ngwords)) {
		return false;
	}
	if (!is_array($strs)) {
		$strs = [$strs];
	}
	foreach ($strs as $str) {
		foreach($ngwords as $ngword){//拒絶する文字列
			if ($ngword !== '' && preg_match("/{$ngword}/ui", $str)){
				return true;
			}
		}
	}
	return false;
}

/**
 * @param int $type
 * @param string $variable_name
 * @param int $filter
 * @param mixed $default_value
 * @return mixed
 */
function filter_input_default ($type , $variable_name, $filter = FILTER_DEFAULT, $default_value = null) {
	$value = filter_input($type, $variable_name, $filter);
	return ($value !== null || $default_value === null) ? $value : $default_value;
}

function png2jpg ($src) {
	if(mime_content_type($src)==="image/png" && gd_check() && function_exists("ImageCreateFromPNG")){//pngならJPEGに変換
		if($im_in=ImageCreateFromPNG($src)){
			$dst = pathinfo($src, PATHINFO_FILENAME ) . 'jpg.tmp';
			ImageJPEG($im_in,$dst,98);
			ImageDestroy($im_in);// 作成したイメージを破棄
			chmod($dst,0606);
			return $dst;
		}
	}
	return false;
}

function check_badip ($host, $dest = '') {
	global $badip;
	foreach($badip as $value){ //拒絶host
		if (preg_match("/$value$/i",$host)) {
			error(MSG016, $dest);
		}
	}
}

function check_badfile ($chk, $dest = '') {
	global $badfile;
	foreach($badfile as $value){
		if(preg_match("/^$value/",$chk)){
			error(MSG005,$dest); //拒絶画像
		}
	}
}

function create_res ($path, $line) {

	list($no,$now,$name,$email,$sub,$com,$url,$host,$pwd,$ext,$w,$h,$time,$chk,$ptime,$fcolor)
		= explode(",", rtrim($line));

	$res = [
		'w' => $w,
		'h' => $h,
		'no' => $no,
		'sub' => $sub,
		'url' => $url,
		'email' => $email,
		'ext' => $ext,
		'time' => $time,
		'fontcolor' => ($fcolor ? $fcolor : DEF_FONTCOLOR), //文字色
	];

	// 画像系変数セット
	$res['img'] = $path.$time.$ext; // 画像ファイル名
	if ($res['img_file_exists'] = ($ext && is_file($res['img']))) { // 画像ファイルがある場合
		$res['src'] = IMG_DIR.$time.$ext;
		$res['srcname'] = $time.$ext;
		$res['size'] = filesize($res['img']);
		$res['thumb'] = is_file(THUMB_DIR.$time.'s.jpg');
		$res['imgsrc'] = $res['thumb'] ? THUMB_DIR.$time.'s.jpg' : $res['src'];
		//描画時間
		$res['painttime'] = DSP_PAINTTIME ? $ptime : '';
		//動画リンク
		$res['pch'] = (USE_ANIME && check_pch_ext(PCH_DIR.$time)) ? $time.$ext : '';
		//コンティニュー
		$res['continue'] = USE_CONTINUE ? $res['no'] : '';
	}

	//日付とIDを分離
	list($res['id'], $res['now']) = separateDatetimeAndId($now);
	//日付と編集マークを分離
	list($res['now'], $res['updatemark']) = separateDatetimeAndUpdatemark($res['now']);
	//名前とトリップを分離
	list($res['name'], $res['trip']) = separateNameAndTrip($name);

	$res['encoded_name'] = urlencode($res['name']);

	// オートリンク
	if(AUTOLINK) {
		$com = auto_link($com);
	}
	$com = preg_replace("/(^|>)((&gt;|＞)[^<]*)/i", "\\1".RE_START."\\2".RE_END, $com); // '>'色設定
	$res['com'] = preg_replace("{<br( *)/>}i","<br>",$com); //<br />を<br>へ

	$res['descriptioncom'] = strip_tags($res['com']); //メタタグに使うコメントからタグを除去

	return $res;
}

?>

