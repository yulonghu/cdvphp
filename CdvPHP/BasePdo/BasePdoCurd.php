<?php
/**
 * MYSQL PDO CURD 封装类
 *
 * 如果debug === true 记录sql执行全程日志
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package CdvPHP\BasePdo
 */
abstract class BasePdoCurd
{
    /**
     * 查询一条记录
     *
     * @see BasePdo::query()
     * @param string $sql
     * @param array $values
     *
     * @return array
     */
    public function getRow($sql, $values = array())
    {/*{{{*/
        return $this->query($sql, $values);
    }/*}}}*/

    /**
     * 查询多条记录
     *
     * @see BasePdo::querys()
     * @param string $sql
     * @param array $values
     *
     * @return array
     */
    public function getRows($sql, $values = array())
    {/*{{{*/
        return $this->querys($sql, $values);
    }/*}}}*/

    /**
     * 更新操作封装, 最后调用 BasePdo::execNoQuery()
     *
     * @param array $data array('user' => 'cdvphp', 'pass' => 123456)
     * @param array $condition array('id' => 1)
     * @param boolean $low_priority 低优先级; 如果等于true, UPDATE [LOW_PRIORITY] tbl_name SET col_name1=expr1, col_name2=expr2
     * 
     * @return boolean true代表成功, 否则失败
     */
    public function update($data, $condition = '', $low_priority = FALSE)
    {/*{{{*/
        if(!$data || !is_array($data))
        {
            return FALSE;
        }

        $arr_set_key = $arr_where_key = $arr_value = array();
        foreach($data as $key => $val)
        {
            $arr_set_key[] = "{$key} = ?";
            $arr_value[] = $val;
        }

        if($condition && is_array($condition))
        {
            foreach($condition as $key => $val)
            {
                $arr_where_key[] = "{$key} = ?";
                $arr_value[] = $val;
            }
        }

        $cmd = "UPDATE " . ($low_priority ? 'LOW_PRIORITY' : '');
        $set = implode(', ', $arr_set_key);

        if($arr_where_key)
        {
            $where = implode(' AND ', $arr_where_key);
            $sql = "{$cmd} %s SET {$set} WHERE {$where}";
        }
        else
        {
            $sql = "{$cmd} %s SET {$set}";
        }

        $sql = sprintf($sql, $this->getTable());

        return $this->execNoQuery($sql, $arr_value);
    }/*}}}*/

    /**
     * 更新操作封装, 最后调用 BasePdo::execNoQuery()
     *
     * @param array $data array('user' => 'cdvphp', 'pass' => 123456)
     * @param boolean $retrun_insert_id 如果等于true, 则返回本次插入的lastInsertId, 否则返回本次操作boolean结果
     * @param boolean $replace 不建议调用insert方法传true, 而是调用replace方法
     * 
     * @return boolean|int 当boolean时, true代表成功; 当int时, 大于0代表成功 
     */
    public function insert($data, $retrun_insert_id = FALSE, $replace = FALSE)
    {/*{{{*/
        if(!$data || !is_array($data))
        {
            return FALSE;
        }

        $arr_set_key = $arr_value = array();
        foreach($data as $key => $val)
        {
            $arr_set_key[] = "{$key} = ?";
            $arr_value[] = $val;
        }

        $set = implode(', ', $arr_set_key);
        $cmd = $replace ? 'REPLACE INTO' : 'INSERT INTO';
        $sql = sprintf("{$cmd} %s SET {$set}", $this->getTable());

        $bool_result = $this->execNoQuery($sql, $arr_value);

        return ($retrun_insert_id && $bool_result) ? $this->lastInsertId() : $bool_result;
    }/*}}}*/

    /**
     * DB replace操作
     * @see BasePdoCurd::insert()
     *
     * @param array $data
     * @param boolean $retrun_replace_id
     *
     * @return boolean|int
     */
    public function replace($data, $retrun_replace_id = FALSE)
    {/*{{{*/
        return $this->insert($data, $retrun_replace_id, TRUE);
    }/*}}}*/

