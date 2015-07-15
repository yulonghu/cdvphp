<?php
/**
 * MYSQL PDO封装类
 *
 * 已经优化了连接方式及常见问题自动重连
 *
 * 使用PDO操作建议在PHP 5.3.6+
 *
 * 如果debug === true 记录sql执行全程日志
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package CdvPHP\BasePdo
 */
class BasePdo extends BasePdoCurd
{
    /**
     * 当前DB resource
     * @var resource $_pdo
     */
    private	$_pdo = null;

    /**
     * 上一次执行的sql命令
     * @var string $query
     */
    public $query = null;

    /**
     * 所有DB连接管理
     * @var array $link
     */
    protected $link = null;

    /**
     * 表名
     * @var string $table
     */
    protected $table = null; 

    /**
     * 数据库连接, $db['persistent'] 默认等于FALSE, 等于TRUE代表使用DB长连接
     *
     * @param array $db 默认在config.global.php文件里配置
     * @param boolean $reset_link 是否强制重新连接MYSQL DB, 默认FALSE
     *
     * @return void
     */
    public function link(array $db, $reset_link = FALSE)
    {/*{{{*/
        $persistent = FALSE;
        if(isset($db['persistent']))
        {
            $persistent = boolean($db['persistent']);
            unset($db['persistent']);
        }

        $key = md5(serialize($db));
        if(isset($this->link[$key]) && !$reset_link && !$this->isGoneAway())
        {
            $this->_pdo = $this->link[$key];
        }
        else
        {
            unset($this->_pdo);
            unset($this->link[$key]);

            if (!in_array('mysql', PDO::getAvailableDrivers(), TRUE))
            {
                throw new PDOException ('Cannot work without a proper database setting up');
            }

            $start_time = microtime(TRUE);
            $arr_opt = array(
                PDO::ATTR_PERSISTENT         => 0,
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_PERSISTENT		 => $persistent,
                // HOWEVER, prior to PHP 5.3.6, the charset option was ignored. If you're running an older version of PHP, you must do it like this:
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'utf8\''
            );

            // fixed bug Less than or equal php 5.3.6, for mysql prepare
            if(version_compare(PHP_VERSION, '5.3.6') <= 0)
            {
                $arr_opt[PDO::ATTR_EMULATE_PREPARES] = FALSE;
            }

            try 
            {
                if(!empty($db['port']))
                {
                    $host = $db['host'] . ';port=' . $db['port'];
                }

                try
                {
                    $arr_opt[PDO::ATTR_TIMEOUT] = 1;
                    $this->_pdo = new PDO("mysql:host={$host};dbname={$db['dbname']};charset=utf8", $db['username'], $db['password']);
                }
                catch(PDOException $e)
                {
                    if(strstr($e->getMessage(),'[2003]') || strstr($e->getMessage(),'[2002]')) // 防止内网丢包，重试解决
                    {
                        $arr_opt[PDO::ATTR_TIMEOUT] = 2;
                        $this->_pdo = new PDO("mysql:host={$host};dbname={$db['dbname']};charset=utf8", $db['username'], $db['password']);
                    }
                    else
                    {
                        throw new PDOException($e);
                    }
                }

                $this->link[$key] = $this->_pdo;
                $end_time = microtime(true);
                $this->debug('Connect Db', array(), ($end_time - $start_time));
            }
            catch(PDOException $e)
            {
                throw new PDOException($e->getMessage()." in function construct port:".$db['port']." db_name:".$db['dbname']);
            }
        }
    }/*}}}*/

    /**
     * 强制重新连接MYSQL DB
     * 
     * @param array $db
     * @return void
     */
    public function relink(array $db)
    {/*{{{*/
        $this->link($db, TRUE);
    }/*}}}*/

    /**
     * 判断MYSQL DB资源是否gone away
     *
     * @return boolean 如果返回结果等于true, 说明DB处于gone away状态或资源不存在
     */
    public function isGoneAway()
    {/*{{{*/
        if ($this->_pdo && false !== strpos($this->_pdo->getAttribute(PDO::ATTR_SERVER_INFO), 'gone away'))
        {
            return true;
        }

        return false;
    }/*}}}*/

    /**
     * 上一次执行SQL语句
     *
     * @return string <code>select * from cdvphp</code>
     */
    public function lastQuery()
    {/*{{{*/
        return $this->query;
    }/*}}}*/

