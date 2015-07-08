<?php
/**
 * MVC入口
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package CdvPHP\Mvc
 */
class Application
{
	/** @var array $_service 已加载的类 */
	private $_service = null;

	/** @var array $_gpc 集合GET、POST、COOKIE全局变量的值 */
	private $_gpc   = null;

	/** @var array $_config 读取配置项的值 */
	private $_config = null;
	
	/**
	 * 实例化
	 *
	 * @return object
	 */
	public static function init()
	{/*{{{*/
		return new self();
	}/*}}}*/

	/**
	 * 全局变量托管、website.log日志记录、MVC调度器
	 *
	 * @return void
	 */
	public function run()
	{/*{{{*/
		$this->_gpc = array(
			'p_' => & $_POST,
			'g_' => & $_GET,
			'c_' => & $_COOKIE
		);

		Superglobal::$inputs = array(
			'get' => $_GET,
			'post' => $_POST,
			'cookie' => $_COOKIE
		);

		$this->_config = ConfigLoader::getVar('system');

		$this->_dispatch();
		$this->_log();
	}/*}}}*/

	/**
	 * _dispatch
	 *
	 * @return boolean
	 */
	private function _dispatch()
	{/*{{{*/
		$arr_method = array();

		if(!isset($this->_config['app']['key']) || !isset(Superglobal::$inputs['get'][$this->_config['app']['key']]))
		{
			Superglobal::$inputs['get'][$this->_config['app']['key']] = "{$this->_config['app']['controller']}{$this->_config['app']['separator']}{$this->_config['app']['action']}";
		}

		if(strpos(Superglobal::$inputs['get'][$this->_config['app']['key']], $this->_config['app']['separator']) === FALSE)
		{
			throw new RuntimeException("{$this->_config['app']['key']} does not exist");
		}

		$arr_method = explode($this->_config['app']['separator'], Superglobal::$inputs['get'][$this->_config['app']['key']]);

		if(!isset($arr_method[1]) || empty($arr_method[1]))
		{
			throw new RuntimeException("Example: ?{$this->_config['app']['key']}={$this->_config['app']['controller']}{$this->_config['app']['separator']}{$this->_config['app']['action']}");
		}

		Superglobal::$methods = array('class' => $arr_method[0], 'method' => $arr_method[1]);

		$arr_method[0] = ucfirst($arr_method[0]) . 'Controller';
		$arr_method[1] = $arr_method[1];

		if(!$this->_checkClassExists($arr_method[0]))
		{
			throw new RuntimeException("{$arr_method[0]} module not Found");
		}

		$obj_class = $this->_getObjects($arr_method[0]);
		if(!$this->_checkMethodExists($obj_class, $arr_method[1]))
		{
			throw new RuntimeException("{$arr_method[1]} method not Found");
		}

		return $this->_setMapParams($arr_method[0], $arr_method[1]);
	}/*}}}*/

	/**
	 * _checkClassExists
	 *
	 * @param string $str_class_name
	 * @return boolean
	 */
	private function _checkClassExists($str_class_name)
	{/*{{{*/
		return class_exists($str_class_name);
	}/*}}}*/

	/**
	 * _checkMethodExists
	 *
	 * @param string $str_class_name
	 * @param string $str_method_name
	 * @return boolean
	 */
	private function _checkMethodExists($str_class_name, $str_method_name)
	{/*{{{*/
		return method_exists($str_class_name, $str_method_name);
	}/*}}}*/

	/**
	 * _getObjects
	 *
	 * @param string $str_class_name
	 * @return boolean
	 */
	private function _getObjects($str_class_name)
	{/*{{{*/
		if(!isset($this->_service['object'][$str_class_name]))
		{
			$this->_service['object'][$str_class_name] = Loader::getInstance($str_class_name);
		}
		return is_null($this->_service['object'][$str_class_name]) ? FALSE : $this->_service['object'][$str_class_name]; 
	}/*}}}*/

	/**
	 * _setMapParams
	 *
	 * @param string $class
	 * @param string $name
	 * @return boolean
	 */
	private function _setMapParams($class, $name)
	{/*{{{*/
		$obj_reflection  	= new ReflectionMethod($class, $name);
		$arr_args  		 	= array();

		foreach($obj_reflection->getParameters() as $param)
		{
			$str_prefix		 	= $str_input_param = $str_input_value = '';
			$bool_defalut_value = FALSE;

			if(strpos($param->getName(), '_') !== FALSE)
			{
				$arr_tmp = explode('_', $param->getName());	
				$str_prefix = $arr_tmp[0] . '_';
			}

			$str_input_param = preg_replace("/^{$str_prefix}/", '', $param->getName());
			if($str_prefix && array_key_exists($str_prefix, $this->_gpc))
			{
				$str_input_value = isset($this->_gpc[$str_prefix][$str_input_param]) ? $this->_gpc[$str_prefix][$str_input_param] : FALSE;
			}
			else
			{
				$str_input_value = isset($this->_gpc['g_'][$str_input_param]) ? $this->_gpc['g_'][$str_input_param] : FALSE;
			}

			if($str_input_value === FALSE)
			{
				$bool_defalut_value = $param->isDefaultValueAvailable();
			}

			if($bool_defalut_value === FALSE && $str_input_value === FALSE)
			{
				throw new RuntimeException("No {$str_input_param} param");
			}

			if($str_input_value !== FALSE)
			{
				if(is_array($str_input_value))
				{
					$arr_args[$str_input_param] = $str_input_value;
				}
				else
				{
					$arr_args[$str_input_param] = trim($str_input_value);
				}
			} else {
				$arr_args[$str_input_param] = $param->getDefaultValue();
			}
		}

		return $obj_reflection->invokeArgs($this->_service['object'][$class], $arr_args); 
	}/*}}}*/

	/**
	 * _log
	 *
	 * @return boolean
	 */
	private function _log()
	{/*{{{*/
		if(isset($this->_config['log']['website']) && $this->_config['log']['website'])
		{
			Loader::getInstance('Logger')->siteInfo();
		}
	}/*}}}*/
}
