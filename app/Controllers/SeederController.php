<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Database\Migration;

class SeederController extends Controller
{
    public function index()
    {
        $migrations = \Config\Services::migrations();

        try {
            $migrations->latest(); // Runs all new migrations
            echo "Migrations completed successfully.\n";
            // Load the database seeder service
            $seeder = \Config\Database::seeder();

            try {
                // Call individual seeders
                $seeder->call('MasterSeeder');
                return 'Database seeding completed successfully.';
            } catch (\Throwable $th) {
                echo "Seed failed: " . $th->getMessage();
                //throw $th;
            }
        } catch (\Throwable $e) {
            // Handle migration errors
            echo "Migration failed: " . $e->getMessage();
            return;
        }
    }
}
