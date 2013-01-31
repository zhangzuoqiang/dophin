<?php
/**
 * 模块基类
 * 
 * Created		: 2011-06-05
 * Modified		: 2012-07-24 
 * @link		: http://www.dophin.co
 * @copyright	: (C) 2011-2012 Dophin 
 * @version		: 1.0.0
 * @author		: Joseph Chen (chenliq@gmail.com)
 */
class Model
{
	/**
	 * 表名前缀
	 * @var string
	 */
	public $tblPre	= null;
	/**
	 * 表名
	 * @var string
	 */
	public $_tbl	= 'model';
	/**
	 * 完整表名
	 * @var string
	 */
	public $tbl		= 'model';
	/**
	 * 主键字段名
	 * @var string
	 */
	public $priKey	= 'id';
	/**
	 * 当前记录主键值
	 * @var int
	 */
	public $id = 0;
	/**
	 * 字段列表
	 * @var string
	 */
	public $fields	= array();
	/**
	 * 上次插入数据的ID
	 * @var int
	 */
	public $lastInsertId = 0;
	/**
	 * 字段为整型的类型列表
	 * @var array
	 */
	public $intTypes = array('int', 'tinyint', 'bigint');
	/**
	 * 默认分页数量
	 * @var int
	 */
	public $pageSize = 10000;
	/**
	 * 分页条代码
	 * @var string
	 */
	public $pagePanel	= '';
	/**
	 * 当前记录数据
	 * @var array()
	 */
	public $data = array();
	/**
	 * 查询相关参数
	 * @var array()
	 */
	public $options = array();
	/**
	 * 当前实例化类对象
	 * @var object
	 */
	public $instanceClass = null;
	/**
	 * 是否使用缓存
	 * @var boolean
	 */
	public $useCache = false;
	/**
	 * 缓存时间长度
	 * @var int
	 */
	public $cacheExpire = 86400;
	/**
	 * 提示信息（包含但不限于错误提示）
	 * @var string
	 */
	public $msg = '';
	/**
	 * 错误信息
	 * @var array
	 */
	protected $errors	= null;
	
	/**
	 * 构造函数
	 * @param string $name 模型名称
	 * @param array|string $params 参数
	 */
	public function __construct($name='', $params='')
	{
		// 有指定的模型
		if ($name) {
			$className = 'Model_'.ucfirst($name);
			// 用指定模型的属性值覆盖默认属性值
			if (class_exists($className)) {
				$this->instCoreiation($className);
			} else {
				if (defined('MO_PATH')) {
					$filename = MO_PATH.substr($className, strrpos($className, '_')+1).'.php';
					if (is_file($filename)) {
						require_once $filename;
					}
					if (class_exists($className)) {
						$this->instCoreiation($className);
					}
				}
				$this->_tbl = $name;
			}
		}
		if (is_string($params)) {
			$this->tblPre = $params;
		} elseif (isset($params['tbl_pre'])) {
			$this->tblPre = $params['tbl_pre'];
		} elseif (is_null($this->tblPre)) {
			$this->tblPre = C('tbl_pre');
		}
		
		$this->tbl = $this->tblPre.$this->_tbl;
		
		// 载入字段配置
		$cfgName = 'fields/'.$this->_tbl;
		$this->fields = Core::C($cfgName);
		if (!$this->fields) {
			$this->fields = Db::getFields($this->tbl);
			// 设置主键信息
			if (is_array($this->fields)) {
				foreach ($this->fields as $field => $v) {
					if ($v['Key'] == 'PRI') {
						$this->fields['_PRI_'] = $field;
						$this->priKey = $field;
					}
				}
				Core::setC($cfgName, $this->fields);
			}
		} else {
			if (isset($this->fields['_PRI_'])) {
				$this->priKey = $this->fields['_PRI_'];
			}
		}
		/**
		 * 实例模型类的表对应主键字段名
		 */
		if ($this->instanceClass && $this->instanceClass->priKey=='id') {
			$this->instanceClass->priKey = $this->priKey;
		}
	}
	
