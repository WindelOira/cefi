<?php

use App\Category;
use App\User;
use App\Patient;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('admin'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Student',
            'email' => 'student@test.com',
            'password' => Hash::make('student'),
            'role' => 'student'
        ]);

        User::create([
            'name' => 'Employee',
            'email' => 'employee@test.com',
            'password' => Hash::make('employee'),
            'role' => 'employee'
        ]);

        Category::create([
            'id'        => 1, 
            'parent'    => NULL, 
            'type'      => 'medical', 
            'name'      => 'Immunization',
        ]);
        Category::create([
            'id'        => 2, 
            'parent'    => 1, 
            'type'      => 'medical', 
            'name'      => 'BCG',
        ]);
        Category::create([
            'id'        => 3, 
            'parent'    => 1, 
            'type'      => 'medical', 
            'name'      => 'OPV 1',
        ]);
        Category::create([
            'id'        => 4, 
            'parent'    => 1, 
            'type'      => 'medical', 
            'name'      => 'OPV 2',
        ]);
        Category::create([
            'id'        => 5, 
            'parent'    => 1, 
            'type'      => 'medical', 
            'name'      => 'OPV 3',
        ]);
        Category::create([
            'id'        => 6, 
            'parent'    => 1, 
            'type'      => 'medical', 
            'name'      => 'DPT 1',
        ]);
        Category::create([
            'id'        => 7, 
            'parent'    => 1, 
            'type'      => 'medical', 
            'name'      => 'DPT 2',
        ]);
        Category::create([
            'id'        => 8, 
            'parent'    => 1, 
            'type'      => 'medical', 
            'name'      => 'DPT 3',
        ]);
        Category::create([
            'id'        => 9, 
            'parent'    => 1, 
            'type'      => 'medical', 
            'name'      => 'Measles',
        ]);
        Category::create([
            'id'        => 10, 
            'parent'    => 1, 
            'type'      => 'medical', 
            'name'      => 'HEPA-B',
        ]);

        Category::create([
            'id'        => 11, 
            'parent'    => NULL, 
            'type'      => 'medical', 
            'name'      => 'Family History',
        ]);
        Category::create([
            'id'        => 12, 
            'parent'    => 11, 
            'type'      => 'medical', 
            'name'      => 'Cardiac Disease',
        ]);
        Category::create([
            'id'        => 13, 
            'parent'    => 11, 
            'type'      => 'medical', 
            'name'      => 'Thyroid Disease',
        ]);
        Category::create([
            'id'        => 14, 
            'parent'    => 11, 
            'type'      => 'medical', 
            'name'      => 'Diabetes',
        ]);
        Category::create([
            'id'        => 15, 
            'parent'    => 11, 
            'type'      => 'medical', 
            'name'      => 'Hypertension',
        ]);
        Category::create([
            'id'        => 16, 
            'parent'    => 11, 
            'type'      => 'medical', 
            'name'      => 'Tuberculosis',
        ]);
        Category::create([
            'id'        => 17, 
            'parent'    => 11, 
            'type'      => 'medical', 
            'name'      => 'Asthma',
        ]);
        Category::create([
            'id'        => 18, 
            'parent'    => 11, 
            'type'      => 'medical', 
            'name'      => 'Kidney Disease',
        ]);
        Category::create([
            'id'        => 19, 
            'parent'    => 11, 
            'type'      => 'medical', 
            'name'      => 'Cancer',
        ]);
        Category::create([
            'id'        => 20, 
            'parent'    => 11, 
            'type'      => 'medical', 
            'name'      => 'Skin Disease',
        ]);

        Category::create([
            'id'        => 21, 
            'parent'    => NULL, 
            'type'      => 'medical', 
            'name'      => 'Previous Illness',
        ]);
        Category::create([
            'id'        => 22, 
            'parent'    => 21, 
            'type'      => 'medical', 
            'name'      => 'Asthma',
        ]);
        Category::create([
            'id'        => 23, 
            'parent'    => 21, 
            'type'      => 'medical', 
            'name'      => 'Chicken Pox',
        ]);
        Category::create([
            'id'        => 24, 
            'parent'    => 21, 
            'type'      => 'medical', 
            'name'      => 'Measles',
        ]);
        Category::create([
            'id'        => 25, 
            'parent'    => 21, 
            'type'      => 'medical', 
            'name'      => 'Mumps',
        ]);
        Category::create([
            'id'        => 26, 
            'parent'    => 21, 
            'type'      => 'medical', 
            'name'      => 'Peptic Ulcer',
        ]);
        Category::create([
            'id'        => 27, 
            'parent'    => 21, 
            'type'      => 'medical', 
            'name'      => 'Dengue',
        ]);
        Category::create([
            'id'        => 28, 
            'parent'    => 21, 
            'type'      => 'medical', 
            'name'      => 'Head Injury',
        ]);
        Category::create([
            'id'        => 29, 
            'parent'    => 21, 
            'type'      => 'medical', 
            'name'      => 'STD',
        ]);
        Category::create([
            'id'        => 30, 
            'parent'    => 21, 
            'type'      => 'medical', 
            'name'      => 'Hypertension',
        ]);
        Category::create([
            'id'        => 31, 
            'parent'    => 21, 
            'type'      => 'medical', 
            'name'      => 'Kidney Problem',
        ]);

        Category::create([
            'id'        => 32, 
            'parent'    => NULL, 
            'type'      => 'medical', 
            'name'      => 'Personal History',
        ]);
        Category::create([
            'id'        => 33, 
            'parent'    => 32, 
            'type'      => 'medical', 
            'name'      => 'Alcohol Use',
        ]);
        Category::create([
            'id'        => 34, 
            'parent'    => 32, 
            'type'      => 'medical', 
            'name'      => 'Tobacco Use',
        ]);
        Category::create([
            'id'        => 35, 
            'parent'    => 32, 
            'type'      => 'medical', 
            'name'      => 'Allergy to Food Drug',
        ]);

        Category::create([
            'id'        => 36, 
            'parent'    => NULL, 
            'type'      => 'medical', 
            'name'      => 'Identification Mark',
        ]);
        Category::create([
            'id'        => 37, 
            'parent'    => 36, 
            'type'      => 'medical', 
            'name'      => 'Scar',
        ]);
        Category::create([
            'id'        => 38, 
            'parent'    => 36, 
            'type'      => 'medical', 
            'name'      => 'Mole',
        ]);
        Category::create([
            'id'        => 39, 
            'parent'    => 36, 
            'type'      => 'medical', 
            'name'      => 'Tattoo',
        ]);
        Category::create([
            'id'        => 40, 
            'parent'    => 36, 
            'type'      => 'medical', 
            'name'      => 'Birthmark',
        ]);

        // $this->call(UsersTableSeeder::class);
    }
}
