<?php
/**
 * 一个简易的关键字过滤函数封装
 *
 * 后面会慢慢扩充词库，如果词库大了，会采用其他解决方案
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package CdvPHP\Censor
 */
Class Censor
{
    /*
     * @var string $_words 屏蔽的关键词列表
     */
    private $_words = null;
    public function __construct()
    {
        $this->_words = 
            '*葛毛毛*
            *GM*
            *官方发言人*
            *客服*
            *qbj*
risiqbj
官方策划
*GM*
*gm*
*求伯军*
*雷军*
*周鸿祎*
*齐向东*
我操
我靠
我日
垃圾金山
法轮
大法
李洪智
游行
反日
带练
刷元宝
鸡巴
外挂
垃圾金山
垃圾360
我日
妈逼
六四
强奸
江泽民
胡锦涛
毛泽东
习近平
*bbsgm*
*BBSGM*
*管理员*
*论坛管理员*
*版主*
*超级版主*
*BBS*
*bbs*
*kingsoft*
歪歪
失忆的歪歪
*失忆中歪歪*
*失忆中*
*失忆*
*千金琬儿*
*千金婉儿*
千金婉儿*
千金琬儿*
*千金琬儿
*千金碗儿*
*破碗*
"客户服务"
*金亮*
*条子*
*传说中的条子*
*月亮兔*
*月亮免*
*ＧＭ*
*我日*
*鸡*
月亮兔*
*月亮兔
*3D程序工程师*
*3D程序工程师
3D程序工程师*
*剑三内测组*
栀子飘香*
*栀子飘香
*栀子飘香*
*官方*
ＧＭ*
*藏独*
*3D程序工程师*
*小*次*';
    }

    /**
     * 检查非法关键字 
     *
     * @param string $string  内容
     *
     * @return boolean  如果字符串里包含非法关键字, 返回结果true, 否则返回false
     */
    public function checkFilterWord($string)
    {/*{{{*/
        $censorexp = '/^('.str_replace(array('\\*', "\r\n", "\n", ' '), array('.*', '|', '|', ''), preg_quote(($this->_words = trim($this->_words)), '/')).')$/i';
        if($this->_words && preg_match($censorexp, $string))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }/*}}}*/
}
