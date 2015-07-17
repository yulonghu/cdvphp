<?php
/**
 * 框架入门 - Memcached、Redis缓存使用
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package Application\Controller
 */
class CacheController
{
    /**
     * Memcached 例子 1
     *
     * http://域名/index.php?method=Cache.mc1
     *
     * $servers数组的标准定义: $servers = array(array('127.0.0.1', 11212), array('127.0.0.2', 11212));
     *
     * @return mixed
     */
    public function mc1()
    {/*{{{*/
        MemcachedCache::$servers = array(array('127.0.0.1', 11212));
        $mc = Loader::getInstance('MemcachedCache');
        var_dump($mc->set('aaa', 1));
        print_r($mc);

    }/*}}}*/

    /**
     * Memcached 例子 2
     *
     * http://域名/index.php?method=Cache.mc2
     *
     * $servers数组的标准定义: $servers = array(array('127.0.0.1', 11212), array('127.0.0.2', 11212));
     *
     * @return mixed
     */
    public function mc2()
    {/*{{{*/
        $mc = Loader::getInstance('MemcachedCache');
   //     $mc->addServer(array(array('127.0.0.1', 11212)));
        $mc->addServer(array(array('1.1.1.1', 2222)));
        var_dump($mc->set('aaa', 1));
        print_r($mc);
    }/*}}}*/

    /**
     * Redis 例子 1
     *
     * Redis 连接只支持单台服务器连接
     *
     * http://域名/index.php?method=Cache.redis1
     *
     * 数组key=>value说明: host 主机IP  password 验证密码   port 端口   timeout 超时时间  $persistent  长连接
     *
     * $sever数组标准定义: $server = array('host' => 127.0.0.1, 'password' => '', 'port' => 6379, 'timeout' => 1, $persistent => 0);
     *
     * @return mixed
     */
    public function redis1()
    {/*{{{*/
        RedisCache::$server = array('host' => '127.0.0.1', 'port' => 6379, 'timeout' => 3);
        $redis = Loader::getInstance('RedisCache');

        // 设置 key => value , 过期时间: 10s
        var_dump($redis->set('redis_test', 123456, 10));
        // 获取 key值
        var_dump($redis->get('redis_test'));

        // 加 1
        var_dump($redis->increment('redis_count'));
        // 减 1
        var_dump($redis->decrement('redis_count'));

        // 设置 key => value
        var_dump($redis->set('redis_del', 123456));
        // 删除 key
        var_dump($redis->delete('redis_del'));

        // 批量设置key
        var_dump($redis->setMulti(array('redis_mset1' => 'val1', 'redis_mset2' => 'val2')));
        // 批量获取key
        var_dump($redis->getMulti(array('redis_mset1', 'redis_mset2')));

        print_r($redis->getServer());
    }/*}}}*/

    /**
     * Redis 例子 2
     *
     * Redis 连接只支持单台服务器连接
     *
     * http://域名/index.php?method=Cache.redis2
     *
     * 数组key=>value说明: host 主机IP  password 验证密码   port 端口   timeout 超时时间  $persistent  长连接
     *
     * $sever数组标准定义: $server = array('host' => 127.0.0.1, 'password' => '', 'port' => 6379, 'timeout' => 1, $persistent => 0);
     *
     * @return mixed
     */
    public function redis2()
    {/*{{{*/
        $redis = Loader::getInstance('RedisCache');
        $redis->addServer(array('host' => '127.0.0.1', 'port' => 6379, 'timeout' => 3));
        print_r($redis);
    }/*}}}*/
}
