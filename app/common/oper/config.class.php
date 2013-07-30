<?php
/**
 * ���ҵ������Ϣ�Ĳ���
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
	
	//��ȡӶ����Сֵ
	public static function getCommission() {
		return intval(self::get('commission'));
	}
	
	//��ȡItem���ύ������
	public static function getItemLimit() {
		return intval(self::get('per_item_limit'));
	}
	
	//��ȡItem����ͼ��������
	public static function getItemImgNumLimit() {
		return intval(self::get('item_img_num_limit'));
	}
	
	//��ȡItem����ͼ��������
	public static function getSignupNotice() {
		return self::get('signup_notice');
	}
}