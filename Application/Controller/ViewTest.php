<?php
/**
 * 框架入门 - 模版操作的例子 (View层)
 *
 * 注: API接口, 一般没有View层
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package Application\Controller
 */
class ViewTestController extends AbstractBaseAction
{
    /**
     * 模版测试
     *
     * http://域名/index.php?method=ViewTest.index
     *
     * @return void
     */
    public function index()
    {/*{{{*/
        // assign 模版内变量赋值
        $this->getView()->assign('a', 1);
        $this->getView()->assign('data', array('user' => 'zhangsan', 'pass' => 123456));
        $this->getView()->assign('name', 'xiaofan');

        // 读取模版
        $this->getView()->display();
    }/*}}}*/

    /**
     * 自定义模版输出测试
     *
     * 模板模板加载的路径, 来自于Global.php配置文件 template\path 配置项
     *
     * http://域名/index.php?method=ViewTest.customTpl
     *
     * @return void
     */
    public function customTpl()
    {/*{{{*/
        // 带目录的模板加载
        $this->getView()->display('Tmp/custom');
        // 不带目录的模板加载; 需要新建 View/Templates/custom.html 文件
        // $this->getView()->display('custom');
    }/*}}}*/
}
