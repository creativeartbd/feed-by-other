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
                if( ! Schema::connection($server->mysql_db_name)->hasTable('feed_mappings') ) {
                    Schema::connection($server->mysql_db_name)->create('feed_mappings', function (Blueprint $table) {
                        $table->bigIncrements('id_feed_mappings')->index();
                        $table->bigInteger('id_feed');
                        $table->string('id_project', 255);
                        $table->string('import_field_slug', 155);
                        $table->string('internal_field_slug', 155)->nullable();
                        $table->tinyInteger('import');
                        $table->tinyInteger('custom_field')->nullable();
                        $table->dateTime('date_add');
                        $table->dateTime('date_upd');
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
        Schema::dropIfExists('feed_mappings');
    }
};
