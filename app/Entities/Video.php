<?php

namespace App\Entities;

use CodeIgniter\Entity;

/**
 * Video Entity.
 *
 * @property int    $id
 * @property string $site_id
 * @property string $cover
 * @property string $title
 * @property string $date
 * @property string $filename
 * @property int    $filesize
 * @property int    $width
 * @property int    $height
 */
class Video extends Entity {
    protected $datamap = [];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $casts = [];
}
