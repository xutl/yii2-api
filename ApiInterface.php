<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace xutl\api;

/**
 * Interface ClientInterface
 * @package xutl\api
 */
interface ApiInterface
{

    /**
     * @param string $id service id.
     */
    public function api($url, $method, array $params = [], array $headers = []);
}