<?php

namespace App\Models;

use CodeIgniter\Model;

class SeoModel extends Model
{
    protected $table         = 'seo';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'url',
        'title',
        'description',
        'meta_keywords',
        'h1',
        'intro_content',
        'seo_content',
    ];
}
