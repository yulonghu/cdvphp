<?php
/**
 * 配置文件读取 (Config\Global.php); 建议配置文件内容只允许读取
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
     * 从配置文件读取具体某项的值
     *
     * 如果节点下是数组，则返回的数据是object类型
     *
     * Example #1
     *
     * <code>
     * print_r(ConfigLoader::getValue('db')->master->host);
     * </code>
     *
     * Example #2
     *
     * <code>
     * print_r(ConfigLoader::getValue('system')->template->path);
     * </code>
     *
     * @return mixed 
     */
    public static function getValue($property)
    {/*{{{*/
        if(empty(self::$_configs))
        {
            self::$_configs = self::_loadFile();
        }

        if(!$property || !isset(self::$_configs[$property]))
        {
            return null;
        }

        if(is_array(self::$_configs[$property]))
        {
            self::$_configs[$property] = json_encode(self::$_configs[$property]);
            self::$_configs[$property] = json_decode(self::$_configs[$property]);
        }

        return self::$_configs[$property];
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
