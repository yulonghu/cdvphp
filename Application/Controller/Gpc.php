<?php
/**
 * 框架入门 - 自动获取$_GET、$_POST 参数例子
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package Application\Controller
 */
class GpcController
{
    /**
     * 获取GET参数的值, 如果$_GET参数password不存在, 默认值为 666888
     *
     * http://域名/index.php?method=Gpc.world&username=cdvphp&password=123456
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
     * 获取$_POST参数的值
     *
     * http://域名/index.php?method=Gpc.check
     *
     * POST: username=cdvphp&password=123456
     *
     * @param mixed $p_username 获取$_POST的值
     * @param mixed $password   获取$_GET的值
     *
     * @return mixed
     */
    public function check($p_username, $password)
    {/*{{{*/
        var_dump($p_username);
        var_dump($password);
    }/*}}}*/

    /**
     * 获取超全局数据($_GET、$_POST、$_COOKIE)的值
     *
     * http://域名/index.php?method=Gpc.index
     *
     * @return mixed
     */
    public function index()
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

    /**
     * 测试一个带下划线的GET参数
     *
     * http://域名/?method=Gpc.getList&pic_user=ddd&pass=123456
     *
     * @param string $pic_user
     * @return string
     */
    public function getList($pic_user, $pass)
    {/*{{{*/
        var_dump($pic_user, $pass);
    }/*}}}*/

    /**
     * 测试一个带下划线的POST参数
     *
     * curl 'http://127.0.0.1/?method=Gpc.setInfo' -d 'pic_user=ddd&pass=123456' -H 'host: 域名'
     *
     * @param string $pic_user
     * @return string
     */
    public function setInfo($p_pic_user, $p_pass)
    {/*{{{*/
        var_dump($p_pic_user, $p_pass);
    }/*}}}*/

    /**
     * 自动获取$_COOKIE
     *
     * @param string $name 相当于 $_COOKIE['name']
     * @return string
     */
    public function getCookie($c_name)
    {/*{{{*/
        var_dump($c_name);
    }/*}}}*/
}
