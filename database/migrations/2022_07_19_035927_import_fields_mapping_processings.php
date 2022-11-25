<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_fields_mapping_processings', function (Blueprint $table) {
            $table->id('id_mapping_processing')->autoIncrement();
            $table->string('id_project', 50);
            $table->text('feed_url');
            $table->string('feed_url_token', 64);
            $table->string('status', 20);
            $table->timestamp('date_add')->nullable();
            $table->timestamp('date_processed')->nullable();
            $table->text('field_names')->nullable();
            $table->string('feed_type', 10);
            $table->text('params')->nullable();
            $table->text('auth_info')->nullable();
            $table->string('field_extra_info', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('import_fields_mapping_processings');
    }
};
