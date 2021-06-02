<?php
/**
 * FeiEYun
 *
 * @ClassName FeiEYun
 * @Author shayvmo
 * @Date 2021-06-01 21:41 星期二
 * @Version 1.0
 * @Description
 */


namespace Shayvmo\Feieyun;


use GuzzleHttp\Exception\GuzzleException;
use Shayvmo\Feieyun\Apis\ApiInterface;
use Shayvmo\Feieyun\Exceptions\ClassNotFoundException;
use Shayvmo\Feieyun\Exceptions\Exception;
use Shayvmo\Feieyun\Exceptions\HttpException;
use Shayvmo\Feieyun\Exceptions\InvalidArgumentException;

class FeiEYun
{
    /** @var string  */
    protected $username;

    /** @var string  */
    protected $u_key;

    /** @var string */
    protected $api_name;

    /** @var array */
    protected $guzzle_options = [];

    private $request_url = 'http://api.feieyun.cn/Api/Open/';
    private $stime;

    public function __construct($username, $u_key)
    {
        $this->username = $username;
        $this->u_key = $u_key;
    }

    public function getHttpClient(): \GuzzleHttp\Client
    {
        return new \GuzzleHttp\Client($this->guzzle_options);
    }

    public function setGuzzleOptions(array $options): FeiEYun
    {
        $this->guzzle_options = $options;
        return $this;
    }

    public function setApiName(string $api_name)
    {
        $this->api_name = $api_name;
        return $this;
    }

    public function getApiName()
    {
        return $this->api_name;
    }

    public function request(array $private_params = [])
    {
        $apiname = $this->getApiName();
        if (empty($apiname)) {
            throw new InvalidArgumentException('api_name is not allow empty');
        }

        $public_params = [
            'user' => $this->username,
            'stime' => $this->getStime(),
            'sig' => $this->getSig(),
            'apiname' => $apiname,
        ];

        $params = array_filter(array_merge($public_params, $private_params));

        try {
            $response = $this->getHttpClient()->post($this->request_url, [
                'form_params' => $params
            ])->getBody()->getContents();

            return json_decode($response, true);

        } catch (GuzzleException $e) {
            throw new Exception('request fail: '.$e->getMessage());
        } catch (\Exception $exception) {
            throw new HttpException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    public function setStime(int $time): FeiEYun
    {
        $this->stime = $time;
        return $this;
    }

    private function getStime(): string
    {
        !$this->stime && $this->stime = (string)time();
        return $this->stime;
    }

    private function getSig()
    {
        return sha1($this->username.$this->u_key.$this->stime);
    }

    public function __call($name,$arguments)
    {
        $class_name = __NAMESPACE__.'\\Apis\\'.ucfirst($name);
        if (!class_exists($class_name)) {
            throw new ClassNotFoundException($class_name.' api class not found');
        }
        /** @var ApiInterface $api_interface */
        $api_interface = new $class_name();

        return $this->setApiName($api_interface->getApiName())->request(...$arguments);
    }


}