<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function currencies()
    {
        $currency = Currency::get();
        return response()->json($currency, 200);
    }

}
