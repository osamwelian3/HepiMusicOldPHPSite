<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Categories;

class Songs extends Model
{
    use SoftDeletes;

    protected $table = 'songs';

    public function getCategory()
    {
        return $this->hasOne('App\Models\Categories', 'id', 'category_id');
    }
}
