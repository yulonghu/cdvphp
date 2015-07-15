<?php
/**
 * 超全局变量读取类
 *
 * 有且只有Application::init()之后, Superglobal::$inputs 默认赋值GET、POST、COOKIE全局变量的值
 *
 * Superglobal::$inputs 别名 Sg::$inputs 
 *
 * <code> 
 * 输出$_GET的值: print_r(Superglobal::$inputs['get']) <br>
 * 输出$_POST的值: print_r(Superglobal::$inputs['post']) <br>
 * 输出$_COOKIE的值: pirnt_r(Superglobal::$inputs['cookie']) <br>
 * 输出method的值: pirnt_r(Superglobal::$methods) <br>
 * 输出outputs的值: pirnt_r(Superglobal::$outputs)
 * </code>
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package CdvPHP\Superglobal
 */
class Sg extends Superglobal
{

}
abstract class Superglobal
{
    /** @var array $inputs 常用于获取$_GET、$_POST、$_COOKIE的值 */
    public static $inputs = array(
        'get' => array(),
        'post' => array(),
        'cookie' => array()
    );

    /** 
     * @var array $outputs 自定义输出 print_r(Superglobal::$outputs)
     */
    public static $outputs = array();

    /** 
     * @var array $methods 解析method print_r(Superglobal::$methods)
     */
    public static $methods = array();

    public static function __callStatic($name, $arguments)
    {/*{{{*/
        // 注意: $name 的值区分大小写
        echo "Calling static method '$name' "
            . implode(', ', $arguments). "\n";
    }/*}}}*/
}
