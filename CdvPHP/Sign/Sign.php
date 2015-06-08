<?php
/**
 * 签名函数
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package CdvPHP\Sign
 */
class Sign
{

	/** @var string $_secret_key 秘钥 */
	private $_secret_key = '';

	/**
	 * 自定义秘钥
	 *
	 * @param string $key
	 * @return void
	 */
	public function setKey($key = '')
	{/*{{{*/
		if($key)
		{
			$this->_secret_key = $key;
		}
	}/*}}}*/

	/**
	 * 签名生成 
	 *
	 * @param array $intputs 数据源必须是数组
	 * @return string 32位md5串
	 */
	public function init($inputs)
	{/*{{{*/
		$str_sign = '';

		if (!empty($inputs['get']))
		{
			$str_sign .= $this->_signArray($inputs['get']);
		}

		if (!empty($inputs['post']))
		{
			$str_sign .= $this->_signArray($inputs['post']);
		}

		if (!empty($inputs['cookie']))
		{
			$str_sign .= $this->_signArray($inputs['cookie']);
		}

		return md5($str_sign . $this->_secret_key);
	}/*}}}*/

	/**
	 * 数据的排序
	 *
	 * @param array $data
	 * @return string
	 */
	private function _signArray(array $data)
	{/*{{{*/
		if (!is_array($data))
		{
			return '';
		}

		sort($data, SORT_STRING);
		$str_sign = '';

		foreach ($data as $key => $val)
		{
			$str_sign .= "{$key}={$val}";
		}

		return md5($str_sign);
	}/*}}}*/
}
