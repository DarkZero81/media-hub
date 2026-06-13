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
    Schema::create('media', function (Blueprint $table) {
        $table->id();
        // ربط الجدول بالمستخدم، وفي حال حُذف المستخدم تُحذف ملفاته تلقائياً
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        // ربط الجدول بالقسم، وفي حال حُذف القسم لا تضيع الملفات بل تصبح بلا قسم أو تمنع الحذف
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        
        $table->string('title');
        $table->text('description')->nullable(); // الوصف اختياري
        $table->string('file_path'); // مسار الملف على السيرفر
        $table->string('file_type'); // لتحديد هل هو 'image' أم 'video'
        $table->unsignedInteger('downloads_count')->default(0); // عداد التحميلات
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
