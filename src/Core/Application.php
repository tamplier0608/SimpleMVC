<?php

/**
 * Application - FrontController
 * @package Core
 * @author Serhii Kukunin <tamplier0608@gmail.com>
 * @since 12.11.2013
 *
 * @TODO boostrap()
 */
class Core_Application
{

    /**
     * Config
     * @access protected
     * @var
     */
    protected $_config;

    /**
     * Environment
     * @access protected
     * @var
     */
    protected $_env;

    /**
     * Request
     * @access protected
     * @var
     */
    protected $_request = null;
    protected $_action = null;

    /**
     * Constructor
     * @access public
     * @param Zend_Config $config
     * @param string $env Application environment
     * @return $this
     */
    public function __construct($config, $env)
    {
        $this->setConfig($config);
        $this->setEnv($env);

        if (!isset($config->phpSettings->display_startup_errors)) {
            ini_set('display_startup_errors', $config->phpSettings->display_startup_errors);
        }

        if (isset($config->phpSettings->display_errors)) {
            ini_set('display_errors', $config->phpSettings->display_errors);
        }

        /**
         * init registry
         */
        Zend_Registry::set('config', $config);

        /**
         * init session
         */
        $session = new Zend_Session_Namespace( 'Application', true );
        Zend_Registry::set( 'session', $session );

        /**
         * init database adapter
         */
        if (isset($config->database)) {
            $dbAdapter = Zend_Db::factory($config->database);
            Zend_Registry::set('db', $dbAdapter);
            Zend_Db_Table::setDefaultAdapter('db');
        }

        return $this;
    }

    /**
     * Set config
     * @access public
     * @param Zend_Config $config
     * @return $this;
     */
    public function setConfig($config)
    {
        $this->_config = $config;
        return $this;
    }

    /**
     * Get config
     * @access public
     * @return Zend_Config $config
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * Set environment
     * @access public
     * @param string $env
     * @return $this;
     */
    public function setEnv($env)
    {
        $this->_env = $env;
        return $this;
    }

    /**
     * Get environment
     * @access public
     * @return string $env
     */
    public function getEnv()
    {
        return $this->_env;
    }

    /**
     * Get request object if created or create it
     * @access public
     * @return Core_Request
     */
    public function getRequest()
    {
        if (is_null($this->_request)) {
            $this->_request = new Core_Request();
        }
        return $this->_request;
    }

    /**
     *
     */
    public function getAction()
    {

    }

    /**
     * Create action object by action name
     * @access protected
     * @param string $actionName
     * @return Core_ActionAbstract
     */
    protected function _createAction($actionName)
    {
        $action = ucfirst($actionName) . 'Action';
        $actionFile = ucfirst($actionName) . '.php';

        $pageSegments = explode('/', $actionName);

        if (count($pageSegments) > 1) {
            $pageSegments[count($pageSegments) - 1] = ucfirst($pageSegments[count($pageSegments) - 1]);
            $actionFile = join('/', $pageSegments) . '.php';

            $pageSegments = array_map('ucfirst', $pageSegments);
            $action = join('_', $pageSegments) . 'Action';
        }

        $actionPath = realpath($this->getConfig()->action->path . $actionFile);

        if (!file_exists($actionPath)) {
            throw new Exception('Action file "' . $actionFile . '" does not exist!');
        }
        require_once $actionPath;

        return new $action();
    }

    /**
     * Prepare application start
     * @access public
     * @depends Core_Bootstrap
     * @return $this;
     */
    public function bootstrap()
    {
        return $this;
    }

    /**
     * Start the application
     * @access public
     */
    public function start()
    {
        $request = $this->getRequest();
        $router = new Core_Router($request, $this->getConfig());
        $router->process();

        // get name of requested action
        $action = $request->getParam('action', 'index');
        $actionObject = $this->_createAction($action);
        $actionObject->setOption('config', $this->getConfig());
        $actionObject->getView()->setViewScript($action . '.phtml');

        // init action
        $actionObject->init();

        // proceed action
        $actionObject->proceed();

        // render view script and layout
        $actionObject->renderView();

    }

}
