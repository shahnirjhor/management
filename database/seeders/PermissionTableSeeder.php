<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::firstOrCreate(['name' => 'profile-read','display_name' => 'Profile']);
        Permission::firstOrCreate(['name' => 'profile-update','display_name' => 'Profile']);

        Permission::firstOrCreate(['name' => 'company-read','display_name' => 'Company']);
        Permission::firstOrCreate(['name' => 'company-update','display_name' => 'Company']);

        Permission::firstOrCreate(['name' => 'role-read','display_name' => 'Role']);
        Permission::firstOrCreate(['name' => 'role-create','display_name' => 'Role']);
        Permission::firstOrCreate(['name' => 'role-update','display_name' => 'Role']);
        Permission::firstOrCreate(['name' => 'role-delete','display_name' => 'Role']);

        Permission::firstOrCreate(['name' => 'user-read','display_name' => 'User']);
        Permission::firstOrCreate(['name' => 'user-create','display_name' => 'User']);
        Permission::firstOrCreate(['name' => 'user-update','display_name' => 'User']);
        Permission::firstOrCreate(['name' => 'user-delete','display_name' => 'User']);
        Permission::firstOrCreate(['name' => 'user-export','display_name' => 'User']);

        Permission::firstOrCreate(['name' => 'student-read','display_name' => 'Student']);
        Permission::firstOrCreate(['name' => 'student-create','display_name' => 'Student']);
        Permission::firstOrCreate(['name' => 'student-update','display_name' => 'Student']);
        Permission::firstOrCreate(['name' => 'student-delete','display_name' => 'Student']);

        Permission::firstOrCreate(['name' => 'village-read','display_name' => 'Village']);
        Permission::firstOrCreate(['name' => 'village-create','display_name' => 'Village']);
        Permission::firstOrCreate(['name' => 'village-update','display_name' => 'Village']);
        Permission::firstOrCreate(['name' => 'village-delete','display_name' => 'Village']);

        Permission::firstOrCreate(['name' => 'class-read','display_name' => 'Class']);
        Permission::firstOrCreate(['name' => 'class-create','display_name' => 'Class']);
        Permission::firstOrCreate(['name' => 'class-update','display_name' => 'Class']);
        Permission::firstOrCreate(['name' => 'class-delete','display_name' => 'Class']);

        Permission::firstOrCreate(['name' => 'year-read','display_name' => 'Year']);
        Permission::firstOrCreate(['name' => 'year-create','display_name' => 'Year']);
        Permission::firstOrCreate(['name' => 'year-update','display_name' => 'Year']);
        Permission::firstOrCreate(['name' => 'year-delete','display_name' => 'Year']);

        Permission::firstOrCreate(['name' => 'school-read','display_name' => 'School']);
        Permission::firstOrCreate(['name' => 'school-create','display_name' => 'School']);
        Permission::firstOrCreate(['name' => 'school-update','display_name' => 'School']);
        Permission::firstOrCreate(['name' => 'school-delete','display_name' => 'School']);
        Permission::firstOrCreate(['name' => 'school-export','display_name' => 'School']);

        Permission::firstOrCreate(['name' => 'college-read','display_name' => 'College']);
        Permission::firstOrCreate(['name' => 'college-create','display_name' => 'College']);
        Permission::firstOrCreate(['name' => 'college-update','display_name' => 'College']);
        Permission::firstOrCreate(['name' => 'college-delete','display_name' => 'College']);
        Permission::firstOrCreate(['name' => 'college-export','display_name' => 'College']);

        Permission::firstOrCreate(['name' => 'teacher-read','display_name' => 'Teacher']);
        Permission::firstOrCreate(['name' => 'teacher-create','display_name' => 'Teacher']);
        Permission::firstOrCreate(['name' => 'teacher-update','display_name' => 'Teacher']);
        Permission::firstOrCreate(['name' => 'teacher-delete','display_name' => 'Teacher']);
        Permission::firstOrCreate(['name' => 'teacher-export','display_name' => 'Teacher']);

        Permission::firstOrCreate(['name' => 'scholarship-read','display_name' => 'Application']);
        Permission::firstOrCreate(['name' => 'scholarship-create','display_name' => 'Application']);
        Permission::firstOrCreate(['name' => 'scholarship-update','display_name' => 'Application']);
        Permission::firstOrCreate(['name' => 'scholarship-delete','display_name' => 'Application']);
        Permission::firstOrCreate(['name' => 'scholarship-export','display_name' => 'Application']);

        Permission::firstOrCreate(['name' => 'scholarship-pending-read','display_name' => 'Application Under Verification']);
        Permission::firstOrCreate(['name' => 'scholarship-approved-read','display_name' => 'Application Approved']);
        Permission::firstOrCreate(['name' => 'scholarship-payment_in_progress-read','display_name' => 'Application Payment In Progress']);
        Permission::firstOrCreate(['name' => 'scholarship-payment_done-read','display_name' => 'Application Payment Done']);
        Permission::firstOrCreate(['name' => 'scholarship-rejected-read','display_name' => 'Application Rejected']);
        Permission::firstOrCreate(['name' => 'scholarship-all-read','display_name' => 'All Application']);

        Permission::firstOrCreate(['name' => 'expense-read','display_name' => 'Expense']);
        Permission::firstOrCreate(['name' => 'expense-create','display_name' => 'Expense']);
        Permission::firstOrCreate(['name' => 'expense-update','display_name' => 'Expense']);
        Permission::firstOrCreate(['name' => 'expense-delete','display_name' => 'Expense']);

        Permission::firstOrCreate(['name' => 'year-wise-read','display_name' => 'Year Wise Report']);
        Permission::firstOrCreate(['name' => 'school-wise-read','display_name' => 'School Wise Report']);
        Permission::firstOrCreate(['name' => 'college-wise-read','display_name' => 'College Wise Report']);
        Permission::firstOrCreate(['name' => 'village-wise-read','display_name' => 'Village Wise Report']);
        Permission::firstOrCreate(['name' => 'course-wise-read','display_name' => 'Course Wise Report']);
        Permission::firstOrCreate(['name' => 'student-wise-read','display_name' => 'Student Wise Report']);
        Permission::firstOrCreate(['name' => 'expense-wise-read','display_name' => 'Expense Wise Report']);
    }
}
