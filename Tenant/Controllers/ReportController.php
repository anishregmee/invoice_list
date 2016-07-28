<?php

namespace App\Modules\Tenant\Controllers;

use App\Http\Requests;
use App\Modules\Tenant\Models\SubagentInvoice;

class ReportController extends BaseController
{

	public function agent()
	{
		$agents = SubagentInvoice::join('course_application', 'course_application.course_application_id','=', 'subagent_invoices.course_application_id')
			->leftjoin('invoices', 'invoices.invoice_id','=', 'subagent_invoices.invoice_id')
			->select(['invoices.invoice_date'])
			->orderby('course_application.course_application_id', 'desc')
			->get();
		
		return view('Tenant::report/agent',['agents'=>$agents]);
	}

	public function application()
	{
		return view('Tenant::report/application');
	}

	public function client()
	{
		return view('Tenant::report/client');
	}



}