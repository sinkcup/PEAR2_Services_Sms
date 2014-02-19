<?php
namespace PEAR2\Services;
/**
 * 发短信
 *
 * @category Services
 * @package  PEAR2_Services_Sms
 * @author   sinkcup <sinkcup@163.com>
 */

class Sms
{
    protected $adapter;

    public function __construct($gateway, array $conf=array())
    {
        $className = '\\PEAR2\Services\\Sms\\Adapter\\' . ucfirst(strtolower($gateway));
        $this->adapter = new $className($conf);
    }

    public function send($mobile, $content)
    {
        return $this->adapter->send($mobile, $content);
    }
    
    public function getRemain()
    {
        return $this->adapter->getRemain();
    }
}
?>
