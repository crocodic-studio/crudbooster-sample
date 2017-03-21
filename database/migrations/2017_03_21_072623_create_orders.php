<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('customers_id')->nullable();
            $table->string('order_number')->nullable();
            $table->string('type_check_enum')->nullable();
            $table->string('type_check_datatable')->nullable();
            $table->date('type_date')->nullable();
            $table->dateTime('type_datetime')->nullable();
            $table->string('type_email')->nullable();
            $table->string('type_filemanager')->nullable();            
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('address')->nullable();
            $table->double('type_money')->nullable();
            $table->integer('type_number')->nullable();
            $table->string('type_password')->nullable();
            $table->string('radio_enum')->nullable();
            $table->string('select_enum')->nullable();
            $table->string('select_datatable')->nullable();
            $table->string('select2_enum')->nullable();
            $table->string('select2_datatable')->nullable();
            $table->string('type_text')->nullable();
            $table->text('type_textarea')->nullable();
            $table->time('type_time')->nullable();
            $table->string('type_upload')->nullable();
            $table->longtext('type_wysiwyg')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
