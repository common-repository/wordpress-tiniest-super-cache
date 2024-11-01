<?php
/*
WP Tiniest Super Cache Engine
*/
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$pl_start = $time;
function wptsc_get_web($url,$post = "") {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_URL,$url);
	if(empty($_SERVER['HTTP_USER_AGENT'])) {
		$agent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.11) Gecko/2009060215 Firefox/3.0.11";
	} else {
		$agent = $_SERVER['HTTP_USER_AGENT'];
	}
	curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	$buf = curl_exec ($ch);
	curl_close ($ch);
	return $buf;
}
// WPTSC Variable
global $cache_dir,$cache_time,$ignore_get,$ignore_post;
$tdir = dirname(__FILE__)."/wptsc-cachedir";
$cache_dir = ($cache_dir)?$cache_dir:$tdir;
$cache_time = ($cache_time)?$cache_time:3600;
//---
$cdir = $cache_dir;
$rurl = "http://".(($host)?$host:$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
list($rurl) = explode("?",$rurl);
if(!is_dir($cdir)) {
	@mkdir($cdir,0777,true);
	@file_put_contents($cdir."/index.html","");
}
$ignore = false;
$gig = @file(dirname(__FILE__)."/wptsc-ignore");
$hardcached = @file(dirname(__FILE__)."/wptsc-hardcache");
foreach((array) $gig as $iurl) {
	$iurl = trim($iurl);
	$iurl = trim($iurl,"/");
	$xrurl = trim($rurl,"/");
	if($iurl == $xrurl) {
		$ignore = true;
		break;
	}
}
foreach((array) $hardcached as $iurl) {
	$iurl = trim($iurl);
	$iurl = trim($iurl,"/");
	$xrurl = trim($rurl,"/");
	if($iurl == $xrurl) {
		$ignore = false;
		$hardcache = true;
		break;
	}
}

$chfile = $cache_dir."/".md5($rurl);
$cadmin = explode("wp-",$rurl);
$nocache = false;
if($_GET and $ignore_get == 0) {
	$nocache = true;
}

if($_POST and $ignore_post == 0) {
	$nocache = true;
}

if($ignore == true) {
	$nocache = true;	
}
if($hardcache==true and empty($_POST['wptsc_docache'])) {
	$nocache = false;
}
if(count($cadmin) < 2 and $nocache == false ) {	
	$diff_time = time() - @filemtime($chfile);	
	$csize = @filesize($chfile);
	if(file_exists($chfile) and $diff_time < $cache_time and intval($csize) > 2048) {
		include($chfile);
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$finish = $time;
		$total_time = round(($finish - $pl_start), 4);
		$cbody .= "<!-- Using cached - Page generated in ".$total_time.' seconds. -->';
		echo $cbody;
		exit;
	}
	@unlink($chfile);
	$gcnt = wptsc_get_web($rurl,array('wptsc_docache'=>1));
	@file_put_contents($chfile,$gcnt);
	$time = microtime();
	$time = explode(' ', $time);
	$time = $time[1] + $time[0];
	$finish = $time;
	$total_time = round(($finish - $pl_start), 4);
	$cbody .= "<!-- Just cached - Page generated in ".$total_time.' seconds. -->';
	echo $gcnt.$cbody;
	exit;
} else {
	@unlink($chfile);
}
?>