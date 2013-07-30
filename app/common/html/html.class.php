<?php
/**
 * html等相关代码或消息工具类
 *
 * @author Balsampear
 * @since 2013-03-23
 */

class Html {
	
	public static function pri($msg, $get = false) {
		$msg = '<span style="color:#ff0000;font-weight:bold;">'.$msg.'</span><br /><br />';
		if ($get) {
			return $msg;
		}else{
			echo $msg;
		}
	}
	
	public static function mkWangwangCode($wangwang) {
		$ret = urlencode($wangwang);
		return '<a target="_blank" href="http://www.taobao.com/webww/ww.php?ver=3&touid='
			.$ret.'&siteid=cntaobao&status=2&charset=gbk"><img border="0" src="http://amos.alicdn.com/online.aw?v=2&uid='
			.$ret.'&site=cntaobao&s=1&charset=gbk" alt="点这里给我发消息" /></a>';
	}
	
	public static function mkItemURL($itemId) {
		return 'http://item.taobao.com/item.htm?id='.$itemId;
	}
}

