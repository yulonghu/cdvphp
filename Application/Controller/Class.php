<?php
/**
 * 框架入门 - 封装类库使用例子
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package Application\Controller
 */
class ClassController
{
	/** @var object $_session 会话类实例 */
	private $_session = null;

	/** @var object $_censor 关键字检查实例 */
	private $_censor = null;

	/**
	 * __construct 这个没什么好说的了
	 *
	 * 做一些类的初始化操作
	 *
	 * @return void
	 */
	public function __construct()
	{/*{{{*/
		Loader::getInstance('Timer')->start();
		$this->_session = Loader::getInstance('Session');
		$this->_censor = Loader::getInstance('Censor');
	}/*}}}*/

	/**
	 * 获取加载了多少类
	 *
	 * http://域名/index.php?method=Class.test
	 *
	 * @return array
	 */
	public function test()
	{/*{{{*/
		// 获取加载了多少类
		print_r(Loader::getRegisteredClass());
	}/*}}}*/

	/**
	 * 检查非法关键词例子
	 *
	 * http://域名/index.php?method=Class.censor
	 *
	 * @return boolean
	 */
	public function censor()
	{/*{{{*/
		var_dump($this->_censor->checkFilterWord('x官方1'));
	}/*}}}*/

	/**
	 * 验证码例子
	 *
	 * 读取验证码 Loader::getInstance('Session')->get('code')
	 *
	 * http://域名/index.php?method=Class.code
	 *
	 * @return image/PNG
	 */
	public function code()
	{/*{{{*/
		Header("Content-type: image/PNG");
		echo Loader::getInstance('Code')->getEasyCode();
	}/*}}}*/
	
	/**
	 * 签名例子
	 *
	 * http://域名/index.php?method=Class.sign
	 *
	 * @return string
	 */
	public function sign()
	{/*{{{*/
		$obj = Loader::getInstance('Sign');
		//$obj->setKey('111111111');
		echo $obj->init(array('get' => $_GET));
	}/*}}}*/

	/**
	 * 写日志
	 *
	 * http://域名/index.php?method=Class.logger
	 *
	 * @return string
	 */
	public function logger()
	{/*{{{*/
		$obj = Loader::getInstance('logger');
		var_dump($obj->siteInfo());
	}/*}}}*/

	/**
	 * HashTable例子
	 *
	 * http://域名/index.php?method=Class.hashTable
	 *
	 * @return int
	 */
	public function hashTable()
	{/*{{{*/
		echo Loader::getInstance('HashTable')->getIntergerHash(12345, 10);
	}/*}}}*/

	/**
	 * 读取配置文件例子
	 *
	 * http://域名/index.php?method=Class.config
	 *
	 * @return int
	 */
	public function config()
	{/*{{{*/
		echo '<pre>';
		// 读取系统配置
		print_r(ConfigLoader::getVar('system'));
		echo '<hr>';
		// 读取全部配置
		print_r(ConfigLoader::getVars());
		echo '<hr>';
		// 读取数组子项
		print_r(ConfigLoader::getValue('admin')); echo PHP_EOL;

		print_r(ConfigLoader::getValue('db')->master);
		print_r(ConfigLoader::getValue('db')->slave);

		print_r(ConfigLoader::getValue('db')->master->host); echo PHP_EOL;
		print_r(ConfigLoader::getValue('system')->template->path);
		echo '</pre>';
	}/*}}}*/

	/**
	 * 会话操作
	 *
	 * http://域名/index.php?method=Class.setSession
	 *
	 * @return int
	 */
	public function setSession()
	{/*{{{*/
		$this->_session->setPrefix('cdv_');
		var_dump($this->_session->set('pass', '123456'));
	}/*}}}*/

	public function getSession()
	{/*{{{*/
		$this->_session->setPrefix('cdv_');
		var_dump($this->_session->get('pass'));
		var_dump($this->_session->getAll());
	}/*}}}*/

	/**
	 * CURL例子
	 *
	 * http://域名/index.php?method=class.curl
	 *
	 * @return mixed
	 */
	public function curl()
	{/*{{{*/
		$curl = Loader::getInstance('Curl');
		echo $curl->get('www.baidu.com');
	}/*}}}*/

	/**
	 * 程序执行结束，输出花费了多少时间
	 *
	 * @return int
	 */
	public function __destruct()
	{/*{{{*/
		echo '<hr> exec_time: ' . Loader::getInstance('Timer')->end();
	}/*}}}*/
}
