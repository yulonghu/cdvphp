<?php
/**
 * 日志收集 
 *
 * 以JSON串格式存入到log文本文件, 排查问题必备
 *
 * 日志文件名按天取名, 文本文件格式:  xxx.log.ymd
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package CdvPHP\Logger
 */
class Logger
{
    /** @var string $full_path 日志收集目录 */
    public $full_path = null;

    /** @var string $site_file_name 相当于access.log, 但这个日志是个性化的记录, 可以自定义文本文件名称 */
    public $site_file_name 	 = 'website.log';

    /** @var string $sql_file_name 线上、线下都建议记录, 可以自定义文本文件名称 */
    public $sql_file_name  	 = 'sql.log';

    /** @var string $debug_file_name 调试环境下使用, 可以自定义文本文件名称 */
    public $debug_file_name  = 'debug.log';

    /**
     * 所有日志路径的初始化
     *
     * @return viod
     */
    public function __construct()
    {/*{{{*/
        if(!$this->full_path)
        {
            $this->full_path = ROOT_PATH . DIRECTORY_SEPARATOR . 'Log';
        }

        if(!is_dir($this->full_path))
        {
            if(@mkdir($this->full_path) === FALSE)
            {
                trigger_error("{$this->full_path} mkdir failed!", E_USER_ERROR);
            }
        }
    }/*}}}*/

    /**
     * website访问日志记录
     *
     * @param string $msg 记录的内容 
     * @return void
     */
    public function siteInfo($msg = '')
    {/*{{{*/
        if(!empty($msg))
        {
            $info['msg'] = $msg; 
        }

        $info['get'] = isset($_GET) ? $_GET : '';
        $info['post'] = isset($_POST) ? $_POST : '';
        $info['cookie'] = isset($_COOKIE) ? $_COOKIE : '';

        $info['timestamp'] = time(); 
        $info['date'] = date('Y-m-d H:i:s');
        $info['ip'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';

        error_log(json_encode($info) . PHP_EOL, 3, $this->full_path . DIRECTORY_SEPARATOR . $this->site_file_name. '.' . date('Ymd'));
    }/*}}}*/

    /**
     * sql日志记录(MySQL)
     *
     * @param string $sql SQL命令
     * @param int $time SQL执行的时间
     * @return void
     */
    public function sqlInfo($sql, $time)
    {/*{{{*/
        $info['sql'] = $sql;
        $info['exec_time'] = $time; 

        $info['timestamp'] = time(); 
        $info['date'] = date('Y-m-d H:i:s');
        $info['ip'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';

        error_log(json_encode($info) . PHP_EOL, 3, $this->full_path . DIRECTORY_SEPARATOR . $this->sql_file_name. '.' . date('Ymd'));
    }/*}}}*/

    /**
     * debug日志记录, 开启之后排查问题变得很轻松
     *
     * @param string $type
     * @param int $errno
     * @param string $file
     * @param int $line
     * @param string $msg
     *
     * @return void
     */
    public function debugInfo($type, $errno, $file, $line, $msg)
    {/*{{{*/
        $info['status'] = $type;
        $info['get'] = isset($_GET) ? $_GET : '';
        $info['post'] = isset($_POST) ? $_POST : '';
        $info['cookie'] = isset($_COOKIE) ? $_COOKIE : '';

        $info['info']['errno'] = $errno;
        $info['info']['file'] = $file;
        $info['info']['line'] = $line;
        $info['info']['msg']  = $msg;

        $info['timestamp'] = time(); 
        $info['date'] = date('Y-m-d H:i:s');
        $info['ip'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';

        error_log(json_encode($info) . PHP_EOL, 3, $this->full_path . DIRECTORY_SEPARATOR . $this->debug_file_name. '.' . date('Ymd'));
    }/*}}}*/
}
