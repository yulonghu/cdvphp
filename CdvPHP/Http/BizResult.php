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
            self::throwError($code);
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
            self::throwError($code);
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
            self::throwError($code);
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
            self::throwError($code);
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
            self::throwError($code);
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
            self::throwError($code);
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
            self::throwError($code);
        }
        return $result;
    }/*}}}*/

    /**
     * 直接抛出异常
     * @return mixed
     */
    static public function throwError($code)
    {/*{{{*/
        Loader::getInstance('HttpResponse')->endJson(array(
            'errno' => $code,
            'errmsg' => isset(Constants::$ErrorDescription[$code]) ? Constants::$ErrorDescription[$code] : '',
            'consume' => round(Loader::getInstance('Timer')->end(), 6),
            'data' => ''
        ));
    }/*}}}*/
}
