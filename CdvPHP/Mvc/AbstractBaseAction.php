<?php
/**
 * Action 基类
 *
 * 自动实例化类包括(view、HttpRequest、HttpResponse)
 *
 * @link http://www.cdvphp.com
 * @author <fanjiapeng@126.com>
 * @package CdvPHP\Mvc
 */
abstract class AbstractBaseAction
{
    /**
     * @var Request
     */
    protected $request = null;

    /**
     * @var Response
     */
    protected $response = null;

    /**
     * @var View
     */
    protected $view = null;

    /**
     * Get request object
     * @return object
     */
    public function getRequest()
    {
        if (!$this->request)
        {
			$this->request = Loader::getInstance('HttpRequest');
        }

        return $this->request;
    }

    /**
     * Get response object; 开发中
     * @return object
	 */
    public function getResponse()
    {
        if (!$this->response)
        {
            $this->response = Loader::getInstance('HttpResponse');
        }

        return $this->response;
	}

    /**
     * Get view object
     * @return object
     */
    public function getView()
    {
        if (!$this->view)
        {
			$this->view = Loader::getInstance('view');
        }

        return $this->view;
    }
}
