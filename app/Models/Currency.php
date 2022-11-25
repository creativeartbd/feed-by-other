<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'currencies';

    protected $primaryKey = 'id_currency';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'currency_name' ,
        'country_code'  ,
        'num'           ,
        'state'         ,
        'timezone_id'   ,
    ];

}
