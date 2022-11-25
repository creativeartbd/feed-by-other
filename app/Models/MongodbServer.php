<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MongodbServer extends Model
{

    protected $table = 'mongodb_servers';

    protected $primaryKey = 'id_mongodb_server';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mongodb_ip'  ,
        'mongodb_port'       ,
        'mongodb_user'       ,
        'mongodb_password'          ,
        'mongodb_db_name'          ,
        'open'      ,
    ];
}
