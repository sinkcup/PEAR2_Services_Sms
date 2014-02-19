<?php
require_once 'autoload.php';
class SmsTest extends PHPUnit_Framework_TestCase
{
    private $etonenet = array(
        'spid' => '1234',
        'sppassword' => 'asdf',
    );

    private $ihuyi = array(
        'account'        => 'cf_user1',
        'password'         => '123456',
        'sign'         => '',
    );

    private $smsbao = array(
        'u' => 'user1',
        'p' => '1234',
        'sign' => '【公司名】',
    );

    private $bechsms = array(
        'accesskey'    => 'asdf',
        'secretkey'    => 'aa11111111bbbbbbbbb',
        'sign'         => '【公司名】',
    );
    
    private $mblox = array(
        'AccountName'    => 'SalesTest2',
        'Password'    => 'g38M2dp21',
        //'senderIDAlpha' => 'company',
        //'senderIDNumeric' => '8615312159527',
    );

    public function testSendMblox()
    {
        $c = new \PEAR2\Services\Sms('mblox', $this->mblox);
        $r = $c->send('+8615312159527', 'hello USA, mblox');
        sleep(1);
        $this->assertEquals(true, $r);
    }
 
    public function testSendBechsms()
    {
        $c = new \PEAR2\Services\Sms('bechsms', $this->bechsms);
        $r = $c->send('+8615312159527', '注册校验码：2013。如非本人操作，请忽略本短信。');
        $this->assertEquals(true, $r);
    }
    
    public function testGetRemainBechsms()
    {
        echo __FUNCTION__ . "\n";
        $c = new \PEAR2\Services\Sms('bechsms', $this->bechsms);
        $r = $c->getRemain();
        var_dump($r);
        $this->assertStringMatchesFormat('%i', strval($r));
    }

    public function testSendEtonenet()
    {
        $c = new \PEAR2\Services\Sms('etonenet', $this->etonenet);
        $r = $c->send('+8615312159527', 'Hello!树先生 etonenet');
        $this->assertEquals(true, $r);
    }

    public function testSendSmsbao()
    {
        $c = new \PEAR2\Services\Sms('smsbao', $this->smsbao);
        $r = $c->send('+8615312159527', 'Hello!树先生 smsbao');
        $this->assertEquals(true, $r);
    }
    
    public function testGetRemainSmsbao()
    {
        echo __FUNCTION__ . "\n";
        $c = new \PEAR2\Services\Sms('smsbao', $this->smsbao);
        $r = $c->getRemain();
        var_dump($r);
        $this->assertStringMatchesFormat('%i', strval($r));
    }

    public function testSendIhuyi()
    {
        echo __FUNCTION__ . "\n";
        $c = new \PEAR2\Services\Sms('ihuyi', $this->ihuyi);
        $r = $c->send('+8615312159527', '您的验证码是：4526。请不要把验证码泄露给其他人。');
        var_dump($r);
        $this->assertEquals(true, $r);
    }
    
    public function testGetRemainIhuyi()
    {
        echo __FUNCTION__ . "\n";
        $c = new \PEAR2\Services\Sms('ihuyi', $this->ihuyi);
        $r = $c->getRemain();
        var_dump($r);
        $this->assertStringMatchesFormat('%i', strval($r));
    }
}
?>
