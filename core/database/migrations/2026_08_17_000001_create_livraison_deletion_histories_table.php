<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('livraison_deletion_histories')) {
            return;
        }

        Schema::create('livraison_deletion_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('livraison_info_id')->nullable()->index();
            $table->string('code')->nullable()->index();
            $table->string('invoice_id')->nullable();
            $table->unsignedBigInteger('sender_magasin_id')->nullable()->index();
            $table->unsignedBigInteger('receiver_magasin_id')->nullable()->index();
            $table->unsignedBigInteger('sender_staff_id')->nullable()->index();
            $table->unsignedBigInteger('receiver_staff_id')->nullable()->index();
            $table->string('sender_name')->nullable();
            $table->string('sender_phone')->nullable();
            $table->string('receiver_name')->nullable();
            $table->string('receiver_phone')->nullable();
            $table->decimal('final_amount', 28, 8)->nullable();
            $table->tinyInteger('payment_status')->nullable();
            $table->tinyInteger('livraison_status')->nullable();
            $table->unsignedInteger('products_count')->default(0);
            $table->unsignedBigInteger('deleted_by_user_id')->nullable()->index();
            $table->string('deleted_by_name')->nullable();
            $table->string('deleted_by_type')->nullable();
            $table->timestamp('order_created_at')->nullable();
            $table->timestamp('deleted_at')->nullable()->index();
            $table->longText('payload')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('livraison_deletion_histories');
    }
};
