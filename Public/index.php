<?php
/**
 * 框架入口文件 (最低要求PHP版本不能低于5.3)
 *
 * 注: method=不区分大小写
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package public\index
 */
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

/** @const FRAMEWORK_PATH 框架根目录 */
define('FRAMEWORK_PATH', dirname(__DIR__) . '/CdvPHP');

/** @const ROOT_PATH 程序目录 */
define('ROOT_PATH', dirname(__DIR__));

// Cdv Autoloader (类库自动加载)
if(is_dir(FRAMEWORK_PATH)) {
    include FRAMEWORK_PATH . '/Loader/Autoloader.php';
    Autoloader::init();
}

if (!class_exists('Autoloader')) {
    throw new RuntimeException('Cdv Framework to run failed.');
}

Application::init()->run();
