<?php

class Debug extends \Zend_Debug
{
    public static function dump($var, $label = null, $echo = true)
    {
        if (APPLICATION_ENV == 'development' && \Zend_Registry::get('config')->debug) {
            if (!is_null($label)) {
                $label = '<span style="color: red">' . $label . ': </span><br />';
            }

            echo '<div style="background: black; padding: 10px;">';
            echo '<span style="color: #078A00">';
            parent::dump($var, $label, $echo);
            echo '</span>';
            echo '</div>';
        }
    }

    public static function dumpPost()
    {
        self::dump($_POST, 'Post');
    }

    public static function dumpGet()
    {
        self::dump($_GET, 'Get');
    }

    public static function dumpRequest()
    {
        self::dump($_REQUEST, 'Request');
    }
}

