<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\Category;
use App\Models\Scholarship;
use App\Traits\DateTime;
use Illuminate\Support\Facades\DB;

/**
 * Class DashboardController
 *
 * @package App\Http\Controllers
 * @category Controller
 */
class DashboardController extends Controller
{
    use DateTime;

    public $today;

    public $company;

    public $financial_start;

    public $income_donut = ['colors' => [], 'labels' => [], 'values' => []];

    public $expense_donut = ['colors' => [], 'labels' => [], 'values' => []];

    public function index()
    {
        $roleName = Auth::user()->getRoleNames();
        if($roleName[0] == "Student") {
            $userId = Auth::user()->id;
            $data_total = DB::table('scholarships')->orderBy('scholarships.year','ASC')->where('scholarships.user_id',$userId)->select(DB::raw('count(scholarships.id) as total_scholarships'))->first();
            $data_pending = DB::table('scholarships')->orderBy('scholarships.year','ASC')->where('scholarships.user_id',$userId)->where('scholarships.status','pending')->select(DB::raw('count(scholarships.id) as total_scholarships'))->first();
            $data_approved = DB::table('scholarships')->orderBy('scholarships.year','ASC')->where('scholarships.user_id',$userId)->where('scholarships.status','approved')->select(DB::raw('count(scholarships.id) as total_scholarships'))->first();
            $data_rejected = DB::table('scholarships')->orderBy('scholarships.year','ASC')->where('scholarships.user_id',$userId)->where('scholarships.status','rejected')->select(DB::raw('count(scholarships.id) as total_scholarships'))->first();
            $data_payment_in_progress = DB::table('scholarships')->orderBy('scholarships.year','ASC')->where('scholarships.user_id',$userId)->where('scholarships.status','payment_in_progress')->select(DB::raw('count(scholarships.id) as total_scholarships'))->first();
            $data_payment_done = DB::table('scholarships')->orderBy('scholarships.year','ASC')->where('scholarships.user_id',$userId)->where('scholarships.status','payment_done')->select(DB::raw('count(scholarships.id) as total_scholarships'))->first();
            $currentYearApApRe = $this->currentYearApApReSt();
            $overallYearApApRe = $this->overallYearApApReSt();
            $monthlyData = $this->getChartData();
        } else {
            $data_total = DB::table('scholarships')->orderBy('scholarships.year','ASC')->select(DB::raw('count(scholarships.id) as total_scholarships'))->first();
            $data_pending = DB::table('scholarships')->orderBy('scholarships.year','ASC')->where('scholarships.status','pending')->select(DB::raw('count(scholarships.id) as total_scholarships'))->first();
            $data_approved = DB::table('scholarships')->orderBy('scholarships.year','ASC')->where('scholarships.status','approved')->select(DB::raw('count(scholarships.id) as total_scholarships'))->first();
            $data_rejected = DB::table('scholarships')->orderBy('scholarships.year','ASC')->where('scholarships.status','rejected')->select(DB::raw('count(scholarships.id) as total_scholarships'))->first();
            $data_payment_in_progress = DB::table('scholarships')->orderBy('scholarships.year','ASC')->where('scholarships.status','payment_in_progress')->select(DB::raw('count(scholarships.id) as total_scholarships'))->first();
            $data_payment_done = DB::table('scholarships')->orderBy('scholarships.year','ASC')->where('scholarships.status','payment_done')->select(DB::raw('count(scholarships.id) as total_scholarships'))->first();
            $currentYearApApRe = $this->currentYearApApRe();
            $overallYearApApRe = $this->overallYearApApRe();
            $monthlyData = $this->getChartData();
        }
        return view('dashboard', compact(
            'data_total',
            'data_pending',
            'data_approved',
            'data_rejected',
            'data_payment_in_progress',
            'data_payment_done',
            'currentYearApApRe',
            'overallYearApApRe',
            'monthlyData'
        ));
    }

    public function getChartData()
    {
        $roleName = Auth::user()->getRoleNames();

        if($roleName[0] == "Student") {

            return response()->json([
                'currentYearApApRe' => $this->currentYearApApReSt(),
                'overallYearApApRe' => $this->overallYearApApReSt()
            ], 200);

        } else {
            return response()->json([
                'currentYearApApRe' => $this->currentYearApApRe(),
                'overallYearApApRe' => $this->overallYearApApRe()
            ], 200);
        }

    }

    private function currentYearApApRe()
    {
        $application = 0; $approved = 0; $rejected = 0;

        $application = Scholarship::whereYear('date', date('Y'))->count('id');
        $approved = Scholarship::where('status','approved')->whereYear('date', date('Y'))->count('id');
        $rejected = Scholarship::where('status','rejected')->whereYear('date', date('Y'))->count('id');

        return [
            'application' => $application,
            'approved' => $approved,
            'rejected' => $rejected
        ];
    }

    private function currentYearApApReSt()
    {
        $userId = Auth::user()->id;
        $application = 0; $approved = 0; $rejected = 0;

        $application = Scholarship::whereYear('date', date('Y'))->where('scholarships.user_id',$userId)->count('id');
        $approved = Scholarship::where('status','approved')->where('scholarships.user_id',$userId)->whereYear('date', date('Y'))->count('id');
        $rejected = Scholarship::where('status','rejected')->where('scholarships.user_id',$userId)->whereYear('date', date('Y'))->count('id');

        return [
            'application' => $application,
            'approved' => $approved,
            'rejected' => $rejected
        ];
    }

