<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create 
                            {--name= : The name of the admin user}
                            {--email= : The email of the admin user}
                            {--password= : The password for the admin user}
                            {--force : Force creation without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating a new admin user...');
        $this->newLine();

        // Get user input
        $name = $this->option('name') ?: $this->ask('Admin name');
        $email = $this->option('email') ?: $this->ask('Admin email');
        $password = $this->option('password') ?: $this->secret('Admin password');

        // Validate input
        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            $this->error('Validation failed:');
            foreach ($validator->errors()->all() as $error) {
                $this->error('- ' . $error);
            }
            return Command::FAILURE;
        }

        // Show summary
        $this->newLine();
        $this->info('Admin User Details:');
        $this->table(['Field', 'Value'], [
            ['Name', $name],
            ['Email', $email],
            ['Password', str_repeat('*', strlen($password))],
        ]);
        $this->newLine();

        // Confirm creation
        if (!$this->option('force') && !$this->confirm('Create this admin user?')) {
            $this->info('Admin user creation cancelled.');
            return Command::SUCCESS;
        }

        try {
            // Create the admin user
            $admin = Admin::create([
                'name' => $name,
                'email' => $email,
                'password' => $password, // Will be hashed by the model
            ]);

            $this->newLine();
            $this->info('✅ Admin user created successfully!');
            $this->info('ID: ' . $admin->id);
            $this->info('Name: ' . $admin->name);
            $this->info('Email: ' . $admin->email);
            $this->newLine();
            $this->info('The admin can now log in at: ' . route('admin.login'));

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to create admin user: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
