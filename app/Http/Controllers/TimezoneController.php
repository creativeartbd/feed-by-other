<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Currency;
use App\Models\Timezone;
use Illuminate\Http\Request;

class TimezoneController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function timezones()
    {
        $timezone = Timezone::get();
        return response()->json($timezone, 200);
    }

}
