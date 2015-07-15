<?php
/**
 * HTTP Request
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package CdvPHP\Http
 */
class HttpRequest
{
    /**#@+
     * @const string METHOD constant names
     */
    const METHOD_OPTIONS  = 'OPTIONS';
    const METHOD_GET      = 'GET';
    const METHOD_HEAD     = 'HEAD';
    const METHOD_POST     = 'POST';
    const METHOD_PUT      = 'PUT';
    const METHOD_DELETE   = 'DELETE';
    const METHOD_TRACE    = 'TRACE';
    const METHOD_CONNECT  = 'CONNECT';
    const METHOD_PATCH    = 'PATCH';
    const METHOD_PROPFIND = 'PROPFIND';
    /**#@-*/

    /**
     * @var string
     */
    protected $method = self::METHOD_GET;

    /**
     * @var string
     */
    protected $headers = array();

    public function __construct()
    {/*{{{*/
        if(!defined('self::METHOD_' . $this->getServer('REQUEST_METHOD')))
        {
            trigger_error('A valid request method', E_USER_ERROR);
        }

        $this->method = $this->getServer('REQUEST_METHOD');
    }/*}}}*/

    /**
     * Return the method for this request
     *
     * @return string
     */
    public function getMethod()
    {/*{{{*/
        return $this->method;
    }/*}}}*/

    /**
     * setQuery($key, $value)
     *
     * setQuery(array($key1, $key2), $value)
     *
     * @param array|string $spec
     * @param array|string $value
     *
     * @return object
     */
    public function setQuery($spec, $value = null)
    {/*{{{*/
        if($spec === null)
        {
            trigger_error('Invalid value passed to setQuery(); must be either array of values or key/value pair', E_USER_ERROR);
        }

        if(is_array($spec))
        {
            foreach($spec as $key)
            {
                $this->setQuery($key, $value);
            }
        }

        if(is_string($spec))
        {
            $_GET[$spec] = $value;
        }

        return $this;
    }/*}}}*/

    /**
     * Return $_GET[key] or $_GET
     *
     * @param string|null $key
     * @param mixed|null  $default Default value to use when the $_GET[key] is missing.
     * @return mixed
     */
    public function getQuery($key = null, $default = null)
    {/*{{{*/
        if ($key === null)
        {
            return $_GET;
        }

        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }/*}}}*/

    /**
     * setPost($key, $value)
     *
     * setPost(array($key1, $key2), $value)
     *
     * @param array|string $spec
     * @param array|string $value
     *
     * @return object
     */
    public function setPost($spec, $value = null)
    {/*{{{*/
        if($spec === null)
        {
            trigger_error('Invalid value passed to setPost(); must be either array of values or key/value pair', E_USER_ERROR);
        }

        if(is_array($spec))
        {
            foreach($spec as $key)
            {
                $this->setPost($key, $value);
            }
        }

        if(is_string($spec))
        {
            $_POST[$spec] = $value;
        }

        return $this;
    }/*}}}*/

    /**
     * Return $_POST[key] or $_POST
     *
     * @param string|null $key
     * @param mixed|null  $default Default value to use when the $_POST[key] is missing.
     * @return mixed
     */
    public function getPost($key = null, $default = null)
    {/*{{{*/
        if ($key === null)
        {
            return $_POST;
        }

        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }/*}}}*/

    /**
     * Return $_COOKIE[key] or $_COOKIE
     *
     * @param string|null $key
     * @param mixed|null  $default Default value to use when the $_COOKIE[key] is missing.
     * @return mixed
     */
    public function getCookie($key = null, $default = null)
    {/*{{{*/
        if($key === null)
        {
            return $_COOKIE;
        }

        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : $default;
    }/*}}}*/

    /**
     * Return $_SERVER[key] or $_SERVER
     *
     * @param string|null $key
     * @param mixed|null  $default Default value to use when the $_SERVER[key] is missing.
     * @return mixed
     */
    public function getServer($key = null, $default = null)
    {/*{{{*/
        if($key === null)
        {
            return $_SERVER;
        }

        return isset($_SERVER[$key]) ? $_SERVER[$key] : $default;
    }/*}}}*/

    /**
     * Return $_ENV[key] or $_ENV
     *
     * @param string|null $key
     * @param mixed|null  $default Default value to use when the $_ENV[key] is missing.
     * @return mixed
     */
    public function getEnv($key = null, $default = null)
    {/*{{{*/
        if($key === null)
        {
            return $_ENV;
        }

        return isset($_ENV[$key]) ? $_ENV[$key] : $default;
    }/*}}}*/

