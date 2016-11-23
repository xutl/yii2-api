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
 * Class Gitlab
 * @package xutl\api
 */
class Gitlab extends BaseApi
{
    public $baseUrl = 'http://192.168.10.103/api/v3';

    public $privateToken;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (empty ($this->privateToken)) {
            throw new InvalidConfigException ('The "privateToken" property must be set.');
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
        $headers = array_merge($headers, ['PRIVATE-TOKEN' => $this->privateToken]);
        $response = parent::api($url, $method, $params, $headers);
        return $response->data;
    }

    public function getUsers()
    {
        return $this->api('users');
    }

    /**
     * 获取所有项目
     * @return array
     */
    public function getProjects()
    {
        return $this->api('projects');
    }
}