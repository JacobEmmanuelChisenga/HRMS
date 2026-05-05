<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Remove duplicate Auditor and IT Admin roles, keeping only the first occurrence.
     */
    public function up(): void
    {
        // Find and remove duplicate roles (keep the one with the lowest ID)
        $duplicateSlugs = ['auditor', 'it_admin'];
        
        foreach ($duplicateSlugs as $slug) {
            $roles = DB::table('roles')
                ->where('slug', $slug)
                ->orderBy('id')
                ->get();
            
            // If there are duplicates, keep the first one and delete the rest
            if ($roles->count() > 1) {
                $keepId = $roles->first()->id;
                $deleteIds = $roles->skip(1)->pluck('id')->toArray();
                
                if (!empty($deleteIds)) {
                    DB::table('roles')->whereIn('id', $deleteIds)->delete();
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot reverse this operation safely
        // If needed, re-run the add migration
    }
};
