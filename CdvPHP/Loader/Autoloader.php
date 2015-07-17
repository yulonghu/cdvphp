<?php
if(class_exists('Autoloader'))
{
    return FALSE;
}
/**
 * 框架内置类、框架初始化类、Autoloader::init()之后, 强大的Loader类库就可以使用了 
 *
 * 自动设置时区 date_default_timezone_set('Asia/Shanghai');
 *
 * lazyload
 *
 * 捕获程序异常
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package CdvPHP\Loader
 */
class Autoloader
{
    /** @var array $_ext 初始化来自于dispatch类库位置 */
    private static $_ext = array('Controller', 'Logic', 'Model', 'Library');

    /**
     * 初始化时区、类库、lazyloading、Handler
     *
     * @return void
     */
    public static function init()
    {/*{{{*/
        self::_checkPhpVersion();
        self::_setDefaultTimezone();
        self::_initAutoLoad();
        self::_autoload();
        self::_setHandler();
    }/*}}}*/

    /**
     * 最低要求PHP版本不能低于5.3
     *
     * @return void
     */
    private static function _checkPhpVersion()
    {/*{{{*/
        if(version_compare(PHP_VERSION, '5.3') < 0)
        {
            trigger_error('Minimum requirements PHP version 5.3', E_USER_ERROR);
        }
    }/*}}}*/

    /**
     * 捕获异常信息
     *
     * @return void
     */
    private static function _setHandler()
    {/*{{{*/
        $config = ConfigLoader::getVar('system');

        if(!isset($config['log']['debug']) || !$config['log']['debug'])
        {
            error_reporting(0);
            set_error_handler('Autoloader::_errorHandler');
            set_exception_handler('Autoloader::_exceptionHandler');
            register_shutdown_function('Autoloader::_shutdownHandler');
        }
        else
        {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
        }
    }/*}}}*/

    /**
     * 记录debug.log  type=error
     *
     * @return void
     */
    public static function _errorHandler($errno, $errstr, $errfile, $errline)
    {/*{{{*/
        Loader::getInstance('Logger')->debugInfo('error', $errno, $errfile, $errline, $errstr);
        /* Don't execute PHP internal error handler */
        return TRUE;
    }/*}}}*/

    /**
     * 记录debug.log  type=exception
     *
     * @return void
     */
    public static function _exceptionHandler($e)
    {/*{{{*/
        Loader::getInstance('Logger')->debugInfo('exception', $e->getCode(), $e->getFile(), $e->getLine(), $e->getMessage());
        exit(0);
    }/*}}}*/

    /**
     * 记录debug.log  type=shutdown
     *
     * @return void
     */
    public static function _shutdownHandler()
    {/*{{{*/
        $e = error_get_last();
        if(!empty($e))
        {
            Loader::getInstance('Logger')->debugInfo('shutdown', $e['type'], $e['file'], $e['line'], $e['message']);
        }
        exit(0);
    }/*}}}*/

    /**
     * 设置时区 Asia/Shanghai
     *
     * @return void
     */
    private static function _setDefaultTimezone()
    {/*{{{*/
        date_default_timezone_set('Asia/Shanghai');
    }/*}}}*/

    /**
     * 初始化加载 Application.php、BaseController.php
     *
     * @return void
     */
    private static function _initAutoLoad()
    {/*{{{*/
        self::loadClass(FRAMEWORK_PATH . '/Mvc/Application.php');
    }/*}}}*/

    /**
     * 注册类名
     *
     * @param string $class_name
     * @return void
     */
    private static function _register($class_name = '')
    {/*{{{*/
        if(!$class_name)
        {
            return FALSE;
        }

        $arr_map = $arr_tmp = array();
        $root_path = $filename = $class_name;

        $arr_map = self::_userAutoload($class_name);
        if(!$arr_map)
        {
            if(($arr_tmp = self::_classMap($class_name)))
            {
                $root_path = $arr_tmp['root_path'];
                $filename  = $arr_tmp['class_name'];
            }
        }

        if($arr_map)
        {
            // Notice: spl_autoload by default lowercase class name 
            self::loadClass(ROOT_PATH. "/Application/{$arr_map['root_path']}/{$arr_map['class_name']}.php");
        }
        else
        {
            self::loadClass(FRAMEWORK_PATH. "/{$root_path}/{$filename}.php");
        }
    }/*}}}*/

    /**
     * 加载类名
     *
     * @param string $str_path_filename
     * @return void
     */
    public static function loadClass($str_path_filename)
    {/*{{{*/
        include $str_path_filename;
    }/*}}}*/

    /**
     * lazyloading
     *
     * @return void
     */
    private static function _autoload()
    {/*{{{*/
        spl_autoload_register(array('Autoloader', '_register'));
    }/*}}}*/

    /**
     * _classMap
     *
     * @param string $class_name
     * @return array
     */
    private static function _classMap($class_name)
    {/*{{{*/
        $arr_data = array(
            'BasePdo' => array('BasePdoCurd'),
            'Loader'  => array('ConfigLoader'),
            'Mvc' => array('AbstractBaseAction'),
            'View' => array('Tpl'),
            'Http' => array('HttpRequest', 'HttpResponse'),
            'Cache' => array('MemcachedCache', 'CacheInterface', 'RedisCache'),
        );

        if($class_name)
        {
            foreach($arr_data as $key => $val)
            {
                if(in_array($class_name, $val))
                {
                    return array('root_path' => $key, 'class_name' => $class_name);
                }
            }
        }
    }/*}}}*/

    /**
     * _userAutoload
     *
     * @param string $class_name
     * @return array
     */
    private static function _userAutoload($class_name)
    {/*{{{*/
        $arr_map = '';
        if(strpos($class_name, self::$_ext[0]) !== FALSE && substr($class_name, -10) == self::$_ext[0])
        {
            $arr_map = array('root_path' => self::$_ext[0], 'class_name' => substr($class_name, 0, -10));
        }
        elseif(strpos($class_name, self::$_ext[1]) !== FALSE && substr($class_name, -5) == self::$_ext[1])
        {
            $arr_map = array('root_path' => self::$_ext[1], 'class_name' => substr($class_name, 0, -5));
        }
        elseif(strpos($class_name, self::$_ext[2]) !== FALSE && substr($class_name, -5) == self::$_ext[2])
        {
            $arr_map = array('root_path' => self::$_ext[2], 'class_name' => substr($class_name, 0, -5));
        }
        elseif(strpos($class_name, self::$_ext[3]) !== FALSE && substr($class_name, -7) == self::$_ext[3])
        {
            $arr_map = array('root_path' => self::$_ext[3], 'class_name' => substr($class_name, 0, -7));
        }

        return $arr_map;
    }/*}}}*/
}
