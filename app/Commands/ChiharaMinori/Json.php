<?php

namespace App\Commands\ChiharaMinori;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class Json extends BaseCommand {
    protected $group = 'ChiharaMinori';
    protected $name = 'cm:json';
    protected $description = '生成页面json';
    protected $usage = 'cm:json';

    public function run(array $params) {
        $items = model('App\Models\DownloadModel')->select('id, title, cover, date')->orderBy('date desc')->asArray()->findAll();
        $items = array_map(function ($item) {
            $item['pc'] = model('App\Models\DownloadDetailModel')->select('size, image')->where(['download_id' => $item['id'], 'type' => 'pc'])->orderBy('id')->asArray()->findAll();
            $item['sp'] = model('App\Models\DownloadDetailModel')->select('size, image')->where(['download_id' => $item['id'], 'type' => 'sp'])->orderBy('id')->asArray()->findAll();
            unset($item['id']);

            return $item;
        }, $items);
        $this->writeJSON('download.json', $items);

        $items = model('App\Models\RadioModel')->select('title, date, filename')->orderBy('date desc')->asArray()->findAll();
        $this->writeJSON('radio.json', $items);

        $items = model('App\Models\VideoModel')->select('title, cover, date, filename, filesize, width, height')->orderBy('date desc')->asArray()->findAll();
        $this->writeJSON('video.json', $items);
        CLI::write('完成');
    }

    public function writeJSON($file, $data) {
        $path = WRITEPATH . 'uploads/json/';
        $filename = $path . $file;
        write_file($filename, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
