<?php
/**
 * 数组操作
 *
 * Created		: 2011-06-14
 * Modified		: 2012-07-19
 * @link		: http://www.dophin.co
 * @copyright	: (C) 2011-2012 Dophin 
 * @version		: 1.0.0
 * @author		: Joseph Chen (chenliq@gmail.com)
 */
class Arr
{

	/**
	 * 将二维数组转成简单键值对的一维数组
	 * @param array $arr
	 * @param string $idField
	 * @param string $nameField
	 */
	public static function simple($arr, $idField='id', $nameField='name')
	{
		$tmp = array();
		if (!is_array($arr))
		{
			return array();
		}
		foreach ($arr as $item)
		{
			$key = $item[$idField];
			$tmp[$key] = $item[$nameField];
		}
		return $tmp;
	}
	
	/**
	 * 将数组转换成某字段为KEY的数组
	 * @param array $arr
	 * @param string $field
	 */
	public static function idArray($arr, $field) 
	{
		$_arr = array();
		if (!is_array($arr))
		{
			return array();
		}
		foreach ($arr as $v) {
			$_arr[$v[$field]] = $v;
		}
		return $_arr;
	}

	/**
	 * 递归将对象转换成数组
	 * @param object $obj
	 */
	public static function obj2arr($obj)
	{
		$obj = (array)$obj;
		foreach ($obj as $k => $v) {
			if (is_object($v)||is_array($v)) {
				$obj[$k] = self::obj2arr($v);
			} else {
				$obj[$k] = $v;
			}
		}
		return $obj;
	}

	/**
	 * 将含字符串的数组合成被引号引用的字符串(where条件in时需要)
	 * @param array $arr
	 * @return string
	 */
	public static function joinQuotesStr($arr)
	{
		return '\''.join('\',\'', $arr).'\'';
	}
	
	/**
	 * 数组无限分类转化
	 * @param array $arr
	 */
	public static function infinityCategory($arr) 
	{
		uasort($arr, 'self::cmd');

		//定义索引数组，用于记录节点在目标数组的位置
		$idxList = array();
		//定义目标数组
		$cList = array();
		foreach ($arr as $v) {
			$v['children'] = array(); //给每个节点附加一个child项
			if ($v['pid'] == 0) {
				$cList[$v['id']] = $v;
				$idxList[$v['id']] = & $cList[$v['id']];
			} else {
				$idxList[$v['pid']]['children'][$v['id']] = $v;
				$idxList[$v['id']] = &$idxList[$v['pid']]['children'][$v['id']];
			}
		}
		return $cList;
	}
	
	/**
	 * 排序函数
	 * @param array $a
	 * @param array $b
	 */
	public static function cmd($a,$b)
	{
		if ($a['pid']==$b['pid']) return 0;
		return $a['pid']>$b['pid'] ? 1 : -1;
	}
}
