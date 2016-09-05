<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace xutl\api;

use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\httpclient\Client;

/**
 * Class Ip
 * @package xutl\api
 */
class Ip extends Component
{
    public $baseUrl = 'http://ip.taobao.com';

    /**
     * 请求Api接口
     * @param string $url
     * @param string $method
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function api($url, $method, array $params = [])
    {
        $client = new Client([
            'baseUrl' => $this->baseUrl,
            'responseConfig' => [
                'format' => Client::FORMAT_JSON
            ],
        ]);
        // 生成授权：主帐户Id + 英文冒号 + 时间戳
        $response = $request = $client->createRequest()
            ->setUrl($url)
            ->setMethod($method)
            ->setData($params)
            ->send();
        if (!$response->isOk) {
            throw new Exception ('Http request failed.');
        }
        return $response->data;
    }

    /**
     * 获取IP地址归宿地
     * @param $ip
     * @return array
     * @throws Exception
     */
    public function get($ip){
        $response = $this->api('service/getIpInfo.php', 'GET', ['ip' => $ip]);
        return $response;
    }
}