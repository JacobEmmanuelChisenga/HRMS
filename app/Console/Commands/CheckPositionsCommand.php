<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Position;
use App\Models\Department;

class CheckPositionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'positions:check {--fix : Fix positions without department_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check positions and their department_id values';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking positions in database...');
        $this->newLine();

        // Get all positions
        $positions = Position::with('department')->get();
        
        if ($positions->isEmpty()) {
            $this->warn('No positions found in database!');
            return 0;
        }

        $this->info("Total positions found: {$positions->count()}");
        $this->newLine();

        $withoutDept = [];
        $withDept = [];

        foreach ($positions as $position) {
            if ($position->department_id === null) {
                $withoutDept[] = $position;
            } else {
                $withDept[] = $position;
            }
        }

        // Display positions with department_id
        if (!empty($withDept)) {
            $this->info('✓ Positions WITH department_id:');
            $this->table(
                ['ID', 'Title', 'Department ID', 'Department Name', 'Company ID'],
                collect($withDept)->map(function ($pos) {
                    return [
                        $pos->id,
                        $pos->title,
                        $pos->department_id,
                        $pos->department ? $pos->department->name : 'N/A',
                        $pos->company_id,
                    ];
                })->toArray()
            );
            $this->newLine();
        }

        // Display positions without department_id
        if (!empty($withoutDept)) {
            $this->warn('✗ Positions WITHOUT department_id:');
            $this->table(
                ['ID', 'Title', 'Company ID', 'Status'],
                collect($withoutDept)->map(function ($pos) {
                    return [
                        $pos->id,
                        $pos->title,
                        $pos->company_id,
                        $pos->status,
                    ];
                })->toArray()
            );
            $this->newLine();

            if ($this->option('fix')) {
                $this->info('Attempting to fix positions...');
                
                // Get all departments grouped by company
                $departmentsByCompany = Department::all()->groupBy('company_id');
                
                foreach ($withoutDept as $position) {
                    $companyDepartments = $departmentsByCompany->get($position->company_id);
                    
                    if ($companyDepartments && $companyDepartments->isNotEmpty()) {
                        // Assign to first department of the company
                        $firstDept = $companyDepartments->first();
                        $position->department_id = $firstDept->id;
                        $position->save();
                        $this->line("  ✓ Fixed: {$position->title} → Assigned to {$firstDept->name}");
                    } else {
                        $this->error("  ✗ Cannot fix: {$position->title} - No departments found for company {$position->company_id}");
                    }
                }
                
                $this->newLine();
                $this->info('Fix complete!');
            } else {
                $this->comment('Tip: Run with --fix flag to automatically assign positions to departments');
            }
        }

        return 0;
    }
}
