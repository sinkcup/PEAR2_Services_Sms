<?php
namespace PEAR2\Services\Sms\Adapter;
/**
 * Adapter for 移通网络（etonenet.com）短信通道
 */

class Etonenet extends \PEAR2\Services\Sms\Adapter
{
    protected $conf = array(
        'spid' => '',
        'sppassword' => '',
        'apiUriPrefix' => 'http://esms.etonenet.com/',
    );

    public function __construct($conf=array())
    {
        parent::__construct($conf);
    }
    
    public function getRemain()
    {
        //todo 没找到接口
        throw new Exception('there is no api');
    }

    /**
     * @param string $mobile 手机号，需要完整的国家区号，比如+8613800138000
     *
     $ $param string $content 内容
     */
    public function send($mobile, $content)
    {
        //移通要求 国家码的加号要去掉
        $mobile = str_replace('+', '', $mobile);

        //移通 会在短信里自动加上签名【xxx】，所以不用自己加了。
        $data = array(
            'command' => 'MT_REQUEST',
            'spid' => $this->conf['spid'],
            'sppassword' => $this->conf['sppassword'],
            'da' => $mobile,
            'dc' => '8', //UTF-16BE
            'sm' => bin2hex(mb_convert_encoding($content, 'UTF-16BE', 'UTF-8')),
        );

        $http = new \HTTPRequest($this->conf['apiUriPrefix'] . 'sms/mt', HTTP_METH_POST);
        $http->addPostFields($data);
        $http->send();
        $code = $http->getResponseCode();
        $body = $http->getResponseBody();
        if($code != 200) {
            throw new Exception($body);
        }

        parse_str($body, $tmp);
        if($tmp['mterrcode'] != '000') {
            throw new Exception($body);
        }
        return true;         
    }
}
?>
