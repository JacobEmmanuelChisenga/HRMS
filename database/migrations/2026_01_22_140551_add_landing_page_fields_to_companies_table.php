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
        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('landing_page_enabled')->default(false)->after('status');
            $table->string('landing_page_title')->nullable()->after('landing_page_enabled');
            $table->text('landing_page_content')->nullable()->after('landing_page_title');
            $table->string('landing_page_image')->nullable()->after('landing_page_content');
            $table->string('landing_page_primary_cta_text')->nullable()->after('landing_page_image');
            $table->string('landing_page_primary_cta_link')->nullable()->after('landing_page_primary_cta_text');
            $table->string('landing_page_secondary_cta_text')->nullable()->after('landing_page_primary_cta_link');
            $table->string('landing_page_secondary_cta_link')->nullable()->after('landing_page_secondary_cta_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'landing_page_enabled',
                'landing_page_title',
                'landing_page_content',
                'landing_page_image',
                'landing_page_primary_cta_text',
                'landing_page_primary_cta_link',
                'landing_page_secondary_cta_text',
                'landing_page_secondary_cta_link',
            ]);
        });
    }
};
