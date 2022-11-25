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
        $mysql_servers = DB::table('mysql_servers')->get();
        if( $mysql_servers ) {
            foreach($mysql_servers as $server ) {
                if( Schema::connection($server->mysql_db_name)->hasTable('feeds') ) {
                    Schema::connection($server->mysql_db_name)->table('feeds', function (Blueprint $table) {
                        $table->timestamp('next_process_time')->useCurrent();
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
        Schema::table('feeds', function (Blueprint $table) {
            //
        });
    }
};
