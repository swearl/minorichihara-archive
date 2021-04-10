<?php

namespace App\Models;

use CodeIgniter\Model;

class DownloadModel extends Model {
    protected $table = 'download';
    protected $returnType = 'App\Entities\Download';
    protected $allowedFields = ['site_id', 'cover', 'title', 'date'];
    protected $useTimestamps = true;
}
