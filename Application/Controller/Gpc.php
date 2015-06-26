<?php
/**
 * 框架入门 - 自动获取$_GET、$_POST 参数例子
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package Application\Controller
 */
class GpcController
{
	/**
	 * 获取GET参数的值, 如果$_GET参数password不存在, 默认值为 666888
	 *
	 * http://域名/index.php?method=Gpc.world&username=cdvphp&password=123456
	 *
	 * @param mixed $username
	 * @param mixed $password 
	 *
	 * @return mixed
	 */
	public function world($username, $password = '666888')
	{/*{{{*/
		var_dump($username);
		var_dump($password);
	}/*}}}*/

	/**
	 * 获取$_POST参数的值
	 *
	 * http://域名/index.php?method=Gpc.world
	 *
	 * POST: username=cdvphp&password=123456
	 *
	 * @param mixed $p_username
	 * @param mixed $password 
	 *
	 * @return mixed
	 */
	public function check($p_username, $password)
	{/*{{{*/
		var_dump($p_username);
		var_dump($password);
	}/*}}}*/

	/**
	 * 获取超全局数据($_GET、$_POST、$_COOKIE)的值
	 *
	 * http://域名/index.php?method=Gpc.index
	 *
	 * @return mixed
	 */
	public function index()
	{/*{{{*/
		Superglobal::$outputs['a'] = 1;
		var_dump(Superglobal::$outputs);

		var_dump(Superglobal::$inputs['get']);
		var_dump(Superglobal::$inputs['post']);
		var_dump(Superglobal::$inputs['cookie']);

		// 别名
		var_dump(Sg::$inputs['get']);
		var_dump(Sg::$inputs['post']);
		var_dump(Sg::$inputs['cookie']);
	}/*}}}*/
}
