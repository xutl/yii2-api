<?php
namespace xutl\api;

class Baidu extends BaseClient
{
    /**
     * @var string API base URL.
     */
    public $apiBaseUrl = 'http://apis.baidu.com/';

    /**
     * @var string 百度ApiKey
     */
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
     * @inheritdoc
     */
    protected function apiInternal($url, $method, array $params, array $headers)
    {
        $headers = array_merge($headers, ['apikey:'.$this->apiKey]);
        return parent::apiInternal($url, $method, $params, $headers);
    }
}
