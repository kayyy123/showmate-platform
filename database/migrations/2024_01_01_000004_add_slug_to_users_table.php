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
            $table->string('slug')->nullable()->after('name');
        });

        // Generate slug untuk user yang sudah ada
        \App\Models\User::whereNull('slug')->chunkById(100, function ($users) {
            foreach ($users as $user) {
                $slug = Str::slug($user->name);
                $original = $slug;
                $counter = 2;
                while (\App\Models\User::where('slug', $slug)->exists()) {
                    $slug = $original . '-' . $counter++;
                }
                $user->update(['slug' => $slug]);
            }
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
