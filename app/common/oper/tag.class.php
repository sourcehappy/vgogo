<?php
/**
 * TAG相关的获取等
 *
 * @author Balsampear
 * @since 2013-03-23
 */
class Tag {
	
	public static function getListByClass($classId) {
		$db = DB::instance();
		$sql = "SELECT t.tag_id,t.name,t.img,tp.name as tpname,t.tagpr_id "
			."FROM vg_tag as t inner join vg_tag_property as tp using(tagpr_id) "
			."WHERE t.class_id={$classId} order by tagpr_id,`order`";
// 		echo $sql;
		return $db->getAll($sql);
	}
	
	public static function getListByProperty($tagprId) {
		$db = DB::instance();
		$sql = "SELECT t.tag_id,t.name,t.img,tp.name as tpname,t.tagpr_id "
			."FROM vg_tag as t inner join vg_tag_property as tp using(tagpr_id) "
			."WHERE t.tagpr_id={$tagprId} order by tagpr_id,t.`order`";
// 		echo $sql;
		return $db->getAll($sql);
	}
	
	public static function getListByProd($prodId) {
		$db = DB::instance();
		$sql = 'select distinct t.tag_id,t.name from vg_tag as t inner join vg_tag_prod_list as tpl using(tag_id) where tpl.prod_id='.$prodId.' order by t.name';
// 		echo $sql;
		return $db->getAll($sql);
	}
	
	public static function getList($tagIds) {
		$db = DB::instance();
		$sql = 'select tag_id,name from vg_tag where tag_id in ('.$tagIds.') order by name';
// 		echo $sql;
		return $db->getAll($sql);
	}
	
	public static function addClickNum($id) {
		$db = DB::instance();
		
		$db->update('vg_tag', array('click_num'=>new MySQLCode('click_num+1')), 'tag_id='.$id);
	}
	
	public static function getName($tagId) {
		$db = DB::instance();
		$sql = 'select name from vg_tag where tag_id='.$tagId;
		// 		echo $sql;
		return $db->getValue($sql);
	}
	
}