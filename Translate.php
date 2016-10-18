<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace xutl\api;

use yii\base\Component;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;

/**
 * Class Translate
 * @package xutl\api
 */
class Translate extends BaseApi
{
    /**
     * @var string
     */
    public $appId;

    /**
     * @var string
     */
    public $appKey;

    /**
     * 指定百度翻译的API网址
     */
    public $baseUrl = "http://api.fanyi.baidu.com";

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (empty ($this->appId)) {
            throw new InvalidConfigException ('The "appId" property must be set.');
        }
        if (empty ($this->appKey)) {
            throw new InvalidConfigException ('The "appKey" property must be set.');
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
        $salt = uniqid();
        $sign = md5($this->appId . $params['q'] . $salt . $this->appKey);
        $params = array_merge($params, ['appid' => $this->appId, 'sign' => $sign, 'salt' => $salt]);
        return parent::api($url, $method, $params, $headers);
    }

    /**
     * 翻译
     *
     * @param string $query 待翻译内容,utf-8，urlencode编码
     * @param string $from 源语言语种：语言代码或auto
     * @param string $to 目标语言语种：语言代码或auto
     * @return array
     */
    public function translate($query, $from = 'auto', $to = 'zh')
    {
        $response = $this->api('api/trans/vip/translate', 'POST', ['q' => $query, 'from' => $from, 'to' => $to]);
        return $response;
    }
}