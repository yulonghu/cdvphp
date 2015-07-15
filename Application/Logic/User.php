<?php
/**
 * 框架入门 - DB操作的例子 (Logic层)
 *
 * 层级关系: controller -> logic -> model
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package Application\Logic
 */
class UserLogic
{
    /** @var object $_user_model */
    private $_user_model = null;

    /**
     * __construct
     * @return void
     */
    public function __construct()
    {/*{{{*/
        $this->_user_model = Loader::getInstance('UserModel');
    }/*}}}*/

    /**
     * 查询多条
     * @return array
     */
    public function getList()
    {/*{{{*/
        return $this->_user_model->getList();
    }/*}}}*/

    /**
     * 根据id读取一条记录
     * @param int $id
     * @return array
     */
    public function getInfo($id)
    {/*{{{*/
        $id = intval($id);
        return $this->_user_model->getInfoById($id);
    }/*}}}*/

    /**
     * 添加
     *
     * @param string $user
     * @param int | string $pass
     *
     * @return boolean
     */
    public function add($user, $pass)
    {/*{{{*/
        $pass = md5($pass);
        $row = array('user' => $user, 'pass' => $pass, 'addtime' => time());
        return $this->_user_model->addUserInfo($row);
    }/*}}}*/

    /**
     * replace
     *
     * @param int $id
     * @param string $user
     * @param int | string $pass
     *
     * @return boolean
     */
    public function replaceInfo($id, $user, $pass)
    {/*{{{*/
        $id = intval($id);
        $pass = md5($pass);
        $row = array('id' => $id, 'user' => $user, 'pass' => $pass, 'addtime' => time());

        return $this->_user_model->replaceUserInfo($row);
    }/*}}}*/

    /**
     * 更新
     *
     * @param int $id
     * @param int | string $pass
     *
     * @return boolean
     */
    public function updateById($id, $pass)
    {/*{{{*/
        $id = intval($id);
        $pass = md5($pass);
        return $this->_user_model->updateById(array('pass' => $pass), $id);
    }/*}}}*/

    /**
     * 删除
     *
     * @param int $id
     * @return string
     */
    public function deleteById($id)
    {/*{{{*/
        $id = intval($id);
        return $this->_user_model->deleteId($id);
    }/*}}}*/
}
