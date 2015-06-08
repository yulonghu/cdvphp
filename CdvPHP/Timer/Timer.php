<?php
/**
 * 时间封装类
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package CdvPHP\Timer
 */
class Timer
{
	/** @var int $_start_time 时间起始值 */
	private static $_start_time = 0;

	/**
	 * 开始时间
	 *
	 * @return void
	 */
	public function start()
	{/*{{{*/
		self::$_start_time = microtime(TRUE);
	}/*}}}*/

	/**
	 * 结束时间, 单位(毫秒)
	 *
	 * @return float
	 */
	public function end()
	{/*{{{*/
		return microtime(TRUE) - self::$_start_time;
	}/*}}}*/

	/**
	 * 获取服务端当前时间(精确到秒)
	 *
	 * @return int
	 */
	public function getTime()
	{/*{{{*/
		return time();
	}/*}}}*/

	/**
	 * 获取服务端当前毫秒时间戳
	 *
	 * @param bool $get_as_float 默认TRUE, 返回毫秒时间戳; 如果get_as_float=false, 返回秒级时间戳
	 * @return int
	 */
	public function getMicrotime($get_as_float = TRUE)
	{/*{{{*/
		$get_as_float = (bool)$get_as_float;
		return microtime($get_as_float);
	}/*}}}*/
}
