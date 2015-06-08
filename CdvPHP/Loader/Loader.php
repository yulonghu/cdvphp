<?php
/**
 * 类的单例, 根据类名加载并实例化该类
 *
 * 自动查找加载类并实例化, 这是一个静态方法类
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package CdvPHP\Loader
 */
class Loader
{
	/** @var array $_class */
	protected $_class = array();

	/** @var object $_class */
	protected static $_obj = null;

	/**
	 * _init
	 *
	 * @return object
	 */
	private static function _init()
	{/*{{{*/
		if(!isset(self::$_obj))
		{
			self::$_obj = new self();
		}

		return self::$_obj;
	}/*}}}*/

	/**
	 * 类的单例, 如果找不到类, 抛出异常
	 *
	 * @param string $class_name 类名
	 *
	 * @return object  
	 */
	public static function getInstance($class_name)
	{/*{{{*/
		self::_checkClassName($class_name);
		$obj = self::_init();

		$class_name = ucfirst($class_name);
		if(!isset($obj->_class[$class_name]))
		{
			$obj->_class[$class_name] =  new $class_name();
		}

		return $obj->_class[$class_name];
	}/*}}}*/

	/**
	 * 清除已经加载的类(单个)
	 *
	 * @param string $class_name 类名
	 *
	 * @return boolean  如果清理成功返回true, 否则返回false
	 */
	public static function clean($class_name)
	{/*{{{*/
		self::_checkClassName($class_name);
		$obj = self::_init();

		if(isset($obj->_class[$class_name]))
		{
			unset($obj->_class[$class_name]);
			return TRUE;
		}

		return FALSE;
	}/*}}}*/

	/**
	 * 清除已经加载的类(全部)
	 *
	 * @return void
	 */
	public static function cleanAll()
	{/*{{{*/
		$obj = self::_init();
		$obj->_class = array();
	}/*}}}*/

	private static function _checkClassName($class_name)
	{/*{{{*/
		return (empty($class_name) || is_array($class_name));
	}/*}}}*/

	/**
	 * 获取用户已经实例化的所有类 (不包含框架实例化的类)
	 *
	 * @return array
	 */
	public static function getRegisteredClass()
    {/*{{{*/
		$obj = self::_init();
        return $obj->_class;
	}/*}}}*/

	/**
	 * 返回所有被 include、 include_once、 require 和 require_once 的文件名
	 *
	 * @return array
	 */
	public static function getIncludeFiles()
    {/*{{{*/
		return get_included_files();
	}/*}}}*/
}
