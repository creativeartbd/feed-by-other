<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalImport extends Model
{
    protected $table = 'external_imports';

    protected $primaryKey = 'id_external_report';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'active'            ,
        'name'              ,
        'import_type'       ,
        'notify_user'       ,
        'date_last_import'  ,
        'import_information',
    ];

}
