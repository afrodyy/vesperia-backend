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
        Schema::table('form_fields', function (Blueprint $table) {
            $table->index('form_id');
        });

        Schema::table('form_field_options', function (Blueprint $table) {
            $table->index('form_field_id');
        });

        Schema::table('form_submission_answers', function (Blueprint $table) {
            $table->index('form_submission_id');
            $table->index('form_field_id');
        });

        Schema::table('form_submissions', function (Blueprint $table) {
            $table->index('form_id');
            $table->index('user_identifier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_fields', function (Blueprint $table) {
            $table->dropIndex('form_id');
        });

        Schema::table('form_field_options', function (Blueprint $table) {
            $table->dropIndex('form_field_id');
        });

        Schema::table('form_submission_answers', function (Blueprint $table) {
            $table->dropIndex('form_submission_id');
            $table->dropIndex('form_field_id');
        });

        Schema::table('form_submissions', function (Blueprint $table) {
            $table->dropIndex('form_id');
            $table->dropIndex('user_identifier');
        });
    }
};
