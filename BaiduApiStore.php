<?php
namespace xutl\api;

use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;

/**
 * Class BaiduApiStore
 * @property string $baseUrl
 * @property string $apiKey
 * @package xutl\api
 */
class BaiduApiStore extends BaseApi
{
    public $baseUrl = 'http://apis.baidu.com';

    /**
     * @var string 百度ApiKey
     */
    public $apiKey;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (empty ($this->apiKey)) {
            throw new InvalidConfigException ('The "apiKey" property must be set.');
        }
    }

    /**
     * 获取Http Client
     * @return Client
     */
    public function getHttpClient()
    {
        if (!is_object($this->_httpClient)) {
            $this->_httpClient = new Client([
                'baseUrl' => $this->baseUrl,
                'responseConfig' => [
                    'format' => Client::FORMAT_JSON
                ],
            ]);
        }
        return $this->_httpClient;
    }

    /**
     * 请求Api接口
     * @param string $url
     * @param string $method
     * @param array $params
     * @param array $headers
     * @return array
     * @throws Exception
     */
    protected function apiInternal($method, $url, array $params = [], array $headers = [])
    {
        $headers = array_merge($headers, ['apikey' => $this->apiKey]);
        return parent::apiInternal($method, $url, $params, $headers);
    }

    /**
     * 查询身份证男女归属地
     * @param string $id
     * @return array
     */
    public function getId($id)
    {
        $response = $this->api('apistore/idservice/id', 'GET', ['id' => $id]);
        return $response;
    }

    /**
     * 查询IP地址归宿地
     * @param string $ip
     * @return array
     */
    public function getIp($ip)
    {
        $response = $this->api('apistore/iplookupservice/iplookup', 'GET', ['ip' => $ip]);
        return $response;
    }

    /**
     * 查询手机号码归属地
     *
     * @param string $mobile number
     * @return array
     */
    public function getMobile($mobile)
    {
        $response = $this->api('apistore/mobilenumber/mobilenumber', 'GET', ['phone' => $mobile]);
        return $response;
    }
}
