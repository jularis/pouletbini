<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('offline_sync_requests')) {
            return;
        }

        Schema::create('offline_sync_requests', function (Blueprint $table) {
            $table->id();
            $table->string('sync_id', 100)->unique();
            $table->string('action')->nullable()->index();
            $table->string('guard')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('offline_sync_requests');
    }
};
