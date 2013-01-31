<?php
/**
 * 分页处理类
 *
 * Created		: 2007-04-14
 * Modified		: 2012-08-16
 * @link		: http://www.dophin.co
 * @copyright	: (C) 2007-2012 Dophin 
 * @version		: 1.0.0
 * @author		: Joseph Chen (chenliq@gmail.com)
 */
class Page
{
	const PAGINATE_BY_NAVPAGE	= 1;
	const PAGINATE_BY_PAGECODE	= 2;
	const PAGINATE_BY_SELECT	= 4;
	const PAGINATE_BY_INPUT		= 8;
	
	/**
	 * 分页变量名
	 * @var string
	 */
	public static $pageVar = 'p';
	/**
	 * 分页面板组件ID
	 * @var string
	 */
	public static $pagePanel = 'page_panel';
	/**
	 * 分页面板组件使用的css的class名
	 * @var string
	 */
	public static $pagePanelClass = 'page_panel_class';
	/**
	 * 分页面板组件中的表单ID
	 * @var string
	 */
	public static $pageFormId = 'page_panel_form';
	/**
	 * 每页显示的最大记录数
	 * @var int
	 */
	public static $size;
	/**
	 * 当前页码
	 * @var int
	 */
	public static $page;
	/**
	 * 分页总数
	 * @var int
	 */
	public static $total;
	/**
	 * 每页起始记录
	 * @var int
	 */
	public static $from;
	/**
	 * 每页结束记录
	 * @var int
	 */
	public static $to;
	/**
	 * 总共记录数
	 * @var int
	 */
	public static $rows;
	/**
	 * 是否显示"显示第几条"的信息
	 * @var boolean
	 */
	public static $showRowsDetail = false;
	/**
	 * 默认分页最大值为15，超过时以另一种方式显示.(扩展用)
	 * @var int
	 */
	public static $maxIndex = 15;
	/**
	 * 首页链接
	 * @var string
	 */
	public static $firstUrl;
	/**
	 * 链接的URL参数模板
	 * @var string
	 */
	public static $qsTpl;
	/**
	 * 表单提交方式
	 * @var string
	 */
	public static $formMethod = 'GET';
	/**
	 * 显示分页的标签数量
	 * @var int
	 */
	public static $showPageNum = 8;

	/**
	 * 初始化分页相关的属性,初始用户输入
	 *
	 * @param int $size
	 * @param int $rows
	 * return boolean
	 */
	public static function init($size=10, $rows=0)
	{
		if ($size>0) {
			self::$size = $size;
		} elseif($size==0) {
			self::$size = 10;
		}
		//得到总记录数
		if ($rows>0) {
			self::$rows = $rows;//总记录数
		} else {
			self::$total = 0;
			self::$rows = 0;
		}
		//计算总页数
		if (self::$rows > 0 ) {
			self::$total = ceil(self::$rows/self::$size);
		}

		//设置当前页码
		self::setPage();

		//处理每页起止记录数
		self::setFromTo(self::$size);
		return true;
	}

	/**
	 * 设置每页起止记录位置
	 * @param $size
	 * @return unknown_type
	 */
	public static function setFromTo($size=0)
	{

		if (empty(self::$page)) {
			self::setPage();
		}
		if (!empty($size)) {
			self::$size = $size;
		}
		self::$from = (self::$page-1) * (self::$size);
		self::$to = self::$from + self::$size;
		if (self::$to > self::$rows) {
			self::$to = self::$rows;
		}
	}

	/**
	 * 设置页数信息
	 * @return boolean
	 */
	public static function setPage() {
		if (empty($_REQUEST[self::$pageVar])) {
			self::$page = 1;
		} else {
			self::$page = (int)$_REQUEST[self::$pageVar];
		}

		if (self::$page < 1) {
			self::$page = 1;
		} elseif (!empty(self::$total) && self::$page > self::$total ) {
			$qs = sprintf(self::$qsTpl, self::$total);
			$last = $_SERVER['PHP_SELF'].'?'.$qs;
			self::$page = self::$total;
		} else {
			;//当前页数即请求的页数
		}
		return true;
	}

