<?php
/**
 * 出租
 * Created		: 2012-12-13
 * Modified		: 2012-12-13
 * @link		: http://www.fzezf.com
 * @copyright	: (C) 2012 fzezf.com 
 * @author		: Joseph Chen (chenliq@gmail.com)
 */
class Controller_Rent extends Controller
{
	/**
	 * 构造函数，初始化操作
	 */
	public function __construct() 
	{
		
	}
	
	/**
	 * 出租列表
	 * @see Controller::index()
	 */
	public function index() 
	{
		$mo = new Model_Rent();
		$this->list = $mo->getList($_POST);
	}
	
	
	
}