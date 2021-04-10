<?php

namespace App\Entities;

use CodeIgniter\Entity;

/**
 * Download Entity.
 *
 * @property int    $id
 * @property string $site_id
 * @property string $cover
 * @property string $title
 * @property string $date
 */
class Download extends Entity {
    protected $datamap = [];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $casts = [];
}