	/**
	 * 实例化子类,将属性覆盖当前模型属性,且使其方法可以被模型调用
	 * @param string $className
	 */
	private function instCoreiation($className) 
	{
		$vars = get_class_vars($className);
		foreach ($vars as $key => $value) {
			if ($key == 'priKey') continue;
			$this->$key = $value;
		}
		$this->instanceClass = new $className;
	}
	
	/**
	 * 调用已实例化模型的方法
	 * @param string $name
	 * @param array $arguments
	 */
	public function __call($name, $arguments) 
	{
		return call_user_func_array(array($this->instanceClass, $name), $arguments);
	}
	
	/**
	 * 获取指定条件查询结果的行数
	 * @param array $options
	 */
	public function count($options=null) 
	{
		if (is_null($options)) {
			$options = $this->options;
		}
		$options['fields'] = 'count(*)';
		$options['multi'] = false;
		$count = Db::fetches($this->tbl, $options);
		return $count;
	}
	
	/**
	 * 选择列表数据
	 * @param array $options
	 */
	public function select($options=null)
	{
		if (is_null($options)) {
			$options = $this->options;
		}
		$options['multi'] = true;
		// 是否有分页
		if (isset($options['has_page']) && $options['has_page']) {
			$rows = $this->count();
			Page::init($this->pageSize, $rows);
			$this->pagePanel = Page::generateHTML();
			$options['limit'] = array(Page::$from, Page::$to);
		} else {
			$options['limit'] = $this->pageSize;
		}
		$list = Db::fetches($this->tbl, $options);
		return $list;
	}
	
	/**
	 * 根据参数条件读取一条记录
	 * @param array $options
	 */
	public function fetch($options=null) 
	{
		if (is_null($options)) {
			$options = $this->options;
		}
		$options['multi'] = false;
		$list = Db::fetches($this->tbl, $options);
		return $list;
	}
	
	/**
	 * 读取一条记录
	 * @param string|int|float $val
	 * @param string $field
	 */
	public function read($val=null, $field='', $fetchFields='*')
	{
		if ($this->useCache) {
			$this->data = Cache::gets($val, $this->tbl);
			if ($this->data) return $this->data;
		}
		if ($field) {
			$this->data = Db::read($this->tbl, $val, $field, $fetchFields);
		} else {
			$this->data = Db::read($this->tbl, $val, $this->priKey, $fetchFields);
		}
		if ($this->useCache) {
			Cache::setProperties('outputSetKey', true);
			Cache::sets($val, $this->data, $this->tbl, null, null, $this->cacheExpire);
		}
		return (!$this->data) ? array() : $this->data;
	}
	
	/**
	 * 保存一条记录数据
	 * @param array $data
	 */
	public function save($data=null)
	{
		// 处理数组中的字段
		$data = $this->data($data);
		if (!$data) {
			return false;
		}
		if (isset($this->options['params']) && $this->options['params']) {
			$params = $this->options['params'];
		} else {
			$params = null;
		}
		$this->lastInsertId = 0;
		if (isset($this->options['where']) && $this->options['where']) {
			return $this->update($data, $this->options['where'], $params);
		} else if (isset($data[$this->priKey]) && $data[$this->priKey]) {
			return $this->update($data, $this->priKey.'='.$data[$this->priKey], $params);
		} else {
			return $this->add($data);
		}
	}
	
	/**
	 * 向表中插入一条数据
	 * @param array $data 要添加的数据
	 * @param bool $multi 是否添加多条数据
	 */
	public function add($data, $multi=false)
	{
		$this->lastInsertId = 0;
		$ret = Db::insert($this->tbl, $data, $multi);
		if ($ret) {
			$this->lastInsertId = Db::lastInsertId();
			$this->id = $this->lastInsertId;
			$this->data[$this->priKey] = $this->id;
			return true;
		} else {
			$errors = Db::errors();
			if (APP_DEBUG) {
				trigger_error('Error occured on insert data to database.'.var_export($errors, true));
			} else {
				trigger_error('Error occured on insert data to database.');
			}
			return false;
		}
	}
	
