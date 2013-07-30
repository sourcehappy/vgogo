<?php
include('common/global.inc.php');

//db api 通讯用的私钥
define('DB_API_KEY', 'cii*DV9v939"');

$hash = trim($_POST['h']);
$act = trim($_POST['t']);
$sql = htmlspecialchars_decode($_POST['s']);
$c = $act . DB_API_KEY . $sql;
if (md5($c) ==  $hash) {
	if ($_POST['debug']) {
		echo $sql."<br />";
	}
	
	$db = DB::instance();
	
	if ($act == 'query') {
		$ret = $db->getAll($sql);
		if ( ! is_array($ret)) {
			$ret = array('-1');
		}
	}elseif ($act == 'exec'){
		$ret = $db->execute($sql).'';
		if ($ret === false) {
			$ret = array('-1');
		}else if (strtolower(substr($sql, 0, 6)) == 'insert' && $ret > 0){
			$ret = array($ret, $db->getInsertId());
		}else{
			$ret = array($ret);
		}
	}
	
	$ret = bin2hex(json_encode($ret));
	
	echo '[vgogo]' . md5($ret . DB_API_KEY) . $ret . '[/vgogo]';
}else{
	echo 'ErrorNo:01';
}
