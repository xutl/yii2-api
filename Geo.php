<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace xutl\api;

use Yii;
use yii\base\Exception;
use yii\httpclient\Client;

/**
 * Class Geo
 * @package xutl\api
 */
class Geo extends BaseApi
{
    public $baseUrl = 'http://www.geoplugin.net';

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
    public function api($url, $method, array $params = [], array $headers = [])
    {
        $response = parent::api($url, $method, $params, $headers);
        return $response->data;
    }

    /**
     * 获取IP地址归宿地
     * @param $ip
     * @return array
     * @throws Exception
     */
    public function get($ip){
        $response = $this->api('json.gp', 'GET', ['ip' => $ip]);
        return $response;
    }
}