	/**
	 * 根据条件更新表中的数据
	 * @param array $data 要添加的数据
	 * @param string $where 更新的条件
	 * @param array $params execute参数
	 * @param string $table 数据添加到哪个表
	 */
	public function update($data, $where=null, $params=null)
	{
		$this->lastInsertId = 0;
		$ret = Db::update($this->tbl, $data, $where, $params);
		if ($ret) {
			return true;
		} else {
			$errors = Db::errors();
			if (APP_DEBUG) {
				trigger_error('Error occured on update data to database.'.var_export($errors, true));
			} else {
				trigger_error('Error occured on update data to database.');
			}
			return false;
		}
	}
	
	/**
	 * 删除记录(状态设为删除)
	 * @param string|int $where 删除条件,为整数时表示删除主键,ID值为$where
	 * @param array $params execute参数
	 * @param array $errMsg 失败的提示信息
	 * @param array $successMsg 成功的提示信息
	 */
	public function trash($where='', $params=null, $errMsg=null, $successMsg=null)
	{
		if (Variable::is_digit($where)) {
			$where = $this->priKey.'='.$where;
		} elseif (Variable::digitSepBunch) {
			$where = $this->priKey.' in ('.$where.')';
		}
		if (is_null($errMsg)) {
			$errMsg = $GLOBALS['_DataBaseTrashFailure'];
		}
		return $this->setStatus(
			-1, 
			$where, 
			$params, 
			$errMsg, 
			$successMsg
		);
	}
	
	/**
	 * 删除记录
	 * @param string|int $where 删除条件,为整数时表示删除主键,ID值为$where
	 * @param array $params execute参数
	 */
	public function delete($where='', $params=null)
	{
		if (Variable::is_digit($where)) {
			$where = $priKey.'='.$where;
		} elseif (Variable::digitSepBunch) {
			$where = $priKey.' in ('.$where.')';
		}
		if (is_null($errMsg)) {
			$errMsg = $GLOBALS['_DataBaseDeleteFailure'];
		}
		$ret = Db::delete($this->tbl, $where, $params);
		if ($ret) {
			return true;
		} else {
			$errors = Db::errors();
			if (APP_DEBUG) {
				trigger_error('Error occured on insert data to database.'.var_export($errors, true));
			} else {
				trigger_error('Error occured on insert data to database.');
			}
			return false;
		}
	}
	
	/**
	 * 对给定字段值进行增加
	 * @param array $fields 要加值的字段和加值数组
	 * @param string $where 更新的条件
	 * @param array $params execute参数
	 */
	public function increase($fields, $where, $params=null) 
	{
		$ret = Db::increase($this->tbl, $fields, $where, $params);
		if ($ret) {
			return true;
		} else {
			$errors = Db::errors();
			if (APP_DEBUG) {
				trigger_error('Error occured on update data to database.'.var_export($errors, true));
			} else {
				trigger_error('Error occured on update data to database.');
			}
			return false;
		}
	}
	
	/**
	 * 设置当前对象模型数据
	 * @param array|object $data
	 */
	public function data($data=null) 
	{
		if (empty($data)) {
			if (empty($this->data)) {
				$data = $_POST;
			} else {
				$data = $this->data;
			}
		} else if (is_object($data)) {
			$data = get_object_vars($data);
		} else if (!is_array($data)) {
			throw new Exception('data_must_be_array_or_object');
		}
		$this->data = array();
		foreach ($this->fields as $field=>$v)
		{
			// 配置的主键对应字段说明
			if ($field == '_PRI_') continue;
			// 主键如果是未设置值，则跳过
			if ($field == $this->priKey && (empty($data[$field])||!ctype_digit($data[$field]))) {
				continue;
			}
			// 字段未设置值，跳过
			else if (!isset($data[$field])) {
				continue;
			} else {
				if (is_array($data[$field])) {
					$data[$field] = json_encode($data[$field]);
				}
				// 如果有字段验证规则，则调用
				if (isset($this->validation[$field])) {
					$ret = $this->validate($data[$field], $field, $this->validation[$field]);
					if (!$ret) {
						return false;
					}
				}
			}
			$this->data[$field] = $data[$field];
		}
		return $this->data;
	}
	
