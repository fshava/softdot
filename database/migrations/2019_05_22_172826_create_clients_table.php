<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('surname');
            $table->enum('grade',['ECD A','ECD B','1','2','3','4','5','6','7','SP']);
            $table->string('class')->nullable();
            $table->enum('sex',['B','G']);
            $table->date('dob')->nullable();
            $table->softDeletes(); //to show those who are transfered or deleted in error
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
        Schema::dropIfExists('clients');
    }
}