    /**
     * (见官网说明)返回最后插入行的ID或序列值
     *
     * @return int
     */
    public function lastInsertId()
    {/*{{{*/
        return $this->_pdo->lastInsertId();
    }/*}}}*/

    /**
     * (见官网说明)Prepares a statement for execution and returns a statement object
     *
     * @param string $sql <code>select * from cdvphp</code>
     * @return object
     */
    public function prepare($sql)
    {/*{{{*/
        return $this->_pdo->prepare($sql);
    }/*}}}*/

    /**
     * 查询单条数据, 查询条件自动安全过滤, 不用担心被注入攻击
     *
     * 如果debug === true 记录sql执行全程日志
     *
     * 例子:
     * <code>
     * $pdo->query('select * from cdvphp', array())
     * $pdo->query('select * from cdvphp where id = ?', array(1))
     * </code>
     *
     * @param string $sql
     * @param array $values 如果没有值, 传空"" 或 array() 都可以
     *
     * @return array
     */
    public function query($sql, $values = array())
    {/*{{{*/
        $arr_result = array();

        try
        {
            $start_time = microtime(TRUE);
            $sth = $this->prepare($sql);

            if($values && is_array($values))
            {
                $result = $sth->execute(array_values($values));
            }
            else
            {
                $result = $sth->execute();
            }

            if($result)
            {
                $arr_result = $sth->fetch(PDO::FETCH_ASSOC);

                if(empty($arr_result))
                {
                    $arr_result = array();
                }
            }

            $end_time = microtime(TRUE);
            $this->debug($sql, $values, ($end_time - $start_time));
        }
        catch(PDOException $e)
        {
            throw new PDOException($e);
        }

        return $arr_result;
    }/*}}}*/

    /**
     * 查询多条数据, 查询条件自动安全过滤, 不用担心被注入攻击
     *
     * 如果debug === true 记录sql执行全程日志
     *
     * 例子:
     * <code>
     * $pdo->querys('select * from cdvphp', array())
     * $pdo->querys('select * from cdvphp where id = ?', array(1))
     * </code>
     *
     * @param string $sql
     * @param array $values 如果没有值, 传空"" 或 array() 都可以
     *
     * @return array
     */
    public function querys($sql, $values = array())
    {/*{{{*/
        $arr_result = array();

        try
        {
            $start_time = microtime(TRUE);
            $sth = $this->prepare($sql);

            if($values && is_array($values))
            {
                $result = $sth->execute(array_values($values));
            }
            else
            {
                $result = $sth->execute();
            }

            if($result)
            {
                $arr_result = $sth->fetchAll(PDO::FETCH_ASSOC);

                if(empty($arr_result))
                {
                    $arr_result = array();
                }
            }

            $end_time = microtime(TRUE);
            $this->debug($sql, $values, ($end_time - $start_time));
        }
        catch(PDOException $e)
        {
            throw new PDOException($e);
        }

        return $arr_result;
    }/*}}}*/

    /**
     * (见官网说明)执行一条 SQL 语句，并返回受影响的行数
     *
     * 推荐使用execNoQuery | execute方法
     *
     * 如果debug === true 记录sql执行全程日志
     *
     * 例子:
     * <code>$pdo->exec('select * from cdvphp')</code>
     *
     * @param string $sql
     * @return int
     */
    public function exec($sql)
    {/*{{{*/
        $result = FALSE;
        try
        {
            $start_time = microtime(TRUE);
            $result = $this->_pdo->exec($sql); 
            $end_time = microtime(TRUE);
            $this->debug($sql, $values, ($end_time - $start_time));
        }
        catch(PDOException $e)
        {
            throw new PDOException($e);
        }

        return $result;
    }/*}}}*/


    /**
     * (执行一条 SQL 语句，并返回受影响的行数) & prepare($sql)
     *
     * 如果debug === true 记录sql执行全程日志
     *
     * 例子:
     * <code>
     * $pdo->execute('select * from cdvphp', array())
     * $pdo->execute('select * from cdvphp where id = ?', array(1))
     * </code>
     *
     * @param string $sql
     * @return int
     */
    public function execute($sql, $values = array())
    {/*{{{*/
        $int_rowcount = 0;
        try
        {
            $start_time = microtime(TRUE);
            $sth = $this->prepare($sql);

            if($values && is_array($values))
            {
                $result = $sth->execute(array_values($values));
            }
            else
            {
                $result = $sth->execute();
            }

            $int_rowcount = $sth->rowCount();
            $end_time = microtime(TRUE);
            $this->debug($sql, $values, ($end_time - $start_time));
        }
        catch(PDOException $e)
        {
            throw new PDOException($e);
        }

        return $int_rowcount;
    }/*}}}*/

