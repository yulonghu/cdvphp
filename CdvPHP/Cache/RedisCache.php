<?php
/**
 * Redis 封装类
 *
 * <pre>
 * RedisCache::$servers = array('host' => 127.0.0.1, 'port' => 6379);
 * $redis = Loader::getInstance('RedisCache');
 * </pre>
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package CdvPHP\Cache
 */
class RedisCache implements CacheInterface
{
    /** @var array $servers Redis服务 */
    static public $server = array();

    /** @var array $redis */
    static public $redis = array();

    /** @var array $_redis */
    private $_redis = null;

    /**
     * auto addserver
     */
    public function __construct()
    {
        if(!empty(self::$server))
        {
            return $this->addserver();
        }
    }

    /**
     * addserver
     *
     * $server = array('host' => 127.0.0.1, 'port' => 6379);
     * $server = array('host' => 127.0.0.1, 'password' => '', 'port' => 6379, 'timeout' => 1, $persistent => 0);
     *
     * @param array $servers
     * @return void
     */
    public function addServer($server = array())
    {/*{{{*/
        if(empty($server)) 
        {
            $server = self::$server;
        }

        if(empty($server))
        {
            trigger_error('Redis server empty', E_USER_ERROR);
        }

        $key = md5(serialize($server));

        if (isset(self::$redis[$key]) && self::$redis[$key] instanceof Redis)
        {
            $this->_redis = self::$redis[$key];
        }
        else
        {
            $func = isset($server['persistent']) ? 'pconnect' : 'connect';

            try
            {
                $this->_redis = new Redis();
                $this->_redis->$func($server['host'], $server['port'], 1);
                $this->_redis->ping();
            }
            catch(RedisException $e)
            {
                try
                {
                    if(!isset($server['timeout']) || empty($server['timeout']))
                    {
                        $this->_redis->$func($server['host'], $server['port']);
                    }
                    else
                    {
                        $this->_redis->$func($server['host'], $server['port'], $server['timeout']);
                    }

                    $this->_redis->ping();
                }
                catch(RedisException $e)
                {
                    trigger_error($e->getMessage(), E_USER_ERROR);
                }
            }

            isset($server['password']) && $this->_redis->auth($server['password']);

            self::$redis[$key] = $this->_redis;
        }

        return $this->_redis;
    }/*}}}*/
    
    /**
     * 设置Redis服务器集群
     * @param array $server
     * @return void
     */
    public function setServer(array $server)
    {/*{{{*/
        self::$server = $server; 
    }/*}}}*/

    /**
     * 获取当前Redis服务器集群列表
     * @return array
     */
    public function getServer()
    {/*{{{*/
        return self::$server; 
    }/*}}}*/

    public function get($key)
    {/*{{{*/
        return $this->_redis->get($key); 
    }/*}}}*/

    public function set($key, $value, $expire = 0)
    {/*{{{*/
        return $this->_redis->set($key, $value, $expire); 
    }/*}}}*/

    public function getMulti(array $keys)
    {/*{{{*/
        return $this->_redis->mget($keys);
    }/*}}}*/

    public function setMulti(array $data, $expire = 0)
    {/*{{{*/
        return $this->_redis->mset($data);
    }/*}}}*/

    public function delete($key)
    {/*{{{*/
        return $this->_redis->del($key);
    }/*}}}*/

    public function increment($key, $count = 1)
    {/*{{{*/
        return $this->_redis->incr($key, intval($count));
    }/*}}}*/

    public function decrement($key, $count = 1)
    {/*{{{*/
        return $this->_redis->decr($key, intval($count));
    }/*}}}*/

    public function __call($name, $args)
    {/*{{{*/
        if(empty($this->_redis))
        {
            $this->_redis = new Redis();
        }

        $callback = array($this->_redis, $name);
        return call_user_func_array($callback, $args);
    }/*}}}*/

    public function __destruct()
    {/*{{{*/
        if(!empty($this->_redis))
        {
            //$this->_redis->close();
        }
    }/*}}}*/
}
