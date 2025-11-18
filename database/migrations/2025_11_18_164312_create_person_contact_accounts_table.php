<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('person_contact_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('persons')->onDelete('cascade');
            $table->enum('type', ['phone', 'whatsapp', 'email', 'facebook', 'instagram', 'twitter', 'linkedin', 'telegram', 'other'])->default('other');
            $table->string('value'); // رقم الهاتف، البريد الإلكتروني، رابط الحساب، إلخ
            $table->string('label')->nullable(); // تسمية اختيارية (مثل: رقم العمل، رقم المنزل، إلخ)
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index('person_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('person_contact_accounts');
    }
};
