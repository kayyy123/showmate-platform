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

        // Backfill store_slug menggunakan DB raw query (eloquent update diabaikan karena store_slug tidak di fillable)
        \Illuminate\Support\Facades\DB::table('users')->whereNull('store_slug')->orderBy('id')->each(function ($user) {
            $source = $user->store_name ?: $user->name;
            $slug = Str::slug($source);
            $original = $slug;
            $counter = 2;
            while (\Illuminate\Support\Facades\DB::table('users')->where('store_slug', $slug)->exists()) {
                $slug = $original . '-' . $counter++;
            }
            \Illuminate\Support\Facades\DB::table('users')->where('id', $user->id)->update(['store_slug' => $slug]);
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
