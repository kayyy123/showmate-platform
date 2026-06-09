<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('store_slug')->nullable()->unique()->after('store_name');
        });

        // Backfill store_slug untuk user yang sudah ada
        \App\Models\User::whereNull('store_slug')->chunkById(100, function ($users) {
            foreach ($users as $user) {
                $source = $user->store_name ?: $user->name;
                $slug = Str::slug($source);
                $original = $slug;
                $counter = 2;
                while (\App\Models\User::where('store_slug', $slug)->exists()) {
                    $slug = $original . '-' . $counter++;
                }
                $user->update(['store_slug' => $slug]);
            }
        });

        // Keep nullable — the model auto-generates on create/update
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('store_slug');
        });
    }
};
