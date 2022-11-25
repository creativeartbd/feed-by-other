<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timezone extends Model
{
    protected $table = 'timezones';

    protected $primaryKey = 'id_timezone';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'time_zone'      ,
        'gmt_offset'     ,
        'country_name'   ,
        'country_code'   ,
    ];

}
