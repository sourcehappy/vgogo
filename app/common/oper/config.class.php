<?php
/**
 * 卖家的相关信息的操作
 *
 * @author Balsampear
 * 2013-03-22
 */

class Config {
	private static $cache = array();
	
	private static function get($key) {
		if (isset(self::$cache[$key])) {
			$ret = self::$cache[$key];
		}else{
			$db = Database::instance();
			$ret = $db->getValue('select `value` from vg_oper_config where `key`=' . $db->q($key));
			self::$cache[$key] = $ret;
		}
		return $ret;
	}
	
	//获取佣金最小值
	public static function getCommission() {
		return intval(self::get('commission'));
	}
	
	//获取Item的提交限制数
	public static function getItemLimit() {
		return intval(self::get('per_item_limit'));
	}
	
	//获取Item的子图数量限制
	public static function getItemImgNumLimit() {
		return intval(self::get('item_img_num_limit'));
	}
	
	//获取Item的子图数量限制
	public static function getSignupNotice() {
		return self::get('signup_notice');
	}
}