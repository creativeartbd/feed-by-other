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
                if( ! Schema::connection($server->mysql_db_name)->hasTable('feeds') ) {
                    Schema::connection($server->mysql_db_name)->create('feeds', function (Blueprint $table) {
                        $table->bigIncrements('id_feed')->index();
                        $table->string('project_token', 50);
                        $table->string('feed_name', 255);
                        $table->string('feed_url_prefix', 15)->nullable();
                        $table->text('feed_url_link');
                        $table->string('feed_type', 55)->nullable();
                        $table->integer('encoding');
                        $table->integer('import_period');
                        $table->json('auth_info')->nullable();
                        $table->json('safety_info');
                        $table->dateTime('date_add')->nullable();
                        $table->dateTime('date_upd')->nullable();
                        $table->dateTime('date_last_processed')->nullable();
                        $table->integer('running')->default(0);
                        $table->integer('status')->default(0);
                        $table->integer('item_count')->default(0);
                        $table->json('params');
                    });
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
        Schema::dropIfExists('feeds');
    }
};
