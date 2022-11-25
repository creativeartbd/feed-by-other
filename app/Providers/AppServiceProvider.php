<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
      
        $mysql_servers = DB::table('mysql_servers')->get();
        if( $mysql_servers ) {
            foreach($mysql_servers as $server ) {
                // Set new connection
                $var =   Config::set('database.connections.'.$server->mysql_db_name, [
                    'driver'    => 'mysql',
                    'host'      => $server->mysql_host_ip,
                    'port'      => $server->mysql_port,
                    'database'  => $server->mysql_db_name,  
                    'username'  => $server->mysql_user,
                    'password'  => $server->mysql_password
                ]);
            }
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
