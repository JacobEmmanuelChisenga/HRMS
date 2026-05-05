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
            // Login Page Customization
            $table->string('login_page_title')->nullable()->after('landing_page_secondary_cta_link');
            $table->text('login_page_subtitle')->nullable()->after('login_page_title');
            $table->string('login_page_image')->nullable()->after('login_page_subtitle');
            
            // Registration Page Customization
            $table->string('registration_page_title')->nullable()->after('login_page_image');
            $table->text('registration_page_subtitle')->nullable()->after('registration_page_title');
            $table->string('registration_page_image')->nullable()->after('registration_page_subtitle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'login_page_title',
                'login_page_subtitle',
                'login_page_image',
                'registration_page_title',
                'registration_page_subtitle',
                'registration_page_image',
            ]);
        });
    }
};
