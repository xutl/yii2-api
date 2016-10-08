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
 * Class BaseApi
 * @package xutl\api
 */
class BaseApi extends Component
{
    public $baseUrl;

    private $_httpClient;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (empty ($this->baseUrl)) {
            throw new InvalidConfigException ('The "baseUrl" property must be set.');
        }
    }

    public function getHttpClient()
    {
        if (!is_object($this->_httpClient)) {
            $this->_httpClient = new Client([
                'baseUrl' => $this->baseUrl,
            ]);
        }
        return $this->_httpClient;
    }

    /**
     * Sends HTTP request.
     * @param string $method request type.
     * @param string $url request URL.
     * @param array $params request params.
     * @param array $headers additional request headers.
     * @return array response.
     * @throws Exception on failure.
     */
    protected function sendRequest($method, $url, array $params = [], array $headers = [])
    {
        $response = $request = $this->getHttpClient()->createRequest()
            ->setUrl($url)
            ->setMethod($method)
            ->setHeaders($headers)
            ->setData($params)
            ->send();
        if (!$response->isOk) {
            throw new Exception ('Http request failed.');
        }
        return $response->data;
    }

    /**
     * Performs request to the API.
     * @param string $url absolute API URL.
     * @param string $method request method.
     * @param array $params request parameters.
     * @param array $headers additional request headers.
     * @return array API response.
     * @throws Exception on failure.
     */
    protected function apiInternal($url, $method, array $params, array $headers)
    {
        return $this->sendRequest($method, $url, $params, $headers);
    }
}