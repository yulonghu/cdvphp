<?php
/**
 * 框架入门 - 手动获取$_GET、$_POST、$_COOKIE、$_SERVER、$_ENV、$_FILES 例子
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package Application\Controller
 */
class HttpController extends AbstractBaseAction
{
	/**
	 * 获取$_GET参数; 如果$_GET参数password不存在, 默认值为 666888
	 *
	 * http://域名/index.php?method=Http.get&username=cdvphp&password=123456
	 *
	 * @return mixed
	 */
	public function get()
	{/*{{{*/
		var_dump($this->getRequest()->getQuery());
		var_dump($this->request->getQuery('username'));
		var_dump($this->request->getQuery('password', 666888));
	}/*}}}*/

	/**
	 * 全局设置 $_GET 参数; 支持批量设置$_GET
	 *
	 * http://域名/index.php?method=Http.setGet
	 *
	 * @return mixed
	 */
	public function setGet()
	{/*{{{*/
		$this->getRequest()->setQuery('username', 123456);
		var_dump($this->request->getQuery('username'));

		// 多个设置
		$this->getRequest()->setQuery(array('username', 'password'), 123456);
		var_dump($this->request->getQuery());
	}/*}}}*/

	/**
	 * 获取$_POST参数; 如果$_POST参数password不存在, 默认值为 666888
	 *
	 * http://域名/index.php?method=Http.post
	 *
	 * @return mixed
	 */
	public function post()
	{/*{{{*/
		var_dump($this->getRequest()->getPost());
		var_dump($this->request->getPost('username'));
		var_dump($this->request->getPost('password', 666888));
	}/*}}}*/

	/**
	 * 全局设置 $_POST 参数; 支持批量设置$_POST
	 *
	 * http://域名/index.php?method=Http.setPost
	 *
	 * @return mixed
	 */
	public function setPost()
	{/*{{{*/
		$this->getRequest()->setPost('username', 123456);
		var_dump($this->request->getPost('username'));

		// 多个设置
		$this->getRequest()->setPost(array('username', 'password'), 123456);
		var_dump($this->request->getPost());
	}/*}}}*/

	/**
	 * 获取$_COOKIE参数; 如果$_COOKIE参数cookie_password不存在, 默认值为 666888
	 *
	 * http://域名/index.php?method=Http.cookie
	 *
	 * @return mixed
	 */
	public function cookie()
	{/*{{{*/
		var_dump($this->getRequest()->getCookie());
		var_dump($this->request->getCookie('cookie'));
		var_dump($this->request->getCookie('cookie_password', 666888));
	}/*}}}*/

	/**
	 * 获取$_SERVER参数; 如果$_SERVER参数server_password不存在, 默认值为 666888
	 *
	 * http://域名/index.php?method=Http.server
	 *
	 * @return mixed
	 */
	public function server()
	{/*{{{*/
		var_dump($this->getRequest()->getServer());
		var_dump($this->request->getServer('HTTP_USER_AGENT'));
		var_dump($this->request->getServer('server_password', 666888));
	}/*}}}*/

	/**
	 * 获取$_ENV参数; 如果$_ENV参数env_password不存在, 默认值为 666888
	 *
	 * http://域名/index.php?method=Http.env
	 *
	 * @return mixed
	 */
	public function env()
	{/*{{{*/
		var_dump($this->getRequest()->getEnv());
		var_dump($this->request->getEnv('HTTP_USER_AGENT'));
		var_dump($this->request->getEnv('env_password', 666888));
	}/*}}}*/

	/**
	 * 获取$_FILES参数; 如果$_FILES参数files_password不存在, 默认值为 666888
	 *
	 * http://域名/index.php?method=Http.files
	 *
	 * @return mixed
	 */
	public function files()
	{/*{{{*/
		var_dump($this->getRequest()->getFiles());
		var_dump($this->request->getFiles('file'));
		var_dump($this->request->getFiles('files_password', 666888));
	}/*}}}*/

	/**
	 * 获取 Request Headers
	 *
	 * http://域名/index.php?method=Http.headers
	 *
	 * @return mixed
	 */
	public function headers()
	{/*{{{*/
		var_dump($this->getRequest()->getHeaders());
		var_dump($this->request->getHeader('ACCEPT_ENCODING'));
		var_dump($this->request->getHeader('server_password', 666888));
	}/*}}}*/

	/**
	 * 输出
	 *
	 * http://域名/index.php?method=Http.response
	 *
	 * @return mixed
	 */
	public function response()
	{/*{{{*/
		echo '<pre>';
		echo $this->getResponse()->appendBody('hello world')->getBody();
		echo PHP_EOL;
	
		$this->response->clearBody();
		echo $this->response->appendBody('hello world')->prependBody(2015)->getBody();
		echo PHP_EOL;

		//$this->response->setRedirect('http://www.baidu.com');

		$this->response->end();
		echo '</pre>';
	}/*}}}*/
}