    private function overallYearApApReSt()
    {
        $userId = Auth::user()->id;
        $application = 0; $approved = 0; $rejected = 0;

        $application = Scholarship::where('scholarships.user_id',$userId)->count('id');
        $approved = Scholarship::where('status','approved')->where('scholarships.user_id',$userId)->count('id');
        $rejected = Scholarship::where('status','rejected')->where('scholarships.user_id',$userId)->count('id');

        return [
            'application' => $application,
            'approved' => $approved,
            'rejected' => $rejected
        ];
    }

    private function overallYearApApRe()
    {
        $application = 0; $approved = 0; $rejected = 0;

        $application = Scholarship::count('id');
        $approved = Scholarship::where('status','approved')->count('id');
        $rejected = Scholarship::where('status','rejected')->count('id');

        return [
            'application' => $application,
            'approved' => $approved,
            'rejected' => $rejected
        ];
    }

    private function monthlyData()
    {
        $paymentDone = []; $labels = [];
        $results = DB::select('SELECT DISTINCT YEAR(date) AS "year", MONTH(date) AS "month" FROM scholarships ORDER BY year DESC LIMIT 12');

        foreach ($results as $result) {
            $labels[] = date('F', mktime(0, 0, 0, $result->month, 10)).' '.$result->year;
            $paymentDone[] = '"'.Scholarship::where('status','payment_done')->whereYear('date', $result->year)->whereMonth('date', $result->month)->count('id').'"';
        }
        return [
            'paymentDone' => $paymentDone,
            'labels' => $labels
        ];
    }

    private function getTotals()
    {
        list($incomes_amount, $open_invoice, $overdue_invoice, $expenses_amount, $open_bill, $overdue_bill) = $this->calculateAmounts();

        $incomes_progress = 100;
        if (!empty($open_invoice) && !empty($overdue_invoice)) {
            $incomes_progress = (int) ($open_invoice * 100) / ($open_invoice + $overdue_invoice);
        }
        // Totals
        $total_incomes = array(
            'total'             => $incomes_amount,
            'open_invoice'      => money($open_invoice, $this->company->default_currency, true),
            'overdue_invoice'   => money($overdue_invoice, $this->company->default_currency, true),
            'progress'          => $incomes_progress
        );

        $expenses_progress = 100;
        if (!empty($open_bill) && !empty($overdue_bill)) {
            $expenses_progress = (int) ($open_bill * 100) / ($open_bill + $overdue_bill);
        }
        $total_expenses = array(
            'total'         => $expenses_amount,
            'open_bill'     => money($open_bill, $this->company->default_currency, true),
            'overdue_bill'  => money($overdue_bill, $this->company->default_currency, true),
            'progress'      => $expenses_progress
        );

        $amount_profit = $incomes_amount - $expenses_amount;
        $open_profit = $open_invoice - $open_bill;
        $overdue_profit = $overdue_invoice - $overdue_bill;

        $total_progress = 100;

        if (!empty($open_profit) && !empty($overdue_profit)) {
            $total_progress = (int) ($open_profit * 100) / ($open_profit + $overdue_profit);
        }

        $total_profit = array(
            'total'         => $amount_profit,
            'open'          => money($open_profit, $this->company->default_currency, true),
            'overdue'       => money($overdue_profit, $this->company->default_currency, true),
            'progress'      => $total_progress
        );

        return array($total_incomes, $total_expenses, $total_profit);
    }

    private function calculateAmounts()
    {
        $incomes_amount = $open_invoice = $overdue_invoice = 0;
        $expenses_amount = $open_bill = $overdue_bill = 0;

        $categories = Category::with(['bills', 'invoices', 'payments', 'revenues'])->orWhere('type', 'income')->orWhere('type', 'expense')->where('enabled', 1)->get();

        foreach ($categories as $category) {
            switch ($category->type) {
                case 'income':
                    $amount = 0;
                    // Revenues
                    foreach ($category->revenues as $revenue) {
                        $amount += $revenue->getConvertedAmount();
                    }
                    $incomes_amount += $amount;

                    // Invoices
                    $invoices = $category->invoices()->accrued()->get();
                    foreach ($invoices as $invoice) {
                        list($paid, $open, $overdue) = $this->calculateInvoiceBillTotals($invoice, 'invoice');

                        $incomes_amount += $paid;
                        $open_invoice += $open;
                        $overdue_invoice += $overdue;

                        $amount += $paid;
                    }
                    break;

                case 'expense':
                    $amount = 0;
                    // Payments
                    foreach ($category->payments as $payment) {
                        $amount += $payment->getConvertedAmount();
                    }

                    $expenses_amount += $amount;

                    // Bills
                    $bills = $category->bills()->accrued()->get();
                    foreach ($bills as $bill) {
                        list($paid, $open, $overdue) = $this->calculateInvoiceBillTotals($bill, 'bill');

                        $expenses_amount += $paid;
                        $open_bill += $open;
                        $overdue_bill += $overdue;

                        $amount += $paid;
                    }
                    break;
            }
        }
        return array($incomes_amount, $open_invoice, $overdue_invoice, $expenses_amount, $open_bill, $overdue_bill);
    }

    private function calculateInvoiceBillTotals($item, $type)
    {
        $paid = $open = $overdue = 0;

        $today = $this->today->toDateString();

        $paid += $item->getConvertedAmount();

        $code_field = $type . '_status_code';

        if ($item->$code_field != 'paid') {
            $payments = 0;
            if ($item->$code_field == 'partial') {
                foreach ($item->payments as $payment) {
                    $payments += $payment->getConvertedAmount();
                }
            }

            if ($item->due_at > $today) {
                $open += $item->getConvertedAmount() - $payments;
            } else {
                $overdue += $item->getConvertedAmount() - $payments;
            }
        }

        return array($paid, $open, $overdue);
    }
}
