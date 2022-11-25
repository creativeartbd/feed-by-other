<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       // Get all mysql servers 
       $mysql_servers = DB::table('mysql_servers')->get();
       if( $mysql_servers ) {
           foreach($mysql_servers as $server ) {
              if( $server->open === 1 ) {
                  if( ! Schema::connection($server->mysql_db_name)->hasTable('feed_scrape_url_to_check_regex') ) {
                      Schema::connection($server->mysql_db_name)->create('feed_scrape_url_to_check_regex', function (Blueprint $table) {
                         $table->bigIncrements('id_fsu')->index();
                         $table->string('url', 500);
                         $table->integer('id_project');
                         $table->integer('id_feed');
                         $table->dateTime('date_upd');
                         $table->dateTime('date_last_check');
                         $table->string('url_hash', 55);
                         $table->dateTime('next_process_time');
                      });
                  }
              }
          }                 
       };
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('check_regex', function (Blueprint $table) {
            //
        });
    }
};