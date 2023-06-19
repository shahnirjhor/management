<?php


namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ApplicationSetting extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\ApplicationSetting::create([
            'item_name' => 'Scholarship Management System',
            'item_short_name' => 'SSMS',
            'item_version' => 'V 1.0',
            'company_name' => 'PValue Solutions Private Limited',
            'company_email' => 'info@pvalue.co.in',
            'company_address' => '03, Ground Floor, 22nd Main Rd, Opp. to PES College Road, Hanumanthnagar, Banashankari 1st Stage, Bengaluru, Karnataka 560050',
            'developed_by' => 'PValue Solutions',
            'developed_by_href' => 'https://pvalue.co.in',
            'developed_by_title' => 'Complete Business Automation Solutions',
            'developed_by_prefix' => 'Design & Developed by',
            'support_email' => 'support@pvalue.co.in',
            'language' => 'en',
            'is_demo' => '0',
            'time_zone' => 'Asia/Kolkata',
        ]);
    }
}
