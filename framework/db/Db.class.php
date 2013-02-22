<?php
/**
 * 数据库操作类
 *
 * Created		: 2009-07-12
 * Modified		: 2012-09-12 
 * @link		: http://www.dophin.co/
 * @copyright	: © 2009-2012 Dophin.Com
 * @version		: 3.0.0
 * @author		: Joseph Chen <chenliq@gmail.com>
 */
class Db
{
	/**
	 * 当前主键字段名
	 * @var string
	 */
	private static $priKey = 'id';
	/**
	 * 当前数据库操作实例
	 * @var object
	 */
	private static $db = null;
	/**
	 * 所有链接的数据库实例
	 * @var array(object, object ... )
	 */
	private static $dbs = array();
	/**
	 * 当前脚本已执行的查询数
	 * @var int
	 */
	private static $queries = 0;
	/**
	 * 字段值为数组时保存的字符串格式,默认转成json格式保存
	 * @var string
	 */
	private static $arraySaveHandler = 'json_encode';
	/**
	 * SQL查询的一些选项设置
	 * @var array
	 */
	private static $options = array();
	
	private static $errors = array();


	/**
	 * 连接到一个数据库
	 * @param array $cfg
	 */
	public static function connect($cfg, $sign='default')
	{
		if (isset($cfg['db_driver'])) {
			$driverName = ucfirst($cfg['db_driver']);
		} else {
			$driverName = 'Mysql';
		}
		self::$db = new $driverName($cfg);
		self::$dbs[$sign] = self::$db;
		self::setLastSqlNameSpace($sign);
		return self::_db();
	}

	/**
	 * 切换使用的DB连接
	 * @param string $sign
	 */
	public static function switchDb($sign='default')
	{
		if (isset(self::$connectList[$sign])) {
			self::$db = self::$connectList[$sign];
			return self::_db();
		} else {
			return false;
		}
	}
	
	/**
	 * 获取当前数据类实例对象
	 */
	public static function _db($cfg=null, $sign='') 
	{
		if (self::$db) {
			return self::$db;
		} else if (is_string($cfg)) {
			return self::switchDb($cfg);
		} else if (is_array($cfg)) {
			if (!$sign) {
				$sign = 'default';
			}
			return self::connect($cfg, $sign);
		} else {
			return self::connect(array(
				'db_driver'		=> $GLOBALS['_db']['driver'],
				'db_host'		=> $GLOBALS['_db']['host'],
				'db_name'		=> $GLOBALS['_db']['name'],
				'db_user'		=> $GLOBALS['_db']['user'],
				'db_pass'		=> $GLOBALS['_db']['pass'],
				'db_charset'	=> $GLOBALS['_db']['charset'],
				'db_persistent'	=> $GLOBALS['_db']['persistent']
			));
		}
	}

	/**
	 * 设置事务是否可提交状态
	 * @param boolean $state
	 */
	public static function setTransactionState($state)
	{
		self::_db()->setTransactionState($state);
	}

	/**
	 * (non-PHPdoc)
	 * @see PDO::beginTransaction()
	 */
	public static function beginTransaction()
	{
		self::_db()->beginTransaction();
	}

	/**
	 * (non-PHPdoc)
	 * @see PDO::commit()
	 */
	public static function commit()
	{
		self::_db()->commit();
	}

	/**
	 * (non-PHPdoc)
	 * @see PDO::commit()
	 */
	public static function rollBack()
	{
		self::_db()->rollBack();
	}
	
	public static function execute($sql, $param=null) 
	{
		return self::_db()->execute($sql, $param);
	}
	
	/**
	 * 向表中插入/更新一条记录
	 * @param string $table
	 * @param array $data 插入的数据列表(数组形式)
	 */
	public static function replace($table, $data) 
	{
		$ret = self::_db()->replace($table, $data);
		if (!$ret) {
			self::$errors = self::_db()->errors;
		}
		return $ret;
	}
	
