<?php namespace App\Modules\Tenant\Models\Invoice;

use Illuminate\Database\Eloquent\Model;
use DB;

class Invoice extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invoices';

    /**
     * The primary key of the table.
     *
     * @var string
     */
    protected $primaryKey = 'invoice_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['amount', 'discount', 'invoice_date', 'invoice_amount', 'description', 'due_date'];

    function getDetails()
    {
         $invoice_reports = Invoice::leftjoin('student_invoices', 'student_invoices.invoice_id', '=', 'invoices.invoice_id')
            ->leftjoin('course_application', 'student_invoices.application_id', '=', 'course_application.course_application_id')
            ->leftjoin('clients', 'clients.client_id', '=', 'course_application.client_id')
            ->leftjoin('payment_invoice_breakdowns', 'payment_invoice_breakdowns.invoice_id', '=', 'invoices.invoice_id')
            ->leftjoin('client_payments', 'client_payments.client_payment_id', '=', 'payment_invoice_breakdowns.payment_id')
            ->leftjoin('persons', 'persons.person_id', '=', 'clients.person_id')
            ->leftjoin('users', 'persons.person_id', '=', 'users.person_id')
            ->leftjoin('person_phones', 'persons.person_id', '=', 'person_phones.person_id')
            ->leftjoin('phones', 'person_phones.phone_id', '=', 'phones.phone_id')
            ->select([DB::raw('CONCAT(persons.first_name, " ", persons.last_name) AS fullname'),'users.email','phones.number','invoices.invoice_amount','invoices.invoice_id','invoices.total_gst','invoices.amount','invoices.invoice_date'])
            ->orderBy('invoices.invoice_id', 'desc')
            ->get();

        return $invoice_reports;
    }


}
