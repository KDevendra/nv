<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            // Admin's own approval layer (null = pending admin review, approved, rejected)
            $table->enum('admin_status', ['approved', 'rejected'])->nullable()->after('show_on_website');
            $table->text('admin_note')->nullable()->after('admin_status');
            $table->timestamp('admin_actioned_at')->nullable()->after('admin_note');
            $table->unsignedBigInteger('admin_actioned_by')->nullable()->after('admin_actioned_at');

            $table->index('admin_status');
            $table->foreign('admin_actioned_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('property_entries', function (Blueprint $table) {
            $table->dropForeign(['admin_actioned_by']);
            $table->dropIndex(['admin_status']);
            $table->dropColumn(['admin_status', 'admin_note', 'admin_actioned_at', 'admin_actioned_by']);
        });
    }
};
