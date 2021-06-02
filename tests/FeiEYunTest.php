<?php
/**
 * FeiEYun
 *
 * @ClassName FeiEYunTest
 * @Author Administrator
 * @Date 2021-06-02 11:57 星期三
 * @Version 1.0
 * @Description
 */


namespace Shayvmo\Feieyun\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Mockery\Matcher\AnyArgs;
use Shayvmo\Feieyun\Exceptions\ClassNotFoundException;
use Shayvmo\Feieyun\Exceptions\HttpException;
use Shayvmo\Feieyun\Exceptions\InvalidArgumentException;
use Shayvmo\Feieyun\FeiEYun;

class FeiEYunTest extends \PHPUnit\Framework\TestCase
{
    public function testRequest()
    {
        $response = new Response(200, [], '{"msg":"ok"}');

        $client = \Mockery::mock(Client::class);

        $time = time();
        $username = 'username';
        $u_key = 'ukey';
        $api_name = 'api_name';
        $client->allows()->post('http://api.feieyun.cn/Api/Open/', [
            'form_params' => [
                'user' => $username,
                'stime' => $time,
                'sig' => sha1($username.$u_key.$time),
                'apiname' => $api_name,
            ]
        ])->andReturn($response);

        $feieyun = \Mockery::mock(FeiEYun::class, [$username, $u_key])->makePartial();
        $feieyun->allows()->getHttpClient()->andReturn($client);

        $this->assertSame(['msg' => 'ok'], $feieyun->setApiName($api_name)->setStime($time)->request());
    }

    public function testGetHttpClient()
    {
        $feieyun = new FeiEYun('username', 'key');

        $this->assertInstanceOf(ClientInterface::class, $feieyun->getHttpClient());
    }

    public function testSetGuzzleOptions()
    {
        $feieyun = new FeiEYun('username', 'key');

        $this->assertNull($feieyun->getHttpClient()->getConfig('timeout'));

        $feieyun->setGuzzleOptions(['timeout' => 5000]);

        $this->assertSame(5000, $feieyun->getHttpClient()->getConfig('timeout'));
    }

    public function testRequestWithInvalidApiName()
    {
        $feieyun = new FeiEYun('username', 'ukey');

        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage('api_name is not allow empty');

        $feieyun->request();

        $this->fail('Failed to assert request throw exception with invalid api_name.');
    }

    public function testCallingNotFoundApiClass()
    {
        $feieyun = new FeiEYun('username', 'ukey');

        $this->expectException(ClassNotFoundException::class);

        $this->expectExceptionMessage('api class not found');

        $feieyun->notFound();

        $this->fail('Failed to assert call not found api class throw exception');
    }

    public function testGetWeatherWithGuzzleRuntimeException()
    {
        $client = \Mockery::mock(Client::class);
        $client->allows()
            ->post(new AnyArgs())
            ->andThrow(new \Exception('request timeout'));

        $feieyun = \Mockery::mock(FeiEYun::class, ['username', 'u_key'])->makePartial();
        $feieyun->allows()->getHttpClient()->andReturn($client);

        // 接着需要断言调用时会产生异常。
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('request timeout');

        $feieyun->setApiName('api_name')->request([]);
    }
}