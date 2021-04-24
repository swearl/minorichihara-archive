<?php

namespace App\Models;

use CodeIgniter\Model;

class DiscographyModel extends Model {
    protected $table = 'discography';
    protected $returnType = 'App\Entities\Discography';
    protected $allowedFields = ['site_id', 'title', 'category', 'date', 'cover', 'content'];
    protected $useTimestamps = true;
}
