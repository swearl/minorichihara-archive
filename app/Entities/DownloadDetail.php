<?php

namespace App\Entities;

use CodeIgniter\Entity;

/**
 * Download Detail Entity.
 *
 * @property int    $id
 * @property int    $download_id
 * @property string $type
 * @property string $size
 * @property string $image
 */
class DownloadDetail extends Entity {
    protected $datamap = [];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $casts = [];
}
