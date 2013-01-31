<?php
/**
 * 类操作
 *
 * Created		: 2011-11-07
 * Modified		: 2011-12-17
 * @link		: http://www.dophin.co
 * @copyright	: (C) 2011-2012 Dophin 
 * @version		: 0.2.0
 * @author		: Joseph Chen (chenliq@gmail.com)
 */
class ReflectionClassExt
{
	
	/**
	 * 获取一个类的方法列表
	 * @param string $className
	 */
	public static function getServiceMethodList($className)
	{
		$obj = new ReflectionClass($className);
		$methodList = $obj->getMethods();
		$list = array();
		foreach ($methodList as $m) {
			if (G::isServiceExist($m->class, $m->name)) {
				$list[] = $m->name;
			}
		}
		return $list;
	}

	/**
	 * 获取一个类属性值
	 * @param string $class
	 * @param string $propertyName
	 */
	public static function getClassPropertyValue($class, $propertyName)
	{
		if (!property_exists($class, $propertyName)) {
			return null;
		}
		$reflectionProperty = new ReflectionProperty($class, $propertyName);
		if ($reflectionProperty->isStatic()) {
			return $reflectionProperty->getValue();
		} else {
			$reflectionClass = new ReflectionClass($class);
			return $reflectionProperty->getValue($reflectionClass->newInstance($class));
		}
	}

	/**
	 * 设置一个类属性值
	 * @param string|object $class
	 * @param string $propertyName
	 */
	public static function setClassPropertyValue($class, $propertyName, $value)
	{
		if (!property_exists($class, $propertyName)) {
			return array();
		}
		$reflectionProperty = new ReflectionProperty($class, $propertyName);
		$reflectionProperty->setValue($class, $value);
		return true;
	}
	
	/**
	 * 获取方法的参数
	 * @param string $className 指定一块果园的主键ID
	 * @param string $methodName 指定一块果园的主键ID
	 */
	public static function getMethodParameters($className, $methodName) 
	{
		$method = new ReflectionMethod($className, $methodName);
		$argList = $method->getParameters();
		$docComment = $method->getDocComment();
		$argCommentList = self::getMethodCommentArguments($docComment);
		$list = array();
		foreach ($argList as $key=>$ReflectionParameter) {
			$comment = isset($argCommentList[$key]) ? $argCommentList[$key] : '';
			if ($ReflectionParameter->isOptional()) {
				$defaultValue = $ReflectionParameter->getDefaultValue();
			} else {
				$defaultValue = '';
			}
			$list[] = array(
				'name'		=> $ReflectionParameter->name,
				'value'		=> $defaultValue,
				'comment'	=> $comment,
			);
		}
		return $list;
	}

	/**
	 * 获取方法所有参数注释
	 * @param string $comment
	 */
	public static function getMethodCommentArguments($comment)
	{
		$pieces = explode('@param', $comment);
		$args = array();
		if(is_array($pieces) && count($pieces) > 1)
		{
			for($i = 0; $i < count($pieces) - 1; $i++)
			{
				$ps = self::strrstr($pieces[$i + 1], '@');
				$ps = self::strrstr($ps, '*/');
				$args[] = self::cleanComment($ps);
			}
		}
		return $args;
	}
	
	/**
	 * 整理方法备注
	 * @param string $comment
	 */
	public static function cleanComment($comment)
	{
		$comment = str_replace("/**", "", $comment);
		$comment = str_replace("*/", "", $comment);
		$comment = str_replace("*", "", $comment);
		$comment = str_replace("\r", "", trim($comment));
		$comment = preg_replace("{\n[ \t]+}", "\n", trim($comment));
		$comment = str_replace("\n", "\\n", trim($comment));
		$comment = preg_replace("{[\t ]+}", " ", trim($comment));
		
		$comment = str_replace("\"", "\\\"", $comment);
		return $comment;
	}
	
	/**
	 * 获取方法说明
	 * @param string $comment
	 */
	public static function getMethodDescription($className, $methodName)
	{
		$method = new ReflectionMethod($className, $methodName);
		$argList = $method->getParameters();
		$docComment = $method->getDocComment();
		$comment = self::cleanComment(self::strrstr($docComment, "@"));
		return trim($comment);
	}
	
	/**
	 * 类中是否存在指定的属性
	 * @param string $className
	 * @param string $propertyName
	 */
	public static function hasProperty($className, $propertyName) 
	{
		$class = new ReflectionClass($className);
		return $class->hasProperty($propertyName);
	}
	
	public static function strrstr($haystack, $needle)
	{
		return substr($haystack, 0, strpos($haystack.$needle,$needle));
	}
}
