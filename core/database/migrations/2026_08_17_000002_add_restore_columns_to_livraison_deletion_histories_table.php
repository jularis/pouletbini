<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('livraison_deletion_histories')) {
            return;
        }

        Schema::table('livraison_deletion_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('livraison_deletion_histories', 'restored_at')) {
                $table->timestamp('restored_at')->nullable()->after('deleted_at')->index();
            }

            if (!Schema::hasColumn('livraison_deletion_histories', 'restored_by_admin_id')) {
                $table->unsignedBigInteger('restored_by_admin_id')->nullable()->after('restored_at')->index();
            }
        });
    }

    public function down()
    {
        if (!Schema::hasTable('livraison_deletion_histories')) {
            return;
        }

        Schema::table('livraison_deletion_histories', function (Blueprint $table) {
            if (Schema::hasColumn('livraison_deletion_histories', 'restored_by_admin_id')) {
                $table->dropColumn('restored_by_admin_id');
            }

            if (Schema::hasColumn('livraison_deletion_histories', 'restored_at')) {
                $table->dropColumn('restored_at');
            }
        });
    }
};
