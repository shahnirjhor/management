<?php

namespace App\Providers;

use View;
use App\Models\ApplicationSetting;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        $check = env('DB_DATABASE', NULL) ;
        view()->composer('*', function ($view) {

            if (Schema::hasTable('application_settings')) {
                $application = ApplicationSetting::first();
            } else {
                $application = NULL;
            }

            $getLang = array (
                'en' => 'English',
                'bn' => 'বাংলা',
                'el' => 'Ελληνικά',
                'pt' => 'Português',
                'es' => 'Español',
                'de' => 'Deutsch',
                'fr' => 'Français',
                'nl' => 'Nederlands',
                'it' => 'Italiano',
                'vi' => 'Tiếng Việt',
                'ru' => 'русский',
                'tr' => 'Türkçe'
            );




            $flag = array(
                "en"=>"flag-icon-us",
                "bn"=>"flag-icon-bd",
                "el"=>"flag-icon-gr",
                "pt"=>"flag-icon-pt",
                "es"=>"flag-icon-es",
                "de"=>"flag-icon-de",
                "fr"=>"flag-icon-fr",
                "nl"=>"flag-icon-nl",
                "it"=>"flag-icon-it",
                "vi"=>"flag-icon-vn",
                "ru"=>"flag-icon-ru",
                "tr"=>"flag-icon-tr"
            );
            $user = [];
            $items = [];
            $items_reminder = [];
            $notifications = 0;
            $companyLogo = "img/company.png";

            if (Auth::check()) {

                $firstCompanies = Auth::user()->companies()->first();
                if(isset($firstCompanies) && !empty($firstCompanies))
                {
                    Session::put('company_id', $firstCompanies->id);
                }

                if (isset(Auth::user()->company_id) && !empty(Auth::user()->company_id)){
                    Session::put('company_id', Auth::user()->company_id);
                }

                $company = Setting::where('company_id', Session::get('company_id'))->where('key', 'general.company_name')->get('value')->first();

                // $companyLogo = "img/company.png";
                if(isset($company->value)) {
                    $company_full_name = $company->value;
                } else {
                    $company_full_name = "No Company Imported";
                }



                $companies = Auth::user()->companies()->get();
                foreach ($companies as $company) {
                    $company->setSettings();
                }
                $companySwitchingInfo = array();
                foreach ($companies as $key => $value) {
                    if ($value->id == Session::get('company_id')) {
                        $str = "";
                    } else {
                        $str = "";
                    }
                    $companySwitchingInfo[$value->id] = $str.$value->company_name;
                }
                if(isset($company->company_logo) && !empty($company->company_logo)) {
                    $companyLogo = $company->company_logo;
                }

                $user = Auth::user();
                $undereads = $user->unreadNotifications;
                foreach ($undereads as $underead) {
                    $data = $underead->getAttribute('data');

                    switch ($underead->getAttribute('type')) {
                        case 'App\Notifications\Item':
                            $items[$data['item_id']] = $data['name'];
                            $notifications++;
                            break;
                        case 'App\Notifications\ItemReminder':
                            $items_reminder[$data['item_id']] = $data['name'];
                            $notifications++;
                            break;
                    }
                }
            }

            if(empty($companySwitchingInfo)) {
                $companySwitchingInfo["0"] = "No Company Imported";
            }

            if(empty($company_full_name)) {
                $company_full_name["0"] = "No Company Imported";
            }

                $view->with('ApplicationSetting', $application)
                     ->with('companySwitchingInfo', $companySwitchingInfo)
                     ->with('getLang', $getLang)
                     ->with('company_full_name', $company_full_name)
                     ->with('companyLogo', $companyLogo)
                     ->with('user', $user)
                     ->with('notify_items', $items)
                     ->with('notify_items_reminder', $items_reminder)
                     ->with('notifications', $notifications)
                     ->with('flag', $flag);
        });

    }
}
