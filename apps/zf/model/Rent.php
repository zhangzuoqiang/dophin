<?php
/**
 * 出租模型
 * Created		: 2012-06-20
 * Modified		: 2012-12-05
 * @link		: http://www.fzzf.co
 * @copyright	: (C) 2012 fzzf.co 
 * @author		: Joseph Chen (chenliq@gmail.com)
 */
class Model_Rent extends Model
{
	// 表名(不含前缀)
	public $_tbl = 'let_house';
	// 表名
	public $tbl = 'let_house';
	
	/**
	 * 构造函数
	 * @param int $id
	 */
	public function __construct($id=0) 
	{
		if ($id)
		{
			$this->data = $this->read($id);
		}
		parent::__construct();
	}
	
	/**
	 * 获取列表
	 * @param array $options
	 * array(
	 * 	'keyword'	=> $keyword,// 关键字
	 * 	'post_time'	=> $post_time,// 发布日期
	 * 	'status'	=> $status,// 状态
	 * 	'order'		=> $order,// 排序字段
	 * 	'limit'		=> $limit,// 获取记录数
	 * 	'has_page'	=> $has_page,// 是否分页
	 *  'fields'	=> $fields//字段列表
	 * )
	 */
	public function getList($options=array()) 
	{
		extract($options);
		if (!isset($fields))
		{
			$fields = 'id,title,update_time';
		}
		// 按状态查询,默认全部
		if (isset($status) && !is_null($status) && ctype_digit((string)$status)) {
			$where = 'status='.$status;
		}
		// 指定日期,则按发布时间属于指定日期查询
		if (isset($post_time))
		{
			if (ctype_digit((string)$post_time))
			{
				$day = strtotime(date('Y-m-d', $post_time));
			} else {
				$day = strtotime($post_time);
			}
		}
		if (isset($post_time2))
		{
			if (ctype_digit((string)$post_time2)) 
			{
				$endDay = strtotime(date('Y-m-d', $post_time2));
			} else {
				$endDay = strtotime($post_time2);
			}
		}
		// 同时指定查询起止时间
		if (isset($post_time) && isset($post_time2))
		{
			$w = 'post_time>='.$day.' and post_time<'.$endDay;
			$where = isset($where) ? $where.' and '.$w : $w;
		}
		// 只指定发布时间起始范围
		else if (isset($post_time)) {
			$w = 'post_time>='.$day;
			$where = isset($where) ? $where.' and '.$w : $w;
		}
		// 只指定发布时间截止范围
		else if (isset($post_time2)) {
			$w = 'post_time<'.($endDay + 86400);
			$where = isset($where) ? $where.' and '.$w : $w;
		}
		// 关键字查询
		if (isset($keyword))
		{
			$w = 'title like \'%:keyword%\'';
			$where = isset($where) ? $where.' and '.$w : $w;
			$params = array('keyword' => $keyword);
		} else {
			$params = null;
		}
		if (!isset($where))
		{
			$where = null;
		}
		if (!isset($limit))
		{
			$limit = $this->pageSize;
		}
		if (!isset($order))
		{
			$order = null;
		}
		// 是否有分页
		if (isset($has_page) && $has_page)
		{
			$rows = $this->where($where)->params($params)->count();
			Page::init($limit, $rows);
			$this->pagePanel = Page::generateBarCode();
			$start = Page::$from;
			$limit = Page::$to;
		} else {
			$start = 0;
		}
		
		$list = $this->field($fields)->where($where)->params($params)
					->order($order)->limit($limit, $start)->select();
		return $list;
	}
	
	
	
}