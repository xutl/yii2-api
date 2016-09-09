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
use yii\base\InvalidConfigException;
use yii\httpclient\Client;

/**
 * Class AvatarData
 * @package xutl\api
 * @see http://avatardata.cn/Docs/Api?id=b3d25cbd-449d-41c3-8765-21649658789e&detailId=479ea133-c824-496e-b0c2-575ab64a08da
 */
class AvatarData extends Component implements ApiInterface
{
    public $baseUrl = 'http://api.avatardata.cn';

    public $apiKey;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (empty ($this->apiKey)) {
            throw new InvalidConfigException ('The "apiKey" property must be set.');
        }
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
        $client = new Client([
            'baseUrl' => $this->baseUrl,
            'responseConfig' => [
                'format' => Client::FORMAT_JSON
            ],
        ]);
        $params = array_merge($params, ['key' => $this->apiKey, 'dtype' => 'JSON', 'format' => 'false']);
        $response = $request = $client->createRequest()
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
}