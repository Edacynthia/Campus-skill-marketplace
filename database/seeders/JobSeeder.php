<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Job;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create sample users for job postings
        $drChima = User::firstOrCreate([
            'email' => 'dr.chima@university.edu.ng'
        ], [
            'first_name' => 'Chima',
            'last_name' => 'Okonkwo',
            'password' => bcrypt('password'),
            'role' => 'faculty',
            'department' => 'Mathematics & Statistics'
        ]);

        $johnOkafor = User::firstOrCreate([
            'email' => 'john.o@university.edu.ng'
        ], [
            'first_name' => 'John',
            'last_name' => 'Okafor',
            'password' => bcrypt('password'),
            'role' => 'student',
            'department' => 'Law'
        ]);

        $profOkafor = User::firstOrCreate([
            'email' => 'prof.okafor@university.edu.ng'
        ], [
            'first_name' => 'Peter',
            'last_name' => 'Okafor',
            'password' => bcrypt('password'),
            'role' => 'faculty',
            'department' => 'Environmental Science'
        ]);

        $mrBull = User::firstOrCreate([
            'email' => 'mr.bull@university.edu.ng'
        ], [
            'first_name' => 'Michael',
            'last_name' => 'Bull',
            'password' => bcrypt('password'),
            'role' => 'staff',
            'department' => 'Student Union'
        ]);

        $jobs = [
            [
                'employer_id' => $drChima->id,
                'title' => 'Need help with Calculus II assignments & exam prep',
                'description' => 'Seeking a high-performing student to assist with weekly assignments and final exam review sessions for Calculus II. Must have strong mathematical background and excellent communication skills.',
                'category' => 'Academic Help',
                'type' => 'on_campus',
                'urgency' => 'urgent',
                'salary' => 15000.00,
                'salary_type' => 'fixed',
                'location' => 'Faculty of Science Building',
                'deadline' => now()->addDays(3),
                'requirements' => ['Strong Calculus background', 'Good communication skills', 'Available 3x per week', 'Minimum GPA of 3.5'],
                'status' => 'active',
                'views_count' => 156,
                'applications_count' => 12
            ],
            [
                'employer_id' => $johnOkafor->id,
                'title' => 'Looking for a cleaner for 1 week (Off-campus hostel)',
                'description' => 'Daily cleaning of a 2-bedroom student hostel near the main gate. Supplies provided. Flexible hours. Perfect for students looking for part-time work.',
                'category' => 'Domestic',
                'type' => 'off_campus',
                'urgency' => 'normal',
                'salary' => 5000.00,
                'salary_type' => 'fixed',
                'location' => 'Student Hostel, Main Gate Area',
                'deadline' => now()->addDays(7),
                'requirements' => ['Experience in cleaning', 'Reliable and punctual', 'Available for 1 week', 'Attention to detail'],
                'status' => 'active',
                'views_count' => 89,
                'applications_count' => 8
            ],
            [
                'employer_id' => $profOkafor->id,
                'title' => 'Research Assistant for Environmental Science Project',
                'description' => 'Help with data collection, literature review, and survey analysis for ongoing environmental impact study. Great opportunity for research experience.',
                'category' => 'Research',
                'type' => 'on_campus',
                'urgency' => 'normal',
                'salary' => 40000.00,
                'salary_type' => 'monthly',
                'location' => 'Environmental Science Department',
                'deadline' => now()->addDays(14),
                'requirements' => ['Environmental Science background', 'Data analysis skills', 'Research experience preferred', 'Available 15 hours/week'],
                'status' => 'active',
                'views_count' => 234,
                'applications_count' => 18
            ],
            [
                'employer_id' => $mrBull->id,
                'title' => 'Graphic Designer for Student Union Event Posters',
                'description' => 'Create 5 promotional posters for upcoming campus events. Must be proficient in design software and able to work with tight deadlines.',
                'category' => 'Tech & Design',
                'type' => 'remote',
                'urgency' => 'urgent',
                'salary' => 15000.00,
                'salary_type' => 'fixed',
                'deadline' => now()->addDays(5),
                'requirements' => ['Proficient in Photoshop/Canva', 'Portfolio of previous work', 'Creative design skills', 'Can meet deadlines'],
                'status' => 'active',
                'views_count' => 198,
                'applications_count' => 15
            ],
            [
                'employer_id' => $drChima->id,
                'title' => 'Lab Assistant - Chemistry Department',
                'description' => 'Assist with laboratory setup, equipment maintenance, and experiment preparation for undergraduate chemistry labs.',
                'category' => 'Academic Help',
                'type' => 'on_campus',
                'urgency' => 'normal',
                'salary' => 25000.00,
                'salary_type' => 'monthly',
                'location' => 'Chemistry Laboratory',
                'deadline' => now()->addDays(10),
                'requirements' => ['Chemistry background', 'Lab safety certification', 'Attention to detail', 'Available afternoons'],
                'status' => 'active',
                'views_count' => 145,
                'applications_count' => 9
            ],
            [
                'employer_id' => $johnOkafor->id,
                'title' => 'Event Photographer for Sports Day',
                'description' => 'Capture photos during the upcoming university sports day events. Must have own camera equipment and experience with sports photography.',
                'category' => 'Events',
                'type' => 'on_campus',
                'urgency' => 'urgent',
                'salary' => 20000.00,
                'salary_type' => 'fixed',
                'location' => 'Sports Complex',
                'deadline' => now()->addDays(2),
                'requirements' => ['Professional camera', 'Sports photography experience', 'Available full day', 'Quick editing skills'],
                'status' => 'active',
                'views_count' => 167,
                'applications_count' => 11
            ],
            [
                'employer_id' => $profOkafor->id,
                'title' => 'Data Entry Clerk - Research Project',
                'description' => 'Enter survey data into spreadsheets and perform basic data validation for environmental research project. No prior experience needed.',
                'category' => 'Research',
                'type' => 'remote',
                'urgency' => 'normal',
                'salary' => 8000.00,
                'salary_type' => 'fixed',
                'deadline' => now()->addDays(21),
                'requirements' => ['Basic computer skills', 'Attention to detail', 'Reliable internet', 'Can work independently'],
                'status' => 'active',
                'views_count' => 78,
                'applications_count' => 6
            ],
            [
                'employer_id' => $mrBull->id,
                'title' => 'Campus Tour Guide for Open Day',
                'description' => 'Lead prospective students and parents on campus tours during upcoming open day. Must be knowledgeable about university facilities and programs.',
                'category' => 'Events',
                'type' => 'on_campus',
                'urgency' => 'normal',
                'salary' => 12000.00,
                'salary_type' => 'fixed',
                'location' => 'Main Campus',
                'deadline' => now()->addDays(7),
                'requirements' => ['Good communication skills', 'Knowledgeable about campus', 'Professional appearance', 'Available on weekends'],
                'status' => 'active',
                'views_count' => 134,
                'applications_count' => 10
            ]
        ];

        foreach ($jobs as $job) {
            Job::create($job);
        }
    }
}
