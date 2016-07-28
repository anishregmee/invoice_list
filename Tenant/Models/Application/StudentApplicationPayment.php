<?php namespace App\Modules\Tenant\Models\Application;

use App\Modules\Tenant\Models\Client\ClientPayment;
use Illuminate\Database\Eloquent\Model;
use DB;

class StudentApplicationPayment extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'student_application_payments';

    /**
     * The primary key of the table.
     *
     * @var string
     */
    protected $primaryKey = 'student_payments_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['course_application_id', 'client_payment_id'];

    /**
     * Disable default timestamp feature.
     *
     * @var boolean
     */
    public $timestamps = false;

    public function add(array $request, $application_id)
    {
        DB::beginTransaction();

        try {
            $payment = ClientPayment::create([
                'client_id' => null, //change this later if necessary
                'amount' => $request['amount'],
                'date_paid' => insert_dateformat($request['date_paid']),
                'payment_method' => $request['payment_method'],
                'payment_type' => $request['payment_type'],
                'description' => $request['description']
            ]);

            $student_payment = StudentApplicationPayment::create([
                'course_application_id' => $application_id,
                'client_payment_id' => $payment->client_payment_id,
            ]);

            DB::commit();
            return $student_payment->student_payments_id;
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            // something went wrong
        }
    }

}
