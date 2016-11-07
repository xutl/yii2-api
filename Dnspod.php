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
 * Class Dnspod
 * @package xutl\api
 */
class Dnspod extends BaseApi
{
    public $baseUrl = 'https://dnsapi.cn';

    public $_version = 1.0;

    public $id;
    public $token;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (empty ($this->id)) {
            throw new InvalidConfigException ('The "id" property must be set.');
        }
        if (empty ($this->token)) {
            throw new InvalidConfigException ('The "token" property must be set.');
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
        $params = array_merge($params, ['login_token' => $this->id . ',' . $this->token, 'format' => 'json']);
        $headers = array_merge($headers, ['user-agent' => 'XTL DDNS Client/' . $this->_version]);
        $response = parent::api($url, $method, $params, $headers);
        if ($response->data['status']['code'] != 1) {
            throw new Exception ($response->data['status']['message']);
        }
        return $response->data;
    }

    /**
     * 获取API版本号
     * @return array
     * @throws Exception
     */
    public function version()
    {
        return $this->api('Info.Version', 'POST');
    }

    public function domainList($type = 'all', $offset = null, $length = 20, $group_id = 0, $keyword = null)
    {
        return $this->api('Domain.List', 'POST', ['type' => $type]);
    }

    /**
     * 动态DNS
     * @param int $domainId
     * @param int $recordId
     * @param string $subDomain 子域名
     * @param string $recordLine 线路
     * @return array
     */
    public function RecordDdns($domainId, $recordId, $subDomain, $recordLine = '默认')
    {
        return $this->api('Record.Ddns', 'POST', ['domain_id' => $domainId, 'record_id' => $recordId, 'record_line' => $recordLine, 'sub_domain' => $subDomain]);
    }
}