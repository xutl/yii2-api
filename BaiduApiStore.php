<?php
namespace xutl\api;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;

/**
 * Class BaiduApiStore
 * @property string $baseUrl
 * @property string $apiKey
 * @package xutl\api
 */
class BaiduApiStore extends Component
{
    public $baseUrl = 'http://apis.baidu.com/';

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
     * 请求Api接口
     * @param string $url
     * @param string $method
     * @param array $params
     * @param array $headers
     * @return \yii\httpclient\Response
     */
    protected function api($url, $method, array $params = [], array $headers = [])
    {
        $client = new Client([
            'baseUrl' => $this->baseUrl,
            'responseConfig' => [
                'format' => Client::FORMAT_JSON
            ],
        ]);
        $headers = array_merge($headers, ['apikey' => $this->apiKey]);
        return $client->createRequest()
            ->setHeaders($headers)
            ->setData($params)
            ->setMethod($method)
            ->setUrl($url)
            ->send();
    }

    /**
     * get mobile
     *
     * @param string mobile number
     * @return array
     */
    public function getMobile($mobile)
    {
        $response = $this->api('apistore/mobilenumber/mobilenumber', 'GET', ['phone' => $mobile]);
        return $response;
    }
}
