<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create a sample user for skills
        $user = User::firstOrCreate([
            'email' => 'faith.o@university.edu.ng'
        ], [
            'first_name' => 'Faith',
            'last_name' => 'Okonkwo',
            'password' => bcrypt('password'),
            'role' => 'student',
            'department' => 'English & Literature'
        ]);

        $skills = [
            [
                'user_id' => $user->id,
                'title' => 'Assignment Proofreading',
                'description' => 'Professional proofreading and editing services for academic assignments, essays, and research papers.',
                'category' => 'Academic',
                'price' => 1000.00,
                'price_type' => 'per_page',
                'price_unit' => 'pg',
                'rating' => 4.9,
                'views_count' => 156,
                'orders_count' => 48
            ],
            [
                'user_id' => $user->id,
                'title' => 'Mathematics Tutoring',
                'description' => 'Expert tutoring in calculus, algebra, and statistics for university students.',
                'category' => 'Academic',
                'price' => 3000.00,
                'price_type' => 'hourly',
                'price_unit' => 'hr',
                'rating' => 4.9,
                'views_count' => 234,
                'orders_count' => 112
            ],
            [
                'user_id' => $user->id,
                'title' => 'Essay Writing Help',
                'description' => 'Guidance and support for essay structure, thesis development, and academic writing.',
                'category' => 'Academic',
                'price' => 2500.00,
                'price_type' => 'fixed',
                'rating' => 4.8,
                'views_count' => 189,
                'orders_count' => 67
            ]
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }

        // Create more sample users and skills
        $tobi = User::firstOrCreate([
            'email' => 'tobi.u@university.edu.ng'
        ], [
            'first_name' => 'Tobi',
            'last_name' => 'Uche',
            'password' => bcrypt('password'),
            'role' => 'student',
            'department' => 'Computer Science'
        ]);

        $digitalSkills = [
            [
                'user_id' => $tobi->id,
                'title' => 'Logo & Brand Identity',
                'description' => 'Custom logo design and complete brand identity packages for businesses and organizations.',
                'category' => 'Digital',
                'price' => 10000.00,
                'price_type' => 'fixed',
                'rating' => 5.0,
                'views_count' => 412,
                'orders_count' => 124
            ],
            [
                'user_id' => $tobi->id,
                'title' => 'Full-Stack Web Development',
                'description' => 'Complete web application development using modern technologies and best practices.',
                'category' => 'Digital',
                'price' => 50000.00,
                'price_type' => 'project_based',
                'rating' => 5.0,
                'views_count' => 278,
                'orders_count' => 67
            ]
        ];

        foreach ($digitalSkills as $skill) {
            Skill::create($skill);
        }

        $chiamaka = User::firstOrCreate([
            'email' => 'chiamaka.a@university.edu.ng'
        ], [
            'first_name' => 'Chiamaka',
            'last_name' => 'Adeyemi',
            'password' => bcrypt('password'),
            'role' => 'student',
            'department' => 'Mass Communication'
        ]);

        $creativeSkills = [
            [
                'user_id' => $chiamaka->id,
                'title' => 'Event Photography',
                'description' => 'Professional photography for events, portraits, and special occasions.',
                'category' => 'Creative',
                'price' => 15000.00,
                'price_type' => 'per_event',
                'price_unit' => 'event',
                'rating' => 4.8,
                'views_count' => 367,
                'orders_count' => 92
            ],
            [
                'user_id' => $chiamaka->id,
                'title' => 'Graphic Design & Flyers',
                'description' => 'Eye-catching graphic designs for flyers, posters, and promotional materials.',
                'category' => 'Creative',
                'price' => 5000.00,
                'price_type' => 'per_design',
                'rating' => 4.8,
                'views_count' => 298,
                'orders_count' => 88
            ]
        ];

        foreach ($creativeSkills as $skill) {
            Skill::create($skill);
        }

        $kunle = User::firstOrCreate([
            'email' => 'kunle.o@staff.university.edu.ng'
        ], [
            'first_name' => 'Kunle',
            'last_name' => 'Ogunleye',
            'password' => bcrypt('password'),
            'role' => 'staff',
            'department' => 'Electrical Engineering'
        ]);

        $repairSkills = [
            [
                'user_id' => $kunle->id,
                'title' => 'Laptop Repair & Maintenance',
                'description' => 'Professional laptop repair, maintenance, and troubleshooting services.',
                'category' => 'Repairs',
                'price' => 5000.00,
                'price_type' => 'diagnosis_fee',
                'rating' => 4.7,
                'views_count' => 523,
                'orders_count' => 156
            ]
        ];

        foreach ($repairSkills as $skill) {
            Skill::create($skill);
        }

        $adaeze = User::firstOrCreate([
            'email' => 'adaeze.b@university.edu.ng'
        ], [
            'first_name' => 'Adaeze',
            'last_name' => 'Bello',
            'password' => bcrypt('password'),
            'role' => 'student',
            'department' => 'Fashion Design'
        ]);

        $beautySkills = [
            [
                'user_id' => $adaeze->id,
                'title' => 'Professional Hair Braiding',
                'description' => 'Expert hair braiding services including various styles and techniques.',
                'category' => 'Beauty & Style',
                'price' => 8000.00,
                'price_type' => 'fixed',
                'rating' => 4.9,
                'views_count' => 645,
                'orders_count' => 203
            ]
        ];

        foreach ($beautySkills as $skill) {
            Skill::create($skill);
        }
    }
}
