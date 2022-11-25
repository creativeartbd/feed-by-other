<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feeds extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_mapping_processing';

    protected $casts = [
        'safety_info' => 'array'
    ];

    protected $fillable = [
        'name',
        'xml_file_url',
        'encoding',
        'id_project', 
        'feed_url', 
        'feed_url_token',
        'status',
        'date_add', 
        'date_processed'
    ];
}
