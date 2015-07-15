<?php
/**
 * 框架入门 - 欢迎页面
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package Application\Controller
 */
class IndexController
{
    /**
     * welcome to index.index
     * @return string
     */
    public function index()
    {/*{{{*/
        header('Content-type:text/html; charset=utf-8');

        echo '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;font-size:24px} h1{ font-size: 70px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.0em; font-size: 36px;margin-left: 6px; } a,a:hover,{color:blue;}</style><div style="padding: 24px 48px;"> <h1>CdvPHP 4.0</h1><p> 欢迎您使用规范的敏捷型PHP开源框架！</p> <p style="margin-top:30px"><a href="?method=Book.index">进入留言本例子</a></p>';
    }/*}}}*/
}
