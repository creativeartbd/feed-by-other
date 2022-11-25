<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class XmlField extends Model
{
    protected $table = 'xml_fields';

    protected $primaryKey = 'id_xml_field';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'field_title'    ,
        'field_slug'     ,
    ];

}
