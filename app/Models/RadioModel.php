<?php

namespace App\Models;

use CodeIgniter\Model;

class RadioModel extends Model {
    protected $table = 'radio';
    protected $returnType = 'App\Entities\Radio';
    protected $allowedFields = ['site_id', 'title', 'date', 'filename'];
    protected $useTimestamps = true;
}
