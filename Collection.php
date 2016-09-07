<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace xutl\api;

use Yii;
use yii\base\Component;
use yii\base\InvalidParamException;

/**
 * Class Collection
 * @package xutl\api
 */
class Collection extends Component
{
    /**
     * @var array list of Api clients with their configuration in format: 'clientId' => [...]
     */
    private $_apis = [];

    /**
     * @param array $apis list of api clients
     */
    public function setApis(array $apis)
    {
        $this->_apis = $apis;
    }

    /**
     * @return ApiInterface[] list of api clients.
     */
    public function getApis()
    {
        $apis = [];
        foreach ($this->_apis as $id => $api) {
            $apis[$id] = $this->get($id);
        }
        return $apis;
    }

    /**
     * @param string $id service id.
     * @return ApiInterface api client instance.
     * @throws InvalidParamException on non existing client request.
     */
    public function get($id)
    {
        if (!array_key_exists($id, $this->_apis)) {
            throw new InvalidParamException("Unknown api client '{$id}'.");
        }
        if (!is_object($this->_apis[$id])) {
            $this->_apis[$id] = $this->create($id, $this->_apis[$id]);
        }
        return $this->_apis[$id];
    }

    /**
     * Checks if client exists in the hub.
     * @param string $id client id.
     * @return boolean whether client exist.
     */
    public function has($id)
    {
        return array_key_exists($id, $this->_apis);
    }

    /**
     * Creates auth client instance from its array configuration.
     * @param string $id api client id.
     * @param array $config api client instance configuration.
     * @return ApiInterface api client instance.
     */
    protected function create($id, $config)
    {
        $config['id'] = $id;
        return Yii::createObject($config);
    }
}