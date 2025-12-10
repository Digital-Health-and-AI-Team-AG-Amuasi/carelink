<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Condition;
use App\Models\Drug;
use App\Models\Medication;
use App\Models\Patient;
use App\Models\Permission;
use App\Models\Pregnancy;
use App\Models\Role;
use App\Models\User;
use App\Models\Visit;
use App\Models\VitalType;
use App\Repositories\Auth\PermissionsRepository;
use App\Repositories\Auth\RolesRepository;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class LocalDevelopmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @param PermissionsRepository $permissionsRepository
     * @param RolesRepository $rolesRepository
     */
    public function run(PermissionsRepository $permissionsRepository, RolesRepository $rolesRepository): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            User::factory()->make(['password' => Hash::make('password')])->except(['email']),
        );

        $user = User::firstOrCreate(
            ['email' => 'johnson.appiah@care.com'],
            User::factory()->make(['password' => Hash::make('careEMRpasscode')])->except(['email']),
        );

        Artisan::call('app:create-non-existing-permissions');

        $role = Role::findOrCreate('admin', 'web');
        $permissionsRepository->givePermissionsToRole(role: $role, permissions: Permission::all());

        $rolesRepository->assignRoleToUser(user: $user, role: $role);

        // Create vital types
        VitalType::factory()->count(10)->create();

        // Create drugs
        $drugNames = [
            'Folic Acid',
            'Ferrous Sulfate',
            'Metformin',
            'Insulin',
            'Calcium Carbonate',
        ];

        foreach ($drugNames as $name) {
            Drug::create(['name' => $name]);
        }

        // Create patients
        Patient::factory()->count(10)->create();

        // Create pregnancies
        Pregnancy::factory()->count(4)->create();

        // Create visits
        Visit::factory()->count(10)->create();

        // Create medications
        Medication::factory()->count(10)->create();

        // Create conditions
        Condition::factory()->count(10)->create();

    }
}
