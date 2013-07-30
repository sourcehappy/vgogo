<?php
/**
 * Database SQL操作类
 */

class Database {
	/**
	 * 字符集utf8
	 * @var string
	 */
	const CHARSET_UTF8 = 'utf8';
	/**
	 * 字符集gbk
	 * @var string
	 */
	const CHARSET_GBK = 'gbk';
	/**
	 * pdo连接
	 * @var PDO
	 */
	protected $pdo = null;
	/**
	 * 数据库地址
	 * @var string
	 */
	protected $host = '';
	 /**
	 * 数据库端口
	 * @var integer
	 */
	protected $port = 3306;
	/**
	 * 数据库用户名
	 * @var string
	 */
	protected $username = '';
	/**
	 * 数据库密码
	 * @var string
	 */
	protected $password = '';
	/**
	 * 数据库名称
	 * @var string
	 */
	protected $database = '';
	/**
	 * 是否为持久连接
	 * @var boolean
	 */
	protected $persistent = false;
	/**
	 * 是否为调试模式
	 * @var boolean
	 */
	protected $debug = false;
	/**
	 * 所有运行过的SQL语句
	 * @var array
	 */
	protected $sqls = array();
	/**
	 * query查询结果
	 * @var PDOStatement
	 */
	protected $queryResult = null;
	/**
	 * 影响的行数
	 * @var integer
	 */
	protected $affectedRowsCount = 0;
	/**
	 * 数据库字符集
	 * @var string
	 */
	protected $charset;
	/**
	 * 数据库类型，例如mysql,mssql，默认为mysql
	 * @var string
	 */
	protected $dbType = 'mysql';
	/**
	 * 错误消息
	 * @var string
	 */
	protected $errorMsg = null;
	
	private static $selfRef = null;
	
	public function __construct($host, $username, $password, $database, $port) {
		$this->host = $host;
		$this->username = $username;
		$this->password = $password;
		$this->database = $database;
		$this->port = $port;
		
	}
	
	/**
	 * 连接数据库
	 * @return boolean
	 */
	protected function connection(){
		if(is_null($this->pdo)){
			$dsn = $this->dsn();
			try{
				$this->pdo = new PDO($dsn, $this->username, $this->password);
// 				$this->pdo = new PDO();
				return true;
			}catch (Exception $e){
				$this->errorMsg = $e->getMessage()."\n Database host : ".$this->host.", port : ".$this->port;
				if($this->debug){
					echo $this->errorMsg;
					exit;
				}
				return false;
			}
		}
		return true;
	}
	
