<?php
/**
 * 日志收集 
 *
 * 以JSON串格式存入到log文本文件, 排查问题必备
 *
 * 日志文件名按天取名, 文本文件格式:  xxx_log.ymd
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
    public $site_file_name 	 = 'website_log';

    /** @var string $sql_file_name 线上、线下都建议记录, 可以自定义文本文件名称 */
    public $sql_file_name  	 = 'sql_log';

    /** @var string $debug_file_name 调试环境下使用, 可以自定义文本文件名称 */
    public $debug_file_name  = 'debug_log';

    /**
     * 所有日志路径的初始化
     *
     * @return viod
     */
    public function __construct()
    {/*{{{*/
        if(!$this->full_path)
        {
            $this->full_path = ROOT_PATH . DIRECTORY_SEPARATOR . 'Logs';
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
    public function siteInfo($reponse = '')
    {/*{{{*/
        $info = '';
        $data = array();

        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
        if(!empty($reponse))
        {
            $data['reponse'] = $reponse;
        }

        $data['get'] = isset($_GET) ? $_GET : '';
        $data['post'] = isset($_POST) ? $_POST : '';
        $data['cookie'] = isset($_COOKIE) ? $_COOKIE : '';

        $info  = '[LOG_TIME] '. date('Y-m-d H:i:s') . "\t";
        $info .= "[IP] {$ip} \t";
        $info .= "[SITE] " . json_encode($data);

        error_log($info . PHP_EOL, 3, $this->full_path . DIRECTORY_SEPARATOR . $this->site_file_name. '.' . date('Ymd'));
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
        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
        $info  = '[LOG_TIME] '. date('Y-m-d H:i:s') . "\t";
        $info .= "[IP] {$ip} \t";
        $info .= "[EXE_TIME] {$time} \t";
        $info .= "[SQL] {$sql}";

        error_log($info . PHP_EOL, 3, $this->full_path . DIRECTORY_SEPARATOR . $this->sql_file_name. '.' . date('Ymd'));
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
        $info = '';
        $data = array();

        $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
        $info  = '[LOG_TIME] '. date('Y-m-d H:i:s') . "\t";
        $info .= "[IP] {$ip} \t";

        $data['status'] = $type;
        $data['get'] = isset($_GET) ? $_GET : '';
        $data['post'] = isset($_POST) ? $_POST : '';
        $data['cookie'] = isset($_COOKIE) ? $_COOKIE : '';

        $data['info']['errno'] = $errno;
        $data['info']['file'] = $file;
        $data['info']['line'] = $line;
        $data['info']['msg']  = $msg;

        $info .= "[DEBUG] " . json_encode($data);

        error_log($info . PHP_EOL, 3, $this->full_path . DIRECTORY_SEPARATOR . $this->debug_file_name. '.' . date('Ymd'));
    }/*}}}*/
}
