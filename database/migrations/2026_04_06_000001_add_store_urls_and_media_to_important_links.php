<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('important_links', function (Blueprint $table) {
            $table->string('url_ios', 500)->nullable()->after('url');
            $table->string('url_android', 500)->nullable()->after('url_ios');
        });

        Schema::create('important_link_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('important_link_id')->constrained('important_links')->cascadeOnDelete();
            $table->string('kind', 20); // image | video
            $table->string('path', 500);
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $linksWithImage = DB::table('important_links')
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->get(['id', 'image']);

        foreach ($linksWithImage as $row) {
            DB::table('important_link_media')->insert([
                'important_link_id' => $row->id,
                'kind' => 'image',
                'path' => $row->image,
                'title' => null,
                'description' => null,
                'sort_order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('important_link_media');

        Schema::table('important_links', function (Blueprint $table) {
            $table->dropColumn(['url_ios', 'url_android']);
        });
    }
};
