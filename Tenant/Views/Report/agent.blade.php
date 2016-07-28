@extends('layouts.tenant')
@section('title', 'Agent Report')
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
							<h1>Agent - <small>Invoice Reports</small></h1>
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
							<h2>Invoices From Sub Agents</h2>
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
				                <th>Amount</th>
				                <th>Commission%</th>
				                <th>Commission</th>
				                <th>Status</th>
				                <th>Processing</th>
				              </tr>
				            </thead>
				            <tbody>
				              <tr>
				                <td>Agt001</td>
				                <td>04/11/2016</td>
				                <td>1</td>
				                <td>7700</td>
				                <td>10</td>
				                <td>770</td>
				                <td>Paid on 23/10/2014</th>
				                <td>
                    				<a href="{{ route('report.application')}}" title="view"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-eye-open" data-toggle="tooltip" data-placement="top" title="View"></i></a>
                    				<a href="#" title="edit"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>
                    				<a href="#" title="delete"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="top" title="Delete"></i></a>
                    				<a href="#" title="make payment"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-piggy-bank" data-toggle="tooltip" data-placement="top" title="Make Payment"></i></a>
                    				<a href="#" title="print invoice"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-print" data-toggle="tooltip" data-placement="top" title="Print Invoice"></i></a>
                    				<a href="#" title="email invoice"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-envelope" data-toggle="tooltip" data-placement="top" title="Email Invoice"></i></a>
				                </td>
				              </tr>
				               <tr>
				                <td>Agt002</td>
				                <td>05/11/2016</td>
				                <td>1</td>
				                <td>3000</td>
				                <td>10</td>
				                <td>300</td>
				                <td>pending</th>
				                <td>
                    				<a href="{{ route('report.application')}}" title="view"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-eye-open" data-toggle="tooltip" data-placement="top" title="View"></i></a>
                    				<a href="#" title="edit"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>
                    				<a href="#" title="delete"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="top" title="Delete"></i></a>
                    				<a href="#" title="make payment"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-piggy-bank" data-toggle="tooltip" data-placement="top" title="Make Payment"></i></a>
                    				<a href="#" title="print invoice"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-print" data-toggle="tooltip" data-placement="top" title="Print Invoice"></i></a>
                    				<a href="#" title="email invoice"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-envelope" data-toggle="tooltip" data-placement="top" title="Email Invoice"></i></a>
				                </td>
				              </tr>
				              <tr>
				                <td></td>
				                <td></td>
				                <td>Total</td>
				                <td>10700</td>
				                <td></td>
				                <td>1070</td>
				                <td></th>
				                <td></td>
				              </tr>
				            </tbody>	
						</table>	
					</div>
				</div>
			</div>
		</div>
	</div>
@stop
