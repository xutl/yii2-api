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
 * Class Showji
 * @package xutl\api
 */
class Showji extends BaseApi
{
    public $baseUrl = 'http://v.showji.com';

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
        $headers = array_merge($headers, [
            'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.19 Safari/537.36',
            'referer' => 'http://m.showji.com/',
        ]);
        $response = parent::api($url, $method, $params, $headers);
        return $response->data;
    }

    /**
     * 获取手机归宿地
     * @param $mobile
     * @return array
     * @throws Exception
     */
    public function get($mobile)
    {
        $response = $this->api('Locating/showji.com2016234999234.aspx', 'GET', ['m' => $mobile, 'output' => 'json']);
        return $response;
    }
}