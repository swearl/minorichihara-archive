<?php

namespace App\Models;

use CodeIgniter\Model;

class VideoModel extends Model {
    protected $table = 'video';
    protected $returnType = 'App\Entities\Video';
    protected $allowedFields = ['site_id', 'cover', 'title', 'date', 'filename', 'filesize', 'width', 'height'];
    protected $useTimestamps = true;
}
