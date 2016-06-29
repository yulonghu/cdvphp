<?php
/**
 * $system 属于框架系统变量, 变量名称、数组键名(key)请不要改动, 只能修改各项对应value值
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package config\config.global
 */
$system = array(
    /** log: 在module目录生成 *.log.Ymd 文本文件 */
    'log' => array(
        /**
         * 当且仅当 debug === TRUE 时，错误日志会直接输出到界面，方便调试; 否则会输出到日志, 这种方式用于线上模式
         * @var boolean $debug
         */
        'debug'   => FALSE,
        /**
         * 当且仅当 website === TRUE 时, 记录访问日志
         * @var boolean $website
         */
        'website' => TRUE,
        /**
         * 当且仅当 sql === TRUE 时, 记录SQL日志
         *
         * @var boolean @sql
         */
        'sql'     => TRUE,
    ),

    /** 如果没有页面展示, 只有服务端API接口, (template)模版配置可以去掉 */
    'template' => array(
        /**
         * 模版路径, 最后斜杠可以省略 (目录具备读写权限)
         * @var string path
         */
        'path' => ROOT_PATH . '/Application/View/Templates/',
        /**
         * 设置模版缓存目录, 最后斜杠可以省略 (目录具备读写权限, 文件夹不存在则会自动创建)
         * @var string cache
         */
        'cache' => ROOT_PATH . '/Application/View/TemplatesCache/',
        /**
         * 1 开启模版刷新, 0 关闭模版刷新
         * @var int refresh
         */
        'refresh' => 1,
        /**
         * 设置模板后缀
         * @var string suffix
         */
        'suffix' => 'html',
    ),

    /** 设置 参数index.php?key、默认Controller + action、分隔符 */
    'app' => array(
        'key' => 'method',
        'separator' => '.',
        'controller' => 'index',
        'action' => 'index'
    ),

    /**
     * 自动输出格式, 默认PHP不做任何转换(注意大小写)
     * 支持2种: php、api
     */
    'output_format' => 'php'
);

/**
 * MySQL DB配置, 变量名$db支持自定义
 *
 * @global array $db
 */
$db = array(
    'master' => array('host' => '127.0.0.1', 'username' => 'root', 'password' => 'cdv123456', 'port' => 3306, 'dbname' => 'cdvphp_new'),
    'slave'  => array('host' => '127.0.0.1', 'username' => 'root', 'password' => 'cdv123456', 'port' => 3306, 'dbname' => 'cdvphp_new'),
);

/** @var string $admin 测试做例子用 */
$admin = 'admin';

define('TEST_CONSTANTS', 'test');

