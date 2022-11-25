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
                Schema::connection($server->mysql_db_name)->create('notifications', function (Blueprint $table) {
                    $table->bigIncrements('id_notification')->index();;
                    $table->string('id_project', 50);
                    $table->tinyInteger('notification_type');
                    $table->tinyInteger('object_type');
                    $table->tinyInteger('object_id');
                    $table->string('title', 20)->default('Feed created');
                    $table->string('content');
                    $table->string('icon', 30);
                    $table->tinyInteger('id_user');
                    $table->timestamps();
                });
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
        Schema::dropIfExists('notifications');
    }
};
