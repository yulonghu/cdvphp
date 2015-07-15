<?php
/**
 * 所有缓存 interface 接口类
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package CdvPHP\Cache
 */
interface CacheInterface
{
    /**
     * 读取一个值
     *
     * @param string $key 健名
     * @return mixed
     */
    public function get($key);

    /**
     * 写入一个值
     *
     * @param string $key 健名
     * @param mixed $value 值
     * @param int $expire 过期时间
     *
     * @return boolean
     */
    public function set($key, $value, $expire = 0);

    /**
     * 删除一个key
     *
     * @param string $key 健名
     * @return boolean
     */
    public function delete($key);

    /**
     * increment 
     *
     * @param string $key 健名
     * @param int $count 增加的值, 默认 1
     * @return int
     */
    public function increment($key, $count = 1);

    /**
     * decrement 
     *
     * @param string $key 健名
     * @param int $count 减少的值, 默认 1
     * @return int
     */
    public function decrement($key, $count = 1);

    /**
     * 批量读取key的值
     *
     * @param array $keys 健名
     * @return array
     */
    public function getMulti(array $keys);

    /**
     * 批量写入key的值
     *
     * @param array $data array(健名=>值)
     * @return boolean
     */
    public function setMulti(array $data, $expire = 0);
}
