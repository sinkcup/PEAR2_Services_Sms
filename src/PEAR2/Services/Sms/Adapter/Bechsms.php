<?php
namespace PEAR2\Services\Sms\Adapter;
/**
 * Adapter for 北程（sms.bechtech.cn）短信通道
 * 只能发大陆短信，不能发国外
 */

class Bechsms extends \PEAR2\Services\Sms\Adapter
{
    protected $conf = array(
        'apiUriPrefix' => 'http://sms.bechtech.cn/Api/',
        'accesskey'    => '',
        'secretkey'    => '',
        'sign'         => '',
    );

    public function __construct($conf=array())
    {
        parent::__construct($conf);
    }
    
    public function getRemain()
    {
        $data = array(
            'accesskey'=>$this->conf['accesskey'],
            'secretkey'=>$this->conf['secretkey'],
        );

        $http = new \HTTPRequest($this->conf['apiUriPrefix'] . 'getLeft/data/json?' . http_build_query($data), HTTP_METH_GET);
        $r = $http->send()->getBody();
        $tmp = json_decode($r, true);
        if(is_numeric($tmp['result'])) {
            return intval($tmp['result']);
        } else {
            throw new Exception($tmp['result']);
        }
    }

    public function send($mobile, $content)
    {
        //只能发大陆，不能发国外
        if(stripos($mobile, '+86') !== 0) {
            throw new Exception('only support China mainland mobile phone');
        }
        $mobile = str_replace('+86', '', $mobile);
 
        $data = array(
            'accesskey'=>$this->conf['accesskey'],
            'secretkey'=>$this->conf['secretkey'],
            'mobile'=>$mobile,
            'content'=>$content . $this->conf['sign'],
        );

        $http = new \HTTPRequest($this->conf['apiUriPrefix'] . 'send/data/json?' . http_build_query($data), HTTP_METH_GET);
        $r = $http->send()->getBody();
        $tmp = json_decode($r, true);

        if($tmp['result'] != '01') {
            throw new Exception($r);
        }
        return true;         
    }
}
?>
