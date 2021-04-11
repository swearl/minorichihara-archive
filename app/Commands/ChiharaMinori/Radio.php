<?php

namespace App\Commands\ChiharaMinori;

use App\Entities\Radio as EntitiesRadio;
use App\Libraries\ChiharaMinori\OfficeSite;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class Radio extends BaseCommand {
    protected $group = 'ChiharaMinori';
    protected $name = 'cm:radio';
    protected $description = '下载radio页面内容';
    protected $usage = 'cm:radio';

    private $_files = [];

    public function run(array $params) {
        $CMOS = new OfficeSite();
        $this->_getRadioFiles();
        for ($i = 1; $i < 4; ++$i) {
            CLI::write(CLI::color('[下载第', 'green') . $i . CLI::color('页]', 'green'));
            $items = $CMOS->getRadio($i);
            foreach ($items as $item) {
                $entity = new EntitiesRadio();
                $item['site_id'] = $item['id'];
                $item['filename'] = $this->_search($item['title']);
                $entity->fill($item);
                CLI::write(CLI::color('[写入数据库]', 'green') . CLI::color($item['site_id'], 'light_gray'));
                model('App\Models\RadioModel')->insert($entity);
            }
        }
        CLI::write('完成');
    }

    private function _getRadioFiles() {
        $path = WRITEPATH . 'uploads/radio/';
        $files = scandir($path);
        $this->_files = array_values(array_filter($files, fn ($file) => ('.' !== $file && '..' !== $file)));
    }

    private function _search($title) {
        $keyword = $title . '-';
        $data = array_filter($this->_files, fn ($item) => (preg_match("/{$keyword}/", $item)));

        return !empty($data) ? array_shift($data) : '';
    }
}
