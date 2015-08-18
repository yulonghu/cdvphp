<?php
/**
 * 框架入门 - serverAPI
 *
 * 常用于客户端与服务端接口交互(Android、Iphone、Winform)
 *
 * Global.php 对应配置'output_format' => 'api'
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package Application\Controller
 */
class ApiController
{
    /**
     * 自定义正确输出json
     *
     * http://域名/index.php?method=api.index
     *
     * @return json
     */
    public function index()
    {/*{{{*/
    }/*}}}*/

    /**
     * 自定义正确输出json
     *
     * http://域名/index.php?method=api.data
     *
     * @return json
     */
    public function data()
    {/*{{{*/
        return array('user' => 'sanmao', 'age' => 20);
    }/*}}}*/

    /**
     * 自定义错误输出json
     *
     * http://域名/index.php?method=api.BizResult
     *
     * @return json
     */
    public function bizResult()
    {/*{{{*/
        BizResult::ensureTrue(false, 1000);
    }/*}}}*/
}
