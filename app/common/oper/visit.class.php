<?php
/**
 * ·ÃÎÊ¼ÇÂ¼
 * 
 * @author Balsampear
 * @since 2013-4-21
 */

class Visit {
	
	public static function add($prodId) {
		global $context; 
		if ($context->getBrowser()) {
			$db = DB::instance();
			$data = array(
				'prod_id' => $prodId,
				'nick' => $context->getBrowser()->getNick(),
				'visit_time' => new MySQLCode('now()'),
				'ips' => $_SERVER['REMOTE_ADDR']
			);
			$db->insert('vg_visit_history', $data);
			return true;
		}
		return false;
	}
	
	public static function checkTodayExist($prodId) {
		global $context; 
		$db = DB::instance();
		$sql = 'SELECT count(*) as rc '
			.'FROM vg_visit_history '
			.'WHERE nick='.$db->q($context->getBrowser()->getNick()).' and prod_id='.$prodId." and DATE_FORMAT(visit_time,'%Y-%m-%d')='".date('Y-m-d')."'";
		//echo $sql;
		return $db->getValue($sql) == 1;
	}
	
	public static function getProdList($limit = 0, $prodId = 0) {
		global $context;
		
		$db = DB::instance();
		$sql = '';
		if ($prodId > 0) {
			$sql = 'prod_id<>'.$prodId;
		}
		if ($context->getBrowser()) {
			$sql = $sql ? $sql.' and ' : ''; 
			$sql = 'select distinct prod_id from vg_visit_history where '.$sql.' nick='.$db->q($context->getBrowser()->getNick()).' order by id desc limit '.$limit;
		}else{
			$sql = $sql ? 'where '.$sql : '';
			$sql = 'select distinct prod_id from vg_visit_history '.$sql.' order by id desc limit '.$limit;
		}
		//echo $sql;
		$data = $db->getAll($sql);
		$ret = array();
		foreach ($data as $row) {
			$ret[] = $row['prod_id'];
		} 
		return $ret;
	}
}