	/**
	 * 处理url参数
	 */
	public static function parseQs() {
		parse_str($_SERVER['QUERY_STRING'], $qs_arr);
		if (array_key_exists(self::$pageVar, $qs_arr)) {
			unset($qs_arr[self::$pageVar]);
		}
		$qs = http_build_query($qs_arr);
		if (!empty($qs))
		{
			self::$firstUrl = $_SERVER['PHP_SELF'].'?'.$qs;
			$qs .= '&';
		} else {
			self::$firstUrl = $_SERVER['PHP_SELF'];
		}
		self::$qsTpl = urldecode($qs.self::$pageVar.'=%s');
	}

	/**
	 * 生成分页面板代码
	 * @param $param 需要传递的变量数组  格式 array("class"=>"投诉","sname"=>"姓名")
	 * 也可以是全局变量的数组,如$_POST,$_GET
	 * @param $type 分页选择/跳转类型
	 * @param $tpl
	 * @return string
	 */
	public static function generateHTML($param=array(), $type=0, $tpl='')
	{
		self::parseQs();//处理url参数

		//默认值
		if ($type == 0) {
			$type = self::PAGINATE_BY_PAGECODE;
		}
		// 显示页面分页,不需要
		if ($type & self::PAGINATE_BY_PAGECODE) {
			$isForm = false;
		} else {
			$isForm = true;
		}

		$qs = http_build_query($param);
		if (!empty($param) && !empty($_POST) && strlen($qs)>1000) {
			self::$formMethod = $method = 'POST';
		} else {
			$method = 'GET';
			$param = array_merge($_GET, $param);
		}
		$panelStr = '<div id="'.self::$pagePanel.'" style="width:99%;"'
		.' class="'.self::$pagePanelClass.'">'."\n";
		if ($isForm) {
			$panelStr .= '<form id="'.self::$pageFormId.'" name="" method="'.$method.'" '
					.'action="'.self::$firstUrl.'">'."\n";
		}

		//假如有附加变量,则添加相关的隐藏域来保存变量值
		if ($isForm && is_array($param)) {
			$param_str  = ' ';
			foreach ($param as $key => $value) {
				$except_param = array(self::$pageVar, 'submit');
				//只显示值不为空的隐藏域
				if ($value!='' && !in_array($key, $except_param)) {
					$panelStr .= '<input type="hidden" '
					.'name="'.$key.'" value="'.$value.'">'."\n";
				}
			}
		}

		if (self::$showRowsDetail && self::$rows>0) {
			$panelStr .= '共有记录：'.self::$rows.' 条&nbsp;&nbsp;';
			if ((self::$from+1) == self::$to) {
				$show = '显示第 '.self::$to.' 条';
			} else {
				$show = '显示：'.(self::$from+1).'-'.self::$to.' 条';
			}
			$panelStr .= $show.'&nbsp;&nbsp;当前页: <font color="#ff0000">'.self::$page.'</font>/'.self::$total;
		} else {
			$panelStr .= '';
		}
		//没有记录则退出
		if (self::$rows <= 0){
			$panelStr .= "\n</form>\n</div>\n";
			return $panelStr;
		}

		/*页码翻页 START*/
		if (self::$rows>0 && ($type & self::PAGINATE_BY_NAVPAGE)) {
			//顺序编码
			$panelStr .= self::getNavPageTurn();
		}
		if (self::$rows>0 && ($type & self::PAGINATE_BY_SELECT)) {
			//下拉框翻页码
			$panelStr .= self::getSelectPageTurn();
		}
		if (self::$rows>0 && ($type & self::PAGINATE_BY_INPUT)) {
			//输入框翻页
			$panelStr .= self::getInputPageTurn();
		}
		if (self::$rows>0 && ($type & self::PAGINATE_BY_PAGECODE)) {
			//页码列表翻页
			$panelStr .= self::getPageCodeTurn();
		}
		/*页码翻页 START*/

		if ($isForm) {
			$panelStr .= "\n</form>\n</div>\n";
		} else {
			$panelStr .= "\n</div>\n";
		}
		return $panelStr;
	}

