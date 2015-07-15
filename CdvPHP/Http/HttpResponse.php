<?php
/**
 * HTTP Response
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package CdvPHP\Http
 */
class HttpResponse
{
    /**
     * @var string
     */
    public static $_content = '';

    /**
     * 设置响应的Body
     *
     * <code>
     * $this->getResponse()->setBody("Hello World");
     * $this->getResponse()->response();
     *
     * # 输出结果
     * Hello World
     * </code>
     *
     * @param string $body 响应的内容
     * @return bool 成功返回 Response实例化对象, 失败返回FALSE
     */
    public function setBody($body)
    {/*{{{*/
        self::$_content = $body;
        return $this; 
    }/*}}}*/

    /**
     * 往已有的响应的Body后附加新的内容
     *
     * <code>
     * $this->getResponse()->setBody("Hello World")->appendBody('2015');
     * $this->getResponse()->response();
     *
     * # 输出结果
     * 2015 Hello World
     * </code>
     *
     * @param string $body 响应的内容
     * @return bool 成功返回 Response实例化对象, 失败返回FALSE
     */
    public function appendBody($body)
    {/*{{{*/
        self::$_content = $body . self::$_content;
        return $this; 
    }/*}}}*/

    /**
     * 往已有的响应的Body前插入新的内容
     *
     * <code>
     * $this->getResponse()->setBody("Hello World")->prependBody('2015');
     * $this->getResponse()->response();
     *
     * # 输出结果
     * Hello World 2015
     * </code>
     *
     * @param string $body 响应的内容
     * @return bool 成功返回 Response实例化对象, 失败返回FALSE
     */
    public function prependBody($body)
    {/*{{{*/
        self::$_content = self::$_content . $body;
        return $this; 
    }/*}}}*/

    /**
     * 获取已经设置的响应body内容
     *
     * <code>
     * echo $this->getResponse()->getBody(); or $this->getResponse()->response();
     *
     * # 输出结果
     * Hello World 2015
     * </code>
     *
     * @return string 
     */
    public function getBody()
    {/*{{{*/
        return self::$_content;
    }/*}}}*/

    /**
     * 清除已经设置的响应body内容
     *
     * <code>
     * echo $this->getResponse()->clearBody();
     * </code>
     *
     * @return bool true成功 
     */
    public function clearBody()
    {/*{{{*/
        self::$_content = '';
        return TRUE;
    }/*}}}*/

    /**
     * 发送响应给请求端
     *
     * <code>
     * $this->getResponse()->end();
     * </code>
     *
     * @return bool true成功 
     */
    public function end()
    {/*{{{*/
        echo self::$_content;
        exit(0);
    }/*}}}*/

    /**
     * 发送响应给请求端; 301跳转
     *
     * <code>
     * $this->getResponse()->setRedirect('http://www.test.cn');
     * </code>
     *
     * @return void
     */
    public function setRedirect($url)
    {/*{{{*/
        if(!empty($url))
        {
            header('HTTP/1.1 301 Moved Permanently');
            header('Location: '. $url);
        }
        exit(0);
    }/*}}}*/
}
