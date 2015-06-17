<?php
/**
 * Session类的封装
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package CdvPHP\Session
 */
class Session
{
	/**
	 * @var string $prefix session变量名前缀, 默认为空
	 */
	private $prefix = null;

	/**
	 * __construct
	 * @return void
	 */
	public function __construct()
	{/*{{{*/
		$session_id = session_id();
		if(empty($session_id))
		{
			session_start();
		}
	}/*}}}*/

	/**
	 * 设置会话变量前缀
	 *
	 * @var string $prefix 设置会话前缀
	 * @return string
	 */
	public function setPrefix($prefix = '')
	{/*{{{*/
		$this->prefix = $prefix;
		return $this->prefix;
	}/*}}}*/
	
	/**
	 * 获取会话变量前缀, 默认值null
	 *
	 * @return string
	 */
	public function getPrefix()
	{/*{{{*/
		return $this->prefix;
	}/*}}}*/
	
	/**
	 * 删除指定会话
	 *
	 * @param string $name 会话名称
	 * @return void
	 */
	public function del($name)
	{/*{{{*/
		if(isset($_SESSION[$this->prefix . $name]))
		{
			unset($_SESSION[$this->prefix . $name]);
		}
	}/*}}}*/
	
	/**
	 * 设置会话值
	 *
	 * @param string $name 会话key
	 * @param string $value 会话value
	 * @return void
	 */
	public function set($name, $value)
	{/*{{{*/
		$_SESSION[$this->prefix . $name] = $value;
		return TRUE;
	}/*}}}*/
	
	/**
	 * 读取会话值
	 *
	 * @param string $name 会话key
	 * @return mixed 找不到key的值, 返回null
	 */
	public function get($name)
	{/*{{{*/
		if (isset($_SESSION[$this->prefix . $name]))
		{
			return $_SESSION[$this->prefix . $name];
		}
		else
		{
			return null;
		}
	}/*}}}*/

	/**
	 * 读取全部会话值
	 *
	 * @return mixed
	 */
	public function getAll()
	{/*{{{*/
		return $_SESSION;
	}/*}}}*/

	/**
	 * 销毁当前用户所有会话值
	 *
	 * @return bool
	 */
	public function destroy()
	{/*{{{*/
		$_SESSION = array();
		return session_destroy();
	}/*}}}*/
}
