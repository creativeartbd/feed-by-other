<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MysqlServer extends Model
{
    protected $table = 'mysql_servers';

    protected $primaryKey = 'id_mysql_server';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mysql_connect_name'  ,
        'mysql_host_ip'       ,
        'mysql_db_name'       ,
        'mysql_port'          ,
        'mysql_user'          ,
        'mysql_password'      ,
        'mysql_socket_path'   ,
        'open'                ,
        'run'                 ,
    ];
}
