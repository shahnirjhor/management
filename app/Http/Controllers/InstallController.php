<?php

namespace App\Http\Controllers;

use DB;
use Config;
use Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * Class InstallController
 *
 * @package App\Http\Controllers
 * @category Controller
 */
class InstallController extends Controller
{
    /**
     * Method to load installation view
     *
     * @access public
     * @return mixed
     */
    public function index()
    {
        if(env('DB_DATABASE')) {

            if (Schema::hasTable('application_settings')) {
                return view('page.login');
            }

            return view('install.manually');
        } else {
            return "No Database !!! <br>Please create a database first !!!";
        }
    }

    /**
     * Store a newly created resource in storage
     *
     * @param Request $request
     * @access public
     * @return mixed
     */
    public function install(Request $request)
    {
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);
        Artisan::call('config:clear');
        return view('page.login');
    }

        /**
     * Store a newly created resource in storage
     *
     * @param Request $request
     * @access public
     * @return mixed
     */
    public function storeClear(Request $request)
    {
        $this->validate($request, [
            'app_name' => 'required',
            'app_url' => 'required',
            'host_name' => 'required',
            'database_port' => 'required',
            'database_name' => 'required',
            'database_username' => 'required'
        ]);
        $appName = $request['app_name'];
        $appUrl = $request['app_url'];
        $host = $request['host_name'];
        $port = $request['database_port'];
        $database = $request['database_name'];
        $username = $request['database_username'];
        $password = $request['database_password'];
        if (!$this->createDbTables($host, $port, $database, $username, $password, $appName, $appUrl))
        {
            $message = "Database Connection Error !!!";
            return redirect()->route('install.index')->with('mysql_error',$message);
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Store a newly created resource in storage
     *
     * @param Request $request
     * @access public
     * @return mixed
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'app_name' => 'required',
            'app_url' => 'required',
            'host_name' => 'required',
            'database_port' => 'required',
            'database_name' => 'required',
            'database_username' => 'required'
        ]);
        $appName = $request['app_name'];
        $appUrl = $request['app_url'];
        $host = $request['host_name'];
        $port = $request['database_port'];
        $database = $request['database_name'];
        $username = $request['database_username'];
        $password = $request['database_password'];
        if (!$this->createDbTables($host, $port, $database, $username, $password, $appName, $appUrl))
        {
            $message = "Database Connection Error !!!";
            return redirect()->route('install.index')->with('mysql_error',$message);
        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Method to create table on database
     *
     * @access public
     * @param $host
     * @param $port
     * @param $database
     * @param $username
     * @param $password
     * @param $appName
     * @param $appUrl
     * @return bool
     */
    public function createDbTables($host, $port, $database, $username, $password, $appName ,$appUrl)
    {
        if (!$this->isDbValid($host, $port, $database, $username, $password, $appName ,$appUrl)) {
            return false;
        }
        $this->saveDbVariables($host, $port, $database, $username, $password, $appName ,$appUrl);
        set_time_limit(300);
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);
        return true;
    }

    /**
     * Method to database validation
     *
     * @access public
     * @param $host
     * @param $port
     * @param $database
     * @param $username
     * @param $password
     * @param $appName
     * @param $appUrl
     * @return bool
     */
    public function isDbValid($host, $port, $database, $username, $password, $appName, $appUrl)
    {
        Config::set('database.connections.install_test', [
            'host' => $host,
            'port' => $port,
            'database' => $database,
            'username' => $username,
            'password' => $password,
            'driver' => env('DB_CONNECTION', 'mysql'),
            'charset' => env('DB_CHARSET', 'utf8mb4'),
        ]);
        try
        {
            DB::connection('install_test')->getPdo();
        } catch (Exception $e) {
            return false;
        }
        DB::purge('install_test');
        return true;
    }

    /**
     * Method to save database variables
     *
     * @access public
     * @param $host
     * @param $port
     * @param $database
     * @param $username
     * @param $password
     * @param $appName
     * @param $appUrl
     */
    public function saveDbVariables($host, $port, $database, $username, $password, $appName, $appUrl)
    {
        $this->updateEnv([
            'DB_HOST' => $host,
            'DB_PORT' => $port,
            'DB_DATABASE' => $database,
            'DB_USERNAME' => $username,
            'DB_PASSWORD' => $password,
            'APP_NAME' => $appName,
            'APP_URL' => $appUrl,
        ]);
        $con = env('DB_CONNECTION', 'mysql');
        $db = Config::get('database.connections.' . $con);
        $db['host'] = $host;
        $db['database'] = $database;
        $db['username'] = $username;
        $db['password'] = $password;
        Config::set('database.connections.' . $con, $db);
        DB::purge($con);
        DB::reconnect($con);
    }

    /**
     * Method to update env
     *
     * @access public
     * @param $data
     * @return bool
     */
    public function updateEnv($data)
    {
        if(empty($data)||!is_array($data)||!is_file(base_path('.env')))
        {
            return false;
        }
        $env = file_get_contents(base_path('.env'));
        $env = explode("\n", $env);
        foreach ($data as $dataKey => $dataValue) {
            $updated = false;
            foreach ($env as $env_key => $env_value) {
                $entry = explode('=', $env_value, 2);
                if ($entry[0] == $dataKey) {
                    $env[$env_key] = $dataKey . '=' . $dataValue;
                    $updated = true;
                } else {
                    $env[$env_key] = $env_value;
                }
            }
            if (!$updated) {
                $env[] = $dataKey . '=' . $dataValue;
            }
        }
        $env = implode("\n", $env);
        file_put_contents(base_path('.env'), $env);
        Artisan::call('config:clear');
        Artisan::call('config:cache');
        return true;
    }
}
