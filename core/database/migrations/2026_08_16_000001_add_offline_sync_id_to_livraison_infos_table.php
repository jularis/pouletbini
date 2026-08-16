<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('livraison_infos') || Schema::hasColumn('livraison_infos', 'offline_sync_id')) {
            return;
        }

        Schema::table('livraison_infos', function (Blueprint $table) {
            $table->string('offline_sync_id', 100)->nullable()->unique()->after('code');
        });
    }

    public function down()
    {
        if (!Schema::hasTable('livraison_infos') || !Schema::hasColumn('livraison_infos', 'offline_sync_id')) {
            return;
        }

        Schema::table('livraison_infos', function (Blueprint $table) {
            $table->dropUnique('livraison_infos_offline_sync_id_unique');
            $table->dropColumn('offline_sync_id');
        });
    }
};
