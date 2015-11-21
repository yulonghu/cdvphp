<?php
/**
 * BizResult
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package CdvPHP\Http
 */
class BizResult
{
    /**
     * 如果$result != is_null, 抛出异常
     * @param mixed $result
     * @param int $code
     * @return mixed
     */
    static public function ensureNull($result, $code)
    {/*{{{*/
        if(!is_null($result))
        {
            self::output($code);
        }
        return $result;
    }/*}}}*/

    /**
     * 如果$result == is_null, 抛出异常
     * @param mixed $result
     * @param int $code
     * @return mixed
     */
    static public function ensureNotNull($result, $code)
    {/*{{{*/
        if(is_null($result))
        {
            self::output($code);
        }
        return $result;
    }/*}}}*/

    /**
     * 如果$result == empty, 抛出异常
     * @param mixed $result
     * @param int $code
     * @return mixed
     */
    static public function ensureNotEmpty($result, $code)
    {/*{{{*/
        if(empty($result))
        {
            self::output($code);
        }
        return $result;
    }/*}}}*/

    /**
     * 如果$result != empty, 抛出异常
     * @param mixed $result
     * @param int $code
     * @return mixed
     */
    static public function ensureEmpty($result, $code)
    {/*{{{*/
        if(!empty($result))
        {
            self::output($code);
        }
        return $result;
    }/*}}}*/

    /**
     * 如果$result === false, 抛出异常
     * @param mixed $result
     * @param int $code
     * @return mixed
     */
    static public function ensureNotFalse($result, $code)
    {/*{{{*/
        if($result === false)
        {
            self::output($code);
        }
        return $result;
    }/*}}}*/

    /**
     * 如果$result !== false, 抛出异常
     * @param mixed $result
     * @param int $code
     * @return mixed
     */
    static public function ensureFalse($result, $code)
    {/*{{{*/
        if($result !== false)
        {
            self::output($code);
        }
        return $result;
    }/*}}}*/

    /**
     * 如果$result !== true, 抛出异常
     * @param mixed $result
     * @param int $code
     * @return mixed
     */
    static public function ensureTrue($result, $code)
    {/*{{{*/
        if(true !== $result)
        {
            self::output($code);
        }
        return $result;
    }/*}}}*/

    /**
     * 直接抛出异常
     * @return mixed
     */
    static public function output($code = 0, $data = '')
    {/*{{{*/
        $arr_msg = array(
            'errno' => $code,
            'errmsg' => isset(Constants::$ErrorDescription[$code]) ? Constants::$ErrorDescription[$code] : '',
            'consume' => round(Loader::getInstance('Timer')->end(), 6),
            'data' => $data === null ? '' : $data
        );

        Loader::getInstance('Logger')->siteInfo($arr_msg);
        Loader::getInstance('HttpResponse')->endJson($arr_msg);
    }/*}}}*/
}
