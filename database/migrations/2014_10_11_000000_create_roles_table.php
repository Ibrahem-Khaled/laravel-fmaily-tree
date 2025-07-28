<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // <-- لا تنسَ إضافة هذا السطر

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });


        // Data to be inserted
        $rolesData = [
            ['name' => 'admin', 'description' => 'Administrator role', 'is_active' => true],
            ['name' => 'editor', 'description' => 'Editor role', 'is_active' => true],
            ['name' => 'viewer', 'description' => 'Viewer role', 'is_active' => true],
        ];

        // Add timestamps to each item
        foreach ($rolesData as &$role) {
            $role['created_at'] = now();
            $role['updated_at'] = now();
        }

        // Insert the data
        DB::table('roles')->insert($rolesData);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
