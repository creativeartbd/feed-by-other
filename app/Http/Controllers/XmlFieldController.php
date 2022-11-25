<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\XmlField;
use Illuminate\Http\Request;

class XmlFieldController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function xmlFields()
    {
        $xmlFields = XmlField::get();
        return response()->json($xmlFields, 200);
    }

}
