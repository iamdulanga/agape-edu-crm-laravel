<?php

namespace Database\Seeders;

use App\Models\Lead;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeadsSeeder extends Seeder
{
    public function run(): void
    {
        $leads = [
            [
                'first_name' => 'Ubaldo',
                'last_name' => 'Littel',
                'email' => 'hartmann.madisyn@example.net',
                'age' => 35,
                'city' => 'Boyerport',
                'phone' => '520.651.0745',
                'status' => 'new',
                'priority' => 'low',
                'study_level' => 'master',
                'passport' => 'yes',
                'inquiry_date' => now()->subDays(5),
                'preferred_universities' => 'Harvard University, Stanford University',
                'special_notes' => 'Looking for MBA programs'
            ],
            [
                'first_name' => 'Rosalyn',
                'last_name' => 'Champlin',
                'email' => 'afadel@example.net',
                'age' => 29,
                'city' => 'Dietrichfort',
                'phone' => '+1.304.571.8545',
                'status' => 'new',
                'priority' => 'medium',
                'study_level' => 'bachelor',
                'passport' => 'no',
                'inquiry_date' => now()->subDays(1),
                'preferred_universities' => 'University of California, MIT',
                'special_notes' => 'Needs scholarship information'
            ],
            [
                'first_name' => 'Lia',
                'last_name' => 'Hartmann',
                'email' => 'nico.rice@example.org',
                'age' => 35,
                'city' => 'East Kylee',
                'phone' => '+1-830-483-4594',
                'status' => 'contacted',
                'priority' => 'high',
                'study_level' => 'phd',
                'passport' => 'yes',
                'inquiry_date' => now()->subDays(7),
                'preferred_universities' => 'Cambridge University, Oxford University',
                'special_notes' => 'Research focused, needs supervisor contact'
            ],
            [
                'first_name' => 'Osvaldo',
                'last_name' => 'Schmeler',
                'email' => 'qfisher@example.org',
                'age' => 22,
                'city' => 'Feilstad',
                'phone' => '463-369-8332',
                'status' => 'qualified',
                'priority' => 'high',
                'study_level' => 'bachelor',
                'passport' => 'yes',
                'inquiry_date' => now()->subDays(10),
                'preferred_universities' => 'University of Toronto, McGill University',
                'special_notes' => 'Ready to apply, needs application assistance'
            ],
            [
                'first_name' => 'Jeremy',
                'last_name' => 'Gutkowski',
                'email' => 'ichamplin@example.com',
                'age' => 22,
                'city' => 'Strackefort',
                'phone' => '+1 (484) 946-6357',
                'status' => 'new',
                'priority' => 'very_high',
                'study_level' => 'foundation',
                'passport' => 'no',
                'inquiry_date' => now()->subDays(3),
                'preferred_universities' => 'Monash University, University of Sydney',
                'special_notes' => 'Urgent - needs passport application help'
            ],
            [
                'first_name' => 'Marcelle',
                'last_name' => 'Jones',
                'email' => 'julia00@example.net',
                'age' => 26,
                'city' => 'Lilianatown',
                'phone' => '+1-737-689-8305',
                'status' => 'converted',
                'priority' => 'low',
                'study_level' => 'master',
                'passport' => 'yes',
                'inquiry_date' => now()->subDays(30),
                'preferred_universities' => 'University of Melbourne, Australian National University',
                'special_notes' => 'Successfully enrolled in Master of Data Science'
            ],
            [
                'first_name' => 'Wilma',
                'last_name' => 'Medhurst',
                'email' => 'milan43@example.net',
                'age' => 34,
                'city' => 'Bertramfurt',
                'phone' => '531-294-5201',
                'status' => 'rejected',
                'priority' => 'high',
                'study_level' => 'diploma',
                'passport' => 'yes',
                'inquiry_date' => now()->subDays(15),
                'preferred_universities' => 'University of British Columbia, University of Alberta',
                'special_notes' => 'Did not meet entry requirements'
            ],
            [
                'first_name' => 'Gay',
                'last_name' => 'Runolfsson',
                'email' => 'krippin@example.org',
                'age' => 33,
                'city' => 'North Cleora',
                'phone' => '(820) 500-4418',
                'status' => 'rejected',
                'priority' => 'very_low',
                'study_level' => 'phd',
                'passport' => 'yes',
                'inquiry_date' => now()->subDays(20),
                'preferred_universities' => 'ETH Zurich, University of Zurich',
                'special_notes' => 'Funding not secured'
            ],
            [
                'first_name' => 'Mariana',
                'last_name' => 'Hammes',
                'email' => 'camron.effertz@example.com',
                'age' => 28,
                'city' => 'West Adaside',
                'phone' => '+1-929-505-5083',
                'status' => 'qualified',
                'priority' => 'very_low',
                'study_level' => 'bachelor',
                'passport' => 'yes',
                'inquiry_date' => now()->subDays(8),
                'preferred_universities' => 'National University of Singapore, Nanyang Technological University',
                'special_notes' => 'Waiting for offer letter'
            ],
            [
                'first_name' => 'Shaina',
                'last_name' => 'Hammes',
                'email' => 'kale17@example.org',
                'age' => 30,
                'city' => 'Brennanview',
                'phone' => '1-541-704-0446',
                'status' => 'qualified',
                'priority' => 'low',
                'study_level' => 'master',
                'passport' => 'yes',
                'inquiry_date' => now()->subDays(12),
                'preferred_universities' => 'University of Hong Kong, Chinese University of Hong Kong',
                'special_notes' => 'Interview scheduled next week'
            ]
        ];

        foreach ($leads as $leadData) {
            Lead::create($leadData);
        }

        $this->command->info('✅ 11 test leads created successfully!');
    }
}