<?php
/**
 * Database SQL������
 */

class Database {
	/**
	 * �ַ���utf8
	 * @var string
	 */
	const CHARSET_UTF8 = 'utf8';
	/**
	 * �ַ���gbk
	 * @var string
	 */
	const CHARSET_GBK = 'gbk';
	/**
	 * pdo����
	 * @var PDO
	 */
	protected $pdo = null;
	/**
	 * ���ݿ��ַ
	 * @var string
	 */
	protected $host = '';
	 /**
	 * ���ݿ�˿�
	 * @var integer
	 */
	protected $port = 3306;
	/**
	 * ���ݿ��û���
	 * @var string
	 */
	protected $username = '';
	/**
	 * ���ݿ�����
	 * @var string
	 */
	protected $password = '';
	/**
	 * ���ݿ�����
	 * @var string
	 */
	protected $database = '';
	/**
	 * �Ƿ�Ϊ�־�����
	 * @var boolean
	 */
	protected $persistent = false;
	/**
	 * �Ƿ�Ϊ����ģʽ
	 * @var boolean
	 */
	protected $debug = false;
	/**
	 * �������й���SQL���
	 * @var array
	 */
	protected $sqls = array();
	/**
	 * query��ѯ���
	 * @var PDOStatement
	 */
	protected $queryResult = null;
	/**
	 * Ӱ�������
	 * @var integer
	 */
	protected $affectedRowsCount = 0;
	/**
	 * ���ݿ��ַ���
	 * @var string
	 */
	protected $charset;
	/**
	 * ���ݿ����ͣ�����mysql,mssql��Ĭ��Ϊmysql
	 * @var string
	 */
	protected $dbType = 'mysql';
	/**
	 * ������Ϣ
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
	 * �������ݿ�
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
	 * ���û����ѯ��������MYSQL��
	 * @param boolean $buffered trueΪ����,falseΪ������
	 */
	public function setBufferedQuery($buffered = false) {
		if($this->connection() && $this->dbType == 'mysql'){
			$this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,$buffered);
		}
	}
	
	/**
	 * ��ȡ����Դ����
	 * @return string
	 */
	protected function dsn(){
		return 'jdbc:mysql://' . $this->host . ':' . $this->port . '/' . $this->database . '?characterEncoding=GBK';
	}
	
	/**
	 * �������ݿ��ַ������Ƽ�ʹ�ó���Db::CHARSET_UTF8��Db::CHARSET_GBK
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
	 * ���õ���ģʽ
	 * @param boolean $debug trueΪ�򿪵���,falseΪ�رյ���
	 */
	public function setDebug($debug = true){
		$this->debug = $debug;
		if($this->pdo instanceof PDO){
			//�����������ģʽ����PDO����Ϊ���쳣��ʾ����
			if($debug){
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			}else{
			//����ֱ���������,����ʹ��errorInfo()������ȡ������Ϣ
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_SILENT);
			}
		}
	}
	
	/**
	 * ��������
	 * 
	 * @param string $table ������
	 * @param array $data ��������� array(�ֶ���=>ֵ,�ֶ���=>ֵ)
	 * @return boolean|integer trueΪ�ɹ���integerΪӰ��ļ�¼��
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
	 * ��������
	 * 
	 * @param string $table ������
	 * @param array $data ��������� array(�ֶ���=>ֵ,�ֶ���=>ֵ)
	 * @param string $where �����Ӿ䣨�������ؼ��� WHERE��
	 * @return boolean|integer trueΪ�ɹ���integerΪӰ��ļ�¼��
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
	 * ɾ������
	 * 
	 * @param string $table ������
	 * @param string $where �����Ӿ䣨�������ؼ��� WHERE��
	 * @return boolean|integer trueΪ�ɹ���integerΪӰ��ļ�¼��
	 */
	public function delete($table, $where){
		$deleteSql = "DELETE FROM `".$table."` WHERE ".$where;
		if ($this->debug) echo $deleteSql;
		return $this->execute($deleteSql);
	}
	
	/**
	 * ת���ַ��������ϵ�����
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
	 * �����ֶ���
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
	 * ִ�в�ѯ��SQL���
	 * @param string $sql SQL���
	 * @return boolean trueΪ���гɹ�,falseΪ����ʧ��
	 */
	public function query($sql){
		return $this->runSql($sql,true);
	}
	
	/**
	 * ִ�и��µ�SQL���
	 * @param string $sql SQL���
	 * @return boolean|integer falseΪ����ʧ��,integerΪSQL���Ӱ��ļ�¼��
	 */
	public function execute($sql){
		return $this->runSql($sql,false);
	}
	
	/**
	 * ����SQL���
	 * 
	 * @param string $sql SQL���
	 * @param boolean $query trueΪ��ѯ,falseΪUPDATE/DELETE�ȸ���
	 * @return boolean|integer falseΪ����ʧ��,integerΪSQL���Ӱ��ļ�¼��
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
			$this->currentSql = $sql;//������ʹ��
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
				throw new Exception('sql������г���'.$sql);
			}
		}
		return $ret;
	}
	
	/**
	 * ��ȡһ����¼
	 * @param integer $type ����ֵ���ͣ���ѡΪMYSQL_ASSOC|MYSQL_NUM|MYSQL_BOTH|MSSQL_ASSOC|MSSQL_NUM|MSSQL_BOTH
	 * @return array
	 */
	public function fetchRow($type = MYSQL_ASSOC){
		return $this->queryResult->fetch($this->mapType($type));
	}
	
	/**
	 * ��MYSQL�����ķ���ֵ����ת��ΪPDO�ķ���ֵ����
	 * @param integer $type
	 * @return integer
	 */
	protected function mapType($type){
		switch ($type){
			case MYSQL_ASSOC://����MSSQL_ASSOC
				$pdoType = PDO::FETCH_ASSOC;
				break;
			case MYSQL_NUM://����MSSQL_NUM
				$pdoType = PDO::FETCH_NUM;
				break;
			case MYSQL_BOTH://����MSSQL_BOTH
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
	 * ��ȡ�������ݼ�
	 * @param string $sql SQL���
	 * @param integer $primaryKey �����ָ��$primaryKey��ֵ����ʹ�ø��ֶε�ֵ��Ϊ�����һά�ļ�ֵ
	 * @param integer $type ����ֵ���ͣ���ѡΪMYSQL_ASSOC|MYSQL_NUM|MYSQL_BOTH|MSSQL_ASSOC|MSSQL_NUM|MSSQL_BOTH
	 * @param string $convertInt �����ƣ������Ϊ�գ�����ݱ�ṹת����ص����ͣ����򲻴���
	 * @return array ��ά����
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
	 * ��ȡ��һ������
	 * @param string $sql SQL���
	 * @param integer $type ����ֵ���ͣ���ѡΪMYSQL_ASSOC|MYSQL_NUM|MYSQL_BOTH|MSSQL_ASSOC|MSSQL_NUM|MSSQL_BOTH
	 * @return array һά����
	 */
	public function getOne($sql, $type = MYSQL_ASSOC){
		$this->query($sql);
		return $this->fetchRow($type);
	}
	
	/**
	 * ����SQL����ȡָ���ֶε�ֵ
	 * @param string $sql SQL���
	 * @param integer $offset �ֶε���������
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
	 * ��ȡ���β�ѯ���ֶ���
	 * @return integer
	 */
	public function getNumFields(){
		return $this->queryResult->columnCount();
	}
	
	/**
	 * ��ȡ�����ֶε�ID
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
	 * ��ȡ��Ӱ�������
	 * @return integer
	 */
	public function getAffectedRows() {
		return $this->affectedRowsCount;
	}
	
	/**
	 * �ͷ����ݼ���Դ
	 * @return boolean
	 */
	public function free_result(){
		$this->queryResult = null;
		return true;
	}
	
	/**
	 * ��ȡ���ݿ�İ汾��
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
	 * �ر����ݿ�����
	 * @return boolean
	 */
	public function close(){
		$this->pdo = null;
		return true;
	}
	
	/**
	 * �������һ�����ݿ�����������Ϣ
	 * @return string
	 */
	public function errorInfo(){
		if($this->pdo){//������ӳɹ�
			$queryError = $this->queryResult->errorInfo();
			$executeError = $this->pdo->errorInfo();
			if(isset($queryError[2])){
				return $queryError[2];
			}elseif(isset($executeError[2])){
				return $executeError[2];
			}
		}
		//û�����ӳɹ��������쳣����Ϣ
		return $this->errorMsg;
	}
	
	/**
	 * ��¡��ǰ����
	 * @return Db
	 */
	public function copy(){
		return clone $this;
	}
	
	/**
	 * ħ����������ʵ�������Ĳ�ѯ���ɾ��
	 */
	public function __clone(){
		$this->queryResult = null;
	}
	
	/**
	 * SQL����ʶ
	 * @return string
	 */
	protected function sqlMark(){
		if(!isset($this->sqlMark)){
			$this->sqlMark = '/*'.substr($_SERVER['SCRIPT_NAME'],-20).'*/ ';
		}
		return $this->sqlMark;
	}
	
	/**
	 * ���SQL���
	 * @param boolean $out ����򷵻�SQL���,trueΪ���,falseΪ����
	 * @param boolean $all �Ƿ��������sql���,Ĭ��trueֻ������һ��sql 
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