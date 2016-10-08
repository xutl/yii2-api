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

class Composer extends BaseApi
{
    public $baseUrl = 'https://packagist.org';

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