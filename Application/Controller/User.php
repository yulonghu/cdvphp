<?php
/**
 * 框架入门 - DB操作的例子 (Controller层)
 *
 * 为了本地测试方便, GET参数调试
 *
 * 层级关系: controller -> logic -> model
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package Application\Controller
 */
class UserController
{
	/** @var object $_user_logic */
	private $_user_logic = null;

	/**
	 * __construct
	 * @return void
	 */
	public function __construct()
	{/*{{{*/
		$this->_user_logic = Loader::getInstance('UserLogic');
	}/*}}}*/

	/**
	 * 根据id读取一条记录
	 *
	 * http://域名/index.php?method=user.getInfo&id=1
	 *
	 * @param int $id
	 * @return array
	 */
	public function getInfo($id)
	{/*{{{*/
		print_r($this->_user_logic->getInfo($id));
	}/*}}}*/

	/**
	 * 查询多条
	 *
	 * http://域名/index.php?method=user.getList
	 *
	 * @return array
	 */
	public function getList()
	{/*{{{*/
		print_r($this->_user_logic->getList());
	}/*}}}*/
	
	/**
	 * 添加
	 *
	 * http://域名/index.php?method=user.add
	 *
	 * @param string $user
	 * @param int | string $pass
	 *
	 * @return string
	 */
	public function add($user, $pass)
	{/*{{{*/
		var_dump($this->_user_logic->add($user, $pass));
	}/*}}}*/

	/**
	 * replace
	 *
	 * http://域名/index.php?method=user.replaceInfo&id=1&user=random&pass=4556789
	 *
	 * @param int $id
	 * @param string $user
	 * @param int | string $pass
	 *
	 * @return string
	 */
	public function replaceInfo($id, $user, $pass)
	{/*{{{*/
		var_dump($this->_user_logic->replaceInfo($id, $user, $pass));
	}/*}}}*/

	/**
	 * 更新
	 *
	 * http://域名/index.php?method=user.updateById&id=1&pass=dddd
	 *
	 * @param int $id
	 * @param int | string $pass
	 *
	 * @return string
	 */
	public function updateById($id, $pass)
	{/*{{{*/
		var_dump($this->_user_logic->updateById($id, $pass));
	}/*}}}*/

	/**
	 * 删除
	 *
	 * http://域名/index.php?method=user.deleteById&id=3
	 *
	 * @param int $id
	 * @return string
	 */
	public function deleteById($id)
	{/*{{{*/
		var_dump($this->_user_logic->deleteById($id));
	}/*}}}*/
}
