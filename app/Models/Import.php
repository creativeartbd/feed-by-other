<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Import extends Model
{
    protected $connection = 'mongodb';    
    protected $collection;

    public function setCollection($collection) {
        $this->collection = $collection;
        return $this;
    }
}
