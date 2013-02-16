<?php
/**
 * MYSQL数据库操作类
 *
 * Created		: 2009-07-12
 * Modified		: 2012-09-12 
 * @link		: http://www.dophin.co/
 * @copyright	: © 2009-2012 Dophin.Com
 * @version		: 3.0.0
 * @author		: Joseph Chen <chenliq@gmail.com>
 */
class Mysql
{
	/**
	 * 当前脚本已执行的查询数
	 * @var int
	 */
	private $queries = 0;
	/**
	 * 当前数据库PDO实例
	 * @var object
	 */
	private $pdo = null;
	/**
	 * PDOStatement
	 * @var object PDOStatement
	 */
	private $stmt = null;
	/**
	 * 上次SQL执行影响的记录数
	 * @var int
	 */
	private $rows = 0;
	/**
	 * 是否打开持久化链接
	 * @param boolean
	 */
	private $persistent = false;
	/**
	 * 字段值为数组时保存的字符串格式,默认转成json格式保存
	 * @var string
	 */
	private $arraySaveHandler = 'json_encode';

	// 控制是否可以结束事务(commit或rollback)
	private $canEndTransaction = true; // 事务是否可以结束(提交或回滚)
	private $isBeginTransaction = false;// 事务是否已开启
	
	/**
	 * 上次执行的SQL语句
	 * @param array
	 */
	private $lastSql = array();
	/**
	 * 上次执行的SQL语句保存的命名空间
	 * @var string
	 */
	private $lastSqlNameSpace = 'default';
	/**
	 * 上次执行的SQL语句的参数列表
	 * @param array
	 */
	private $lastSqlParam = array();
	
	/**
	 * 需要记录sql错误的代码列表
	 * @var array
	 */
	private $logSqlcodes = array(1048, 1062, 1064, 1067, 1136);
	
	/**
	 * SQL执行出错的信息
	 * @var array
	 */
	public $errors = array();
	
	public function __construct($cfg)
	{
		$dns = 'mysql:host='.$cfg['db_host'].';dbname='.$cfg['db_name'];
		if (isset($cfg['db_persistent']) && $cfg['db_persistent']) {
			$this->persistent = true;
		}
		$options = array(
				PDO::ATTR_PERSISTENT => $this->persistent,
				PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false,
				PDO::MYSQL_ATTR_INIT_COMMAND => 'SET CHARACTER SET \''.$cfg['db_charset'].'\''
		);
		try {
			$this->pdo = new PDO($dns, $cfg['db_user'], $cfg['db_pass'], $options);
		} catch (PDOException $e) {
			if (APP_DEBUG)
			{
				header('content-type:text/plain;charset=utf-8;');
				debug_print_backtrace();
				exit;
			}
			echo 'Connect to mysql server error!';
			exit;
		}
	}

	/**
	 * 执行SQL语句
	 * @param string $sql 要执行的sql语句
	 * @param array | boolean $param 当执行参数化查询时需要的参数
	 * @see PDO::exec()
	 */
	public function execute($sql, $param=null)
	{
		$this->queries++;
		if (empty($sql) && $this->debug) {
			debug_print_backtrace();
			exit;
		}
		$this->lastSql[$this->lastSqlNameSpace] = $sql;
		$this->lastSqlParam[$this->lastSqlNameSpace] = $param;
		if (is_null($param)) {
			$this->rows = $this->pdo->exec($sql);
			if ($this->rows === false) {
				$this->errors = $this->pdo->errorInfo();
// 				if (in_array($this->errors[1], $this->logSqlcodes)) {
// 					$this->errors['sql'] = $sql;
// 					$this->errors['params'] = $param;
// 				}
				$this->errors($this->errors);
				return false;
			} else {
				return true;
			}
		} else {
			$this->stmt = $this->pdo->prepare($sql);
			if (is_array($param)) {
				$paramList = array();
				foreach ($param as $k=>$v) {
					$paramList[$k] = $v;
				}
				$param = $paramList;
				$result = $this->stmt->execute($param);
			} else {
				$result = $this->stmt->execute();
			}
			if (false === $result) {
				$this->errors = $this->stmt->errorInfo();
				$this->errors['sql'] = $sql;
				$this->errors['params'] = $param;
				$this->rows = 0;
			} else {
				$this->errors = null;
				$this->rows = $this->stmt->rowCount();
			}
			return $result;
		}
	}

