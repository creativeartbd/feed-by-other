<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('projects', function ($table) {
            $table->bigIncrements('id_project')->index();
            $table->string('name');
            $table->string('website');
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('timezone_id');
            $table->integer('is_selected')->default(0);
            $table->integer('id_mysql_server')->nullable();
            $table->tinyInteger('id_mongodb_server')->nullable();
            $table->string('token');
            $table->timestamps();
        });

        $mysql_servers = DB::table('mysql_servers')->get();
        if( $mysql_servers ) {
            foreach($mysql_servers as $server ) {

                Schema::connection($server->mysql_db_name)->create('projects', function ($table) {
                    $table->bigIncrements('id_project')->index();
                    $table->string('name');
                    $table->string('website');
                    $table->unsignedBigInteger('country_id');
                    $table->unsignedBigInteger('currency_id');
                    $table->unsignedBigInteger('timezone_id');
                    $table->integer('is_selected')->default(0);
                    $table->string('token');
                    $table->timestamps();
                });      
                
            }
        }


        /*
        Schema::connection('shibbir_DB1')->create('projects', function ($table) {
            $table->bigIncrements('id_project')->index();
            $table->string('name');
            $table->string('website');
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('currency_id');
            $table->unsignedBigInteger('timezone_id');
            $table->integer('is_selected')->default(0);
            $table->integer('id_mysql_server')->nullable();
            $table->tinyInteger('id_mongodb_server')->nullable();
            $table->string('token');
            $table->timestamps();
        });
        */

        // $config = config('database.connections');
        // config(['database.testDB' => [
        //     'driver'    => 'mysql',
        //     'host'      => 'localhost',
        //     'port'      => '1234',
        //     'database'  => 'testDB',
        //     'username'  => 'root',
        //     'password'  => '',
        // ]]);
        // $config['test'] = [
        //     'driver' => 'mysql',
        //     'url' => null,
        //     'host' => '127.0.0.1',
        //     'port' => '8889',
        //     'database' => 'shibbir_laravel',
        //     'username' => 'shibbir_laravel',
        //     'password' => 'CommonPassword',
        //     'unix_socket' => '',
        //     'charset' => 'utf8mb4',
        //     'collation' => 'utf8mb4_unicode_ci',
        //     'prefix' => '',
        //     'prefix_indexes' => true,
        //     'strict' => true,
        //     'engine' => 'InnoDB',
        //     'options' => []
        
        // ];
        // dd(config('database.connections'));
        // dd( $config );

        // Get all mysql servers 
        // $mysql_servers = DB::table('mysql_servers')->get();
        // if( $mysql_servers ) {
        //     foreach($mysql_servers as $server ) {

                // dd( $server );

                // $config[$server->mysql_db_name] = [
                //     'driver'    => 'mysql',
                //     'host'      => $server->mysql_host_ip,
                //     'port'      => $server->mysql_port,
                //     'database'  => $server->mysql_db_name,  
                //     'username'  => $server->mysql_user,
                //     'password'  => $server->mysql_password
                // ];

                // Set new connection
                // config(["database.connections.{$server->mysql_db_name}" => [
                //     'driver'    => 'mysql',
                //     'host'      => $server->mysql_host_ip,
                //     'port'      => $server->mysql_port,
                //     'database'  => $server->mysql_db_name,  
                //     'username'  => $server->mysql_user,
                //     'password'  => $server->mysql_password
                // ]]);
                    // dd( DB::connection($server->mysql_db_name) );
        //             DB::connection($server->mysql_db_name)->create('projects', function ($table) {
        //                 $table->bigIncrements('id_project')->index();
        //                 $table->string('name');
        //                 $table->string('website');
        //                 $table->unsignedBigInteger('country_id');
        //                 $table->unsignedBigInteger('currency_id');
        //                 $table->unsignedBigInteger('timezone_id');
        //                // $table->integer('is_selected')->default(0);
        //                // $table->integer('id_mysql_server')->nullable();
        //                // $table->tinyInteger('id_mongodb_server')->nullable();
        //                 $table->string('token');
        //                 $table->timestamps();
        //             });
                
        //     }
        // }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
