<?php
/**
 * 分类的数据管理
 * 
 * @author Balsampear
 * @since 2013-04-07
 */

class ClassData {
	
	public static function getList($parentClassId = false, $classId = 0) {
		$db = DB::instance();
		if ($parentClassId !== false) {
			return $db->getAll('select `name`,class_id from `vg_class` where parent_class_id='.$parentClassId);
		}else{
			return $db->getAll('select `name`,class_id from `vg_class` where class_id='.$classId);
		}
	}
	
	public static function getName($classId, $getOne = false) {
		if ($classId <= 0) return '';
		$db = DB::instance();
		$row = $db->getOne('select `name`,parent_class_id from `vg_class` where class_id='.$classId);
		if ($getOne) {
			return $row['name'];
		}else{
			if ($row['parent_class_id'] > 0) {
				return self::getName($row['parent_class_id']) .' > '. $row['name'];
			}else{
				return $row['name'];
			}
		}
	}
	
	public static function loadTreeList($classId = 0, $format = 'js') {
		if ($format == 'js') {
			$ret = array(array(0, ''));
		}else{
			$ret = array('');
		}
		self::loadTreeListEx($ret, $classId, 0, $format);
		return $ret;
	}
	
	private static function loadTreeListEx(&$outData, $classId, $level = 0, $format = 'js') {
		$db = DB::instance();
		$list = $db->getAll('select class_id,`name` from `vg_class` where parent_class_id='.$classId);
		foreach ($list as $row) {
			$v = str_pad($row['name'], strlen($row['name']) + $level * 4 * 6, "&nbsp;", STR_PAD_LEFT);
			if ($format == 'js') {
				$outData[] = array($row['class_id'], $v);
			}else{
				$outData[$row['class_id']] = $v;
			}
			self::loadTreeListEx($outData, $row['class_id'], $level + 1, $format);
		}
		
	}
	
	public static function addClickNum($id) {
		$db = DB::instance();
		
		$db->update('vg_class', array('click_num'=>new MySQLCode('click_num+1')), 'class_id='.$id);
	}
}