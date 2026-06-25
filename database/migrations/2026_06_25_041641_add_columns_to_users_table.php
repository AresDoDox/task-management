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
        Schema::table('users', function (Blueprint $table) {
            // Thêm các cột mới
            $table->string('username', 50)->unique()->after('id');
            $table->string('full_name', 100)->nullable()->after('username');
            $table->string('avatar_url')->nullable()->after('email'); // hoặc sau password
            // Soft deletes
            $table->softDeletes()->after('updated_at');

            // Index cho username (unique đã tạo, nhưng vẫn có thể thêm index)
            $table->index('username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'full_name', 'avatar_url']);
            $table->dropSoftDeletes();
            // Xóa index nếu có
            $table->dropIndex(['username']);
        });
    }
};
