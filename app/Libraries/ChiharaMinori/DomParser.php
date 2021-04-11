<?php

namespace App\Libraries\ChiharaMinori;

use PHPHtmlParser\Dom;
use PHPHtmlParser\Dom\Node\HtmlNode;

class DomParser {
    public static function getList(HtmlNode $contents) {
        $items = $contents->find('.post-item');
        $result = [];
        foreach ($items as $item) {
            $uri = $item->find('a')[0]->getAttribute('href') ?? '';
            if ('' === $uri) {
                continue;
            }
            $tmp = explode('/', $uri);
            $id = array_pop($tmp);
            $image = $item->find('.post-img')[0]->getAttribute('src') ?? '';
            $title = trim($item->find('.post-title')[0]->innerHTML ?? '');
            $option = $item->find('.post-option')[0]->innerHTML ?? '';
            $result[] = compact('id', 'uri', 'image', 'title', 'option');
        }

        return $result;
    }

    public static function getRadio(HtmlNode $contents) {
        $items = self::getItems($contents, '.post-item');
        $result = [];
        foreach ($items as $item) {
            $uri = self::getFirst($item, 'a')->getAttribute('href') ?? '';
            if ('' === $uri) {
                continue;
            }
            $tmp = explode('/', $uri);
            $id = array_pop($tmp);
            $title = self::getFirst($item, '.post-title')->innerHtml();
            $date = self::convertDate(self::getFirst($item, '.post-date')->innerHtml());
            $result[] = compact('id', 'uri', 'title', 'date');
        }

        return $result;
    }

    public static function getDownloadDetail(HtmlNode $contents) {
        $types = ['pc', 'sp'];
        $result = [];
        foreach ($types as $type) {
            $node = self::getFirst($contents, ".{$type}_dl");
            $result[$type] = self::getDownloadDetailType($node);
        }

        return $result;
    }

    public static function getDownloadDetailType(HtmlNode $type) {
        $result = [];
        $items = self::getItems($type, 'dd ul li');
        foreach ($items as $item) {
            $size = self::getFirst($item, '.size')->innerHtml();
            $image = self::getFirst($item, '.dl_btn a')->getAttribute('href');
            $result[] = compact('size', 'image');
        }

        return $result;
    }

    public static function getDownloadDate(string $option) {
        $dom = new Dom();
        $dom->loadStr($option);
        $date = self::convertDate(trim($dom->find('span')[0]->innerHTML()));

        return $date;
    }

    /**
     * Get Items.
     *
     * @return HtmlNode[]
     */
    public static function getItems(HtmlNode $node, string $selector) {
        return $node->find($selector);
    }

    /**
     * Get First Item.
     *
     * @return HtmlNode
     */
    public static function getFirst(HtmlNode $node, string $selector) {
        return $node->find($selector)[0];
    }

    public static function convertDate($date) {
        if ('' !== $date) {
            $tmp = explode('.', $date);
            $date = $tmp[0] . '-' . str_pad($tmp[1], 2, '0', STR_PAD_LEFT) . '-' . str_pad($tmp[2], 2, '0', STR_PAD_LEFT);
        }

        return $date;
    }
}
