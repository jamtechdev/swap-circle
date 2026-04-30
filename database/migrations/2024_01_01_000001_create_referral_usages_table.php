<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralUsagesTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('referral_usages')) {
            return;
        }

        Schema::create('referral_usages', function (Blueprint $table) {
            $table->id('referral_usages_id');
            $table->unsignedBigInteger('referrer_id');
            $table->unsignedBigInteger('used_by_id');
            $table->string('refer_code', 20);
            $table->datetime('date_used');
        });
    }

    public function down()
    {
        Schema::dropIfExists('referral_usages');
    }
}
