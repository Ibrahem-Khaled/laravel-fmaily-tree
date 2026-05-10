<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_settings', function (Blueprint $table) {
            $table->id();
            $table->string('page_title');
            $table->string('page_subtitle')->nullable();
            $table->longText('intro_html')->nullable();
            $table->timestamps();
        });

        Schema::create('support_channels', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('type', 32); // email, phone, whatsapp, url, other
            $table->string('value', 1024);
            $table->string('icon', 128)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('support_faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->longText('answer');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('support_settings')->insert([
            'page_title' => 'الدعم الفني',
            'page_subtitle' => 'نسعد بخدمتك وإجابتك عن استفساراتك',
            'intro_html' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('support_faqs');
        Schema::dropIfExists('support_channels');
        Schema::dropIfExists('support_settings');
    }
};
