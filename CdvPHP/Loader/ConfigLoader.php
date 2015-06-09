<?php
/**
 * 配置文件读取 (Config\Global.php)
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package CdvPHP\Loader
 */
class ConfigLoader
{
	/** @var string $configFile global.php的全路径 */
	public static $configFile = '';

	/** @var string $_config 读取到的配置文件内容 */
	private static $_configs = array();

	/**
	 * 从配置文件读取单个变量的值, 变量不存在返回 null
	 *
	 * Example #1
	 *
	 * <code>
	 * print_r(ConfigLoader::getVar('system'));
	 * </code>
	 *
	 * @param string $property  变量名称
	 *
	 * @return mixed  
	 */
	public static function getVar($property)
	{/*{{{*/
		if(empty(self::$_configs))
		{
            self::$_configs = self::_loadFile();
		}

		return ($property && isset(self::$_configs[$property])) ? self::$_configs[$property] : null;
	}/*}}}*/

	/**
	 * 从配置文件读取全部变量的值
	 *
	 * Example #1
	 *
	 * <code>
	 * print_r(ConfigLoader::getVars());
	 * </code>
	 *
	 * @return array  
	 */
	public static function getVars()
	{/*{{{*/
		if(empty(self::$_configs))
		{
			self::$_configs = self::_loadFile();
		}

		return self::$_configs;
	}/*}}}*/

	/**
	 * 加载文件, 读取所有变量, 无法读取到配置文件的常量
	 *
	 * @return array  
	 */
	private static function _loadFile()
	{/*{{{*/
		if(empty(self::$configFile))
		{
			self::$configFile = realpath(ROOT_PATH . '/Config/Global.php');
		}

		include self::$configFile;
        return get_defined_vars();
	}/*}}}*/
}
