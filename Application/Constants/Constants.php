<?php
/**
 * 框架入门 - 常量错误码、常量错误信息定义
 *
 * 常用于客户端与服务端接口交互(Android、Iphone、Winform)
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package Application\Constants
 */
class Constants
{
    const ERR_TEST = 1000;

    static public $ErrorDescription = array(
        self::ERR_TEST => '测试, 系统默认错误',
    );
}