    /**
     * Return $_FILES[key] or $_FILES
     *
     * @param string|null $key
     * @param mixed|null  $default Default value to use when the $_FILES[key] is missing.
     * @return mixed
     */
    public function getFiles($key = null, $default = null)
    {/*{{{*/
        if($key === null)
        {
            return $_FILES;
        }

        return isset($_FILES[$key]) ? $_FILES[$key] : $default;
    }/*}}}*/

    /**
     * 获取 $_SERVER['HTTP_*'] 值; $name 不区分大小写
     *
     * @param string|null $name
     * @param mixed|null  $default Default value to use when the requested header is missing.
     * @return mixed
     */
    public function getHeaders($name = null, $default = null)
    {/*{{{*/
        if(empty($this->headers))
        {
            foreach($this->getServer() as $key => $val)
            {
                if(substr($key, 0, 4) == 'HTTP')
                {
                    $this->headers[strtoupper(substr($key, 5))] = $val;
                }
            }
        }

        if($name === null)
        {
            return $this->headers;    
        }

        $name = strtoupper($name);

        return isset($this->headers[$name]) ? $this->headers[$name] : $default;
    }/*}}}*/

    /**
     * 获取 $_SERVER['HTTP_*'] 值; $name 不区分大小写
     *
     * 函数 getHeaders 别名关系
     *
     * @param string|null $name
     * @param mixed|null  $default Default value to use when the requested header is missing.
     * @return mixed
     */
    public function getHeader($name, $default = null)
    {/*{{{*/
        return $this->getHeaders($name, $default);
    }/*}}}*/

    /**
     * Is this an OPTIONS method request?
     *
     * @return bool
     */
    public function isOptions()
    {/*{{{*/
        return ($this->method === self::METHOD_OPTIONS);
    }/*}}}*/

    /**
     * Is this a PROPFIND method request?
     *
     * @return bool
     */
    public function isPropFind()
    {/*{{{*/
        return ($this->method === self::METHOD_PROPFIND);
    }/*}}}*/

    /**
     * Is this a GET method request?
     *
     * @return bool
     */
    public function isGet()
    {/*{{{*/
        return ($this->method === self::METHOD_GET);
    }/*}}}*/

    /**
     * Is this a HEAD method request?
     *
     * @return bool
     */
    public function isHead()
    {/*{{{*/
        return ($this->method === self::METHOD_HEAD);
    }/*}}}*/

    /**
     * Is this a POST method request?
     *
     * @return bool
     */
    public function isPost()
    {/*{{{*/
        return ($this->method === self::METHOD_POST);
    }/*}}}*/

    /**
     * Is this a PUT method request?
     *
     * @return bool
     */
    public function isPut()
    {/*{{{*/
        return ($this->method === self::METHOD_PUT);
    }/*}}}*/

    /**
     * Is this a DELETE method request?
     *
     * @return bool
     */
    public function isDelete()
    {/*{{{*/
        return ($this->method === self::METHOD_DELETE);
    }/*}}}*/

    /**
     * Is this a TRACE method request?
     *
     * @return bool
     */
    public function isTrace()
    {/*{{{*/
        return ($this->method === self::METHOD_TRACE);
    }/*}}}*/

    /**
     * Is this a CONNECT method request?
     *
     * @return bool
     */
    public function isConnect()
    {/*{{{*/
        return ($this->method === self::METHOD_CONNECT);
    }/*}}}*/

    /**
     * Is this a PATCH method request?
     *
     * @return bool
     */
    public function isPatch()
    {/*{{{*/
        return ($this->method === self::METHOD_PATCH);
    }/*}}}*/

    /**
     * Is the request a Javascript XMLHttpRequest?
     *
     * Should work with Prototype/Script.aculo.us, possibly others.
     *
     * @return bool
     */
    public function isXmlHttpRequest()
    {/*{{{*/
        $header = $this->getHeaders()->get('X_REQUESTED_WITH');
        return null !== $header && $header == 'XMLHttpRequest';
    }/*}}}*/

    /**
     * Is this a Flash request?
     *
     * @return bool
     */
    public function isFlashRequest()
    {/*{{{*/
        $header = $this->getHeaders()->get('USER_AGENT');
        return null !== $header && stristr($header, ' flash');
    }/*}}}*/
}
