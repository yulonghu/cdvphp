<?php
/**
 * 分表、分库的hash函数
 * 开发中，经常会用到
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package CdvPHP\HashTable
 */
class HashTable
{
	/**
	 * 数字hash
	 *
	 * @param int $number 数字
	 * @param int $hash_count hash数量
	 *
	 * @return int
	 */
	public function getIntergerHash($number, $hash_count)
	{/*{{{*/
		return abs($number % $hash_count);
	}/*}}}*/

	/**
	 * 字符串hash
	 *
	 * @param int $string 字符串
	 * @param int $hash_count hash数量
	 *
	 * @return int
	 */
	public function getStringHash($string, $hash_count)
	{/*{{{*/
		$int_unsign = sprintf('%u', crc32($string));
		return abs($int_unsign % $hash_count);
	}/*}}}*/
}
