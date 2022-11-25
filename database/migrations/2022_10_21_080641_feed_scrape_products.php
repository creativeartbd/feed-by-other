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
                    if( ! Schema::connection($server->mysql_db_name)->hasTable('feed_scrape_products') ) {
                        Schema::connection($server->mysql_db_name)->create('feed_scrape_products', function (Blueprint $table) {
                           $table->bigIncrements('id_feed_scrape')->index();
                           $table->integer('id_feed');
                           $table->integer('id_project');
                           $table->integer('id_product');
                           $table->dateTime('date_upd');
                           $table->dateTime('date_last_scrape');
                           $table->text('product_url');
                           $table->dateTime('next_process_time');
                        });
                    }
                }
            }                 
         }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};