    /**
     * 删除操作封装, 最后调用 BasePdo::execNoQuery()
     *
     * @param array $condition array('user' => 'cdvphp', 'id' => 1)
     * @param int $limit  担心误删多条, 自定义删除limit条数
     * 
     * @return boolean true代表成功, 否则失败
     */
    public function delete($condition, $limit = 0)
    {/*{{{*/
        if(!$condition || !is_array($condition))
        {
            return FALSE;
        }

        $arr_where_key = $arr_value = array();
        foreach($condition as $key => $val)
        {
            $arr_where_key[] = "{$key} = ?";
            $arr_value[] = $val;
        }

        $where = implode(' AND ', $arr_where_key);
        $limit = $limit ? " LIMIT {$limit}" : '';
        $sql = "DELETE FROM %s WHERE {$where} {$limit}";
        $sql = sprintf($sql, $this->getTable());

        return $this->execNoQuery($sql, $arr_value);
    }/*}}}*/

    /**
     * 简易删除操作封装, 最后调用 BasePdo::execNoQuery()
     *
     * @param int $id
     * @return boolean true代表成功, 否则失败
     */
    public function deleteById($id)
    {/*{{{*/
        $pkid = $this->id();
        $sql = "DELETE FROM %s WHERE {$pkid} = ?";
        $sql = sprintf($sql, $this->getTable());

        return $this->execNoQuery($sql, array($id));
    }/*}}}*/

    /**
     * 获取记录总数
     *
     * @param string $sql
     * @param array $condition
     *
     * @return int
     */
    public function getCount($condition = array())
    {/*{{{*/
        $arr_where_key = $arr_value = array();

        if($condition && is_array($condition))
        {
            foreach($condition as $key => $val)
            {
                $arr_where_key[] = "{$key} = ?";
                $arr_value[] = $val;
            }
        }

        if($arr_where_key)
        {
            $where = implode(' AND ', $arr_where_key);
            $sql = "SELECT count(*) as cnt FROM %s WHERE {$where}";
        }
        else
        {
            $sql = "SELECT count(*) as cnt FROM %s";
        }

        $sql = sprintf($sql, $this->getTable());
        $row =$this->query($sql, $arr_value);

        return isset($row['cnt']) ? $row['cnt'] : 0;
    }/*}}}*/

    /**
     * addByArray alias insert
     *
     * @param array $pairs
     * @return boolean
     */
    public function addByArray($data = array())
    {/*{{{*/
        return $this->insert($data);
    }/*}}}*/

    /**
     * on duplicate key update
     * 用于唯一索引更新
     *
     * @param array $data
     * @param array $condition
     *
     * @return boolean true成功 false失败
     */
    public function addDupByArray($data = array(), $condition = array())
    {/*{{{*/
        if (empty($data) || !is_array($data)) {
            return FALSE;
        }

        if (empty($condition) || !is_array($condition)) {
            return FALSE;
        }

        $arr_set_key = $arr_where_key = $arr_value = array();

        foreach ($data as $key => $val) {
            $arr_set_key[] = "{$key} = ?";
            $arr_value[] = $val;
        }

        foreach ($condition as $key => $val) {
            $arr_where_key[] = "{$key} = ?";
            $arr_value[] = $val;
        }

        $cmd = 'INSERT INTO';
        $set = implode(', ', $arr_set_key);
        $where = implode(', ', $arr_where_key);

        $sql = "{$cmd} %s SET {$set} ON DUPLICATE KEY UPDATE {$where}";
        $sql = sprintf($sql, $this->getTable());

        return $this->execNoQuery($sql, $arr_value);
    }/*}}}*/

    /**
     * 禁止被克隆
     *
     * @return void
     */
    private function __clone()
    {/*{{{*/
    }/*}}}*/
}
