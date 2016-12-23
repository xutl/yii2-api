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
     * 生成签名
     * @param int $time
     * @return string
     */
    public function sign($time)
    {
        return md5($this->apiKey . $time);
    }

    /**
     * 校验签名是否正确
     * @param int $time
     * @param string $sign
     * @return bool
     */
    public function checkSign($time, $sign)
    {
        if ($this->sign($time) != $sign) {
            return false;
        } else {
            return true;
        }
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
     * 查询直播状态
     * @param $streamId
     * @return array
     */
    public function liveChannelGetStatus($streamId)
    {
        $time = time() + 10;
        $sign = md5($this->apiKey . $time);
        return $this->api('common_access', 'GET', [
            'cmd' => $this->appId,
            'interface' => 'Live_Channel_GetStatus',
            'Param.s.channel_id' => $this->bizId . '_' . $streamId,
            't' => $time,
            'sign' => $sign
        ]);
    }

    /**
     * 用于查询某条直播流截止到调用时间为止已经生成的录制文件。
     * @param string $streamId
     * @param int $page
     * @param int $page_size
     * @param string $sortType
     * @return array
     */
    public function liveTapeGetFileList($streamId, $page = 1, $page_size = 100, $sortType = 'asc')
    {
        $time = time() + 10;
        $sign = md5($this->apiKey . $time);
        return $this->api('common_access', 'GET', [
            'cmd' => $this->appId,
            'interface' => 'Live_Tape_GetFilelist',
            'Param.s.channel_id' => $this->bizId . '_' . $streamId,
            'Param.n.page_no' => $page,
            'Param.n.page_size' => $page_size,
            'Param.s.sort_type' => $sortType,
            't' => $time,
            'sign' => $sign
        ]);
    }

    /**
     * 查询直播中的频道新产生的截图文件。
     * @param string $streamId
     * @param int $bid
     * @param int $count
     * @return array
     */
    public function liveQueueGet($streamId, $bid, $count = 100)
    {
        $time = time() + 10;
        $sign = md5($this->apiKey . $time);
        return $this->api('common_access', 'GET', [
            'cmd' => $this->appId,
            'interface' => 'Live_Queue_Get',
            'Param.s.channel_id' => $this->bizId . '_' . $streamId,
            'Param.n.bid' => $bid,
            'Param.n.count' => $count,
            't' => $time,
            'sign' => $sign
        ]);
    }

    /**
     * 获取推流地址
     * 如果不传key和过期时间，将返回不含防盗链的url
     * @param int|string $streamId 您用来区别不通推流地址的唯一id
     * @param null|string $time 过期时间 sample 2016-11-12 12:00:00
     * @return string
     */
    public function getPushUrl($streamId, $time = '2099-12-30 23:59:59')
    {
        $liveCode = $this->bizId . "_" . $streamId;
        if ($time) {
            $txTime = strtoupper(base_convert(strtotime($time), 10, 16));
            $txSecret = md5($this->antiTheftKey . $liveCode . $txTime);
            $extStr = "?" . http_build_query([
                    "bizid" => $this->bizId,
                    "txSecret" => $txSecret,
                    "txTime" => $txTime
                ]);
        }
        return "rtmp://" . $this->bizId . ".livepush.myqcloud.com/live/" . $liveCode . (isset($extStr) ? $extStr : "");
    }

    /**
     * 获取播放地址
     * @param int|string $streamId 您用来区别不通推流地址的唯一id
     * @return array
     */
    public function getPlayUrls($streamId)
    {
        $liveCode = $this->bizId . "_" . $streamId;
        return [
            'rtmp' => "rtmp://" . $this->bizId . ".liveplay.myqcloud.com/live/" . $liveCode,
            'flv' => "http://" . $this->bizId . ".liveplay.myqcloud.com/live/" . $liveCode . ".flv",
            'hls' => "http://" . $this->bizId . ".liveplay.myqcloud.com/live/" . $liveCode . ".m3u8"
        ];
    }
}