    /**
     * (执行一条 SQL 语句) & prepare($sql) 
     *
     * 非常适合DB的 update、insert、replace、delete操作 
     *
     * 如果debug === true 记录sql执行全程日志
     *
     * 例子:
     * <code>
     * $pdo->execNotQuery('select * from cdvphp', array())
     * $pdo->execNotQuery('select * from cdvphp where id = ?', array(1))
     * </code>
     *
     * @param string $sql
     * @return boolean true代表成功, 否则失败
     */
    public function execNotQuery($sql, $values = array())
    {/*{{{*/
        $bool_result = FALSE;

        try
        {
            $start_time = microtime(TRUE);
            $sth = $this->prepare($sql);

            if($values && is_array($values))
            {
                $bool_result = $sth->execute(array_values($values));
            }
            else
            {
                $bool_result = $sth->execute();
            }

            $end_time = microtime(TRUE);
            $this->debug($sql, $values, ($end_time - $start_time));
        }
        catch(PDOException $e)
        {
            throw new PDOException($e);
        }

        return $bool_result;
    }/*}}}*/

    /**
     * (见官网说明)Quotes a string for use in a query
     *
     * @param string $string
     * @return string
     */
    public function quote($string)
    {/*{{{*/
        return $this->_pdo->quote($string);
    }/*}}}*/

    /**
     * (见官网说明)启动一个事务
     *
     * @return boolean
     */
    public function beginTransaction()
    {/*{{{*/
        return $this->_pdo->beginTransaction();
    }/*}}}*/

    /**
     * (见官网说明)提交一个事务
     *
     * @return boolean
     */
    public function commit()
    {/*{{{*/
        return $this->_pdo->commit();
    }/*}}}*/

    /**
     * (见官网说明)回滚一个事务
     *
     * @return boolean
     */
    public function rollBack()
    {/*{{{*/
        return $this->_pdo->rollBack();
    }/*}}}*/

    /**
     * (见官网说明)获取跟数据库句柄上一次操作相关的 SQLSTATE
     *
     * @return mixed
     */
    public function errorCode()
    {/*{{{*/
        return $this->_pdo->errorCode();
    }/*}}}*/

    /**
     * (见官网说明)Fetch extended error information associated with the last operation on the database handle
     *
     * @return array
     */
    public function errorInfo()
    {/*{{{*/
        return $this->_pdo->errorInfo();
    }/*}}}*/

    /**
     * SQL语句调试.
     * debug===true时, 默认记录在sql.log
     *
     * @param string $statement <code>select * from cdvphp where id = ?</code>
     * @param array $params <code>参数的值 array(1)</code>
     * @param float $execute_time 当前sql执行时间
     *
     * @return void
     */
    public function debug($statement, $params = array(), $execute_time = 0)
    {/*{{{*/
        $config = ConfigLoader::getVar('system');
        if(isset($config['log']['sql']) && $config['log']['sql'])
        {
            $statement = preg_replace_callback(
                '/[?]/',
                function ($k) use ($params) {
                    static $i = 0;
                    return sprintf("'%s'", $params[$i++]);
                },
                    $statement
                );

            $this->query = $statement;
            Loader::getInstance('Logger')->sqlInfo($statement, $execute_time);
        }
    }/*}}}*/

    /**
     * 获取当前的表名, 表名一般在model里配置
     *
     * @return string
     */
    public function getTable()
    {/*{{{*/
        return $this->table;
    }/*}}}*/

    /**
     * 禁止克隆
     *
     * @return void
     */
    private function __clone(){}

        /**
         * 魔术方法 __call
         *
         * @param string $name
         * @param array $args
         *
         * return mixed
         */
        public function __call($name, $args)
        {/*{{{*/
            $callback = array($this->_pdo, $name);
            return call_user_func_array($callback , $args);
        }/*}}}*/

    /**
     * 脚步执行完, 自动释放db资源
     *
     * @return void
     */
    public function __destruct()
    {/*{{{*/
        if(isset($this->_pdo) && is_resource($this->_pdo))
        {
            $this->_pdo = null;
        }
    }/*}}}*/
}
