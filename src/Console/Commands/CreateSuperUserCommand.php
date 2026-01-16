<?php

namespace HasinHayder\TyroDashboard\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use HasinHayder\Tyro\Models\Role;
use HasinHayder\Tyro\Support\PasswordRules;

class CreateSuperUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tyro-dashboard:createsuperuser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a superuser with admin privileges';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Create a new superuser');

        $userModel = config('tyro-dashboard.user_model', config('tyro.models.user', 'App\\Models\\User'));

        if (!class_exists($userModel)) {
            $this->error("User model [{$userModel}] not found.");
            return self::FAILURE;
        }

        $name = $this->ask('Name');
        $email = $this->ask('Email Address');

        // Check if email already exists
        if ($userModel::where('email', $email)->exists()) {
            $this->error('User with this email already exists.');
            return self::FAILURE;
        }

        while (true) {
            $password = $this->secret('Password');
            $confirmPassword = $this->secret('Password (again)');

            if ($password !== $confirmPassword) {
                $this->error('Passwords do not match. Please try again.');
                continue;
            }

            $validator = Validator::make(['password' => $password], [
                'password' => PasswordRules::get(['name' => $name, 'email' => $email]),
            ]);

            if ($validator->fails()) {
                $this->warn('Password is considered weak:');
                foreach ($validator->errors()->all() as $error) {
                    $this->warn(" - {$error}");
                }

                if ($this->confirm('Do you want to use this weak password anyway?', false)) {
                    break;
                }
            } else {
                break;
            }
        }

        // Create User
        $this->info('Creating user...');
        
        try {
            $user = $userModel::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
            ]);
        } catch (\Exception $e) {
            $this->error('Failed to create user: ' . $e->getMessage());
            return self::FAILURE;
        }

        // Assign Role
        $this->info('Assigning admin role...');
        
        $adminRoles = config('tyro-dashboard.admin_roles', ['super-admin', 'admin']);
        $roleAssigned = false;

        foreach ($adminRoles as $roleSlug) {
            $role = Role::where('slug', $roleSlug)->first();
            if ($role) {
                $user->roles()->attach($role->id);
                $this->info("Assigned role: {$role->name} ({$roleSlug})");
                $roleAssigned = true;
                break; // Assign only the first matching high-privilege role
            }
        }

        if (!$roleAssigned) {
            $this->warn('No admin/super-admin role found in the system.');
            $this->warn('User created but has no administrative privileges.');
            $this->info('Available roles: ' . Role::pluck('slug')->implode(', '));
            
            if ($this->confirm('Would you like to assign an existing role?', true)) {
                 $roleSlug = $this->choice('Select role', Role::pluck('slug')->toArray());
                 $role = Role::where('slug', $roleSlug)->first();
                 $user->roles()->attach($role->id);
                 $this->info("Assigned role: {$role->name}");
            }
        }

        $this->info('Superuser created successfully.');
        return self::SUCCESS;
    }
}
