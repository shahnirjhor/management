<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Company;
use App\Models\Account;
use App\Models\Category;
use App\Models\Currency;
use App\Models\InvoiceStatus;
use App\Models\OfflinePayment;
use App\Models\Setting;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sUser = User::where('id', '1')->select('id')->first();

        $company = Company::create([
            'domain' => 'https://pvalue.co.in',
            'enabled' => '1'
        ]);
        $company->users()->attach($sUser->id);
        $account = Account::create([
            'company_id' => $company->id,
            'name' => 'Cash',
            'number' => '1',
            'currency_code' => 'INR',
            'bank_name' => 'Cash',
            'enabled' => '1',
        ]);
        $invoiceStatusRows = [
            [
                'company_id' => $company->id,
                'name' => 'Draft',
                'code' => 'draft'
            ],
            [
                'company_id' => $company->id,
                'name' => 'Sent',
                'code' => 'sent'
            ],
            [
                'company_id' => $company->id,
                'name' => 'Viewed',
                'code' => 'viewed'
            ],
            [
                'company_id' => $company->id,
                'name' => 'Approved',
                'code' => 'approved'
            ],
            [
                'company_id' => $company->id,
                'name' => 'Partial',
                'code' => 'partial'
            ],
            [
                'company_id' => $company->id,
                'name' => 'Paid',
                'code' => 'paid'
            ],
        ];

        foreach ($invoiceStatusRows as $row) {
            InvoiceStatus::create($row);
        }

        $categoriesRows = [
            [
                'company_id' => $company->id,
                'name' => 'Transfer',
                'type' => 'other',
                'color' => '#605ca8',
                'enabled' => '1'
            ],
            [
                'company_id' => $company->id,
                'name' => 'Deposit',
                'type' => 'income',
                'color' => '#f39c12',
                'enabled' => '1'
            ],
            [
                'company_id' => $company->id,
                'name' => 'Withdraw',
                'type' => 'expense',
                'color' => '#d2d6de',
                'enabled' => '1'
            ],
            [
                'company_id' => $company->id,
                'name' => 'General',
                'type' => 'item',
                'color' => '#00c0ef',
                'enabled' => '1'
            ],
        ];
        foreach ($categoriesRows as $row) {
            Category::create($row);
        }

        $offlinePaymentRows = [
            [
                'company_id' => $company->id,
                'name' => 'Cash',
                'code' => 'offlinepayment.cash.1',
                'show_to_customer' => '0',
                'order' => '1'
            ],
            [
                'company_id' => $company->id,
                'name' => 'Bank Transfer',
                'code' => 'offlinepayment.bank_transfer.2',
                'show_to_customer' => '0',
                'order' => '2'
            ]
        ];

        foreach ($offlinePaymentRows as $row) {
            OfflinePayment::create($row);
        }

        $currencyRows = [
            [
                'company_id' => $company->id,
                'name' => 'Indian Rupee',
                'code' => 'INR',
                'rate' => '1.00',
                'enabled' => '1',
                'precision' => config('money.INR.precision'),
                'symbol' => config('money.INR.symbol'),
                'symbol_first' => config('money.INR.symbol_first'),
                'decimal_mark' => config('money.INR.decimal_mark'),
                'thousands_separator' => config('money.INR.thousands_separator'),
            ],
            [
                'company_id' => $company->id,
                'name' => 'US Dollar',
                'code' => 'USD',
                'rate' => '1.00',
                'enabled' => '1',
                'precision' => config('money.USD.precision'),
                'symbol' => config('money.USD.symbol'),
                'symbol_first' => config('money.USD.symbol_first'),
                'decimal_mark' => config('money.USD.decimal_mark'),
                'thousands_separator' => config('money.USD.thousands_separator'),
            ],
        ];

        foreach ($currencyRows as $row) {
            Currency::create($row);
        }

        $rows = [
            [
                'company_id' => $company->id,
                'key' => 'general.company_name',
                'value' => 'PValue Solutions Private Limited',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.company_email',
                'value' => 'info@pvalue.co.in',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.default_locale',
                'value' => 'en-GB',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.default_account',
                'value' => $account->id,
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.financial_start',
                'value' => Carbon::now()->format('d-m'),
            ],

            [
                'company_id' => $company->id,
                'key' => 'general.timezone',
                'value' => 'Europe/London',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.date_format',
                'value' => 'd M Y',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.date_separator',
                'value' => 'space',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.percent_position',
                'value' => 'after',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.bill_number_prefix',
                'value' => 'BILL-',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.bill_number_digit',
                'value' => '5',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.bill_number_next',
                'value' => '1',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.bill_item',
                'value' => 'settings.bill.item',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.invoice_number_prefix',
                'value' => 'SAN-',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.invoice_number_digit',
                'value' => '6',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.invoice_number_next',
                'value' => '1',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.invoice_item',
                'value' => 'settings.invoice.item',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.invoice_price',
                'value' => 'settings.invoice.price',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.invoice_quantity',
                'value' => 'settings.invoice.quantity',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.send_item_reminder',
                'value' => '1',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.schedule_item_stocks',
                'value' => '3,5,7',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.default_payment_method',
                'value' => 1,
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.email_protocol',
                'value' => 'mail',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.email_sendmail_path',
                'value' => '/usr/sbin/sendmail -bs',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.schedule_time',
                'value' => '09:00',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.admin_theme',
                'value' => 'skin-green-light',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.list_limit',
                'value' => '25',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.use_gravatar',
                'value' => '0',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.session_handler',
                'value' => 'file',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.session_lifetime',
                'value' => '30',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.file_size',
                'value' => '2',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.file_types',
                'value' => 'pdf,jpeg,jpg,png',
            ],
            [
                'company_id' => $company->id,
                'key' => 'general.wizard',
                'value' => '0',
            ],
        ];

        foreach ($rows as $row) {
            Setting::create($row);
        }


        $addressRow = [
            [
                'company_id' => $company->id,
                'key' => 'general.company_address',
                'value' => '03, Ground Floor, 22nd Main Rd, Opp. to PES College Road, Hanumanthnagar, Banashankari 1st Stage, Bengaluru, Karnataka 560050',
            ],
        ];
        foreach ($addressRow as $row) {
            Setting::create($row);
        }

        $defaultCurrencyRow = [
            [
                'company_id' => $company->id,
                'key' => 'general.default_currency',
                'value' => 'INR',
            ],
        ];
        foreach ($defaultCurrencyRow as $row) {
            Setting::create($row);
        }

    }

}
