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
 * Class GoogleCode
 * @property string $baseUrl
 * @property string $apiKey
 * @package xutl\api
 */
class GoogleCode extends BaseApi
{
    public $baseUrl = 'https://storage.googleapis.com';

    public $proxy;

    /**
     * 获取Http Client
     * @return Client
     */
    public function getHttpClient()
    {
        if (!is_object($this->_httpClient)) {
            $config = [
                'baseUrl' => $this->baseUrl,
                'responseConfig' => [
                    'format' => Client::FORMAT_JSON
                ]
            ];
            if ($this->proxy) {
                $config['requestConfig'] = [
                    'proxy' => $this->proxy
                ];
            }
            $this->_httpClient = new Client($config);
        }
        return $this->_httpClient;
    }

    /**
     * 分页获取所有
     * @param int $page
     * @return array
     */
    public function getAll($page = 1)
    {
        $response = $this->api("https://codesite-archive.appspot.com/archive/search?q=undefined&page={$page}", 'GET');
        return $response;
    }

    /**
     * 搜索
     * @param string $query
     * @param int $page
     * @return array
     */
    public function getSearch($query, $page = 1)
    {
        $response = $this->api("https://codesite-archive.appspot.com/archive/search?q={$query}&page={$page}", 'GET');
        return $response;
    }

    /**
     * 获取指定label下的
     * @param string $label
     * @param int $page
     * @return array
     */
    public function getLabel($label, $page = 1)
    {
        $response = $this->api("https://codesite-archive.appspot.com/search/query?query=label%3A{$label}&page={$page}", 'GET');
        return $response;
    }

    /**
     * 获取项目详情
     * @param string $project 项目名称
     * @return array
     *
     * domain - 字符串。该项目的域名，如“code.google.com”。
     * name - 字符串。项目，如“hg4j”的名称。
     * summary - 字符串。该项目的单行总结，如“纯Java的API和工具包的Mercurial DVCS”。
     * description - 字符串。该项目的详细描述。这将是HTML中。
     * stars- 整数。明星的项目有编号。
     * contentLicense - 枚举字符串。内容许可的类型。对于精确映射见下文。
     * labels - 字符串数组。项目标签的数组。
     * links- {name: string, url: string}数组。项目链接的数组。
     * blogs- {name: string, url: string}数组。项目博客数组。
     * creationTime- 整数。当创建项目的Unix时间戳。如果前谷歌代码开始加入这个约会创建项目可以省略。
     * hasSource - 布尔值。是否该项目具有与其相关联的源代码。
     * repoType - 字符串。源代码库的类型。一个“SVN”，“HG”，或“混帐”的。
     * subrepos - 字符串数组。如果适用项目子回购的数组。
     * ancestorRepo - 字符串。该项目的母公司（如果适用），例如，如果该项目的另一个服务器端克隆。
     * logoName - 字符串。如果适用上传到谷歌代码，自定义标识的名称。
     * imageUrl - 字符串。URL到项目的标志，如果它的存在。
     * movedTo - 字符串。URL到互联网上的项目的新的位置，如果它已经移动了。谷歌代码归档提供302重定向如果该字段存在。
     */
    public function getProject($project)
    {
        $response = $this->api("google-code-archive/v2/code.google.com/{$project}/project.json", 'GET');
        return $response;
    }

    /**
     * 检索wiki页面
     * @param string $project
     * @return array
     */
    public function getWiki($project)
    {
        $response = $this->api("google-code-archive/v2/code.google.com/{$project}/wikis.json", 'GET');
        return $response;
    }

    public function getIssues($project, $page = 1)
    {
        $response = $this->api("google-code-archive/v2/code.google.com/{$project}/issues-page-{$page}.json", 'GET');
        return $response;
    }

    public function getCommits($project, $page = 1)
    {
        $response = $this->api("google-code-archive/v2/code.google.com/{$project}/commits-page-{$page}.json", 'GET');
        return $response;
    }

    public function getSource($project, $page = 1)
    {
        $response = $this->api("google-code-archive/v2/code.google.com/{$project}/source-page-{$page}.json", 'GET');
        return $response;
    }

    /**
     * 检索下载页面
     * @param string $project
     * @param int $page
     * @return array
     *
     * page- 整数。当前页号。
     * totalPages- 整数。总数的项目下载文件的页面。
     * downloads- Download数组。下载对象的数组。
     *
     * 一个Download对象包含以下字段：
     *
     * filename - 字符串。该文件的名称。
     * summary - 字符串。下载的小结。
     * description - 字符串。下载的完整描述。
     * labels - 字符串数组。下载的标签阵列。
     * releaseDate- 整数。下载的发布日期的Unix时间戳。
     * fileSize- 整数。以字节为单位下载文件的大小。
     * uploadDate- 整数。下载的上传日期的Unix时间戳。
     * sha1Checksum-字符串。SHA-1 校验的文件。
     * stars- 整数。恒星的下载数量已。
     */
    public function getDownloads($project, $page = 1)
    {
        $response = $this->api("google-code-archive/v2/code.google.com/{$project}/downloads-page-{$page}.json", 'GET');
        return $response;
    }
}