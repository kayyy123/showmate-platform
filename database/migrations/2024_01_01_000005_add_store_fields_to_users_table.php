<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('store_name')->nullable()->after('name');
            $table->text('store_description')->nullable()->after('store_name');
            $table->string('whatsapp_number', 20)->nullable()->after('store_description');
            $table->string('instagram_url')->nullable()->after('whatsapp_number');
            $table->string('tiktok_url')->nullable()->after('instagram_url');
            $table->string('shopee_url')->nullable()->after('tiktok_url');
            $table->string('tokopedia_url')->nullable()->after('shopee_url');
            $table->string('store_logo')->nullable()->after('tokopedia_url');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'store_name',
                'store_description',
                'whatsapp_number',
                'instagram_url',
                'tiktok_url',
                'shopee_url',
                'tokopedia_url',
                'store_logo',
            ]);
        });
    }
};
