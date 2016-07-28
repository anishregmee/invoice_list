<?php namespace App\Modules\Tenant\Models\Payment;

use Illuminate\Database\Eloquent\Model;
use DB;

class CollegePayment extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'college_payments';

    /**
     * The primary key of the table.
     *
     * @var string
     */
    protected $primaryKey = 'college_payment_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['course_application_id', 'amount', 'date_paid', 'payment_method', 'description', 'payment_type'];

    function add(array $request, $application_id)
    {
        DB::beginTransaction();

        try {
            $payment = CollegePayment::create([
                'course_application_id' => $application_id,
                'amount' => $request['amount'],
                'date_paid' => insert_dateformat($request['date_paid']),
                'payment_method' => $request['payment_method'],
                'payment_type' => $request['payment_type'],
                'description' => $request['description']
            ]);

            DB::commit();
            return $payment->college_payment_id;
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            // something went wrong
        }
    }

}
