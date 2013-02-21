<?php
/**
 * 品牌模型
 * Created		: 2013-02-20
 * Modified		: 2013-02-20
 * @brand		: http://www.mp99.cn
 * @copyright	: (C) 2012-2013 mp99.cn 
 * @author		: Joseph Chen (chenliq@gmail.com)
 */
class Model_Brand extends Model
{
	/**
	 * 表名(不含前缀)
	 * @var string
	 */
	public $_tbl = 'brand_info';
	/**
	 * 表名
	 * @var string
	 */
	public $tbl = 'brand_info';
	/**
	 * 保存品牌内容表
	 * @var string
	 */
	public $tbl_content = 'brand_info_content';
	/**
	 * 品牌内容保存方式:txt-文本文件内容保存，db-保存到数据库独立的表
	 * @var string
	 */
	public $saveConentType = 'txt';
	/**
	 * 品牌内容以文本文件保存时的存储路径，相对于应用根路径
	 * @var string
	 */
	public $saveContentPath = 'brand';
	/**
	 * 保存品牌内容，静态页等相关文件路径的ID基数
	 * @var int
	 */
	public $pathRadix = 200;
	/**
	 * 状态列表
	 * @var array
	 */
	public $statusList = array(
		-1	=> '删除',
		0	=> '待审核',
		1	=> '正常',
	);
	
	public $validation = array(
		'title'	=> 'require'
	);
	
	/**
	 * 构造函数
	 * @param int $id
	 */
	public function __construct($id=0) 
	{
		if ($id) {
			$this->data = $this->read($id);
		}
		if ($GLOBALS['_path_radix'])
		{
			$this->pathRadix = $GLOBALS['_path_radix'];
		}
		$this->saveContentType = $GLOBALS['_save_brand_note_type'];
		$this->saveContentPath = $GLOBALS['_save_brand_note_path'];
		parent::__construct();
	}
	
	/**
	 * 获取列表
	 * @param array $options
	 * array(
	 * 	'cid'		=> $cid,// 分类ID
	 * 	'keyword'	=> $keyword,// 关键字
	 * 	'create_time'	=> $create_time,// 发布日期
	 * 	'create_time2'=> $create_time2,// 发布日期截止时间
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
		if (!isset($fields)) {
			$fields = 'id,title,title_image,create_time';
		}
		// 按状态查询,默认全部
		if (isset($status) && !is_null($status) && ctype_digit((string)$status)) {
			$where = 'status='.$status;
		}
		// 指定日期,则按发布时间属于指定日期查询
		if (isset($create_time)) {
			if (ctype_digit((string)$create_time)) {
				$day = strtotime(date('Y-m-d', $create_time));
			} else {
				$day = strtotime($create_time);
			}
		}
		if (isset($create_time2)) {
			if (ctype_digit((string)$create_time2)) {
				$endDay = strtotime(date('Y-m-d', $create_time2));
			} else {
				$endDay = strtotime($create_time2);
			}
		}
		// 同时指定查询起止时间
		if (isset($create_time) && isset($create_time2)) {
			$w = 'create_time>='.$day.' and create_time<'.$endDay;
			$where = isset($where) ? $where.' and '.$w : $w;
		}
		// 只指定发布时间起始范围
		else if (isset($create_time)) {
			$w = 'create_time>='.$day;
			$where = isset($where) ? $where.' and '.$w : $w;
		}
		// 只指定发布时间截止范围
		else if (isset($create_time2)) {
			$w = 'create_time<'.($endDay + 86400);
			$where = isset($where) ? $where.' and '.$w : $w;
		}
		// 分类查询
		if (isset($cid) && ctype_digit((string)$cid)) {
			$where = isset($where) ? $where.' and cid='.$cid : 'cid='.$cid;
		}
		// 关键字查询
		if (isset($keyword)) {
			$w = 'FIND_IN_SET(:keyword, `keywords`)';
			$where = isset($where) ? $where.' and '.$w : $w;
			$params = array('keyword' => $keyword);
		} else {
			$params = null;
		}
		if (!isset($where)) {
			$where = null;
		}
		if (!isset($limit)) {
			$limit = $this->pageSize;
		}
		if (!isset($order)) {
			$order = 'order_score desc';
		}
		// 是否有分页
		if (isset($has_page) && $has_page) {
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
	
	/**
	 * 获取品牌ID对应的HTML静态页路径
	 * @param int $id
	 * @param string $filename
	 * @return string
	 */
	public function getHtmlFile($id, $filename='') 
	{
		if (!$filename)
		{
			$filename = String::idCrypt($id);
		}
		return WEB_PATH.'html/brand/'.String::getIdRadixPath($id, $this->pathRadix).'/'.$filename.'.html';
	}
	
	/**
	 * 获取品牌ID对应的HTML静态页URL
	 * @param int $id
	 * @param string $filename
	 * @return string
	 */
	public function getHtmlUrl($id, $filename='') 
	{
		if (!$filename)
		{
			$filename = String::idCrypt($id);
		}
		return 'html/brand/'.String::getIdRadixPath($id, $this->pathRadix).'/'.$filename.'.html';
	}
	
}