	/**
	 * 要获取的字段列表
	 * @param string|array $fields
	 */
	public function field($fields) 
	{
		if (!$fields) {
			return $this;
		}
		$this->options['fields'] = $fields;
		return $this;
	}
	
	/**
	 * 查询的条件
	 * @param string|array $fields
	 */
	public function where($where) 
	{
		if (!$where) {
			return $this;
		}
		if (is_array($where)) {
			$whereStr = '';
			foreach ($where as $key => $value) {
				$whereStr .= $key.'=:'.$key.' and ';
				$this->options['params'][$key] = $value;
			}
			$where = substr($whereStr, 0, -4);
		}
		$this->options['where'] = $where;
		return $this;
	}
	
	/**
	 * 查询结果的排序
	 * @param string $order
	 */
	public function order($order=null) 
	{
		if (!$order) {
			return $this;
		}
		$this->options['order'] = $order;
		return $this;
	}
	
	/**
	 * SQL查询条数的限制
	 * @param int $num
	 * @param int $start
	 * @return Model
	 */
	public function limit($num, $start=0)
	{
		$this->options['limit'] = $start.','.$num;
		return $this;
	}
	
	/**
	 * SQL查询用到的param参数
	 * @param array $params
	 * @return Model
	 */
	public function params($params) 
	{
		if (!$params) {
			return $this;
		}
		$this->options['params'] = $params;
		return $this;
	}
	
	/**
	 * 清空options参数
	 * @return boolean
	 */
	public function clearOptions()
	{
		$this->options = array();
		return true;
	}
	
	/**
	 * 字段值验证
	 * @param string $value
	 * @param string $field
	 * @param array|string $rule
	 */
	public function validate($value, $field, $rule) 
	{
		if (is_string($rule)) {
			$ruleName = $rule;
		} else {
			$ruleName = $rule[0];
		}
		if (is_array($rule) && isset($rule[1])) {
			$msg = $rule[1];
		}
		switch ($ruleName)
		{
			case 'require':
				if (is_null($value) || $value=='') {
					$replacement = array('{field}' => $field);
					if (!isset($msg)) {
						$msg = 'field_is_required';
					}
					$this->msg = Core::getLang($msg, $replacement);
					return false;
				}
			break;
			
			default:
				if (method_exists($this, $ruleName)) {
					$ret = call_user_func_array(array($this, $ruleName), array($value));
					if (!$ret) {
						return false;
					}
				} else if (function_exists($ruleName)) {
					$ret = call_user_func($ruleName, $value);
					if (!$ret) {
						$replacement = array('{field}' => $field, '{value}' => $value);
						if (!isset($msg)) {
							$msg = 'field_invalid';
						}
						$this->msg = Core::getLang($msg, $replacement);
						return false;
					}
				}
			break;
		}
		return true;
	}
	
	/**
	 * 设置数据对象的值
	 * @param string $field
	 * @param mixed $value
	 */
	public function __set($field, $value)
	{
		// 设置数据对象属性
		$this->data[$field] = $value;
	}
	
	/**
	 * 获取数据对象的值
	 * @param string $field
	 * @param mixed $value
	 */
	public function __get($field)
	{
		return isset($this->data[$field])?$this->data[$field]:null;
	}
	
	/**
	 * 获取模型上次操作对应的SQL语句
	 */
	public function getLastSql($withParam=true) 
	{
		if ($withParam) {
			return array(
				'sql'	=> Db::getLastSql(),
				'param'	=> Db::getLastSqlParam()
			);
		} else {
			return Db::getLastSql();
		}
	}
	
	/**
	 * 返回/设置错误信息
	 * @param $errors false-返回错误信息,其它参数设置错误信息
	 */
	public function errors($errors=false)
	{
		if ($errors === false) {
			return $this->errors;
		} else {
			$this->errors = $errors;
			return $this->errors;
		}
	}
}












