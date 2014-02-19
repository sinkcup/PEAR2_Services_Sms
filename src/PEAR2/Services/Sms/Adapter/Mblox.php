<?php
namespace PEAR2\Services\Sms\Adapter;
/**
 * Adapter for Mblox（mblox.com）短信通道
 */

class Mblox extends \PEAR2\Services\Sms\Adapter
{
    protected $conf = array(
        'apiUriPrefix' => 'http://xml3.mblox.com:8180/send',
        'AccountName'  => '',
        'Password'     => '',
        'sign'         => '',
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
   
    public function send($mobile, $content)
    {
        //mblox要求 国家码的加号要去掉
        $mobile = str_replace('+', '', $mobile);
        $tmp = '<?xml version="1.0" encoding="ISO-8859-1" ?>
<NotificationRequest Version="3.5">
<NotificationHeader>
<PartnerName>' . $this->conf['AccountName'] . '</PartnerName>
<PartnerPassword>' . $this->conf['Password'] . '</PartnerPassword>
</NotificationHeader>
<NotificationList BatchID="' . substr(time(), 0, 8) . '">
<Notification SequenceNumber="1" MessageType="SMS" Format="Unicode">
<Message>' . $content . $this->conf['sign'] . '</Message>
<Profile>-1</Profile>';
        if(isset($this->conf['senderIDAlpha'])) {
            $tmp .= '<SenderID Type="Alpha">' . $this->conf['senderIDAlpha'] . '</SenderID>';
        } else if(isset($this->conf['senderIDNumeric'])) {
            $tmp .= '<SenderID Type="Numeric">' . $this->conf['senderIDNumeric'] . '</SenderID>';
        } else if(isset($this->conf['senderIDShortcode'])) {
            $tmp .= '<SenderID Type="Shortcode">' . $this->conf['senderIDShortcode'] . '</SenderID>';
        } else {
            $tmp .= '<SenderID Type="Alpha">pear2</SenderID>';
        }
        $tmp .= '<Subscriber>
<SubscriberNumber>' . $mobile . '</SubscriberNumber>
</Subscriber>
</Notification>
</NotificationList>
</NotificationRequest>';
        $data = str_replace('\n', '', $tmp);

        $http = new \HTTPRequest($this->conf['apiUriPrefix'], HTTP_METH_POST);
        $http->addPostFields(array('XMLDATA' => $data));
        $r = $http->send()->getBody();
        $tmp = $this->filter($r);

        if(!isset($tmp['NotificationResultHeader']->RequestResultCode) || $tmp['NotificationResultHeader']->RequestResultCode != 0 || !isset($tmp['NotificationResultList']->NotificationResult->NotificationResultCode) || $tmp['NotificationResultList']->NotificationResult->NotificationResultCode != 0) {
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
