<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExternalImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_imports', function (Blueprint $table) {
            $table->bigIncrements('id_external_report');
            $table->integer('active');
            $table->string('name');
            $table->string('import_type');
            $table->integer('notify_user');
            $table->dateTime('date_last_import');
            $table->longText('import_information');
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
        Schema::dropIfExists('external_imports');
    }
}
