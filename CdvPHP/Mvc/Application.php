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
        Loader::getInstance('Timer')->start();
        $self = new self();

        $self->_gpc = array(
            'p_' => & $_POST,
            'g_' => & $_GET,
            'c_' => & $_COOKIE
        );

        Superglobal::$inputs = array(
            'get' => $_GET,
            'post' => $_POST,
            'cookie' => $_COOKIE
        );

        $self->_config = ConfigLoader::getVar('system');

        return $self;
    }/*}}}*/

    /**
     * 全局变量托管、website.log日志记录、MVC调度器
     *
     * @return void
     */
    public function run()
    {/*{{{*/
        $data = $this->_dispatch();
        $this->_Output($data);

        unset($data);
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
            trigger_error("{$this->_config['app']['key']} does not exist", E_USER_ERROR);
        }

        $arr_method = explode($this->_config['app']['separator'], Superglobal::$inputs['get'][$this->_config['app']['key']]);

        if(!isset($arr_method[1]) || empty($arr_method[1]))
        {
            trigger_error("Example: ?{$this->_config['app']['key']}={$this->_config['app']['controller']}{$this->_config['app']['separator']}{$this->_config['app']['action']}", E_USER_ERROR);
        }

        Superglobal::$methods = array('class' => $arr_method[0], 'method' => $arr_method[1]);

        $arr_method[0] = ucfirst($arr_method[0]) . 'Controller';
        $arr_method[1] = $arr_method[1];

        if(!$this->_checkClassExists($arr_method[0]))
        {
            trigger_error("{$arr_method[0]} module not Found", E_USER_ERROR);
        }

        $obj_class = $this->_getObjects($arr_method[0]);
        if(!$this->_checkMethodExists($obj_class, $arr_method[1]))
        {
            trigger_error("{$arr_method[1]} module not Found", E_USER_ERROR);
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
                unset($arr_tmp);
            }

            $str_input_param = $param->getName();
            if($str_prefix && array_key_exists($str_prefix, $this->_gpc))
            {
                $str_input_param = preg_replace("/^{$str_prefix}/", '', $param->getName());
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
                trigger_error("No {$str_input_param} param", E_USER_ERROR);
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
     * _Output
     * @param mixed $data
     *
     * @return void
     */
    private function _Output(& $data = '')
    {/*{{{*/
        if(isset($this->_config['output_format']) && $this->_config['output_format'] == 'api')
        {
            BizResult::output(0, $data);
        }
        else
        {
            Loader::getInstance('Logger')->siteInfo();
        }
    }/*}}}*/
}
