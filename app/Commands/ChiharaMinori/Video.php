<?php

namespace App\Commands\ChiharaMinori;

use App\Entities\Video as EntitiesVideo;
use App\Libraries\ChiharaMinori\OfficialSite;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class Video extends BaseCommand {
    protected $group = 'ChiharaMinori';
    protected $name = 'cm:video';
    protected $description = '下载video页面内容';
    protected $usage = 'cm:video';

    private $_files = [];

    public function run(array $params) {
        $CMOS = new OfficialSite();
        $this->_getVideoFiles();
        for ($i = 1; $i < 3; ++$i) {
            CLI::write(CLI::color('[下载第', 'green') . $i . CLI::color('页]', 'green'));
            $items = $CMOS->getVideo($i);
            foreach ($items as $item) {
                $entity = new EntitiesVideo();
                CLI::write(CLI::color('[下载封面]', 'green') . CLI::color($item['id'], 'light_gray'));
                $item['cover'] = $CMOS->downloadImage($item['cover']);
                $item['site_id'] = $item['id'];
                $item['filename'] = $this->_search($item['date']);
                $item['filesize'] = !empty($item['filename']) ? $this->_getFileSize($item['filename']) : 0;
                $entity->fill($item);
                CLI::write(CLI::color('[写入数据库]', 'green') . CLI::color($item['site_id'], 'light_gray'));
                model('App\Models\VideoModel')->insert($entity);
            }
        }
        CLI::write('完成');
    }

    private function _getVideoFiles() {
        $path = WRITEPATH . 'uploads/video/';
        $files = scandir($path);
        $this->_files = array_values(array_filter($files, fn ($file) => ('.' !== $file && '..' !== $file)));
    }

    private function _search($date) {
        $keyword = 'video_' . str_replace('-', '', $date) . '-';
        $data = array_filter($this->_files, fn ($item) => (preg_match("/{$keyword}/", $item)));

        return !empty($data) ? array_shift($data) : '';
    }

    private function _getFileSize($file) {
        $path = WRITEPATH . 'uploads/video/';

        return file_exists($path . $file) ? filesize($path . $file) : 0;
    }
}
