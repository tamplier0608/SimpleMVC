<?php

interface ITemplate
{
    public function assign($name, $var);

    public function render($template);
}

/**
 * View class
 * @implements ITemplate
 * @author Serhii Kukunin <tamplier0608@gmail.com>
 * @since 06.11.2013
 */
class Core_View implements ITemplate
{
    /**
     * Vars
     * @access private
     */
    private $_vars = array();

    /**
     * View script name
     * @access private
     */
    private $_viewScript = '';

    /**
     * Layout script name
     * @access private
     */
    private $_layoutScript = '';

    /**
     * Config
     * @access private
     */
    private $_config = null;

    /**
     * @access private
     */
    private $_disableView = false;

    /**
     * @access private
     */
    private $_disableLayout = false;

    /**
     * Constructor of view
     * @access public
     */
    public function __construct($config = null)
    {
        if (!is_null($config)) {
            $this->setConfig($config);
        }

        return $this;
    }

    /**
     * Set config
     * @access public
     * @param type $config
     * @return $this
     */
    public function setConfig($config)
    {
        $this->_config = $config;
        return $this;
    }

    /**
     * Assign var by its name
     * @access public
     * @param string $name
     * @param mixed $var
     * @return $this
     */
    public function assign($name, $var)
    {
        $this->_vars[$name] = $var;
        return $this;
    }

    /**
     * Set view script
     * @access public
     * @param string $viewScript
     * @return $this
     */
    public function setViewScript($viewScript)
    {
        $this->_viewScript = $viewScript;
        return $this;
    }

    /**
     * Get view script
     * @access public
     * @return string
     */
    public function getViewScript()
    {
        return $this->_viewScript;
    }

    /**
     * Set layout script
     * @access public
     * @param string $layoutScript
     * @return $this
     */
    public function setLayoutScript($layoutScript)
    {
        $this->_layoutScript = $layoutScript;
        return $this;
    }

    /**
     * Get layout script
     * @access public
     * @return string
     */
    public function getLayoutScript()
    {
        return $this->_layoutScript;
    }

    /**
     * Disable view
     * @access public
     * @return $this
     */
    public function disableView()
    {
        $this->_disableView = true;
        return $this;
    }

    /**
     * Check if view is enabled
     * @access public
     * @return bool
     */
    public function viewIsEnabled()
    {
        return $this->_disableView ? false : true;
    }

    /**
     * Disable layout
     * @access public
     * @return $this
     */
    public function disableLayout()
    {
        $this->_disableLayout = true;
        return $this;
    }

    /**
     * Check if layout is enabled
     * @access public
     * @return $this
     */
    public function layoutIsEnabled()
    {
        return $this->_disableLayout ? false : true;
    }

    /**
     * Renders of view script
     * @access public
     * @param bool $echo If true view script will be rendered
     * @throws Exception
     * @return string
     */
    public function render($echo = true)
    {
        if (is_null($this->_config)) {
            throw new Exception('View object: config is not set!');
        }

        extract($this->_vars);

        if (false == $this->viewIsEnabled()) {
            return false;
        }

        if (!empty($this->_viewScript)) {

            $viewScriptPath = $this->_config->view->path . $this->_viewScript;

            if (!file_exists($viewScriptPath)) {
                throw new Exception('View object: view script not found. Path: ' . $viewScriptPath);
            }

            ob_start();
            include $viewScriptPath;
            $content = ob_get_clean();
        } else {
            throw new Exception('View object: view script is not set!');
        }

        if (true == $this->layoutIsEnabled()) {

            if (empty($this->_layoutScript)) {
                $layoutScriptPath = $this->_config->layout->path . $this->_config->layout->script;
            } else {
                $layoutScriptPath = $this->_config->layout->path . $this->_layoutScript;
            }
            if (!file_exists($layoutScriptPath)) {
                throw new Exception('View object: layout script not found. Path: ' . $layoutScriptPath);
            }

            ob_start();
            include $layoutScriptPath;
            $view = ob_get_clean();

        } else {
            $view = $content;
        }


        if ($echo) {
            echo $view;
        } else {
            return $view;
        }
    }
}