	/**
	 * 设置缓冲查询（仅限于MYSQL）
	 * @param boolean $buffered true为缓冲,false为不缓冲
	 */
	public function setBufferedQuery($buffered = false) {
		if($this->connection() && $this->dbType == 'mysql'){
			$this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,$buffered);
		}
	}
	
	/**
	 * 获取数据源名称
	 * @return string
	 */
	protected function dsn(){
		return 'jdbc:mysql://' . $this->host . ':' . $this->port . '/' . $this->database . '?characterEncoding=GBK';
	}
	
	/**
	 * 设置数据库字符集，推荐使用常量Db::CHARSET_UTF8和Db::CHARSET_GBK
	 * @param string $charset
	 */
	public function setCharset($charset){
		$charset = str_replace('-', '', $charset);
		$this->charset = $charset;
		if($this->dbType == 'mysql'){
			if($charset != ''){
				if($this->pdo instanceof PDO){
					$this->execute("SET NAMES ".$charset);
				}
			}
		}
	}
	
	/**
	 * 设置调试模式
	 * @param boolean $debug true为打开调试,false为关闭调试
	 */
	public function setDebug($debug = true){
		$this->debug = $debug;
		if($this->pdo instanceof PDO){
			//如果开启调试模式，则将PDO调整为用异常提示错误
			if($debug){
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			}else{
			//否则不直接输出错误,可以使用errorInfo()方法获取错误消息
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_SILENT);
			}
		}
	}
	
	/**
	 * 插入数据
	 * 
	 * @param string $table 表名称
	 * @param array $data 插入的数据 array(字段名=>值,字段名=>值)
	 * @return boolean|integer true为成功，integer为影响的记录数
	 */
	public function insert($table, array $data){
		$fields = array();
		$values = array();
		foreach($data as $key => $val){
			if (is_string($key)){
				$fields[] = $this->fieldQuote($key);
			}
			$values[] = $this->q($val);
		}
		if ($fields){
			$insertSql = "INSERT INTO `".$table."` (".implode(',',$fields).") VALUES(".implode(',',$values).")";
		}else{
			$insertSql = "INSERT INTO `".$table."` VALUES(".implode(',',$values).")";
		}
		if ($this->debug) echo $insertSql;
		return $this->execute($insertSql);
	}
	
	/**
	 * 更新数据
	 * 
	 * @param string $table 表名称
	 * @param array $data 插入的数据 array(字段名=>值,字段名=>值)
	 * @param string $where 条件子句（不包含关键字 WHERE）
	 * @return boolean|integer true为成功，integer为影响的记录数
	 */
	public function update($table, array $data, $where){
		$sets = array();
		foreach($data as $key => $val){
			$sets[] = $this->fieldQuote($key)."=".$this->q($val);
		}
		$updateSql = "UPDATE `".$table."` SET ".implode(",",$sets)." WHERE ".$where;
		if ($this->debug) echo $updateSql;
		return $this->execute($updateSql);
	}
	
	/**
	 * 删除数据
	 * 
	 * @param string $table 表名称
	 * @param string $where 条件子句（不包含关键字 WHERE）
	 * @return boolean|integer true为成功，integer为影响的记录数
	 */
	public function delete($table, $where){
		$deleteSql = "DELETE FROM `".$table."` WHERE ".$where;
		if ($this->debug) echo $deleteSql;
		return $this->execute($deleteSql);
	}
	
	/**
	 * 转义字符串并加上单引号
	 * @param string $string
	 * @return string
	 */
	public function q($string){
		if(!$this->connection()){
			return false;
		}
		if (is_array($string)){
			$string = json_encode($string);
		}
		if (is_object($string)){
			if (get_class($string) == 'MySQLCode'){
				return $string->get();
			}
		}
		return $this->pdo->quote($string);
	}
	
	/**
	 * 处理字段名
	 * @param string $field
	 * @return string
	 */
	protected function fieldQuote($field){
		switch ($this->dbType){
			case 'mysql':
				$field = '`'.$field.'`';
				break;
			case 'mssql':
				$field = '['.$field.']';
				break;
		}
		return $field;
	}
	
	/**
	 * 执行查询的SQL语句
	 * @param string $sql SQL语句
	 * @return boolean true为运行成功,false为运行失败
	 */
	public function query($sql){
		return $this->runSql($sql,true);
	}
	
	/**
	 * 执行更新的SQL语句
	 * @param string $sql SQL语句
	 * @return boolean|integer false为运行失败,integer为SQL语句影响的记录数
	 */
	public function execute($sql){
		return $this->runSql($sql,false);
	}
	
	/**
	 * 运行SQL语句
	 * 
	 * @param string $sql SQL语句
	 * @param boolean $query true为查询,false为UPDATE/DELETE等更新
	 * @return boolean|integer false为运行失败,integer为SQL语句影响的记录数
	 */
	protected function runSql($sql, $query){
		if(!$this->connection()){
			return false;
		}
		$ret = false;
		if($this->debug){
			$this->sqls[] = $sql;
		}
		//$sql = $this->sqlMark().$sql;
		$this->free_result();
		
		if($query){
			$this->currentSql = $sql;//供子类使用
			$this->queryResult = $this->pdo->prepare($sql);
			if($this->queryResult){
				$ret = $this->queryResult->execute();
			}
		}else{
			$this->affectedRowsCount = $this->pdo->exec($sql);
			$ret = $this->affectedRowsCount;
		}
		if ($ret === false && $this->debug){
			if($this->dbType == 'mssql'){
				throw new Exception('sql语句运行出错：'.$sql);
			}
		}
		return $ret;
	}
	
	/**
	 * 获取一条记录
	 * @param integer $type 返回值类型，可选为MYSQL_ASSOC|MYSQL_NUM|MYSQL_BOTH|MSSQL_ASSOC|MSSQL_NUM|MSSQL_BOTH
	 * @return array
	 */
	public function fetchRow($type = MYSQL_ASSOC){
		return $this->queryResult->fetch($this->mapType($type));
	}
	
	/**
	 * 将MYSQL函数的返回值类型转换为PDO的返回值类型
	 * @param integer $type
	 * @return integer
	 */
	protected function mapType($type){
		switch ($type){
			case MYSQL_ASSOC://包括MSSQL_ASSOC
				$pdoType = PDO::FETCH_ASSOC;
				break;
			case MYSQL_NUM://包括MSSQL_NUM
				$pdoType = PDO::FETCH_NUM;
				break;
			case MYSQL_BOTH://包括MSSQL_BOTH
				$pdoType = PDO::FETCH_BOTH;
				break;
			default:
				$pdoType = PDO::FETCH_ASSOC;
		}
		return $pdoType;
	}
	
	public function getAllex($sql, $convertInt){
		return $this->getAll($sql, '', MYSQL_ASSOC, $convertInt);
	}
	
	/**
	 * 获取整个数据集
	 * @param string $sql SQL语句
	 * @param integer $primaryKey 如果有指定$primaryKey的值，则使用该字段的值做为数组的一维的键值
	 * @param integer $type 返回值类型，可选为MYSQL_ASSOC|MYSQL_NUM|MYSQL_BOTH|MSSQL_ASSOC|MSSQL_NUM|MSSQL_BOTH
	 * @param string $convertInt 表名称，如果不为空，则根据表结构转换相关的整型，否则不处理
	 * @return array 二维数组
	 */
	public function getAll($sql, $primaryKey = '', $type = MYSQL_ASSOC, $convertInt = '', $isKV = false){
		if ($convertInt){
			$fieldList = $this->getAll('DESCRIBE `'.$convertInt.'`','Field');
		}
		$this->query($sql);
		if($primaryKey == ''){
			$return = $this->queryResult->fetchAll($this->mapType($type));
			if ($convertInt){
				foreach ($return as &$row){
					foreach ($row as $k => &$v){
						if (strpos($fieldList[$k]['Type'],'int(') !== false){
							$v = intval($v);
						}
					}
				}
			}
			return $return;
		} 
		$return = array();
		while($row = $this->fetchRow($type)){
			if ($convertInt){
				foreach ($row as $k => &$v){
					if (strpos($fieldList[$k]['Type'],'int(') !== false){
						$v = intval($v);
					}
				}
			}
			if ($isKV){
				@$return[$row[$primaryKey]] = $row[0];
			}else{
				@$return[$row[$primaryKey]] = $row;
			}
		}
		return $return;
	}
	
	/**
	 * 获取第一行数据
	 * @param string $sql SQL语句
	 * @param integer $type 返回值类型，可选为MYSQL_ASSOC|MYSQL_NUM|MYSQL_BOTH|MSSQL_ASSOC|MSSQL_NUM|MSSQL_BOTH
	 * @return array 一维数组
	 */
	public function getOne($sql, $type = MYSQL_ASSOC){
		$this->query($sql);
		return $this->fetchRow($type);
	}
	
	/**
	 * 根据SQL语句获取指定字段的值
	 * @param string $sql SQL语句
	 * @param integer $offset 字段的数字索引
	 * @return string
	 */
	public function getValue($sql, $offset = 0){
		$this->query($sql);
		$row = $this->fetchRow(MYSQL_NUM);
		if(isset($row[$offset])){
			return $row[$offset];
		}
		return null;
	}
	
	/**
	 * 获取本次查询的字段数
	 * @return integer
	 */
	public function getNumFields(){
		return $this->queryResult->columnCount();
	}
	
	/**
	 * 获取插入字段的ID
	 * @return integer
	 */
	public function getInsertId(){
		if($this->dbType == 'mssql'){
			$this->query("SELECT LAST_INSERT_ID=@@IDENTITY");
			$row = $this->fetchRow();
			if(isset($row['LAST_INSERT_ID'])){
				return $row['LAST_INSERT_ID'];
			}
			return 0;
		}
		return intval($this->pdo->lastInsertId());
	}
	
	/**
	 * 获取受影响的行数
	 * @return integer
	 */
	public function getAffectedRows() {
		return $this->affectedRowsCount;
	}
	
	/**
	 * 释放数据集资源
	 * @return boolean
	 */
	public function free_result(){
		$this->queryResult = null;
		return true;
	}
	
	/**
	 * 获取数据库的版本号
	 * @return string
	 */
	public function version(){
		if(!$this->connection()){
			return null;
		}
		if($this->dbType == 'mssql'){
			$this->query("SELECT SERVERPROPERTY('productversion')");
			$result = $this->fetchRow(MYSQL_NUM);
			if (sizeof($result)) {
				return $result[0];
			}
			return null;
		}
		return $this->pdo->getAttribute(PDO::ATTR_SERVER_VERSION);
	}
	
	/**
	 * 关闭数据库连接
	 * @return boolean
	 */
	public function close(){
		$this->pdo = null;
		return true;
	}
	
	/**
	 * 返回最后一次数据库操作错误的信息
	 * @return string
	 */
	public function errorInfo(){
		if($this->pdo){//如果连接成功
			$queryError = $this->queryResult->errorInfo();
			$executeError = $this->pdo->errorInfo();
			if(isset($queryError[2])){
				return $queryError[2];
			}elseif(isset($executeError[2])){
				return $executeError[2];
			}
		}
		//没有连接成功，返回异常的信息
		return $this->errorMsg;
	}
	
	/**
	 * 克隆当前对象
	 * @return Db
	 */
	public function copy(){
		return clone $this;
	}
	
	/**
	 * 魔术方法，将实例副本的查询结果删除
	 */
	public function __clone(){
		$this->queryResult = null;
	}
	
	/**
	 * SQL语句标识
	 * @return string
	 */
	protected function sqlMark(){
		if(!isset($this->sqlMark)){
			$this->sqlMark = '/*'.substr($_SERVER['SCRIPT_NAME'],-20).'*/ ';
		}
		return $this->sqlMark;
	}
	
	/**
	 * 输出SQL语句
	 * @param boolean $out 输出或返回SQL语句,true为输出,false为返回
	 * @param boolean $all 是否输出所有sql语句,默认true只输出最后一句sql 
	 * @return string
	 */
	public function sqlOutput($out = true, $all = true){
		if($all){
			$ret = implode("<br>",$this->sqls);
		}else{
			$ret = $this->sqls[count($this->sqls)-1];
		}
		if ($out){
			echo $ret;
		}else{
			return $ret;
		}
	}
}

class MySQLCode {
	private $str = '';
	
	public function __construct($s){
		if (is_string($s)){
			$this->str = $s;
		}
	}
	
	public function get(){
		return $this->str;
	}
}