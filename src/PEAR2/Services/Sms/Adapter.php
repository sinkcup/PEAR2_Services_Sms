<?php
namespace PEAR2\Services\Sms;
/**
 * Base class for adapters
 *
 * PHP version 5
 */

abstract class Adapter
{
    protected $conf = array();

    protected function __construct($conf=array())
    {
        $this->conf = array_merge($this->conf, $conf);
    }

    protected function getRemain() {}

    protected function send($mobile, $content) {}
}
?>
