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
     * @var request
     */
    protected $request = null;

    /**
     * @var response
     */
    protected $response = null;

    /**
     * @var view
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
     * Get response object
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
