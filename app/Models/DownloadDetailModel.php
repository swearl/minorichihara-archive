<?php

namespace App\Models;

use CodeIgniter\Model;

class DownloadDetailModel extends Model {
    protected $table = 'download_detail';
    protected $returnType = 'App\Entities\DownloadDetail';
    protected $allowedFields = ['download_id', 'type', 'size', 'image'];
    protected $useTimestamps = true;
}
