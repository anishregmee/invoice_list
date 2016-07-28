@extends('layouts.tenant')
@section('title', 'Report Client')
@section('breadcrumb')
    @parent
    <li><a href="{{url('tenant/clients')}}" title="All Clients"><i class="fa fa-users"></i> Clients</a></li>
    <li>Notes</li>
@stop

@section('content')
  
  	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-default">
					<div class="box-header">
						<div class="box-title col-md-12">
							<h1>Client - <small>Invoice Reports</small></h1>
							<hr>
							<section class="small">
						        {!! Form::open(['class'=>'form-inline']) !!}
						              
						            <div class="form-group">
							            {!! Form::label('filter_id', 'Filter By:') !!}
							                <select name="filter_id", class="form-control">
							                    <option value="1">College Name</option>
							                    <option value="2">Email</option>
							                    <option value="3">Phone</option>
							                </select>
						            </div>
						            
							        <div class="form-group">
							            {!! Form::label('search_for', 'Search for:') !!}
							            {!! Form::text('search_for', '',['placeholder'=>'search here..', 'class'=>'form-control'] ) !!}
							        </div>
						              
						            <div class="form-group">
						                {!! Form::submit('Search', ['class'=>'btn btn-success']) !!}
						            </div>
						            {!! Form::close() !!}
		        			</section>
		        			<br>
							<h2>College Invoice</h2>
							<hr>
						</div>
					</div>
					<div class="box-body">
						<table class="table table-striped table-bordered">
							<thead>
					            <tr>
					                <th>Id</th>
					                <th>Invoice Date</th>
					                <th>Intake</th>
					                <th>Amount with Discount</th>
					                <th>Discount</th>
					                <th>Outstanding</th>
					                <th>Status</th>
					                <th>Processing</th>
					            </tr>
					        </thead>
					        <tbody>
					              <tr>
					                <td>Col001</td>
					                <td>12/10/2014</td>
					                <td>1</td>
					                <td>7700</td>
					                <td>300</td>
					                <td>5000 {{ Html::linkRoute('report.agent', '(View payment)')}}</td>
					                <td>Paid on 23/10/2014</td>
					                <td>
					                	<a href="{{ route('report.application')}}" title="view"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-eye-open" data-toggle="tooltip" data-placement="top" title="View"></i></a>
	                    				<a href="#" title="claim invoice"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-hand-up" data-toggle="tooltip" data-placement="top" title="Claim Invoice"></i></a>
	                    				<a href="#" title="withdraw/cancel"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="top" title="Withdral / Cancel"></i></a>
	                    				<a href="#" title="future invoices"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-list-alt" data-toggle="tooltip" data-placement="top" title="Future Invoices"></i></a>
					                </td>
					              </tr>
					               <tr>
					                <td>Col002</td>
					                <td>13/10/2014</td>
					                <td>2</td>
					                <td>3000</td>
					                <td>300</td>
					                <td>2000 {{ Html::linkRoute('report.agent', '(View payment)')}}</td>
					                <td>pending</td>
					                <td>
					                	<a href="{{ route('report.application')}}" title="view"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-eye-open" data-toggle="tooltip" data-placement="top" title="View"></i></a>
	                    				<a href="#" title="claim invoice"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-hand-up" data-toggle="tooltip" data-placement="top" title="Claim Invoice"></i></a>
	                    				<a href="#" title="withdraw/cancel"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="top" title="Withdral / Cancel"></i></a>
	                    				<a href="#" title="future invoices"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-list-alt" data-toggle="tooltip" data-placement="top" title="Future Invoices"></i></a>
					                </td>
					              </tr>
					        </tbody>
						</table>	
					</div>
				</div>
			</div>
		</div>
	</div>
@stop