	/**
	 * 返回上次执行操作的受影响记录数
	 */
	public function affectRows()
	{
		return $this->rows;
	}
	
	/**
	 * 向表中插入/更新一条记录
	 * @param string $table
	 * @param array $data 插入的数据列表(数组形式)
	 */
	public function replace($table, $data)
	{
		foreach ($data as $field => $value)
		{
			$fieldList[] = '`'.$field.'`';
			$paramKey = ':'.$field;
			$valueList[] = $paramKey;
			if (is_array($value)) {
				$value = $this->arraySaveHandler($value);
			}
			$param[$paramKey] = $value;
		}
		$fieldStr = join(', ', $fieldList);
		$valueStr = join(', ', $valueList);
		$sql = "REPLACE INTO `$table` ($fieldStr) VALUES ($valueStr)";
		$ret = $this->execute($sql, $param);
		return $ret;
	}
	
	/**
	 * 向表中插入一条记录
	 * @param string $table
	 * @param array $data 插入的数据列表(数组形式)
	 * @param boolean $multi 插入的数据是否多条
	 * @return int | false
	 */
	public function insert($table, $data, $multi=false) 
	{
		$param = array();
		$fieldList = array();
		$valueList = array();
		// 多维数组,一次插入多条数据
		if ($multi || is_array(current($data))) {
			$dataNums = count($data);
			$needFieldList = 1;
			$i = 1;
			foreach ($data as $fv) {
				$tmpValues = array();
				foreach ($fv as $field => $value)
				{
					$needFieldList && $fieldList[] = '`'.$field.'`';
					$paramKey = ':'.$field.$i;
					$tmpValues[] = $paramKey;
					if (is_array($value)) {
						$value = $this->arraySaveHandler($value);
					}
					$param[$paramKey] = $value;
				}
				$i++;
				// 第二次循环字段不再需要保存字段列表
				$needFieldList = 0;
				$tmpValues = join(', ', $tmpValues);
				$valueList[] = '('.$tmpValues.')';
			}
			$fieldStr = join(', ', $fieldList);
			$valueStr = join(",\n", $valueList);
			$sql = "INSERT INTO `$table` ($fieldStr) VALUES $valueStr";
		} else {
			$dataNums = 1;
			foreach ($data as $field => $value) {
				$fieldList[] = '`'.$field.'`';
				$paramKey = ':'.$field;
				$valueList[] = $paramKey;
				if (is_array($value)) {
					$value = $this->arraySaveHandler($value);
				}
				$param[$paramKey] = $value;
			}
			$fieldStr = join(', ', $fieldList);
			$valueStr = join(', ', $valueList);
			$sql = "INSERT INTO `$table` ($fieldStr) VALUES ($valueStr)";
		}
		$ret = $this->execute($sql, $param);
		return $ret;
	}
	
	/**
	 * 更新一个表的数据
	 * @param string $table
	 * @param array $data 要更新的数据列表(数组形式)
	 * @param string $where 查询条件字符串
	 * @param string $param SQL查询参数
	 * @return int | false
	 */
	public function update($table, $data, $where, $param=null)
	{
		$set_list = array();
		is_null($param) && $param=array();
		if (is_array($data)) {
			foreach ($data as $field => $value) {
				// @开头的值表示字段参与操作的字符串
				if (0 === strpos($value, '@@')) {
					$set_list[] = '`'.$field.'`='.$value;
				} else if (0 === strpos($field, '@+')) {
					$set_list[] = '`'.$field.'`=`'.$field.'`+'.$value;
				} else {
					$param_key = ':'.$field;
					$set_list[] = '`'.$field.'`='.$param_key;
					$param[$param_key] = $value;
				}
			}
			$set = implode(',', $set_list);
		} else {
			$set = $data;
		}
		// where条件必须要业务层提供,防止因失误导致所有数据被更新
		if ($where)
		{
			$where = ' where ' . $where;
		} else {
			$where = '';
		}
		$sql = 'update `' . $table . '` set ' . $set . $where;
		$ret = $this->execute($sql, $param);
		return $ret;
	}

