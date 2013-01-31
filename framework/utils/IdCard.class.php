<?php
/**
 * 身份证相关操作
 *
 * Created		: 2012-08-24
 * Modified		: 2012-08-24
 * @link		: http://www.dophin.co
 * @copyright	: (C) 2012 Dophin 
 * @version		: 0.1.0
 * @author		: Joseph Chen (chenliq@gmail.com)
 */
class IdCard
{
	
	/**
	 * 是否身份证号
	 */
	public static function isIdCard($idCard) {
		$len = strlen($idCard);
		if ($len == 18) {
			return self::idcardCheck18($idCard);
		} elseif ($len == 15)    {
			$idCard = self::idcard15to18($idCard);
			if (!$idCard) {
				return false;
			}
			return self::idcardCheck18($idCard);
		} else    {
			return false;
		}
	}

	/**
	 * 将15位身份证升级到18位
	 * @param unknown_type $idcard
	 */
	public static function idcard15to18($idcard)
	{
		if (strlen($idcard) != 15){
			return false;
		} else {
			// 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
			if (array_search(substr($idcard, 12, 3), array('996', '997', '998', '999')) !== false){
				// 年份80后的百岁老人才是19世纪的
				if (substr($idcard, 6, 2) > 80) {
					$idcard = substr($idcard, 0, 6) . '18'. substr($idcard, 6, 9);
				} else {
					$idcard = substr($idcard, 0, 6) . '19'. substr($idcard, 6, 9);
				}
			}else{
				$idcard = substr($idcard, 0, 6) . '19'. substr($idcard, 6, 9);
			}
		}
		$idcard = $idcard . self::idcardVerifyNumber($idcard);
		return $idcard;
	}

	/**
	 * 18位身份证校验码有效性检查
	 * @param string $idcard
	 */
	public static function idcardCheck18($idcard)
	{
		if (strlen($idcard) != 18) {
			return false;
		}
		$idcard_base = substr($idcard, 0, 17);
		if (self::idcardVerifyNumber($idcard_base) != strtoupper(substr($idcard, 17, 1))) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * 计算身份证校验码，根据国家标准GB 11643-1999
	 * @param unknown_type $idcardBase
	 */
	public static function idcardVerifyNumber($idcardBase)
	{
		if(strlen($idcardBase) != 17)
		{
			return false;
		}

		//加权因子
		$factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);

		//校验码对应值
		$verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
		$checksum = 0;
		for ($i = 0; $i < strlen($idcardBase); $i++)
		{
			$checksum += substr($idcardBase, $i, 1) * $factor[$i];
		}
		$mod = $checksum % 11;
		$verify_number = $verify_number_list[$mod];
		return $verify_number;
	}

}