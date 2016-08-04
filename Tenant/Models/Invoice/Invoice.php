<?php namespace App\Modules\Tenant\Models\Invoice;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

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

     function getInvoiceDetails()
    {
        $invoice_reports = Invoice::leftjoin('payment_invoice_breakdowns', 'payment_invoice_breakdowns.invoice_id', '=', 'invoices.invoice_id')
            ->leftjoin('client_payments', 'client_payments.client_payment_id', '=', 'payment_invoice_breakdowns.payment_id')
            ->leftjoin('clients', 'clients.client_id', '=', 'client_payments.client_id')
            ->leftjoin('persons', 'persons.person_id', '=', 'clients.person_id')
            ->leftjoin('users', 'clients.user_id', '=', 'users.user_id')
            ->leftjoin('person_phones', 'persons.person_id', '=', 'person_phones.person_id')
            ->leftjoin('phones', 'person_phones.phone_id', '=', 'phones.phone_id')
            ->select([DB::raw('CONCAT(persons.first_name, " ", persons.last_name) AS fullname'),'users.email','phones.number','invoices.invoice_amount','invoices.invoice_id','invoices.total_gst','invoices.invoice_date',DB::raw('SUM(client_payments.amount) AS total_paid')])
            ->groupBy('payment_invoice_breakdowns.invoice_id')
            ->orderBy('invoices.invoice_id', 'desc')
            ->get();

        return $invoice_reports;
    }
        

    function getDate()
    {
        $date = Carbon::now();

        return $date;
    }


}