	/**
	 * 执行删除操作
	 * @param string $table
	 * @param string $where
	 * @return int | false
	 */
	public function delete($table, $where, $param=null)
	{
		if ($where) {
			$sql = 'delete from `'.$table.'` where '.$where;
			$ret = $this->execute($sql, $param);
			return $ret;
		} else {
			$this->errors(
				$GLOBALS['_DBDeleteWhereCannotEmpty']
			);
			return false;
		}
	}
	
	/**
	 * 从表中读取一条记录
	 * @param string $table 表名
	 * @param string $value 值(查询字段的值)
	 * @param string $field 字段
	 * @param string $fetchFields 获取的字段
	 */
	public function read($table, $value, $field='id', $fetchFields='*') 
	{
		$sql = 'select '.$fetchFields.' from `'.$table.'` where `'.$field.'`=:'.$field;
		$param = array(
			$field => $value
		);
		return $this->fetchFromSql($sql, false, $param);
	}

	/**
	 * 直接从SQL语句获取数据
	 * @param $sql SQL语句
	 * @param $multi 是否获取多条
	 * @param $param SQL参数
	 * @param $fetchStyle 获取数据的字段方式
	 */
	public function fetchFromSql($sql, $multi=true, $param=null, $fetchStyle=PDO::FETCH_ASSOC)
	{
		empty($param) && $param=true;
		empty($fetchStyle) && $fetchStyle=PDO::FETCH_ASSOC;
		$res= $this->execute($sql, $param);
		
		if ($res === false) {
			return false;
		}
		// 只有一个字段,则只取第一列数据
		if ($this->stmt->columnCount() == 1) {
			$fetchStyle = PDO::FETCH_COLUMN;
		}
		
		if ($multi) {
			$ret = $this->stmt->fetchAll($fetchStyle);
		} else {
			$ret = $this->stmt->fetch($fetchStyle, PDO::FETCH_ORI_FIRST);
		}
		
		// 取完数据要关闭游标,避免表被锁定
		$this->stmt->closeCursor();
		$this->stmt = null;
		return $ret;
	}

	/**
	 * 获取上次插入记录的ID
	 * @see PDO::lastInsertId()
	 */
	public function lastInsertId($name=null)
	{
		return $this->pdo->lastInsertId($name);
	}
	
	/**
	 * 上次执行的SQL语句
	 * @param string $name
	 */
	public function getLastSql($name='default') 
	{
		return $this->lastSql[$name];
	}
	
	/**
	 * 上次执行的SQL语句参数
	 * @param string $name
	 */
	public function getLastSqlParam($name='default') 
	{
		return $this->lastSqlParam[$name];
	}
	
	/**
	 * 设置上次执行的SQL语句保存的命名空间
	 * @param string $name
	 */
	public function setLastSqlNameSpace($name='default') 
	{
		$this->lastSqlNameSpace = $name;
		$this->lastSqlParamNameSpace = $name;
	}
	
	/**
	 * 执行show tables status 语句并返回一个表的信息
	 * @param string $table
	 */
	public function showTableStatus($table) 
	{
		$sql = 'SHOW TABLE STATUS LIKE \''.$table.'\'';
		$record = $this->fetchFromSql($sql, false);
		return $record;
	}

	/**
	 * 获取自增字段当前值
	 * @param string $table_name
	 */
	public function getAutoIncrement($table)
	{
		$sql = 'SHOW TABLE STATUS LIKE \''.$table.'\'';
		$record = $this->fetchFromSql($sql, false);
		return $record['Auto_increment'];
	}

	/**
	 * 获取数据库中所有的表
	 * @return array()
	 */
	public function showTables()
	{
		$sql = 'show tables';
		$arr = $this->fetchFromSql($sql, true, null, PDO::FETCH_COLUMN);
		$result = array();
		foreach ($arr as $v) {
			$result[] = $v;
		}
		return $result;
	}

