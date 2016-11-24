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
use yii\base\InvalidConfigException;

/**
 * 京东万像接口
 * @package xutl\api
 */
class Way extends BaseApi
{
    /**
     * @var string 接口地址
     */
    public $baseUrl = 'https://way.jd.com/';

    /**
     * @var string 令牌
     */
    public $appkey;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (empty ($this->appkey)) {
            throw new InvalidConfigException ('The "appkey" property must be set.');
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
    public function api($url, $method, array $params = [], array $headers = [])
    {
        $params = array_merge($params, ['appkey' => $this->appkey]);
        $response = parent::api($url, $method, $params, $headers);
        return $response->data;
    }
}