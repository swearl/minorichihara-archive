<?php

namespace App\Entities;

use CodeIgniter\Entity;

/**
 * Radio Entity.
 *
 * @property int    $id
 * @property string $site_id
 * @property string $title
 * @property string $date
 * @property string $filename
 */
class Radio extends Entity {
    protected $datamap = [];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $casts = [];
}
