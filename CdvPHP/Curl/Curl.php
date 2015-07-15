<?php
/**
 * Curl 封装类
 *
 * 如果url参数没有填写完整的格式, 自动补全 http://
 * 
 * 已经封装的POST、GET请求忽略SSL证书
 *
 * PHP脚本执行结束, 自动关闭cURL资源，并且释放系统资源
 *
 * Examples
 * <pre>
 * $curl = Loader::getIntances('Curl');
 * $data = $curl->getHeader('www.baidu.com');
 * if($curl->errno)
 * {
 *    die('request failed');
 * }
 * else
 * {
 *    echo $data;
 * }
 *
 * 打印Curl基本信息
 * print_r($curl->getinfo);
 * </pre>
 * 
 * 设置3秒超时
 * <pre>
 * $curl = Loader::getIntances('Curl');
 * $curl->setOpt(CURLOPT_TIMEOUT, TRUE); // or $curl->$opts[CURLOPT_TIMEOUT] = 3;
 * $curl->setOpt(CURLOPT_CONNECTTIMEOUT, TRUE); // or $curl->$opts[CURLOPT_CONNECTTIMEOUT] = 3;
 * print_r($curl->get('www.baidu.com')); 
 * </pre>
 *
 * Cookie操作
 * <pre>
 * $curl = Loader::getIntances('Curl');
 * $curl->setOpt(CURLOPT_COOKIE, 'cookie_user=c_cookie_id&cookie_pass=123456');
 * print_r($curl->get('http://xxxx.cn/test/checkuser'));
 * print_r($curl->post('http://xxxx.cn/test/checkuser'));
 * </pre>
 *
 * 设置代理方式
 * <pre>
 * $curl = Loader::getIntances('Curl');
 * $curl->setOpt(CURLOPT_COOKIE, 'cookie_user=c_cookie_id&cookie_pass=123456');
 * $curl->setOpt(CURLOPT_HTTPHEADER, array('Host: fjp.web.free.wifi.360.cn'));
 * print_r($curl->get('http://127.0.0.1/test/checkuser'));
 * </pre>
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package CdvPHP\Curl
 */
class Curl
{
    /** @var array $opts CURL Option */
    public $opts = array(
        CURLOPT_TIMEOUT => 1,
        CURLOPT_CONNECTTIMEOUT => 1,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_SSL_VERIFYPEER => FALSE,
        CURLOPT_SSL_VERIFYHOST => FALSE,
        CURLOPT_FOLLOWLOCATION => FALSE,
    );

    /** @var object $_ch */
    private $_ch = null;
    /** @var int $errno */
    public $errno = 0;
    /** @var string $getinfo */
    public $getinfo = '';

    /**
     * curl init
     * @return void
     */
    public function __construct()
    {/*{{{*/
        if(!$this->_ch)
        {
            $this->_ch = curl_init();
        }
    }/*}}}*/

    /**
     * _request
     *
     * @param string $url
     * @param string $data
     *
     * @return mixed
     */
    private function _request($url, $data = '')
    {/*{{{*/
        $start_time = microtime(TRUE);
        $url = trim($url);

        if(empty($url))
        {
            return FALSE;
        }

        if(substr($url, 0, 7) != 'http://' && substr($url, 0, 8) != 'https://')
        {
            $url = "http://{$url}";
        }

        if(isset($this->opts[CURLOPT_POSTFIELDS]))
        {
            $this->opts[CURLOPT_POSTFIELDS] = $data;
        }

        $this->opts[CURLOPT_URL] = $url;
        curl_setopt_array($this->_ch, $this->opts);
        $result = curl_exec($this->_ch);

        $this->errno = curl_errno($this->_ch);
        $this->getinfo = curl_getinfo($this->_ch); 

        if($this->errno)
        {
            $result = '';
        }

        $end_time = microtime(TRUE);
        return $result;
    }/*}}}*/

    /**
     * CURL GET方法封装
     *
     * Example #1
     *
     * <code>
     * $curl = Loader::getIntances('Curl');
     * echo $curl->get('www.baidu.com');
     * </code>
     *
     * @param string $url 请求的URL地址
     * @return mixed
     */
    public function get($url)
    {/*{{{*/
        $this->opts[CURLOPT_HTTPGET] = TRUE;

        return $this->_request($url);
    }/*}}}*/

    /**
     * CURL POST方法封装
     *
     * Example #1
     *
     * <code>
     * $curl = Loader::getIntances('Curl');
     * echo $curl->post('www.baidu.com', array('user' => 'ceshi', 'nickname' => '哈哈'));
     * </code>
     *
     * Example #2
     *
     * <code>
     * $curl = Loader::getIntances('Curl');
     * print_r($curl->post('www.baidu.com', 'user=ceshi&nickname=哈哈'));
     * </code>
     *
     * Example #3
     *
     * <code>
     * $curl = Loader::getIntances('Curl');
     * print_r($curl->post('http://www.baidu.com/test/checkuser', 
     * array('user' => 'ceshi', 'nickname' => 'haha'))
     * );
     * </code>
     *
     * @param string $url 请求的URL地址
     * @param string $data POST数据体
     *
     * @return mixed
     */
    public function post($url, $data = array())
    {/*{{{*/
        $this->opts[CURLOPT_POSTFIELDS] = TRUE;

        if($data && is_array($data))
        {
            $data = http_build_query($data);
        }

        return $this->_request($url, $data);
    }/*}}}*/

    /**
     * CURL GET方法, 只获取目的URL地址Header头信息
     *
     * Example #1
     *
     * <code>
     * $curl = Loader::getIntances('Curl');
     * echo $curl->getHeader('www.baidu.com');
     * </code>
     *
     * @param string $url 请求的URL地址
     * @return mixed
     */
    public function getHeader($url)
    {/*{{{*/
        $this->opts[CURLOPT_HTTPGET] = TRUE;
        $this->opts[CURLOPT_NOBODY]  = TRUE;
        $this->opts[CURLOPT_HEADER]  = TRUE;

        return $this->_request($url);
    }/*}}}*/

    /**
     * 设置一个cURL传输选项, 相当于 curl_setopt (实际开发中, 需要设置的真的不多)
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setOpt($key, $value)
    {/*{{{*/
        if($key)
        {
            $this->opts[$key] = $value;
        }
    }/*}}}*/

    /**
     * 获取配置的 all options
     *
     * Example #1
     *
     * <code>
     * $curl = Loader::getIntances('Curl');
     * print_r($curl->getOpts());
     * </code>
     * @return array
     */
    public function getOpts()
    {/*{{{*/
        return $this->opts;
    }/*}}}*/

    /**
     * 关闭cURL资源，并且释放系统资源
     * @param resource $ch
     * @return void
     */
    public function close($ch = '')
    {/*{{{*/
        $ch = $ch ? $ch : $this->_ch;
        if($ch && is_resource($ch))
        {
            curl_close($ch);
        }
    }/*}}}*/

    /**
     * auto curl close
     * @return void
     */
    public function __destruct()
    {/*{{{*/
        $this->close(); 
    }/*}}}*/
}

