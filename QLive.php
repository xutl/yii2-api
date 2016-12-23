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
 * Class QLive
 * @package xutl\api
 */
class QLive extends BaseApi
{
    public $appId;
    public $bizId;

    /**
     * Anti-theft keys
     * @var string
     */
    public $antiTheftKey;

    public $apiKey;

    public $baseUrl = 'http://fcgi.video.qcloud.com';

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
        $response = parent::api($url, $method, $params, $headers);
        return $response->data;
    }


    /**
     * 开启或者关闭一个直播流的可推流状态。
     * @param string $streamId
     * @param bool $status
     * @return array
     */
    public function liveChannelSetStatus($streamId, $status)
    {
        $time = time() + 10;
        $sign = md5($this->apiKey . $time);
        return $this->api('common_access', 'GET', [
            'cmd' => $this->appId,
            'interface' => 'Live_Channel_SetStatus',
            'Param.s.channel_id' => $this->bizId . '_' . $streamId,
            'Param.n.status' => $status ? 1 : 0,
            't' => $time,
            'sign' => $sign
        ]);
    }

    /**
     * 启用直播流
     * @param string $streamId
     * @return array
     */
    public function enabledStream($streamId)
    {
        $time = time() + 10;
        $sign = md5($this->apiKey . $time);
        return $this->api('common_access', 'GET', [
            'cmd' => $this->appId,
            'interface' => 'Live_Channel_SetStatus',
            'Param.s.channel_id' => $this->bizId . '_' . $streamId,
            'Param.n.status' => 1,
            't' => $time,
            'sign' => $sign
        ]);
    }
}