<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalePharmasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_pharmas', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->float('sub_total',8,2);  
            $table->float('grand_total',8,2);  
            $table->string('discount_percentage');
            $table->string('discount_flat');
            $table->float('paid',8,2);  
            $table->float('due',8,2);  
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
        Schema::dropIfExists('sale_pharmas');
    }
}
