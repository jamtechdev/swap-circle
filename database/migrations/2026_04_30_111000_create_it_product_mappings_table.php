<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItProductMappingsTable extends Migration
{
    public function up()
    {
        Schema::create('it_product_mappings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('local_product_id')->nullable()->index();
            $table->string('admin_product_uuid', 100)->nullable()->index();
            $table->string('admin_product_code', 80)->unique();
            $table->string('admin_product_name')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('it_product_mappings');
    }
}
