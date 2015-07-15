<?php
/**
 * 框架入门 - DB操作的例子 (Model层)
 *
 * 层级关系: controller -> logic -> model
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package Application\Model
 */
class UserModel extends BasePdo
{
    /**
     * __construct
     * @return void
     */
    public function __construct()
    {/*{{{*/ 
    }/*}}}*/

    /**
     * 设置表的主键ID
     * @return sring
     */
    public function id()
    {/*{{{*/
        return 'id';
    }/*}}}*/

    /**
     * 设置表名
     *
     * @param stirng $db_name 表名称
     * @param int  $table_id 分表ID
     * @return sring
     */	
    public function table($db_name, $table_id = 0)
    {/*{{{*/
        $this->table = $db_name . '.user';
    }/*}}}*/

    /**
     * 选择性连接DB
     *
     * 选择性选择表名
     *
     * @param stirng $db_type 从库、主库选择
     * @return void
     */	
    public function init($db_type = 'slave') 
    {/*{{{*/ 
        $arr_data = ConfigLoader::getVar('db');

        $this->link($arr_data[$db_type]);
        $this->table($arr_data[$db_type]['dbname']);
    }/*}}}*/

    /**
     * 添加, 选择的是主库
     *
     * @param array $row
     * @return boolean
     */
    public function addUserInfo($row)
    {/*{{{*/
        $this->init('master');

        return $this->insert($row);
    }/*}}}*/

    /**
     * replace, 选择的是主库
     *
     * @param array $row
     * @return boolean
     */
    public function replaceUserInfo($row)
    {/*{{{*/
        $this->init('master');

        return $this->replace($row, true);
    }/*}}}*/

    /**
     * 更新, 选择的是主库
     *
     * @param array $row
     * @param int $id
     *
     * @return boolean
     */
    public function updateById($row, $id)
    {/*{{{*/
        $this->init('master');
        return $this->update($row, array('id' => $id));
    }/*}}}*/

    /**
     * 根据id读取一条记录, 选择的是从库
     *
     * @param int $id
     * @return array
     */
    public function getInfoById($id)
    {/*{{{*/
        $this->init('slave');
        $sql = 'select * from %s where id = ?';
        $sql = sprintf($sql, $this->table);

        return $this->getRow($sql, array($id));
    }/*}}}*/

    /**
     * 查询多条, 选择的是从库
     *
     * @return array
     */
    public function getList()
    {/*{{{*/
        $this->init('slave');
        $sql = 'select * from %s Order by id DESC LIMIT 10';
        $sql = sprintf($sql, $this->table);

        return $this->getRows($sql);
    }/*}}}*/

    /**
     * 删除, 选择的是主库
     *
     * @param int $id
     * @return boolean
     */
    public function deleteId($id)
    {/*{{{*/
        $this->init('master');
        return $this->deleteById($id);
    }/*}}}*/
}
