<?php
/**
 * 框架入门 - 留言本例子
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package Application\Controller
 */
class BookController extends AbstractBaseAction
{
	/**
	 * 留言本数据列表
	 * @return string
	 */
	public function index()
	{/*{{{*/
		// 获取GET参数page, 如果$_GET['page']不存在，初始默认值0
		$page = intval($this->getRequest()->getQuery('page', 0));
	
		// 实例化Model, 读取数据
		$count = 10;
		$data = Loader::getInstance('BookModel')->getList($page, $count);

		// 把从DB查询的结果输出到模版变量list
		$this->getView()->assign('data', $data);

		// 加载模版
		$this->view->display();
	}/*}}}*/

	/**
	 *  新增留言内容 - 加载模版
	 *  @return string
	 */
	public function add()
	{/*{{{*/
		$this->getView()->display();
	}/*}}}*/

	/**
	 *  新增留言内容
	 *  采用手动获取POST参数方式
	 *  @return string
	 */
	public function postadd()
	{/*{{{*/
		$name = $this->getRequest()->getPost('name');
		$content = $this->getRequest()->getPost('content');

		$msg = array('errno' => 0, 'errmsg' => '', 'data' => '');
		if(empty($name) || empty($content))
		{
			$msg['errno'] = 100;
			$msg['errmsg'] = '留言作者 或者 留言内容 不能为空';
			$this->getResponse()->setBody(json_encode($msg))->end();
		}


		$result = Loader::getInstance('BookModel')->add($name, $content);
		// $result = true 入库成功
		if($result)
		{
			$msg['errno'] = 0;
			$msg['data'] = '留言成功啦';
		}
		else
		{
			$msg['errno'] = 101;
			$msg['errmsg'] = '留言失败啦';
		}

		$msg = json_encode($msg);
		$this->getResponse()->setBody($msg)->end();

		// exit 终止模版文件输出, 这是一个获取POST数据接口
		//exit(0);
	}/*}}}*/

	/**
	 *  回复留言内容 - 加载模版
	 *  采用框架内置获取GET参数方式
	 *  @param intval $id
	 *  @return string
	 */
	public function reply($id)
	{/*{{{*/
		$data = Loader::getInstance('BookModel')->getInfoById($id);
		if(empty($data))
		{
			$this->getResponse()->setBody('id not found')->end();
		}

		$this->getView()->assign('data', $data);
		$this->view->display();
	}/*}}}*/

	/**
	 *  回复留言内容
	 *  采用框架内置获取POST参数方式
	 *  @param intval $p_id
	 *  @param intval $p_reply
	 *  @return string
	 */
	public function postreply($p_id, $p_reply)
	{/*{{{*/
		$msg = array('errno' => 0, 'errmsg' => '', 'data' => '');
		if(empty($p_reply))
		{
			$msg['errno'] = 100;
			$msg['errmsg'] = '回复内容 不能为空';
			$this->getResponse()->setBody(json_encode($msg))->end();
		}

		$result = Loader::getInstance('BookModel')->updateById($p_id, $p_reply);
		// $result = true 回复留言成功
		if($result)
		{
			$msg['errno'] = 0;
			$msg['data'] = '回复留言成功啦';
		}
		else
		{
			$msg['errno'] = 101;
			$msg['errmsg'] = '回复留言失败啦';
		}

		$msg = json_encode($msg);
		$this->getResponse()->setBody($msg)->end();
	}/*}}}*/

	/**
	 *  删除留言内容
	 *  采用框架内置常量方式获取GET参数
	 *  @return string
	 */
	public function del()
	{/*{{{*/
		$id = Sg::$inputs['get']['id'];

		Loader::getInstance('BookModel')->delById($id);

		header('Location:'. $this->getRequest()->getServer('HTTP_REFERER'));

		exit(0);
	}/*}}}*/
}
