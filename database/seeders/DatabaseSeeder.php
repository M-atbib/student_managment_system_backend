<?php
namespace Database\Seeders;

use App\Models\Etablissement;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer les permissions
        $permissions = [
            'manage students',
            'view students',
            'manage etablissement',
            'view etablissement',
            'view groups',
            'manage groups',
            'view payment',
            'manage payment',
            'view timetable',
            'manage timetable',
            'manage annonce',
            'view remarque',
            'manage remarque',
            'manage document',
            'manage presence',
            'view dashboardinfo',
            //student 
            'manage upload',
            'view annonce',
            'view myinfo',
        ];

        foreach ($permissions as $permission) {
            if (Permission::where('name', $permission)->doesntExist()) {
                Permission::create(['name' => $permission]);
            }
        }

        // Créer les rôles et leur assigner des permissions
        $owner = Role::create(['name' => 'owner']);
        $owner->givePermissionTo([
            'manage students', 
            'view students', 
            'view etablissement',
            'manage etablissement',
            'view groups',
            'manage groups',
            'view payment',
            'manage payment',
            'view timetable',
            'manage timetable',
            'manage annonce',
            'view remarque',
            'manage remarque',
            'manage document',
            'manage presence',
            'manage upload',
            'view dashboardinfo'
        ]);

        $coiffure = Role::create(['name' => 'coiffure']);
        $coiffure->givePermissionTo([
            'manage students', 
            'view students', 
            'view etablissement',
            'view groups',
            'manage groups',
            'view payment',
            'manage payment',
            'view timetable',
            'manage timetable',
            'manage annonce',
            'view remarque',
            'manage remarque',
            'manage upload',
            'manage document',
            'manage presence'
        ]);

        $esthetique = Role::create(['name' => 'esthetique']);
        $esthetique->givePermissionTo([
            'manage students', 
            'view students', 
            'view etablissement',
            'view groups',
            'manage groups',
            'view payment',
            'manage payment',
            'view timetable',
            'manage timetable',
            'manage annonce',
            'view remarque',
            'manage remarque',
            'manage upload',
            'manage document',
            'manage presence'
        ]);

        $studentRole = Role::create(['name' => 'student']);
        $studentRole->givePermissionTo([
            'view myinfo', 'view annonce',
        ]);

        // Créer les établissements et leurs groupes
        $etablissements = [
            'Elhouria Coiffure',
            'Elhouria Esthetique',
            'Group Elhouria Esthetique Gueliz',
            'Atlas Academy Coiffure Gueliz',
        ];

        foreach ($etablissements as $etablissementName) {
            DB::transaction(function () use ($etablissementName) {
                $etablissement = Etablissement::create([
                    'uuid' => (string) Str::uuid(),
                    'branch_name' => $etablissementName,
                    'branch_logo' => 'vide.png',
                ]);

                for ($i = 1; $i <= 3; $i++) {
                    Group::create([
                        'uuid' => (string) Str::uuid(),
                        'name' => 'Group ' . $i,
                        'etab_uuid' => $etablissement->uuid,
                    ]);
                }
            });
        }

        // Assign roles to specific users and link them to an etablissement (branch)
        $users = [
            [
                'name' => 'Atbib Larbi', 
                'email' => 'atbiblarbi@groupeelhourria.com', 
                'role' => 'owner', 
                'branch' => 'Elhouria Coiffure',
                'password' => 'atbiblarbi123',

            ], 
            [
                'name' => 'El mallouki Atiqa', 
                'email' => 'elmalloukiatiqa@groupeelhourria.com', 
                'role' => 'coiffure', 
                'branch' => 'Atlas Academy Coiffure Gueliz',
                'password' => 'elmalloukiatiq123',
            ],
            [
                'name' => 'Hadda ali Nadia', 
                'email' => 'haddaalinadia@groupeelhourria.com', 
                'role' => 'coiffure', 
                'branch' => 'Elhouria Coiffure',
                'password' => 'haddaalinadia123',
            ],
            [
                'name' => 'Ben tebaa Atika', 
                'email' => 'bentebaaatika@groupeelhourria.com', 
                'role' => 'esthetique', 
                'branch' => 'Elhouria Esthetique',
                'password' => 'bentebaaatika123',

            ],
            [
                'name' => 'Mach zohra', 
                'email' => 'machzohra@groupeelhourria.com', 
                'role' => 'esthetique', 
                'branch' => 'Group Elhouria Esthetique Gueliz',
                'password' => 'bentebaaatika123',
            ],
        ];

        foreach ($users as $userData) {
            DB::transaction(function () use ($userData) {
                // Fetch etablissement UUID for branch
                $etablissement = Etablissement::where('branch_name', $userData['branch'])->first();
                
                $user = User::create([
                    'uuid' => (string) Str::uuid(),
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password']),
                    'branch_uuid' => $etablissement->uuid,
                ]);

                // Assign role to user
                $user->assignRole($userData['role']);
            });
        }
    }
}
