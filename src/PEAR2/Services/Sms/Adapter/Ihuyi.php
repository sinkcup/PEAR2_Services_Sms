<?php
namespace PEAR2\Services\Sms\Adapter;
/**
 * Adapter for 互亿无线（www.ihuyi.com）短信通道
 * 只能发大陆短信，不能发国外
 * 短信验证码接口/订单通知短信专用接口
 * 互亿无线短信验证码接口/订单通知短信专用接口，具有全国全网发送、3-5秒内响应、100%到达、通道稳定免维护等优势专为网站短信验证码、网站订单通知等互动应用开设。
 */

class Ihuyi extends \PEAR2\Services\Sms\Adapter
{
    protected $conf = array(
        'apiUriPrefix' => 'http://106.ihuyi.com/webservice/sms.php',
        'account'        => '',
        'password'         => '',
        'sign'         => '',
    );

    public function __construct($conf=array())
    {
        parent::__construct($conf);
    }
    
    public function getRemain()
    {
        $data = array(
            'account'  => $this->conf['account'],
            'password' => md5($this->conf['password']),
        );

        $http = new \HTTPRequest($this->conf['apiUriPrefix'] . '/?method=GetNum&' . http_build_query($data), HTTP_METH_GET);
        $r = $http->send()->getBody();

        $tmp = $this->filter($r);
        if($tmp['code'] != 2) {
            throw new Exception($r);
        }
        return intval($tmp['num']);
    }

    public function send($mobile, $content)
    {
        //只能发大陆，不能发国外
        if(stripos($mobile, '+86') !== 0) {
            throw new Exception('only support China mainland mobile phone');
        }
        $mobile = str_replace('+86', '', $mobile);
 
        $data = array(
            'account'  => $this->conf['account'],
            'password' => md5($this->conf['password']),
            'mobile'   => $mobile,
            'content'  => $content . $this->conf['sign']
        );

        $http = new \HTTPRequest($this->conf['apiUriPrefix'] . '?method=Submit', HTTP_METH_POST);
        $http->addPostFields($data);
        $r = $http->send()->getBody();

        $tmp = $this->filter($r);
        if($tmp['code'] != 2) {
            throw new Exception($r);
        }
        return true;
    }

    private function filter($str)
    {
        if (preg_match('/<\?xml\s+.+$/is', $str, $matches)) {
            $xml = simplexml_load_string($matches[0]);
            return get_object_vars($xml);
        }
        throw new Exception($str);
    }
}
?>
