<?php namespace App\Modules\Tenant\Controllers;

use App\Http\Requests;
use App\Modules\Tenant\Models\Invoice\Invoice;
use Flash;
use DB;
use Carbon\Carbon;

use Illuminate\Http\Request;

class InvoiceReportController extends BaseController
{

     public function getInvoiceReportIndex()
    {
        $invoice_reports = Invoice::leftjoin('student_invoices', 'student_invoices.invoice_id', '=', 'invoices.invoice_id')
            ->leftjoin('course_application', 'student_invoices.application_id', '=', 'course_application.course_application_id')
            ->leftjoin('clients', 'clients.client_id', '=', 'course_application.client_id')
            ->leftjoin('persons', 'persons.person_id', '=', 'clients.person_id')
            ->leftjoin('users', 'persons.person_id', '=', 'users.person_id')
            ->leftjoin('person_phones', 'persons.person_id', '=', 'person_phones.person_id')
            ->leftjoin('phones', 'person_phones.phone_id', '=', 'phones.phone_id')
            ->select([DB::raw('CONCAT(persons.first_name, " ", persons.last_name) AS fullname'),'users.email','phones.number','invoices.invoice_amount','invoices.invoice_id','invoices.total_gst','invoices.amount','invoices.invoice_date'])
            ->orderBy('invoices.invoice_id', 'desc')
            ->get();

        return view("Tenant::Invoice Report/invoice_list",['invoice_reports'=>$invoice_reports]);
    }

     
     public function getInvoicePaid()
    {
        $invoice_paid = Invoice::leftjoin('student_invoices', 'student_invoices.invoice_id', '=', 'invoices.invoice_id')
            ->leftjoin('course_application', 'student_invoices.application_id', '=', 'course_application.course_application_id')
            ->leftjoin('clients', 'clients.client_id', '=', 'course_application.client_id')
            ->leftjoin('persons', 'persons.person_id', '=', 'clients.person_id')
            ->leftjoin('users', 'persons.person_id', '=', 'users.person_id')
            ->leftjoin('person_phones', 'persons.person_id', '=', 'person_phones.person_id')
            ->leftjoin('phones', 'person_phones.phone_id', '=', 'phones.phone_id')
            ->select([DB::raw('CONCAT(persons.first_name, " ", persons.last_name) AS fullname'),'users.email','phones.number','invoices.invoice_amount','invoices.invoice_id','invoices.total_gst','invoices.amount','invoices.invoice_date'])
            ->orderBy('invoices.invoice_id', 'desc')
            ->get();
        $date = Carbon::now();

        return view("Tenant::Invoice Report/invoice_paid",['invoice_paid'=>$invoice_paid, 'date'=>$date]);
    }
        


    public function getInvoicefuture()
    {
        $invoice_future = Invoice::leftjoin('student_invoices', 'student_invoices.invoice_id', '=', 'invoices.invoice_id')
            ->leftjoin('course_application', 'student_invoices.application_id', '=', 'course_application.course_application_id')
            ->leftjoin('clients', 'clients.client_id', '=', 'course_application.client_id')
            ->leftjoin('persons', 'persons.person_id', '=', 'clients.person_id')
            ->leftjoin('users', 'persons.person_id', '=', 'users.person_id')
            ->leftjoin('person_phones', 'persons.person_id', '=', 'person_phones.person_id')
            ->leftjoin('phones', 'person_phones.phone_id', '=', 'phones.phone_id')
            ->select([DB::raw('CONCAT(persons.first_name, " ", persons.last_name) AS fullname'),'users.email','phones.number','invoices.invoice_amount','invoices.invoice_id','invoices.total_gst','invoices.amount','invoices.invoice_date'])
            ->orderBy('invoices.invoice_id', 'desc')
            ->get();

        $date = Carbon::now();

        return view("Tenant::Invoice Report/invoice_future",['invoice_future'=>$invoice_future, 'date'=>$date]);
    }
        

}//end of controller
