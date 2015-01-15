<?php

/**
 * Class Request
 * @package Core
 */
class Core_Request
{
    /**
     *
     */
    const REQUEST_METHOD_POST = 'POST';
    /**
     *
     */
    const REQUEST_METHOD_GET = 'GET';
    /**
     *
     */
    const REQUEST_METHOD_PUT = 'PUT';
    /**
     *
     */
    const REQUEST_METHOD_DELETE = 'DELETE';
    /**
     *
     */
    const GLOBAL_REQUEST = 'REQUEST';
    /**
     *
     */
    const REQUEST_XMLHTTPREQUEST = 'XMLHttpRequest';


    /**
     *
     */
    public function __construct()
    {
        switch ($this->getMethod()) {
            case self::REQUEST_METHOD_PUT:
            case self::REQUEST_METHOD_DELETE:
                $data = json_decode(file_get_contents('php://input'), true);
                $this->setParams($data);
                break;
        }
    }

    /**
     * @param $name
     * @param null $default
     * @return null
     */
    public function getParam($name, $default = null)
    {
        if (isset($_REQUEST[$name]) && $_REQUEST[$name] !== '') {
            return $_REQUEST[$name];
        } else {
            return $default;
        }
    }

    /**
     * @param $name
     * @param $value
     * @param string $method
     * @return $this
     */
    public function setParam($name, $value, $method = self::REQUEST_METHOD_GET)
    {
        switch ($method) {
            case self::REQUEST_METHOD_GET:
                $_GET[$name] = $value;
                break;
            case self::REQUEST_METHOD_POST:
                $_POST[$name] = $value;
                break;
        }

        $_REQUEST[$name] = $value;

        return $this;
    }

    /**
     * @param array $params
     * @return $this|bool
     */
    public function setParams($params = array())
    {
        if (!is_array($params)) {
            return false;
        }
        foreach ($params as $key => $val) {
            $this->setParam($key, $val);
        }
        return $this;
    }

    /**
     * @param string $method
     * @return mixed
     */
    public function getParams($method = self::GLOBAL_REQUEST)
    {
        switch ($method) {
            case self::REQUEST_METHOD_GET:
                $return = $_GET;
                break;
            case self::REQUEST_METHOD_POST:
                $return = $_POST;
                break;
            case self::GLOBAL_REQUEST:
                $return = $_REQUEST;
                break;
        }
        return $return;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return mixed
     */
    public function getPost()
    {
        return $_POST;
    }

    /**
     * @return bool
     */
    public function isPost()
    {
        if ($_SERVER['REQUEST_METHOD'] == self::REQUEST_METHOD_POST) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isPut()
    {
        if ($_SERVER['REQUEST_METHOD'] == self::REQUEST_METHOD_PUT) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isDelete()
    {
        if ($_SERVER['REQUEST_METHOD'] == self::REQUEST_METHOD_DELETE) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isXmlHttpRequest()
    {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            $_SERVER['HTTP_X_REQUESTED_WITH'] == self::REQUEST_XMLHTTPREQUEST
        ) {
            return true;
        }
        return false;
    }

    /**
     * @return null
     */
    public function getModuleName()
    {
        return $this->getParam('module', Core_Router::$defaultParams['module']);
    }

    /**
     * @return null
     */
    public function getControllerName()
    {
        return $this->getParam('controller', Core_Router::$defaultParams['controller']);
    }

    /**
     * @return null
     */
    public function getActionName()
    {
        return $this->getParam('action', Core_Router::$defaultParams['action']);
    }
}