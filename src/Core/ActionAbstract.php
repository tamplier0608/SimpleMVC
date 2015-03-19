<?php

/**
 * Base action class
 * @depends Core_Request
 * @author Serhii Kukunin <tamplier0608@gmail.com>
 * @since 06.11.2013
 */
abstract class Core_ActionAbstract
{
    /**
     * Action options
     * @property array
     * @access private
     */
    private $_options = array(
        'viewScript' => '',
        'layoutScript' => '',
        'config' => '',
        'request' => null
    );

    /**
     * Object of view
     * @property View
     * @access private
     */
    private $_viewObject = null;

    /**
     * Constuctor of action
     * @access public
     * @param array $options
     * @return \Core_ActionAbstract
     */
    public function __construct($options = array())
    {
        $this->_options = array_merge($this->_options, $options);
        return $this;
    }

    /**
     * Init action
     * @todo may to implement in children class
     */
    public function init() {}

    /**
     * Action body
     * @access public
     * @todo Need to implement in children class
     */
    abstract public function process();

    /**
     * Get option by the key
     * @access public
     * @param string $key Name of option
     * @return string|null If option exists return its string value or null if not exists
     */
    public function getOption($key)
    {
        if (isset($this->_options[$key])) {
            return $this->_options[$key];
        }
        return null;
    }

    /**
     * Set option value
     * @access public
     * @param string $key Name of option
     * @param mixed $value
     * @return \Core_ActionAbstract
     */
    public function setOption($key, $value)
    {
        $this->_options[$key] = $value;
        return $this;
    }

    /**
     * Get view object
     * @access public
     * @return View Object of view
     */
    public function getView()
    {
        if (is_null($this->_viewObject)) {
            $view = new Core_View($this->getOption('config'));
            $view->setViewScript($this->getOption('viewScript'))
                ->setLayoutScript($this->getOption('layoutScript'));
            $this->_viewObject = $view;
        }
        return $this->_viewObject;
    }

    /**
     * Get request object
     * @access public
     * @return Core_Request $request
     */
    public function getRequest()
    {
        $request = $this->getOption('request');
        if (is_null($request)) {
            $request = new Core_Request();
            $this->setOption('request', $request);
        }

        return $request;
    }

    /**
     * Render view
     * @access public
     * @throws Exception
     * @return \Core_ActionAbstract
     */
    public function renderView()
    {
        $viewScript = $this->getOption('viewScript');
        if (!isset($viewScript)) {
            throw new Exception('Wrong view script!');
        }

        $view = $this->getView();
        $view->render();

        return $this;
    }
}