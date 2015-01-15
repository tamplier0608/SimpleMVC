<?php

class Core_Router
{
    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var null
     */
    protected $_config;

    /**
     * @var array
     */
    protected $_routes = array();

    /**
     * @var string
     */
    protected $_urlDelimiter = '/';

    /**
     * @var string
     */
    protected $_requestURI = '';

    /**
     * @var array
     */
    public static $defaultParams = array(
        'module' => 'default',
        'controller' => 'index',
        'action' => 'index'
    );

    /**
     * @param Request $request
     * @param null $config
     */
    public function __construct(Core_Request $request, $config = null)
    {
        $this->_request = $request;
        $this->_config = $config;
        $this->_requestURI = $_SERVER['REQUEST_URI'];

        return $this;
    }

    /**
     * @TODO рефакторинг
     * @return type
     */
    public function process()
    {
        $requestURI = explode('?', $this->_requestURI);

        $action = str_replace($this->_config->baseurl, '', $requestURI[0]);
        $action = trim($action, $this->_urlDelimiter);

        $this->_request->setParam('action', $action);

        $params = array();
        if (!empty($requestURI[1])) {
            $params = explode('&', $requestURI[1]);
        }

        for ($i = 0; $i < count($params); $i++) {
            if (isset($params[$i + 1])) {
                $this->_request->setParam($params[$i], $params[$i + 1]);
            }
        }

        return array_merge(array($action), $params);
    }

    /**
     * @param array $config
     * @return $this
     */
    public function setConfig(array $config)
    {
        $this->_config = $config;

        return $this;
    }
}