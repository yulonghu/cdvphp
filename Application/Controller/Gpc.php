<?php
/**
 * 框架入门 - 获取GET、POST参数例子
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package Application\Controller
 */
class GpcController
{
	/**
	 * 获取GET参数, 如果GET参数password不存在, 默认值为 666888
	 *
	 * http://域名/index.php?method=Hello.world&username=cdvphp&password=123456
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
	 * 获取POST参数
	 *
	 * http://域名/index.php?method=Hello.world
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
	 * 获取超全局数据($_GET、$_POST、$_COOKIE)
	 *
	 * http://域名/index.php?method=Hello.gpc
	 *
	 * @return mixed
	 */
	public function gpc()
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
