<?php

namespace App\Libraries\ChiharaMinori;

use App\Config\Minorin;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use PHPHtmlParser\Dom;

class OfficeSite {
    const BASE_URL = 'https://www.minorichihara.com';

    /**
     * Guzzle Client.
     *
     * @var \GuzzleHttp\Client
     */
    public $client;

    /**
     * Dom.
     *
     * @var \PHPHtmlParser\Dom
     */
    public $dom;

    /**
     * CookieJar.
     *
     * @var \GuzzleHttp\Cookie\CookieJar|null
     */
    public $cookie = null;

    public function __construct() {
        $config = new Minorin();
        $options = [
            'base_uri' => self::BASE_URL,
            'timeout' => 5,
            'allow_redirects' => false,
        ];
        $pf = $config->pf;
        if (!empty($pf)) {
            $jar = CookieJar::fromArray(compact('pf'), 'minorichihara.com');
            $options['cookies'] = $jar;
        }
        $proxy = $config->proxy;
        if (!empty($proxy)) {
            $options['proxy'] = $proxy;
        }
        $this->client = new Client($options);
        $this->dom = new Dom();
    }

    public function getDownload(int $page = 1) {
        $items = $this->getListContent('download', $page);

        return array_map(function ($item) {
            $item['date'] = DomParser::getDownloadDate($item['option']);
            unset($item['option']);

            return $item;
        }, $items);
    }

    public function getDownloadDetail($id) {
        $contents = $this->getPostsContent('download', $id);

        return DomParser::getDownloadDetail($contents);
    }

    public function getRadio(int $page = 1) {
        $contents = $this->getPostsContent('radio', '', $page);
        $items = DomParser::getRadio($contents);

        return $items;
    }

    public function getListContent($type, int $page = 1) {
        $contents = $this->getPostsContent($type, '', $page);

        return DomParser::getList($contents);
    }

    public function getPostsContent($type, $id = '', int $page = 1) {
        $uri = "/posts/{$type}";
        if ('' !== $id) {
            $uri .= "/{$id}";
        } elseif ($page > 1) {
            $uri .= "?page={$page}";
        }
        $contents = $this->getContents($uri);

        return $contents;
    }

    /**
     * Get #contents Content.
     *
     * @param string $uri
     *
     * @return \PHPHtmlParser\Dom\Node\HtmlNode
     */
    public function getContents($uri) {
        $res = $this->client->get($uri);
        $content = $res->getBody()->getContents();
        $this->dom->loadStr($content);
        if (200 != $res->getStatusCode()) {
            $body = $this->dom->find('body')[0];
            $message = strip_tags(trim($body->innerHtml));
            throw new Exception($message, $res->getStatusCode());
        }
        $contents = $this->dom->find('#contents')[0];

        return $contents;
    }

    public function downloadImage($image) {
        $url = parse_url($image);
        $tmp = explode('/', $url['path']);
        $filename = array_pop($tmp);
        $path = WRITEPATH . 'uploads/images/';
        $res = $this->client->get($image);
        $data = $res->getBody()->getContents();
        write_file($path . $filename, $data);

        return $filename;
    }
}
