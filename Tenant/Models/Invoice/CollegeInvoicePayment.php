<?php namespace App\Modules\Tenant\Models\Invoice;

use Illuminate\Database\Eloquent\Model;
use DB;

class CollegeInvoicePayment extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'college_invoice_payments';

    /**
     * The primary key of the table.
     *
     * @var string
     */
    protected $primaryKey = 'college_invoice_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['payment_id', 'college_invoice_id'];

    public $timestamps = false;

}
