<?php

namespace Database\Seeders;

use App\Models\Annonce;
use App\Models\Document;
use App\Models\Etablissement;
use App\Models\Group;
use App\Models\Payment;
use App\Models\Presence;
use App\Models\Remarque;
use App\Models\Student;
use App\Models\Timetable;
use App\Models\User;
use Carbon\Carbon;
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
            Permission::create(['name' => $permission]);
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
            'view myinfo','view annonce',
        ]);

        // Créer 5 établissements
        $etablissements = Etablissement::factory()->count(5)->create();

        $groups = collect();
        foreach ($etablissements as $etablissement) {
            for ($i = 1; $i <= 3; $i++) {
                $groups->push(Group::create([
                    'uuid' => (string) Str::uuid(),
                    'name' => 'Group ' . $i,
                    'etab_uuid' => $etablissement->uuid
                ]));
            }

            // Ajouter deux annonces pour chaque établissement
            for ($j = 1; $j <= 2; $j++) {
                Annonce::create([
                    'uuid' => (string) Str::uuid(),
                    'text' => 'Annonce ' . $j . ' for ' . $etablissement->name,
                    'etab_uuid' => $etablissement->uuid,
                    'sector' => rand(0, 1) ? 'coiffure' : 'esthetique',
                    'date_validite' => now()->addMonths(rand(1, 12)),
                ]);
            }
        }

        // Créer les horaires pour chaque groupe
        foreach ($groups as $group) {
            Timetable::create([
                'uuid' => (string) Str::uuid(),
                'title' => 'Timetable for ' . $group->name,
                'group_uuid' => $group->uuid,
                'name_file' => 'timetable_' . $group->uuid . '.pdf'
            ]);
        }

        // Créer 3 utilisateurs avec les rôles owner, coiffure, et esthetique
        $roles = ['owner', 'coiffure', 'esthetique'];
        foreach ($roles as $role) {
            DB::transaction(function () use ($role) {
                $user = User::factory()->create();
                $user->assignRole($role);
                if ($role == 'owner') {
                    $user->branch_uuid = null;
                }else{
                    $user->branch_uuid = Etablissement::inRandomOrder()->first()->uuid;
                }
                $user->save();

            });
        }

        // Créer 100 étudiants avec le rôle 'student' et assigner à des groupes aléatoires
        $groups = Group::all();
        foreach (range(1, 100) as $index) {
            $group = $groups->random();
            $password = 'password12345';

            // Generate the inscription number
            $currentYear = Carbon::now()->year;
            $uuid = (string) Str::uuid();
            $inscription_number = $uuid . '/' . $currentYear;

            $student = Student::create([
                'uuid' => (string) Str::uuid(),
                'inscription_number' => $inscription_number,
                'CIN' => 'CIN-' . Str::random(5),
                'id_massar' => 'IDM-' . Str::random(5),
                'full_name' => 'Student ' . $index,
                'birth_date' => now()->subYears(rand(18, 25)),
                'birth_place' => 'Place ' . rand(1, 100),
                'gender' => rand(0, 1) ? 'Male' : 'Female',
                'school_level' => 'Level ' . rand(1, 5),
                'address' => 'Address ' . rand(1, 100),
                'phone_number' => '06' . rand(10000000, 99999999),
                'email' => 'student' . $index . '@example.com',
                'password' => Hash::make($password),
                'plain_password' => $password,
                'responsable' => json_encode([
                    [
                        'nom' => 'Responsable ' . $index,
                        'nature' => rand(0, 1) ? 'mere' : 'pere',
                        'num' => '06' . rand(10000000, 99999999)
                    ],
                    [
                        'nom' => 'Responsable ' . $index,
                        'nature' => rand(0, 1) ? 'mere' : 'pere',
                        'num' => '06' . rand(10000000, 99999999)
                    ]
                ]),
                'photo' => 'photo' . $index . '.jpg',
                'training_duration' => 'Duration ' . rand(1, 3) . ' years',
                'sector' => rand(0, 1) ? 'coiffure' : 'esthetique',
                'filières_formation' => 'Formation ' . rand(1, 3),
                'training_level' => 'Level ' . rand(1, 5),
                'group_uuid' => $group->uuid,
                'monthly_amount' => rand(1000, 2000),
                'registration_fee' => rand(100, 500),
                'product' => rand(500, 2000),
                'frais_diplôme' => rand(200, 500),
                'annual_amount' => rand(5000, 10000),
                'status' => rand(0, 1) ? 'active' : 'archive',
                'date_start_at' => now(),
                'date_fin_at' => now()->addYears(2),
            ]);

            $student->assignRole('student');

            // Ajouter une remarque pour chaque étudiant
            Remarque::create([
                'uuid' => (string) Str::uuid(),
                'text' => 'Remarque for ' . $student->full_name,
                'student_uuid' => $student->uuid,
            ]);

            // Ajouter un document pour chaque étudiant
            Document::create([
                'uuid' => (string) Str::uuid(),
                'name_file' => 'Document_' . $student->full_name . '.pdf',
                'student_uuid' => $student->uuid,
            ]);
        }
        
        
        
        // Créer les paiements pour les étudiants
        $students = Student::all();
        $types = ['mensualite', 'inscription', 'assurance', 'produit', 'diplome'];
        $methode = ['espece', 'cheque', 'virement'];

        foreach ($students as $student) {
            for ($i = 1; $i <= 10; $i++) {
                Payment::create([
                    'uuid' => (string) Str::uuid(),
                    'student_uuid' => $student->uuid,
                    'type' => $types[array_rand($types)],
                    'methode' => $types[array_rand($methode)],
                    'montant' => rand(100, 1000),
                    'date_payment' => now()->timezone('Africa/Casablanca')->subDays(rand(0, 365))->format('Y-m-d'),
                ]);
            }
        }
        foreach ($students as $student) {
            Presence::create([
                'uuid' => (string) Str::uuid(),
                'title' => 'Presence ' . $index,
                'type' => 'attendance',
                'justification' => rand(0, 1) ? true : false,
                'remarque' => 'Remarque for ' . $student->full_name,
                'date' => now()->subDays(rand(0, 30))->format('Y-m-d'),
                'student_uuid' => $student->uuid
            ]);
        }
    }
}
