<?php
/**
 * 文章的获取
 * 
 * @author Balsampear
 * @since 2013-4-16
 */

class Article {
	
	public static function getList($propertyId = 0, $limit = 1) {
		$db = DB::instance();
		$where = '';
		if ($propertyId) {
			$where = ' where artpr_id='.$propertyId;
		}
		$sql = 'select art_id,title,main_img,jump_url from vg_article '.$where.' order by art_id desc limit '.$limit;
		return $db->getAll($sql);
	}
	
	public static function get($artId) {
		$db = DB::instance();
		$sql = 'select art_id,title,main_img,`text`,class_id from vg_article where art_id='.$artId;
		return $db->getOne($sql);
	}
}