	/**
	 * 获取表字段详细说明
	 * @param string $tbl
	 */
	public function desc($tbl)
	{
		$exists = $this->isTableExist($tbl);
		if (!$exists) {
			trigger_error('table '.$tbl.' not exists.');
			return false;
		}
		$sql = 'desc `'.$tbl.'`';
		return $this->fetchFromSql($sql);
	}
	
	/**
	 * 获取一个表的字段信息
	 * @param string $tbl
	 */
	public function getFields($tbl) 
	{
		$sql = 'show full fields from `'.$tbl.'`';
		$list = $this->fetchFromSql($sql);
		if (!$list) {
			return false;
		}
		$fields = array();
		foreach ($list as $v) {
			$fieldName = $v['Field'];
			unset($v['Field'], $v['Collation'], $v['Privileges']);
			$v['Unsigned'] = (false !== strpos($v['Type'], 'unsigned'));
			preg_match('/([\w]+)(?:\(([\d]+)\)){0,1}/i', $v['Type'], $m);
			if (isset($m[2])) {
				$v['Length'] = $m[2];
			} else {
				$v['Length'] = null;
			}
			$v['Type'] = $m[1];
			$fields[$fieldName] = $v;
		}
		return $fields;
	}

	/**
	 * 指定表是否存在
	 * @param string $tbl
	 */
	public function isTableExist($tbl)
	{
		$sql = 'SHOW TABLES LIKE \''.$tbl.'\'';
		$ret = $this->fetchFromSql($sql, false);
		return $ret;
	}
	
	/**
	 * 根据参数创建一条sql语句
	 * @param string $table
	 * @param array|string $where
	 * @param array $options
	 */
	public function buildSql($table, $options=null) 
	{
		if (empty($options['fields'])) {
			$field = '*';
		} else {
			$field = $options['fields'];
		}
		if (false !== strpos($table, '`')) {
// 			$table = $table;
		} else if (false !== stripos($table, 'as')) {
// 			$table = $table;
		} else {
			$table = '`'.$table.'`';
		}
		if (empty($options['alias'])) {
			$alias = '';
		} else {
			$alias = ' AS '.$options['alias'];
		}
		if (empty($options['join'])) {
			$join = '';
		} else {
			$join = ' '.join(' ', $options['join']);
		}
		if (empty($options['where'])) {
			$where = '';
		} else {
			$where = ' where '.$options['where'];
		}
		if (empty($options['order'])) {
			$order = '';
		} else {
			$order = ' order by '.$options['order'];
		}
		if (false === strpos($field, 'count(*)') && isset($options['multi']) && !$options['multi']) {
			$limit = ' limit 1';
		} else if (empty($options['limit'])) {
			$limit = '';
		} else {
			$limit = ' limit '.$options['limit'];
		}
		$sql = 'select '.$field.' from '.$table.$join.$alias.$where.$order.$limit;
		return $sql;
	}
	
	/**
	 * 处理数组字段值
	 * @param array $value
	 */
	public function arraySaveHandler($value) 
	{
		if ($this->arraySaveHandler == 'json_encode') {
			$value = json_encode($value);
		} else if ($this->arraySaveHandler == 'serialize') {
			$value = serialize($value);
		} else {
			$value = json_encode($value);
		}
		return $value;
	}

	/**
	 * 设置事务是否可提交状态
	 * @param boolean $state
	 */
	public function setTransactionState($state)
	{
		$this->canEndTransaction = $state;
	}

	/**
	 * (non-PHPdoc)
	 * @see PDO::beginTransaction()
	 */
	public function beginTransaction()
	{
		if (!$this->pdo->inTransaction) {
			$this->pdo->beginTransaction();
		}
		return true;
	}

	/**
	 * (non-PHPdoc)
	 * @see PDO::commit()
	 */
	public function commit()
	{
		if ($this->pdo->inTransaction) {
			if ($this->canEndTransaction) {
				$this->pdo->commit();
			}
			return true;
		} else {
			return false;
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see PDO::commit()
	 */
	public function rollBack()
	{
		if ($this->pdo->inTransaction) {
			if ($this->canEndTransaction) {
				$this->pdo->rollBack();
			}
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * SQL查询错误
	 * @param array $errors
	 */
	public function errors($errors) 
	{
		;
	}
}