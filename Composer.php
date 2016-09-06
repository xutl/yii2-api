<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace xutl\api;

use yii\base\Component;
use yii\base\Exception;
use yii\httpclient\Client;

class Composer extends Component implements ApiInterface
{
    public $baseUrl = 'https://packagist.org';

    /**
     * 请求Api接口
     * @param string $url
     * @param string $method
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function api($url, $method, array $params = [], array $headers = [])
    {
        $client = new Client([
            'baseUrl' => $this->baseUrl,
            'responseConfig' => [
                'format' => Client::FORMAT_JSON
            ],
        ]);
        $response = $client->createRequest()
            ->setData($params)
            ->setMethod($method)
            ->setHeaders($headers)
            ->setUrl($url)
            ->send();
        if (!$response->isOk) {
            throw new Exception ('Http request failed.');
        }
        return $response->data;
    }

    /**
     * 列出所有包
     * @return  array
     */
    public function getAll()
    {
        return $this->api('packages/list.json', 'GET');
    }

    /**
     * 按供应商列出包
     * @param string $vendor
     * @return array
     */
    public function getVendor($vendor)
    {
        return $this->api('packages/list.json', 'GET', ['vendor', $vendor]);
    }

    /**
     * 按类型列出包
     * @param string $type
     * @return array
     */
    public function getType($type)
    {
        return $this->api('packages/list.json', 'GET', ['type', $type]);
    }

    /**
     * 搜索
     * @param string $query
     * @return array
     */
    public function search($query)
    {
        return $this->api('search.json', 'GET', ['q', $query]);
    }

    /**
     * 获取包
     * @param string $vendor
     * @param string $package
     * @return array
     */
    public function getPackage($vendor, $package)
    {
        return $this->api("packages/{$vendor}/{$package}.json", 'GET');
    }
}