<?php

class ViewTest extends PHPUnit_Framework_TestCase
{
    private $view;
    private $config;

    public function setUp()
    {
        $this->view = new Core_View();
        $this->config = new Zend_Config_Ini(realpath(__DIR__ . '/../fixtures/application.ini'));
    }

    /**
     * @covers Core_View::setConfig
     */
    public function testSetConfig()
    {
        $this->view->setConfig($this->config);
        $configValue = $this->getPrivatePropertyValue('_config', $this->view);

        $this->assertInstanceOf('Zend_Config_Ini', $configValue);
    }

    /**
     * @covers Core_View::setViewScript
     */
    public function testSetViewScript()
    {
        $view = 'view.phtml';
        $result = $this->view->setViewScript($view);
        $this->assertInstanceOf('Core_View', $result);
        var_dump($this->view);

        $viewScript = $this->getPrivatePropertyValue('_viewScript', $this->view);
        $this->assertEquals($view, $viewScript);

        return $viewScript;
    }

    /**
     * @covers Core_View::getViewScript
     * @depends testSetViewScript
     */
    public function testGetViewScript($viewScript)
    {
        var_dump($this->view);

        $script = $this->view->getViewScript();

        $this->assertEquals($viewScript, $script);

        die;
    }

    /**
     * @covers Core_View::setLayoutScript
     */
    public function testSetLayoutScript()
    {
        $layout = 'layout.phtml';
        $result = $this->view->setLayoutScript($layout);
        $this->assertInstanceOf('Core_View', $result);

        $viewScript = $this->getPrivatePropertyValue('_layoutScript', $this->view);
        $this->assertEquals($layout, $viewScript);

        return $viewScript;
    }

    /**
     * @covers Core_View::getViewScript
     * @depends testSetLayoutScript
     */
    public function testGetLayoutScript($layoutScript)
    {
        $script = $this->view->getLayoutScript();
        $this->assertEquals($layoutScript, $script);
    }


    private function getPrivatePropertyValue($name, $object)
    {
        $reflection = new ReflectionClass('Core_View');
        $property = $reflection->getProperty($name);
        $property->setAccessible(true);

        return $property->getValue($object);
    }
}