	/**
	 * 向表中插入一条记录
	 * @param string $table
	 * @param array $data 插入的数据列表(数组形式)
	 * @param boolean $multi 插入的数据是否多条
	 * @return int | false
	 */
	public static function insert($table, $data, $multi=false) 
	{
		$ret = self::_db()->insert($table, $data, $multi);
		if (!$ret) {
			self::$errors = self::_db()->errors;
		}
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
	public static function update($table, $data, $where=null, $param=null)
	{
		$ret = self::_db()->update($table, $data, $where, $param);
		if (!$ret) {
			self::$errors = self::_db()->errors;
		}
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
	public static function increase($table, $data, $where=null, $param=null)
	{
		$tmp = array();
		foreach ($data as $key=>$value)
		{
			$tmp['@+'.$key] = $value;
		}
		unset($data);
		$ret = self::_db()->update($table, $tmp, $where, $param);
		if (!$ret) {
			self::$errors = self::_db()->errors;
		}
		return $ret;
	}

	/**
	 * 执行删除操作
	 * @param string $table
	 * @param string $where
	 * @return int | false
	 */
	public static function delete($table, $where, $param=null)
	{
		$ret = self::_db()->delete($table, $where, $param);
		if (!$ret) {
			self::$errors = self::_db()->errors;
		}
		return $ret;
	}
	
	/**
	 * 从表中读取一条记录
	 * @param string $table 表名
	 * @param string $value 值(查询字段的值)
	 * @param string $field 字段，查询条件的字段
	 * @param string $fetchFields 获取的字段
	 */
	public static function read($table, $value, $field='id', $fetchFields='*') 
	{
		$ret = self::_db()->read($table, $value, $field, $fetchFields);
		if (!$ret) {
			self::$errors = self::_db()->errors;
		}
		return $ret;
	}
	
	/**
	 * 读取指定条件的数据
	 * @param string|array $options
	 */
	public static function select($options=array()) 
	{
		if (is_string($options)) {
			$table = $options;
			$options = array();
		}
		$options = array_merge(self::$options, $options);
		if (!isset($options['multi'])) {
			$options['multi'] = true;
		}
		$sql = self::_db()->buildSql($table, $options);
		if (empty($options['params'])) {
			$param = null;
		} else {
			$param = $options['params'];
		}
		$ret = self::_db()->fetchFromSql($sql, $options['multi'], $param);
		if (!$ret) {
			self::$errors = self::_db()->errors;
		}
		return $ret;
	}
	
	/**
	 * 读取指定条件的数据
	 * @param string $table
	 * @param string|array $options
	 */
	public static function fetches($table, $options=array()) 
	{
		if (is_string($options)) {
			$options = array(
				'where'	=> $options
			);
		}
		$options = array_merge(self::$options, $options);
		if (!isset($options['multi'])) {
			$options['multi'] = true;
		}
		$sql = self::_db()->buildSql($table, $options);
		if (empty($options['params'])) {
			$param = null;
		} else {
			$param = $options['params'];
		}
		$ret = self::_db()->fetchFromSql($sql, $options['multi'], $param);
		if (!$ret) {
			self::$errors = self::_db()->errors;
		}
		return $ret;
	}
	
	/**
	 * 直接从SQL语句获取数据
	 * @param $sql SQL语句
	 * @param $multi 是否获取多条
	 * @param $param SQL参数
	 * @param $fetchStyle 获取数据的字段方式
	 */
	public static function fetchFromSql($sql, $multi=true, $param=null, $fetchStyle=PDO::FETCH_ASSOC)
	{
		return self::_db()->fetchFromSql($sql, $multi, $param, $fetchStyle);
	}
	
	/**
	 * 重置查询的选项
	 */
	public static function resetSelectOptions() 
	{
		self::$options = array();
		return true;
	}
	
	/**
	 * 设置当前查询表名的别名
	 * @param  $alias
	 */
	public static function alias($alias)
	{
		self::$options['alias'] = $alias;
	}
	
	/**
	 * 查询获取的字段列表
	 * @param string|array $fields
	 */
	public static function field($fields) 
	{
		if (is_array($fields)) {
			$fields = '`'.join('`,`', $fields).'`';
		}
		self::$options['fields'] = $fields;
	}
	
	/**
	 * 查询的条件
	 * @param string|array $fields
	 */
	public static function where($where) 
	{
		if (is_array($where)) {
			$where = join(' and ', $where);
		}
		self::$options['where'] = $where;
		return self;
	}
	
	/**
	 * 查询的包含的参数
	 * @param array $fields
	 */
	public static function params($param) 
	{
		self::$options['params'] = $param;
	}
	
	/**
	 * 查询的结果排序
	 * @param string|array $fields
	 */
	public static function order($order) 
	{
		if (is_array($order)) {
			$order = join(',', $order);
		}
		self::$options['order'] = $order;
	}
	
	/**
	 * 查询的结果排序
	 * @param string|array $fields
	 */
	public static function limit($limit) 
	{
		if (is_array($limit)) {
			$limit = join(',', $limit);
		}
		self::$options['limit'] = $limit;
	}
	
	/**
	 * 联合表查询
	 * @param string $table
	 * @param string $on
	 * @param string $joinType
	 */
	public static function join($option, $on='', $joinType='inner') 
	{
		if (is_array($option)) {
			self::$options['join'] = array();
			if (isset($option[0])) {
				foreach ($option as $j) {
					if (!empty($j['join'])) {
						$joinType = $j['join'];
					}
					self::$options['join'][] = $joinType.' join '.$j['table'].' on '.$j['on'];
				}
			} else {
				if (!empty($option['join'])) {
					$joinType = $option['join'];
				}
				self::$options['join'][] = $joinType.' join '.$option['table'].' on '.$option['on'];
			}
		} else {
			$join = ' '.$joinType.' join '.$option.' on '.$on;
			if (empty(self::$options['join'])) {
				self::$options['join'] = array();
			}
			self::$options['join'][] = $join;
		}
	}
	
	/**
	 * 重置所有条件参数
	 */
	public static function options($options=array()) 
	{
		self::$options = $options;
	}
	
	/**
	 * 返回上次执行操作的受影响记录数
	 */
	public static function affectRows() 
	{
		return self::_db()->affectRows();
	}
	
	/**
	 * 获取上次插入记录的ID
	 * @see PDO::lastInsertId()
	 */
	public static function lastInsertId($param=null) 
	{
		return self::_db()->lastInsertId($param);
	}
	
	/**
	 * 上次执行的SQL语句
	 * @param string $name
	 */
	public static function getLastSql($name='default') 
	{
		return self::_db()->getLastSql($name);
	}
	
	/**
	 * 上次执行的SQL语句参数
	 * @param string $name
	 */
	public static function getLastSqlParam($name='default') 
	{
		return self::_db()->getLastSqlParam($name);
	}
	
	/**
	 * 获取上次执行的SQL语句和参数
	 * @param string $name
	 * @return array(
	 * 	'sql' => $sql,
	 * 	'param' => $param
	 * )
	 */
	public static function getLastExecute($name='default') 
	{
		return array(
			'sql'	=> self::getLastSql(),
			'param'	=> self::getLastSqlParam()
		);
	}
	
	/**
	 * 获取一个表的字段信息
	 * @param string $table
	 */
	public static function getFields($table) 
	{
		return self::_db()->getFields($table);
	}
	
	/**
	 * 返回一个表的信息
	 * @param string $table
	 */
	public static function showTableStatus($table) 
	{
		return self::_db()->showTableStatus($table);
	}
	
	/**
	 * 获取自增字段当前值
	 * @param string $table
	 */
	public static function getAutoIncrement($table) 
	{
		return self::_db()->getAutoIncrement($table);
	}
	
	/**
	 * 设置上次执行的SQL语句保存的命名空间
	 * @param string $name
	 */
	public static function setLastSqlNameSpace($name='default') 
	{
		self::_db()->setLastSqlNameSpace($name);
	}
	
	
	/**
	 * 设置/获取当前数据库数组数据保存的处理方式
	 * @param string $handler
	 */
	public static function arraySaveHandler($handler='')
	{
		if ($handler) {
			self::$arraySaveHandler = $handler;
		} else {
			return self::$arraySaveHandler;
		}
	}
	
	/**
	 * 获取SQL执行出错信息
	 */
	public static function errors() 
	{
		return self::_db()->errors;
	}
}