<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';

    protected $primaryKey = 'id_project';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'          ,
        'website'       ,
        'country_id'    ,
        'currency_id'   ,
        'timezone_id'   ,
        'token'         ,
        'is_selected'   ,
        'user_id'       ,
        'id_mysql_server', 
        'id_mongodb_server'
    ];

}
