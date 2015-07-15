<?php
/**
 * 框架入门 - 自定义使用例子
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package Application\Controller
 */
class LibraryController
{
    /**
     * index
     *
     * http://域名/index.php?method=Library.index
     *
     * @return string
     */
    public function index()
    {/*{{{*/
        echo Loader::getInstance('HttpLibrary')->get();
    }/*}}}*/
}
