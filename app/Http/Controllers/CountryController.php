<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function countries()
    {
        $country = Country::get();
        return response()->json($country, 200);
    }

}
