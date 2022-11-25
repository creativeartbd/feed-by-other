<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\FeedsController;
use App\Http\Controllers\FeedMappingsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\TimezoneController;
use App\Http\Controllers\XmlFieldController;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('auth/redirect', function () {
    return Socialite::driver('google')->stateless()->redirect();
});

Route::any('auth/callback', function () {
    try {
        $user = Socialite::driver('google')->stateless()->user();
        return response()->json( $user, 200);

    } catch (\Exception $e) {
        return $e->getMessage();
    }
});

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login')->name('login');
    Route::post('register', 'register');
    Route::post('refresh', 'refresh');
});

Route::group(['middleware' => ['isNotExpiredToken']], function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('logout', 'logout');
        Route::post('update', 'update');
        Route::post('resetpwd', 'resetpwd');
    });
    
    Route::controller(ImportController::class)->group(function() {
        Route::post('projects/search', 'search_product')->name('search_product');
        Route::post('projects/import/save-columns', 'save_columns')->name('save_columns');
        Route::get('projects/import/more/{token}/{userId}', 'get_more_product')->name('get_more_product');  
        Route::get('projects/import/all/{token}/{userId}', 'get_products_key_value')->name('get_products_key_value');    
        Route::post('projects/import/sort', 'get_products_by_sort')->name('get_products_by_sort');    
       
    });
    
    Route::controller(ProjectController::class)->group(function() {
        Route::post('projects', 'store' );
        Route::get('projects/{user_id}', 'index');
        Route::get('projects/select/{id}', 'select' );
        Route::get('projects/{token}/{user_id}', 'get' );
        Route::get('projects/settings/{token}/{user_id}', 'get' );
        Route::put('projects/settings/update/{token}/{userId}', 'update' );
        Route::delete('projects/delete/{id}', 'delete' );   
        Route::get('projects/search/{keyword}', 'search' );
    });
    
    Route::controller(FeedsController::class)->group(function() {
        Route::post('projects/import/mapping/refresh', 'refresh_feed');
        Route::post('projects/import', "store_import");
        Route::get('projects/import/{token}/{userId}', 'get_import');
        Route::get('projects/import/list/running/{token}/{userId}/{id_feed}', 'running');
        Route::put('projects/import/list/update/{token}/{userId}/{id_feed}', 'update_feed_status');
        Route::put('projects/import/list/update-running/{token}/{userId}/{id_feed}', 'update_running');
        Route::get('projects/csv-xml-import/get/{user_id}/{project_token}', 'get_xml_csv');
    });
    
    
    Route::controller(FeedMappingsController::class)->group(function() {
        Route::post('projects/import/mapping', 'store_mapping');
        Route::get('projects/import/mapping/show/{token}/{userId}/{id_feed}', 'show_mapping');
        Route::put('projects/import/mapping/update/{token}/{userId}/{id_feed}', 'update_mapping');
        Route::delete('projects/import/mapping/delete/{token}/{userId}/{id_feed}', 'delete_mapping');
    });
    
    Route::controller(ProductController::class)->group(function() {
        Route::post('xml-import', 'xml-mport');
        Route::post('save-import-data', 'save-import-data');
    });
             
    //Countries
    Route::get('countries', [App\Http\Controllers\CountryController::class, 'countries'])->name("countries");
    //Currencies
    Route::get('currencies', [App\Http\Controllers\CurrencyController::class, 'currencies'])->name("currencies");
    //Timezones
    Route::get('timezones', [App\Http\Controllers\TimezoneController::class, 'timezones'])->name("timezones"); 
    //XmlFields Import
    Route::get('xml-fields', [App\Http\Controllers\XmlFieldController::class, 'xmlFields'])->name("xmlFields");
    // Get XML/CSV import list
});