	public static function getNavPageTurn()
	{
		$space = '&nbsp;&nbsp;&nbsp;';
		$page_first = '';
		$page_pre  = '';
		$page_next  = '';
		$page_last   = '';
		if (self::$page != 1) {
			$page_first = '<a href="'.self::$firstUrl.'">【首页】</a>&nbsp;';
		}
		$uri = $_SERVER['PHP_SELF'] . '?';
		if (self::$page > 1){
			$qs = sprintf(self::$qsTpl, self::$page - 1);
			$page_pre = '<a href="'.$uri.$qs.'">【上一页】</a>&nbsp;';
		}
		if (self::$page < self::$total) {
			$page = self::$page<self::$total ? (self::$page + 1) : self::$total;
			$qs = sprintf(self::$qsTpl, $page);
			$page_next ='<a href="'.$uri.$qs.'">【下一页】</a>&nbsp;';
		}
		if (self::$page != self::$total) {
			$qs = sprintf(self::$qsTpl, self::$total);
			$page_last = '<a href="'.$uri.$qs.'">【末页】</a>&nbsp;';
		}
		$ret = $page_first . $page_pre . $page_next . $page_last;
		if (!empty($ret)) {
			$ret = $space.$ret;
		}
		return $ret;
	}

	public static function getSelectPageTurn(){
		$ret = '&nbsp;转到第 <select name="'.self::$pageVar.'" '
		.'onChange="this.form.submit()" '
		.'style="font-size:12px; vertical-align:baseline">'.chr(10);
		for ($j=1;$j<=(self::$total);$j++){
			if ($j == self::$page) {
				$ret .= "<option selected>$j</option>\n";
			}else {
				$ret .= "<option>$j</option>\n";
			}
		}
		$ret .= "</select> 页\n";
		return $ret;
	}

	public static function getInputPageTurn() {
		$pageValue = (self::$page == self::$total) ? 1 : self::$page+1;
		return '转到第
			<input name="'.self::$pageVar.'" type="text" value="'.$pageValue.'"
			style="align:center;
			height:10pt;width:18pt;
			font-size:9pt;
			text-align: center;
			padding:0px 1px 0px 1px;
			border-bottom:1px solid #000000;
			border-left:0px;
			border-top:0px;
			border-right:0px">&nbsp;页
			<input type="submit" value="GO"
			style="height: 11pt;width:20pt;font-size:9pt;
			padding:2px 0px 0px 0px; margin: 1px;
			border:0px dashed #000000;">';
	}

	public static function getPageCodeTurn()
	{
		$tmp = ceil(self::$showPageNum/2);
		//设置超止页数
		if (self::$page>$tmp && self::$total>self::$showPageNum) {
			$start = self::$page-$tmp+1;
			$end = self::$page+$tmp;
			if ($end > self::$total) {
				$start = self::$total-(self::$showPageNum-1);
				$end = self::$total;
			}
		} else {
			$start = 1;
			// 超过10页，只显示1到10页
			if (self::$total>self::$showPageNum) $end = self::$showPageNum;
			else $end = self::$total;
		}
		$str = "";
		//起始页不是第一页才有必要加入链接
		if (self::$page != 1)
		{
			$str .= '<a href="'.self::$firstUrl.'" class="page_item page_first_label">'
			.'第一页</a>&nbsp&nbsp';
			if ($start != 1)
			{
				$str .= '...&nbsp;';
			}
			$str .= "\n";
		}
		for($i=$start;$i<=$end;$i++) {
			$pageurl = $_SERVER['PHP_SELF'].'?'.sprintf(self::$qsTpl, $i);
			if ($i==self::$page) {
				$str .= '<span class="page_item page_current">'.$i.'</span>&nbsp';
			} else {
				$str .= '<a href="'.$pageurl.'" class="page_item">'.$i.'</a>&nbsp';
			}
			$str .= "\n";
		}
		$qs = sprintf(self::$qsTpl, self::$total);
		if (self::$page != self::$total)
		{
			if ($end != self::$total)
			{
				$str .= '...&nbsp;';
			}
			$str .= '<a href="'.$_SERVER['PHP_SELF'].'?'.$qs.'" '
					.'class="page_item page_last_label">'
					."最后一页</a>";
		}
		return $str;
	}

}
