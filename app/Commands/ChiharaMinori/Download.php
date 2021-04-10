<?php

namespace App\Commands\ChiharaMinori;

use App\Entities\Download as EntitiesDownload;
use App\Libraries\ChiharaMinori\OfficeSite;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class Download extends BaseCommand {
    /**
     * The Command's Group.
     *
     * @var string
     */
    protected $group = 'ChiharaMinori';

    /**
     * The Command's Name.
     *
     * @var string
     */
    protected $name = 'cm:download';

    /**
     * The Command's Description.
     *
     * @var string
     */
    protected $description = '下载download页面内容';

    /**
     * The Command's Usage.
     *
     * @var string
     */
    protected $usage = 'cm:download';

    /**
     * Actually execute a command.
     */
    public function run(array $params) {
        $CMOS = new OfficeSite();
        CLI::write(CLI::color('[下载页面]', 'green') . CLI::color('download', 'light_gray'));
        $items = $CMOS->getDownload();
        foreach ($items as $item) {
            CLI::write(CLI::color('[下载封面]', 'green') . CLI::color($item['id'], 'light_gray'));
            $filename = $CMOS->downloadImage($item['image']);
            $entity = new EntitiesDownload();
            $entity->site_id = $item['id'];
            $entity->title = $item['title'];
            $entity->date = $item['date'];
            $entity->cover = $filename;
            CLI::write(CLI::color('[写入数据库]', 'green') . CLI::color($item['id'], 'light_gray'));
            model('App\Models\DownloadModel')->insert($entity);
        }
        CLI::write('完成');
    }
}
