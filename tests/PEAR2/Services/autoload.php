<?php
function autoload($className)
{
    $thisDir = dirname(__FILE__) . '/';
    if(strpos($className, '\\') !== false) {
        if(strpos($className, 'PEAR2\\Services\\Sms') === 0) {
            require_once($thisDir . '../../../src/' . str_replace('\\', '/', $className) . '.php');
        } else {
            require_once(str_replace('\\', '/', $className) . '.php');
        }
        return true;
    }
}
spl_autoload_register('autoload');
?>
