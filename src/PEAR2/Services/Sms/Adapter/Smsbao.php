<?php
namespace PEAR2\Services\Sms\Adapter;
/**
 * Adapter for 短信宝（smsbao.com）短信通道
 * 只能发大陆，不能发国外
 */

class Smsbao extends \PEAR2\Services\Sms\Adapter
{
    protected $conf = array(
        'apiUriPrefix' => 'http://smsbao.com/',
        'u' => '',
        'p' => '',
        'sign' => '',
    );

    public function __construct($conf=array())
    {
        parent::__construct($conf);
    }
    
    public function getRemain()
    {
        $data = array(
            'u' => $this->conf['u'],
            'p' => md5($this->conf['p']),
        );

        $http = new \HTTPRequest($this->conf['apiUriPrefix'] . 'query?' . http_build_query($data), HTTP_METH_GET);
        $r = $http->send()->getBody();

        $tmp = explode("\n", $r);
        if($tmp[0] != 0) {
            throw new Exception($r);
        }
        $tmp2 = explode(',', $tmp[1]);
        return intval($tmp2[1]);
    }


    public function send($mobile, $content)
    {
        //只能发大陆，不能发国外
        if(stripos($mobile, '+86') !== 0) {
            throw new Exception('only support China mainland mobile phone');
        }
        $mobile = str_replace('+86', '', $mobile);

        $data = array(
            'u' => $this->conf['u'],
            'p' => md5($this->conf['p']),
            'm' => $mobile,
            'c'=>$content . $this->conf['sign'],
        );

        $http = new \HTTPRequest($this->conf['apiUriPrefix'] . 'sms', HTTP_METH_POST);
        $http->addPostFields($data);
        $r = $http->send()->getBody();

        if($r != 0) {
            throw new Exception($r);
        }
        return true;         
    }
}
?>
