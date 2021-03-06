<?php namespace App\Modules\Tenant\Models\Invoice;

use App\Modules\Tenant\Models\College\OtherCommission;
use App\Modules\Tenant\Models\College\TuitionCommission;
use App\Modules\Tenant\Models\Payment\CollegePayment;
use Illuminate\Database\Eloquent\Model;
use DB;

class CollegeInvoice extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'college_invoices';

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
    protected $fillable = ['course_application_id', 'total_commission', 'total_gst', 'payable_to_college', 'due_date', 'installment_no', 'invoice_date'];

    function add(array $request, $application_id)
    {
        DB::beginTransaction();

        try {
            $college_invoice = CollegeInvoice::create([
                'course_application_id' => $application_id,
                'total_commission' => $request['total_commission'],
                'total_gst' => $request['total_gst'],
                'payable_to_college' => $request['payable_to_college'],
                'installment_no' => $request['installment_no'],
                'invoice_date' => insert_dateformat($request['invoice_date'])
            ]);

            if(isset($request['tuition_fee']))
            {
                $ci_commission = TuitionCommission::create([
                    'tuition_fee' => $request['tuition_fee'],
                    'enrollment_fee' => $request['enrollment_fee'],
                    'material_fee' => $request['material_fee'],
                    'coe_fee' => $request['coe_fee'],
                    'other_fee' => $request['other_fee'],
                    'sub_total' => $request['sub_total'],
                    'description' => $request['description'],
                    'commission_percent' => $request['commission_percent'],
                    'commission_amount' => $request['commission_amount'],
                    'commission_gst' => $request['gst'],
                    'college_invoice_id' => $college_invoice->college_invoice_id
                ]);
            }

            if(isset($request['incentive']))
            {
                $ci_commission = OtherCommission::create([
                    'amount' => $request['incentive'],
                    'gst' => $request['gst'],
                    'description' => $request['description'],
                    'college_invoice_id' => $college_invoice->college_invoice_id
                ]);
            }

            DB::commit();
            return $college_invoice->college_invoice_id;
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            // something went wrong
        }
    }

    function getDetails($invoice_id)
    {
        $college_invoice = CollegeInvoice::leftJoin('ci_tuition_commissions', 'ci_tuition_commissions.college_invoice_id', '=', 'college_invoices.college_invoice_id')
            ->leftJoin('ci_other_commissions', 'ci_other_commissions.college_invoice_id', '=', 'college_invoices.college_invoice_id')
            ->select('college_invoices.*', 'ci_tuition_commissions.*', 'ci_other_commissions.amount', 'ci_other_commissions.gst', 'ci_other_commissions.description as other_description')
            ->find($invoice_id);
        return $college_invoice;
    }

    function getStats($application_id)
    {
        $stats = array();
        $stats['invoice_amount'] = $this->getTotalAmount($application_id);
        $stats['total_paid'] = $this->getTotalPaid($application_id);
        $due_amount = $stats['invoice_amount'] - $stats['total_paid'];
        $stats['due_amount'] = ($due_amount < 0)? 0 : $due_amount;
        return $stats;
    }

    function getTotalAmount($application_id)
    {
        $invoices = CollegeInvoice::select('total_commission')
            ->where('course_application_id', $application_id)
            ->orderBy('created_at', 'desc')
            ->sum('total_commission');
        return $invoices;
    }

    function getTotalPaid($application_id)
    {
        $payments = CollegePayment::where('course_application_id', $application_id)->sum('amount');
        return $payments;
    }

}
