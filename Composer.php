<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace xutl\api;

use yii\base\Component;
use yii\httpclient\Client;

class Composer extends Component
{
    public $baseUrl = 'https://packagist.org/';

    /**
     * 请求Api接口
     * @param string $url
     * @param string $method
     * @param array $params
     * @param array $headers
     * @return \yii\httpclient\Response
     */
    protected function api($url, $method, array $params = [])
    {
        $client = new Client([
            'baseUrl' => $this->baseUrl,
            'responseConfig' => [
                'format' => Client::FORMAT_JSON
            ],
        ]);
        return $client->createRequest()
            ->setData($params)
            ->setMethod($method)
            ->setUrl($url)
            ->send();
    }

    /**
     * 列出所有包
     * @return \yii\httpclient\Response
     */
    public function getAll()
    {
        return $this->api('packages/list.json', 'GET');
    }

    /**
     * 按供应商列出包
     * @param string $vendor
     * @return \yii\httpclient\Response
     */
    public function getVendor($vendor)
    {
        return $this->api('packages/list.json', 'GET', ['vendor', $vendor]);
    }

    /**
     * 按类型列出包
     * @param string $type
     * @return \yii\httpclient\Response
     */
    public function getType($type)
    {
        return $this->api('packages/list.json', 'GET', ['type', $type]);
    }

    /**
     * 搜索
     * @param string $query
     * @return \yii\httpclient\Response
     */
    public function search($query)
    {
        return $this->api('search.json', 'GET', ['q', $query]);
    }

    /**
     * 获取包
     * @param string $vendor
     * @param string $package
     * @return \yii\httpclient\Response
     */
    public function getPackage($vendor, $package)
    {
        return $this->api("packages/{$vendor}/{$package}.json", 'GET');
    }
}