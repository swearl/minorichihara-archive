<?php

namespace App\Commands\ChiharaMinori;

use App\Entities\Download as EntitiesDownload;
use App\Entities\DownloadDetail as EntitiesDownloadDetail;
use App\Libraries\ChiharaMinori\OfficialSite;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class Download extends BaseCommand {
    protected $group = 'ChiharaMinori';
    protected $name = 'cm:download';
    protected $description = '下载download页面内容';
    protected $usage = 'cm:download';

    public function run(array $params) {
        $CMOS = new OfficialSite();
        CLI::write(CLI::color('[下载封面页]', 'red'));
        CLI::write(CLI::color('[下载页面]', 'green') . CLI::color('download', 'light_gray'));
        $items = $CMOS->getDownload();
        $ids = [];
        foreach ($items as $item) {
            CLI::write(CLI::color('[下载封面]', 'green') . CLI::color($item['id'], 'light_gray'));
            $filename = $CMOS->downloadImage($item['image']);
            $entity = new EntitiesDownload();
            $entity->site_id = $item['id'];
            $entity->title = $item['title'];
            $entity->date = $item['date'];
            $entity->cover = $filename;
            CLI::write(CLI::color('[写入数据库]', 'green') . CLI::color($item['id'], 'light_gray'));
            $download_id = model('App\Models\DownloadModel')->insert($entity);
            $ids[$download_id] = $item['id'];
        }
        CLI::write(CLI::color('[下载内页]', 'red'));
        foreach ($ids as $download_id => $site_id) {
            CLI::write(CLI::color('[下载页面]', 'green') . CLI::color($site_id, 'light_gray'));
            $detail = $CMOS->getDownloadDetail($site_id);
            foreach ($detail as $type => $typeItems) {
                foreach ($typeItems as $img) {
                    $entity = new EntitiesDownloadDetail();
                    CLI::write(CLI::color('[下载图片]', 'green') . CLI::color($site_id . ': ' . $img['size'], 'light_gray'));
                    $entity->image = $CMOS->downloadImage($img['image']);
                    $entity->download_id = $download_id;
                    $entity->type = $type;
                    $entity->size = $img['size'];
                    CLI::write(CLI::color('[写入数据库]', 'green') . CLI::color($site_id . ': ' . $img['size'], 'light_gray'));
                    model('App\Models\DownloadDetailModel')->insert($entity);
                }
            }
        }
        CLI::write('完成');
    }
}
