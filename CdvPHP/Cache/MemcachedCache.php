<?php
/**
 * Memcached 封装类
 *
 * <pre>
 * MemcachedCache::$servers = array(array('10.16.57.205', 11212));
 * $mcd = Loader::getInstance('MemcachedCache');
 * print_r($mcd->getServer());
 * var_dump($mcd->set('abcd', 1));
 * </pre>
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package CdvPHP\Cache
 */
class MemcachedCache implements CacheInterface
{
    /** @var array $servers Mc服务端IP地址 */
    static public $servers = array();
    /** @var array $memcached */
    static public $memcached = array();

    /** @var bool $compress */
    public $compress = TRUE;
    /** @var bool $tcp_nodelay */
    public $tcp_nodelay = TRUE;

    /** @var string $prefix_key */
    public $prefix_key = '';
    /** @var array $_mcd */
    private $_mcd = null;

    /**
     * auto addserver
     */
    public function __construct()
    {/*{{{*/
       return $this->addserver();
    }/*}}}*/

    /**
     * addserver
     *
     * $servers = array(array('127.0.0.1', 11211));
     * $servers = array(array('127.0.0.1', 11211, 33));
     *
     * @param array $servers
     * @return void
     */
    public function addServer($servers = array())
    {/*{{{*/
        if(empty($servers)) 
        {
            $servers = self::$servers;
        }

        if(empty($servers))
        {
            trigger_error('Memcached servers empty', E_USER_ERROR);
        }

        $key = md5(serialize($servers));

        if (isset(self::$memcached[$key]) && self::$memcached[$key] instanceof Memcached)
        {
            $this->_mcd = self::$memcached[$key];
        }
        else
        {
            $this->_mcd = new Memcached();
            // 一致性hash
            $this->_mcd->setOption(Memcached::OPT_DISTRIBUTION, Memcached::DISTRIBUTION_CONSISTENT);
            $this->_mcd->setOption(Memcached::OPT_LIBKETAMA_COMPATIBLE, TRUE);

            if(!$this->compress)
            {
                $this->_mcd->setOption(Memcached::OPT_COMPRESSION, FALSE);
            }

            if($this->tcp_nodelay)
            {
                $this->_mcd->setOption(Memcached::OPT_TCP_NODELAY, TRUE);
            }

            // key前缀
            if($this->prefix_key)
            {
                $this->_mcd->setOption(Memcached::OPT_PREFIX_KEY, $this->prefix_key);
            }

            $this->_mcd->addServers($servers);
            self::$memcached[$key] = $this->_mcd;
        }

        return $this->_mcd;
    }/*}}}*/
    
    /**
     * 设置MC服务器集群
     * @param array $servers
     * @return void
     */
    public function setServer(array $servers)
    {/*{{{*/
        self::$servers = $servers; 
    }/*}}}*/

    /**
     * 获取当前MC服务器集群列表
     * @return array
     */
    public function getServer()
    {/*{{{*/
        return self::$servers; 
    }/*}}}*/

    public function get($key)
    {/*{{{*/
        return $this->_mcd->get($key); 
    }/*}}}*/

    public function set($key, $value, $expire = 0)
    {/*{{{*/
        return $this->_mcd->set($key, $value, $expire); 
    }/*}}}*/

    public function getMulti(array $keys)
    {/*{{{*/
        return $this->_mcd->getMulti($keys);
    }/*}}}*/

    public function setMulti(array $data, $expire = 0)
    {/*{{{*/
        return $this->_mcd->setMulti($data, $expire);
    }/*}}}*/

    public function delete($key)
    {/*{{{*/
        return $this->_mcd->delete($key);
    }/*}}}*/

    public function increment($key, $count = 1)
    {/*{{{*/
        return $this->_mcd->increment($key, intval($count));
    }/*}}}*/

    public function decrement($key, $count = 1)
    {/*{{{*/
        return $this->_mcd->decrement($key, intval($count));
    }/*}}}*/

    public function __call($name, $args)
    {/*{{{*/
        if(empty($this->_mcd))
        {
            $this->_mcd = new Memcached();
        }

        $callback = array($this->_mcd, $name);
        return call_user_func_array($callback, $args);
    }/*}}}*/

    public function __destruct()
    {/*{{{*/
        if(!empty($this->_mcd))
        {
            $this->_mcd->quit();
        }
    }/*}}}*/
}
