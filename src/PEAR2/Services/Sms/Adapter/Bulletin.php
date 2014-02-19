<?php
namespace PEAR2\Services\Sms\Adapter;
/**
 * Adapter for bulletin.com 短信通道
 * 支持多国短信
 */

class Bulletin extends \PEAR2\Services\Sms\Adapter
{
    protected $conf = array(
        'apiUriPrefix' => 'https://www.bulletinmessenger.net/api/',
        'userId'  => '',
        'password'     => '',
        'sign'         => '',
    );

    public function __construct($conf=array())
    {
        parent::__construct($conf);
    }
    
    public function send($mobile, $content)
    {
        $mobile = str_replace('+', '', $mobile);
 
        $data = array(
            'userId'   => $this->conf['userId'],
            'password' => $this->conf['password'],
            'to' => $mobile,
            'body' => $content . $this->conf['sign'],
        );

        $http = new \HTTPRequest($this->conf['apiUriPrefix'] . '3/sms/out', HTTP_METH_POST);
        $http->addPostFields($data);
        $http->send();
        $code = $http->getResponseCode();
        $body = $http->getResponseBody();
        if($code != 200) {
            throw new Exception($body, $code);
        }
        $tmp = $this->filter($body);

        if($tmp['@attributes']['isError'] != 'false') {
            throw new Exception($body);
        }
        return true;         
    }

    private function filter($str)
    {
        if (preg_match('/<\?xml\s+.+$/is', $str, $matches)) {
            $xml = simplexml_load_string($matches[0]);
            return get_object_vars($xml);
        }
        throw new \Exception($str);
    }
}
?>
