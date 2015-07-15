<?php
/**
 * 框架入门 - 留言本例子 (Model层)
 *
 * 层级关系: controller -> model
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package Application\Model
 */
class BookModel extends BasePdo
{
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
        $this->table = $db_name . '.book';
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
     * @param string $name
     * @param string $content
     * @return boolean
     */
    public function add($name, $content)
    {/*{{{*/
        $this->init('master');

        $row['name'] = $name;
        $row['content'] = $content;
        $row['addtime'] = time();

        return $this->insert($row);
    }/*}}}*/

    /**
     * 更新, 选择的是主库
     *
     * @param string $reply
     * @param int $id
     *
     * @return boolean
     */
    public function updateById($id, $reply)
    {/*{{{*/
        $this->init('master');

        $row['reply'] = $reply;

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
    public function getList($page, $count)
    {/*{{{*/
        $this->init('slave');

        $start = $page * $count;
        $sql = "select * from %s where isrecycled = 0 Order by id DESC LIMIT {$start}, {$count}";
        $sql = sprintf($sql, $this->table);

        return $this->getRows($sql);
    }/*}}}*/

    /**
     * 删除, 选择的是主库
     *
     * @param int $id
     * @return boolean
     */
    public function delById($id)
    {/*{{{*/
        $this->init('master');

        $row['isrecycled'] = 1;

        return $this->update($row, array('id' => $id));
    }/*}}}*/
}
